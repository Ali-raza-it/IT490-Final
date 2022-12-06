#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$artist = $_POST['Search Artist'];

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
$request['artist'] = $artist;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);


if ($response!==0)) 
{
	$_SESSION['concertData'] = $response;
      	header("location: Frontend/searchConcert.php");
      	exit;
}
else
{
        header("location: Frontend/searchConcert.php");
        exit;
}


