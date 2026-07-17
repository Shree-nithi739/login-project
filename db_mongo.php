<?php

require_once __DIR__ . "/../vendor/autoload.php";

try{

$client = new MongoDB\Client(
"mongodb://admin:password@127.0.0.1:27017"
);

$db = $client->login_project;

$collection = $db->users;

}
catch(Exception $e){

die("MongoDB Error : ".$e->getMessage());

}

?>