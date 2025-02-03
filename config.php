<?php
$hostName = "localhost";
$dbuser = "root";
$dbPassword = "";
$dbName = "signup";

$conn = mysqli_connect($hostName, $dbuser, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
