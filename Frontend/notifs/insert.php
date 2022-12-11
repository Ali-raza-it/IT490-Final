<?php
//insert.php
if(isset($_POST["subject"]))
{
 include("connect.php");
 $userR = mysqli_real_escape_string($con, $_POST["userR"]);
 $userS = mysqli_real_escape_string($con, $_POST["userS"]);
 $subject = mysqli_real_escape_string($con, $_POST["subject"]);
 $comment = mysqli_real_escape_string($con, $_POST["comment"]);
 $lnk = mysqli_real_escape_string($con, $_POST["lnk"]);
 
 $query = "
 INSERT INTO comments(userR, userS, comment_subject, comment_text, lnk)
 VALUES ('$userR','$userS','$subject','$comment','$lnk');
 ";
 
 
 mysqli_query($con, $query);
}
?>
