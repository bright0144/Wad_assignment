<?php
include_once("../../config/config.php");

if (!isset($_SESSION["id"])) {
    http_response_code(404);
    echo json_encode("User not logged in");
}

  
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


?>
