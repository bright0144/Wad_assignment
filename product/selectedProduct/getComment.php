<?php
session_start(); // important!

// Database connection details.
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "test";

header('Content-Type: application/json');

if (!isset($_GET['product_id'])) {
  http_response_code(400);
  echo json_encode(["status" => "error", "message" => "Product ID not provided."]);
  exit();
}

$conn = new mysqli($servername, $db_username, $db_password, $dbname, 3307);
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
  exit();
}

$product_id = filter_var($_GET['product_id'], FILTER_SANITIZE_NUMBER_INT);
$current_user_id = isset($_SESSION['id']) ? filter_var($_SESSION['id'], FILTER_VALIDATE_INT) : 0;

$sql = "SELECT pr.product_review_id, pr.comment, pr.product_review_date, c.username 
        FROM product_review AS pr
        JOIN customer AS c ON pr.cust_id = c.cust_id
        WHERE pr.product_id = ? 
        ORDER BY pr.product_review_date DESC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
  http_response_code(500);
  echo json_encode(["status" => "error", "message" => "Failed to prepare statement: " . $conn->error]);
  $conn->close();
  exit();
}

$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];
while ($row = $result->fetch_assoc()) {
  $reviews[] = $row;
}

$like_sql = "SELECT rl.cust_id FROM review_like AS rl WHERE rl.product_review_id = ?";
$like_stmt = $conn->prepare($like_sql);

foreach ($reviews as &$review) {
  $review_id = $review['product_review_id'];
  $like_stmt->bind_param("i", $review_id);
  $like_stmt->execute();
  $like_result = $like_stmt->get_result();

  $liked_users = [];
  $liked_by_user = false;

  while ($like_row = $like_result->fetch_assoc()) {
    $cust_id = $like_row['cust_id'];
    $liked_users[] = $cust_id;

    if ($cust_id == $current_user_id) {
      $liked_by_user = true;
    }
  }

  $review['liked_users'] = $liked_users;
  $review['like_count'] = count($liked_users);
  $review['liked_by_user'] = $liked_by_user;
}

$like_stmt->close();
$stmt->close();
$conn->close();

echo json_encode($reviews);
