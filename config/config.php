<?php
$hostName = "localhost";
$username = "root";
$password = "";
$dbName = "P5_G1";
$port = 3307;

$conn = mysqli_connect($hostName, $username, $password, $dbName, $port);

if ($conn->connect_error) {
  die("Connection to MySQL has failed: " . mysqli_connect_error());
}
