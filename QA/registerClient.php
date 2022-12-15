#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$username = $_POST['username'];
$password = $_POST['password'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$confirmPassword = $_POST['confirm_Password'];

if($password !== $confirmPassword)
{
	header('Location: Frontend/register.php');
	exit();
}	


$client = new rabbitMQClient("DBQARabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}

$request = array();
$request['type'] = "register";
$request['username'] = $username;
$request['password'] = $password;
$request['firstname'] = $firstname;
$request['lastname'] = $lastname;
$request['email'] = $email;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
echo "\n\n";

if ($response==1) {
  header('Location: Frontend/login.php');
}
else {
  header('Location: Frontend/register.php');
}


echo $argv[0]." END".PHP_EOL;

