<?php
session_start();

if (!isset($_SESSION['id'])) {
  header("Location: ../index.php");
  exit;
}
$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['address_id'])) {
  include_once("../config/config.php");
  $addressId = (int)$_GET["address_id"];
  $stmt = $conn->prepare("DELETE FROM customer_addresses WHERE address_id = ?;");
  $stmt->bind_param("i", $addressId);
  if (!$stmt->execute()) {
    $response['success'] = false;
    $response['message'] = "Error: " . $stmt->error;
  } else {
    $response['success'] = true;
    $response['message'] = "Successfully deleleted the address";
  }

  $stmt->close();
  $conn->close();
}

echo json_encode($response);
