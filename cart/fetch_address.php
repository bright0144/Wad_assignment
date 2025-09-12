<?php

session_start();

//Necessary database connection
include_once("../config/config.php");

// Check user is logged in
if (!isset($_SESSION["id"])) {
  echo json_encode(["status" => "error", "message" => "User not logged in."]);
  exit();
}

//Retrieve user id for session variable
$cust_id = $_SESSION["id"];

//fetch address
try {
   
    $sqlFetchAddress = "SELECT * FROM `customer_addresses` WHERE cust_id = ?";
    $stmtFetchAddress = $conn->prepare($sqlFetchAddress);
   
    $stmtFetchAddress->bind_param("i", $cust_id);
    $stmtFetchAddress->execute();
    $result = $stmtFetchAddress->get_result();

    $addresses = [];
    while ($row = $result->fetch_assoc()) {
      $addresses[] = $row;
    }

    $stmtFetchAddress->close();
    echo json_encode(["status" => "success", "addresses" => $addresses]);
} catch (Exception $e) {
  echo json_encode(["status" => "error", "message" => "An error occurred when fetching address, " . $e->getMessage()]);
}

$conn->close();
