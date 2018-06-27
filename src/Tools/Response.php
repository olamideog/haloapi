<?php
namespace Api\Tools;
class Response{

	public static function error200(){
		header("HTTP/1.1 200 OK");
		echo "Successful";
		die;
	}

	public static function error301(){
		header("HTTP/1.1 301 Moved Permanently");
		die;
	}

	public static function error400($message=""){
		header("HTTP/1.1 400 Bad Request");
		echo $message;
		die;
	}

	public static function error404($message=""){
		header("HTTP/1.1 404 Not Found");
		echo $message;
		die;
	}

	public static function error500($message=""){
		header("HTTP/1.1 500 Internal Server Error ");
		echo $message;
		die;
	}

	public static function jsondata(array $data){
		if(empty($data)){
			return $this->error404();
		}
		$this->error200();
		header("Content-Type: application/json");
		echo json_encode($data);
		die;
	}
}