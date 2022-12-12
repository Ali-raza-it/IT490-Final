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

//add

if(isset($_POST["sendReq"]))
{
$userS = $uname;
$userR = $sruname;
$lnk = '../searchUserClient.php?user='.$userS;

$query = "
INSERT INTO comments(userR, userS, comment_subject, comment_text, lnk)
VALUES ('$userR','$userS','Friend Request','Friend Request From ".$uname."','$lnk');
";
$result = mysqli_query($con, $query); 
}

header("location: SearchedProfile.php");
exit;

?>
