<?php
// Database connection details.
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "test"; 

// Set the content type to JSON for a consistent response.
header('Content-Type: application/json');

// Check if product_id is provided in the query string.
if (!isset($_GET['product_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Product ID not provided."]);
    exit();
}

// Create a database connection.
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check the connection.
if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$product_id = filter_var($_GET['product_id'], FILTER_SANITIZE_NUMBER_INT);

// Use a prepared statement to prevent SQL injection.
$sql = "SELECT pr.comment, pr.product_review_date, c.username 
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

echo json_encode($reviews);

$stmt->close();
$conn->close();
?>
