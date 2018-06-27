<?php
namespace Api\Core;

/**
 * Purpose is to turn on the lights after verification
 * This is the full version
 */
trait Lights{
	protected static function verifyRequestOrigin(){
		return true;
	}

	protected static function verifyRequestKey($request){
		try{
			$conn = new \PDO("mysql:host=localhost;dbname=api", "root", "");
			$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			try{
				$sth = $conn->prepare("SELECT * FROM accounts WHERE account_name=':username' LIMIT 1");
				$sth->bindParam(':username', $request->get('username'));
				$result = $sth->execute();
				if($result->rowCount() == 1){
					foreach($result->fetchAll(\PDO::FETCH_ASSOC) as $row){
						if($row['account_apikey'] == password_hash($request->get('apiKey'))){
							return true;
						}
					}
				}
			}catch(\Exception $e){
				\Api\Tools\Response::error400("Key verification Failed");
			}			

		}catch(\PDOException $e){
			\Api\Tools\Response::error500($e->getMessage()." Key verification Failed");
		}
	}

	protected static function checkLights(){
		if(!empty($_SESSION)  && ($_SESSION['authenticated'] === true)){
			return true;
		}elseif((self::verifyRequestKey(new \Api\Tools\Request()) === true) && (self::verifyRequestOrigin() === true)){
			session_start();
			$_SESSION['authenticated'] = true;
			return true;
		}else{
			\Api\Tools\Response::error404("Lights Won't turn on as account doesn't exist");
		}
	}
}