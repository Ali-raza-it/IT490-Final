#!/usr/bin/php
<?php
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$username = $_SESSION['username'];
$artist = $_POST['artist'];

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
$request['type'] = "concert";
$request['username'] = $username;
$request['artist'] = $artist;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

$_SESSION['concertData'] = $response;
	header("location: Frontend/searchConcert.php");
      	exit;

?>
