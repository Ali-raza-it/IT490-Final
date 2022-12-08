<?php
session_start();
$db_username = 'root';
$db_password = 'roo';
$conn = new pdo( 'mysql:host=localhost;dbname=IT490', $db_username, $db_password);
if(!$conn){
    die("Fatal Error: connection failed");
}