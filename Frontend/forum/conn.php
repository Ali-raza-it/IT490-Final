<?php
session_start();
$db_username = 'testUser';
$db_password = '12345';
$conn = new PDO( 'mysql:host=localhost;dbname=testdb', $db_username, $db_password );
if(!$conn){
die("Fatal Error: Connection Failed!");
}

?>