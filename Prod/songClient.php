#!/usr/bin/php
<?php
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$song = $_POST['song'];

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
$request['type'] = "song";
$request['title'] = $song;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

$_SESSION['songData'] = $response;
	header("location: Frontend/searchSong.php");
	exit;
?>
