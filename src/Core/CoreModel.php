<?php
namespace Api\Core;

/**
 * The parent class for all models
 * Have a lot of useful functions to handle
 * database transactions.
 */

use Api\Core\Connection;
use Api\Tools\Response;

class CoreModel{
	protected $table="";

	protected $fillable = array();

	protected $primaryKey;

	protected $sqlQuery="";
	
	private $_connection;
	
	private $_statement;

	private $_parameters;
	
	public function __construct($profile=""){
		$this->_connection = Connection::getInstance($profile);
		$this->setTable();
		$this->setPrimarykey();
		return $this;
	}

	protected function setTable(){
		if(empty($this->table)){
			$this->table = str_replace("Api\Models\\", "", get_called_class());
		}		
	}

	protected function setPrimarykey(){
		if(empty($this->primaryKey)){
			$this->primaryKey = $this->table."_id";
		}
	}

	public function __set($property, $value=""){
		if(array_key_exists($property, $this->fillable)){
			$this->$property = $value;
		}		
	}

	public function __get($property=""){
		if(empty($property)){
			return;
		}
		return (property_exists($this, $property)) ? $this->$property : "" ;		
	}	

	public function where($field, $parameter, $operator="="){
		$this->sqlQuery .= " WHERE ".$field." ".$operator." ':".$field."'";
		$this->_parameters[] = array(':'.$field, $parameter);
		return $this;
	}

	public function andWhere($field, $parameter, $operator="="){
		$this->sqlQuery .= " AND WHERE ".$field." ".$operator." ':".$field."'";
		$this->_parameters[] = array(':'.$field, $parameter);
		return $this;
	}

	public function orWhere($field, $parameter, $operator="="){
		$this->sqlQuery .= " OR WHERE ".$field." ".$operator." ':".$field."'";
		$this->_parameters[] = array(':'.$field, $parameter);
		return $this;
	}

	/**
	 * Update data in a row
	 * @param  array  $fields Field name as key and their values
	 * @return void/int       Nothing or the primary key value of the updated data
	 */
	public function update($fields = array()){
		if(!empty($fields) && (count($fields) > 0)){
			$cols = " ";
			$counter = 0;
			$size = count($fields);

			foreach($fields as $k=>$v){
				$cols .= $k."=:".$k;
				$this->_parameters[] = array(":".$k, $v);
				$counter +=1;

				if($counter < $size){
					$cols .= ", ";
				}
			}

			$this->sqlQuery = "UPDATE ".$this->table." SET ".$cols;

			$result = $this->rawQuery($this->sqlQuery, $this->_parameters);
			if($result === true){
				return $this->_statement->rowCount();
			}			
		}else{
			Response::error400("Fields and values not specified");
		}
	}

	/**
	 * Insert data into a row
	 * @param  array  $fields Field name as key and their values
	 * @return void/int       Nothing or the primary key value of the inserted data
	 */
	public function insert($fields = array()){
		
		if(!empty($fields) && (count($fields) > 0)){
			$cols = " (";
			$vals = " VALUES (";
			$counter = 0;
			$size = count($fields);

			foreach($fields as $k=>$v){
				$cols .= $k;
				$vals .= ":".$k;
				$this->_parameters[] = array(":".$k, $v);
				$counter +=1;
				if($counter < $size){
					$cols .= ", ";
					$vals .= ", ";
				}
			}
			$cols .= ")";
			$vals .= ")";

			$this->sqlQuery = "INSERT INTO ".$this->table.$cols.$vals;

			$result = $this->rawQuery($this->sqlQuery, $this->_parameters);
			if($result === true){
				return $this->_connection->lastInsertId();
			}			
		}else{
			Response::error400("Fields and values not specified");
		}
	}

	public function get($fields=array()){
		$query = "SELECT ";
		if(!empty($fields) && count($fields) > 0){
			$counter = 0;
			$size = count($fields);

			foreach($fields as $k=>$v){
				$query .= $v;
				$counter +=1;
				if($counter < $size){
					$query .= ", ";
				}
			}
		}else{
			$query .= "*";
		}
		$this->sqlQuery = $query." FROM ".$this->table.$this->sqlQuery;

		$result = $this->rawQuery($this->sqlQuery, $this->_parameters);

		if($result === true){
			return $this->_statement->fetchAll(\PDO::FETCH_ASSOC); 
			//shouldn't this be using the set to override the properties making them available AND ALSO return an array of data?
			//It is possible, Giving us more power to create collectables
		}
		return;
	}

	public function rawQuery($query, $parameters = array()){
		if(empty(!$query)){

			try{
				$this->_statement = $this->_connection->prepare($query);

				if(!empty($parameters) && is_array($parameters)){
					foreach($parameters as $k => $v){
						$this->_statement->bindParam($v[0], $v[1]);
					}
				}			
				
				$this->_statement->execute();
				return true;
			}catch(\Exception $e){
				Response::error400("Something went wrong with Query");
			}
		}
	}
}
?>