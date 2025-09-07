<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("Location: ../index.php");
  exit;
}

header('Content-type: application/json');
$response = ['success' => false, 'message' => ''];

$input = json_decode(file_get_contents("php://input"), true);

if (
  $_SERVER['REQUEST_METHOD'] === "POST"
  && isset($input["email"])
  && isset($input["password"])
  && isset($input["phone_number"])
) {

  include_once("../config/config.php");

  $userId      = (int)$_SESSION['id'];
  $email       = trim($input["email"]);
  $password    = trim($input["password"]);
  $phoneNumber = trim($input["phone_number"]);

  // Check if email exists
  $stmt = $conn->prepare("SELECT cust_id FROM customer WHERE email = ? AND cust_id != ?");
  $stmt->bind_param("si", $email, $userId);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    $response['success'] = false;
    $response['message'] = "Email is already registered with another account.";
    echo json_encode($response);
    exit;
  }
  $stmt->close();

  // Check if phone number exists
  $stmt = $conn->prepare("SELECT cust_id FROM customer WHERE phone_number = ? AND cust_id != ?");
  $stmt->bind_param("si", $phoneNumber, $userId);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    $response['success'] = false;
    $response['message'] = "Phone number is already registered with another account.";
    echo json_encode($response);
    exit;
  }
  $stmt->close();

  // Update now
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $conn->prepare("
    UPDATE customer 
    SET email = ?, password = ?, phone_number = ?
    WHERE cust_id = ?
  ");
  $stmt->bind_param("sssi", $email, $hashedPassword, $phoneNumber, $userId);

  if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
      $response['success'] = true;
      $response['message'] = "Profile updated successfully";
    } else {
      $response['success'] = false;
      $response['message'] = "No changes made";
    }
  } else {
    $response['success'] = false;
    $response['message'] = "Error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
} else {
  $response['success'] = false;
  $response['message'] = "Invalid request or missing fields";
}

echo json_encode($response);

