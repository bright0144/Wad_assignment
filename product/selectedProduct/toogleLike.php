<?php
session_start();
header('Content-Type: application/json');

// DB connection details
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "test"; 

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

$current_user_id = $_SESSION['id'];

// Check for POST parameters
if (!isset($_POST['product_review_id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Review ID not provided."]);
    exit();
}

$product_review_id = filter_var($_POST['product_review_id'], FILTER_SANITIZE_NUMBER_INT);

// Create DB connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Check if the user already liked this review
$check_sql = "SELECT 1 FROM review_like WHERE product_review_id = ? AND cust_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $product_review_id, $current_user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // User already liked it - remove the like (unlike)
    $delete_sql = "DELETE FROM review_like WHERE product_review_id = ? AND cust_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("ii", $product_review_id, $current_user_id);
    if ($delete_stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Review unliked."]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to unlike review."]);
    }
    $delete_stmt->close();
} else {
    // User has not liked it yet - add a like
    $insert_sql = "INSERT INTO review_like (product_review_id, cust_id) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ii", $product_review_id, $current_user_id);
    if ($insert_stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Review liked."]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to like review."]);
    }
    $insert_stmt->close();
}

$check_stmt->close();
$conn->close();
