<?php
namespace Api\Core;

/**
 * Handle Database Connection
 * Returning a single instance of the connection
 * which is passed off to the model
 *
 * It will connect to the specified profile
 * when a model is instatiated
 * Else, it will use the first profile found in config.json
 */
use Api\Tools\Response;

class Connection{
	use Light;
	
	private $connection;
	private static $_instance;
	private $_jsonConfig;	
	
	/**
	 * [getInstance description]
	 * @param  string $profile Profile Name
	 * @return object
	 */
	public static function getInstance(string $profile=""){
		if(self::checkLights() === true){
			if (!(self::$_instance instanceof self)) {
	 			self::$_instance = new self($profile);
		    }
		    return self::$_instance->connection;
		}else{
			return Response::error400();
		}	 	
	}

	/**
	 * Called on declaration
	 * @param string $profile Profile Name
	 */
	public function __construct(string $profile=""){
		$jsonConfig = file_get_contents('src/config/config.json');
		$this->_jsonConfig = json_decode($jsonConfig, true);
		
		if($this->_jsonConfig['debug'] === true){

			$profile = (empty($profile)) ? reset($this->_jsonConfig['sandbox']) : $this->_jsonConfig['sandbox'][$profile];
		}else{
			$profile = (empty($profile)) ? reset($this->_jsonConfig['production']) : $this->_jsonConfig['production'][$profile];
		}

		try{
			$this->connection = new \PDO("mysql:host=".$profile['host'].";dbname=".$profile['dbname'], $profile['username'], $profile['password']);
			$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			return $this->connection;
		}catch(\PDOException $e){
			Response::error500($e->getMessage());
		}
	}

	/**
	 * Magic method to prevent duplication of connection
	 * @return void
	 */
	private function __clone() {
	}
}
?>