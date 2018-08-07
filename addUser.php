<?php

$servername = "localhost";
$username = "chiron_svc";
$password = "first-S3cr3t";
$dbname = "chiron";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully<br>";

$username = 'kostya';
$email = '';
$level = 10;
$confirmed = 1;
$approved = 1;
#$sql = "update users(username, password, email, userlevel, emailConfirmed, emailApproved) values('$username', '$password', '$email', $level, $confirmed, $approved)";
$sql = "update users set password='$password' where username='kostya'";
$conn->query($sql);

$conn->close();

