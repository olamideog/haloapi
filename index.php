<?php
include_once("vendor/autoload.php");

$Users = new Api\Models\Users;
echo "For Model Users <br/>";
echo "Default Table Name: ". $Users->table."<br/>";
echo "Default Primary Name: ". $Users->primaryKey."<br/>";
echo "<pre>";
var_dump($Users->get());
echo "</pre>";
echo "<br/><br/>";

$request = new  Api\Tools\Request;
var_dump(get_class_methods($request));
?>