#!/usr/bin/php
<?php
session_start();
  if(!isset($_SESSION['valid']) OR $_SESSION['valid'] !== true){
      header("location: login.php");
      exit;
 }
  if(isset($_SESSION['response'])){
      $rep = $_SESSION['response'];

      $uname = $rep[0];
      $fname = $rep[1];
      $lname = $rep[2];
      $email = $rep[3];
      include "nav.php";
  }

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

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
$request['type'] = "user rec";
$request['username'] = $uname;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

$_SESSION['recData'] = $response;

	header("location: Frontend/userRec.php");
        exit;
?>
