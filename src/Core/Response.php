<?php
namespace Api\Core;
class Response{

	public static function error200(){
		return header("HTTP/1.1 200 OK");
	}

	public static function error301(){
		return header("HTTP/1.1 301 Moved Permanently");
	}

	public static function error400(){
		return header("HTTP/1.1 400 Bad Request");
	}

	public static function error404(){
		return header("HTTP/1.1 404 Not Found");
	}

	public static function error500($message=""){
		return header("HTTP/1.1 500 Internal Server Error");
	}

	public static function jsondata(array $data){
		if(empty($data)){
			return $this->error404();
		}
		$this->error200();
		header("Content-Type: application/json");
		echo json_encode($data);
	}
}