#!/usr/bin/php
<?php
  session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$username = $_GET['user'];

if($username==$_SESSION['response'][0]){
header("location: Frontend/Profile.php");
      exit;
      }

$client = new rabbitMQClient("DBProdRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}

$request = array();
$request['type'] = "search user all";
$request['username'] = $username;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

$_SESSION['userSearched'] = $response;

header('Location: Frontend/SearchedProfile.php');

echo $argv[0]." END".PHP_EOL;
