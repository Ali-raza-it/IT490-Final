<?php
//insert.php

if(isset($_POST["subject"]))
{

 include("connect.php");
	if(isset($_POST["userS"]) and $_POST["userS"]!='')
	{
	$userS = mysqli_real_escape_string($con, $_POST["userS"]);
	$lnk = '../searchUserClient.php?user='.$userS;
	}

 $userR = mysqli_real_escape_string($con, $_POST["userR"]);

 $subject = mysqli_real_escape_string($con, $_POST["subject"]);
 $comment = mysqli_real_escape_string($con, $_POST["comment"]);
 
 $query = "
 INSERT INTO comments(userR, userS, comment_subject, comment_text, lnk)
 VALUES ('$userR','$userS','$subject','$comment','$lnk');
 ";
 
 
 mysqli_query($con, $query);
}


?>
