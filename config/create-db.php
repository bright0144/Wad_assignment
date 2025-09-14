<?php
$hostName = "localhost";
$username = "root";
$password = "";
$port = 3307;

$conn = mysqli_connect($hostName, $username, $password, null, $port);

if (mysqli_connect_error()) {
  die("Connection to MySQL has failed: " . $conn->connect_error);
}

$dbName = "P5_G1";

$dropDb = "DROP DATABASE IF EXISTS `$dbName`";

if (mysqli_query($conn, $dropDb)) {
  echo "DB dropped successfully!<br>";
} else {
  echo "Error dropping db: " . $conn->error;
}

$createDb = "CREATE DATABASE IF NOT EXISTS `$dbName`";

if (mysqli_query($conn, $createDb)) {
  echo "DB created successfully<br>";
} else {
  echo "Error dropping db: " . $conn->error;
}

mysqli_close($conn);
