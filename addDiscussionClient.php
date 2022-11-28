#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$username = $_POST['username'];
$content = $_POST['content'];
$time = $_POST['time'];
$topicID = $_POST['topic'];

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
$request['username'] = $username;
$request['post content'] = $content;
$request['timestamp'] = $time; 
$request['topic'] = $topicID;
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

