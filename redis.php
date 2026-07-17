<?php

try{

$redis = new Redis();

$redis->connect("127.0.0.1",6379);

}
catch(Exception $e){

die("Redis Error : ".$e->getMessage());

}

?>