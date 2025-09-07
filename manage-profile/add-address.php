<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("Location: ../index.php");
  exit;
}

header('Content-type: application/json');
$response = ['success' => false, 'message' => ''];

$input = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($input["address"]) && isset($input["state"]) && isset($input["postcode"])) {
  include_once("../config/config.php");

  $userId = (int)$_SESSION['id'];
  $address = trim($input["address"]);
  $state = trim($input["state"]);
  $postcode = trim($input["postcode"]);

  $stmt = $conn->prepare("INSERT INTO customer_addresseses (address, postcode, state, cust_id) VALUES (?, ?, ?, ?)");

  $stmt->bind_param("sssi", $address, $postcode, $state, $userId);

  if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = "Address saved successfully";
    $response['address_id'] = $stmt->insert_id;
  } else {
    $response['message'] = "Error: " . $stmt->error;
    $response['success'] = false;
  }

  $stmt->close();
  $conn->close();
}

echo json_encode($response);
