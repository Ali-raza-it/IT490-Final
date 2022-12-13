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

