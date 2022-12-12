<?php
include 'conn.php';
$id = mysqli_real_escape_string($pdo,$_POST['id']);
$name = mysqli_real_escape_string($pdo,$_POST['name']);
$msg = mysqli_real_escape_string($pdo,$_POST['msg']);
if($name != "" && $msg != ""){
	$sql = $conn->query("INSERT INTO discussion (parent_comment, student, post)
			VALUES ('$id', '$name', '$msg')");
	echo json_encode(array("statusCode"=>200));
}
else{
	echo json_encode(array("statusCode"=>201));
}
$conn = null;

?>