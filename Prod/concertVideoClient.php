#!/usr/bin/php
<?php
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$video = $_POST['search'];

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
$request['type'] = "concert video";
$request['video'] = $video;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

$_SESSION['concertVideo'] = $response;
	header("location: Frontend/concertVideo.php");
      	exit;


