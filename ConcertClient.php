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

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

//if ($response=='true') {
  //header('Location: Frontend/landing.php');
//}
//else {
  //header('Location: Frontend/login.php');
//}
echo $argv[0]." END".PHP_EOL;

