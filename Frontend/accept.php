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

//header("location: SearchedProfile.php");
//exit;

?>
<h1> Accept friend request page! </h1>
<h4> this currently does not do anything so redirecting you back! </h4>
<meta http-equiv="refresh" content="7; url='SearchedProfile.php'" />
