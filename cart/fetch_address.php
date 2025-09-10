<?php
// Start the session to access user information.
session_start();

// Database connection details.
// IMPORTANT: Please replace the placeholder values with your actual database credentials.
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "test"; // Replace with your database name

// Set the content type to JSON for a clean response.
header('Content-Type: application/json');

// Check if the user is authenticated from the session.
if (!isset($_SESSION["id"])) {
  echo json_encode(["status" => "error", "message" => "User not logged in."]);
  exit();
}

$cust_id = $_SESSION["id"];

// Create a new database connection.
$conn = new mysqli($servername, $db_username, $db_password, $dbname, 3307);

// Check the connection.
if ($conn->connect_error) {
  echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
  exit();
}

try {
  if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Prepare a SQL statement to fetch all orders for the logged-in user.
    $sql = "SELECT * FROM `customer_addresses` WHERE cust_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
      throw new Exception("Failed to prepare statement: " . $conn->error);
    }
    $stmt->bind_param("i", $cust_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $addresses = [];
    while ($row = $result->fetch_assoc()) {
      $addresses[] = $row;
    }

    $stmt->close();
    echo json_encode(["status" => "success", "addresses" => $addresses]);
  }
} catch (Exception $e) {
  echo json_encode(["status" => "error", "message" => "An error occurred: " . $e->getMessage()]);
}

$conn->close();
