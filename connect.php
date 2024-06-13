<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$basename = "projekt";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $basename, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
