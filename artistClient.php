#!/usr/bin/php
@ob_end_clean();
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
  }



require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

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
$request['type'] = "artist";
$request['artist'] = $artist;
$request['message'] = $msg;
$response = $client->send_request($request);

$_SESSION['artistData'] = $response;

      header("location: Frontend/searchArtist.php");
      exit;

?>


