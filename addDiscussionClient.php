#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$name = $_SESSION['name'];
$reply = $_POST['commentid'];
$parent = $_POST['messageone'];



$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}

$request = array();
$request['type'] = "add discussion";
$request['username'] = $name;
$request['content'] = $reply;
$request['parentComent'] = $parent;
$request['msg'] = $msg; 
$response = $client->send_request($request);
//$response = $client->publish($request);

if ($response=='true') {
  header('Location: Frontend/forum/index.php');
}
//else {
  //header('Location: Frontend/login.php');
//}

