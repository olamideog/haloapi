<?php
namespace Api\Tools;

/**
 * To handle all super globals and filter them accordingly
 */
class Request{
	private $_post;
	private $_get;

	public function __construct(){

		if((!empty($_POST)) || (!empty($_GET))){
			$this->_cleanInput();
		}
	}

	private function _cleanInput(){
		if (($_SERVER['REQUEST_METHOD'] == 'POST') || (!empty($_POST))) {
			foreach($_POST as $k => $v){
				$this->_post[$k] = $v;
			}
		}

		if (($_SERVER['REQUEST_METHOD'] == 'GET') || (!empty($_GET))) {
			foreach($_GET as $k => $v){
				$this->_get[$k] = $v;
			}
		}
	}

	public function post($field=""){
		return (!empty($field)) ? $this->_post[$field] : $this->_post;
	}

	public function get($field=""){
		return (!empty($field)) ? $this->_get[$field] : $this->_get;
	}

	public function __get($property=""){
		if(empty($property)){
			return;
		}
		if(array_key_exists($property, $this->_post)){
			return $this->_post[$property];
		}
		if(array_key_exists($property, $this->_get)){
			return $this->_get[$property];
		}
	}
}