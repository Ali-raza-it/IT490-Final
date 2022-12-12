<?php 
session_start();
if(!isset($_SESSION['valid']) OR $_SESSION['valid'] !== true){
header("location: login.php");
exit;
}
if(isset($_SESSION['response'])){
$response = $_SESSION['response'];

$uname = $response[0];
$fname = $response[1];
$lname = $response[2];
$email = $response[3];

}

if(isset($_SESSION['userSearched'])){
$sruser = $_SESSION['userSearched'];

$sruname = $sruser[0][0];
$srfname = $sruser[0][1];
$srlname = $sruser[0][2];
$sremail = $sruser[0][3];

}
include("notifs/connect.php");


//delete
if(isset($_POST["accept"]))
{

//accept friendship goes here

}
require_once('../path.inc');
require_once('../get_host_info.inc');
require_once('../rabbitMQLib.inc');

$username = $uname;
$friendusername = $sruname;

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
$request['type'] = "add friend";
$request['username'] = $username;
$request['friendusername'] = $friendusername;
$request['message'] = $msg;
$FL = $client->send_request($request);

if($FL!=0){
$query = "delete from comments where userR='".$uname."' and userS='".$sruname."'";
$result = mysqli_query($con, $query);
}

$_SESSION['friendlist'] = $FL;

header("location: SearchedProfile.php");
exit;

?>
