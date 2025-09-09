<?php
session_start();
include_once("../../config/config.php");

// Read raw JSON body
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

if (!isset($input['productReviewId'], $input['isLiked'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid data."]);
    exit();
}

$productReviewId = intval($input['productReviewId']);
$isLiked = filter_var($input['isLiked'], FILTER_VALIDATE_BOOLEAN);



if ($isLiked) {
    // Unlike
    $sql = "DELETE FROM review_like WHERE product_review_id = ? AND cust_id = ?";
} else {
    // Like
    $sql = "INSERT INTO review_like (product_review_id, cust_id) VALUES (?, ?)";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $productReviewId, $_SESSION['id']);

if ($stmt->execute()) {
        http_response_code(200);
    echo json_encode(["status" => "success", "message" => $isLiked ? "Review unliked." : "Review liked."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error."]);
}
$stmt->close();
$conn->close();
