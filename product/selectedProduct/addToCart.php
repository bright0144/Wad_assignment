<?php
session_start();

include_once("../../config/config.php");

//Check user logged in
if (!isset($_SESSION["id"])) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

// Get raw POST data and decode JSON
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

$productId = isset($input['productId']) ? intval($input['productId']) : null;
$productPrice = isset($input['productPrice']) ? floatval($input['productPrice']) : null;
$productQty = isset($input['productQuantity']) ? intval($input['productQuantity']) : null;

$id = $_SESSION["id"];
$cartStatus = "active";

if ($productId === null || $productPrice === null || $productQty === null || $productId <= 0 || $productQty <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid product data."]);
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
        echo json_encode(["status" => "success", "message" => "Selected card is added to cart successfully! "]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error adding product to cart: " . $stmt->error]);
    }

    // Close the statement and connection.
    $stmt->close();
    $conn->close();


?>
