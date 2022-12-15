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
$request['type'] = "concert";
$request['username'] = $uname;
$request['artist'] = $artist;
$request['message'] = $msg;
$response = $client->send_request($request);

$con = mysqli_connect("localhost", "ali", "12345", "testdb");

foreach ($response as $concert) { 

//select * from comments where userR='arm4' and comment_text='Drake performing in Bogota at Festival Estereo Picnic 2023 on 2023-03-23';

$a = 'Concert Alert for '.$concert[4];
$b = $concert[1]. ' performing in '. $concert[2]. ' at ' .$concert[0]. ' on ' .$concert[4];
$c = $concert[4];
//$query = "INSERT INTO comments (userR, comment_subject, comment_text) VALUES ('$uname', '$a', '$b');";
$query = "select * from comments where userR='$uname' and comment_text='$b';";

$result = mysqli_query($con, $query);

    $rowcount=mysqli_num_rows($result);
    
    if($rowcount>0){
    }
    else{
    $q1 = "INSERT INTO comments (userR, comment_subject, comment_text, dt) VALUES ('$uname', '$a', '$b', '$c');";
    $result = mysqli_query($con, $q1);
    }


}

$_SESSION['concertData'] = $response;
	header("location: Frontend/searchConcert.php");
      	exit;

?>


