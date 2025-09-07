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

// Check if the request method is POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the user is authenticated from the session.
    if (!isset($_SESSION["id"])) {
        echo json_encode(["status" => "error", "message" => "User not logged in."]);
        exit();
    }

    // Sanitize and retrieve POST data.
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $productPrice = filter_input(INPUT_POST, 'product_price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $productQty = filter_input(INPUT_POST, 'product_quantity', FILTER_SANITIZE_NUMBER_INT);
    $id = $_SESSION["id"];
    $cartStatus = "active"; // Default status for a new cart item.

    // Validate the data.
    if (!$productId || $productPrice === null || !$productQty) {
        echo json_encode(["status" => "error", "message" => "Invalid product data."]);
        exit();
    }

    // Create a new database connection.
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check the connection.
    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    // Prepare a SQL statement to prevent SQL injection.
    $sql = "INSERT INTO cart_item (cart_item_qty, cart_item_price, cart_status, cust_id, product_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare statement: " . $conn->error]);
        $conn->close();
        exit();
    }

    // Bind parameters and execute the statement.
    // 'idssi' is the type definition for the parameters: integer, double, string, string, integer.
    $stmt->bind_param("idssi", $productQty, $productPrice, $cartStatus, $id, $productId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Product added to cart successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error adding product to cart: " . $stmt->error]);
    }

    // Close the statement and connection.
    $stmt->close();
    $conn->close();

} else {
    // Not a POST request.
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
