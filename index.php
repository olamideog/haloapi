<?php
include_once("vendor/autoload.php");
$request = new  Api\Tools\Request;
$Users = new Api\Models\Users('db_default');
/*echo "For Model Users <br/>";
echo "Default Table Name: ". $Users->table."<br/>";
echo "Default Primary Name: ". $Users->primaryKey."<br/>";
echo "<pre>";
var_dump($Users->get());
echo "</pre>";
echo "<br/><br/>";*/

/**
 * Typical request will be as follows
 * localhost/haloapi?username=olamide&apikey=639hddjiiu8029
 * Api\Core\Lights can also be imporved to use password encryption for the request that's been sent
 * but for demo purposes Ap\Core\light is been used so it is in demo mode
 *
 *
 * Additional requests types can be added. The data is using a database connection at the moment.
 * The model can be seen in Api\Models namespace.
 * There's still a lot to be done to make it production ready but this test shows the principle of 
 * Traits, Singleton and OOP
 */
$api_key = $request->get('api_key');
if(($request->get('api_key') == 'api_key_value') && ($request->get('username') == 'olamide')){
	foreach($request->post() as $key=>$value){
		if($key == 'method'){
			switch{
				case 'get/users';
					echo json_encode(array('status' => true, 'data' => $Users->get()));
				break;
			}		
		}
	}
}
?>