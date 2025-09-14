<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

$current_user_id = filter_var($_SESSION['id'], FILTER_VALIDATE_INT);

include_once("../config/config.php");

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    $orders = [];

    $sql = "
    SELECT 
        o.order_id, o.order_date, o.order_status, o.total_amt, o.cust_id, 
        ca.address 
    FROM `order` o
    JOIN customer_addresses ca ON o.address = ca.address_id
    WHERE o.cust_id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

    // Add order items
    foreach ($orders as &$order) {
        $orderItems = [];
        $orderId = $order['order_id'];

        $stmtItems = $conn->prepare("SELECT oi.order_item_id, oi.order_item_qty,
         oi.order_item_price, p.product_name, p.product_id FROM order_item oi JOIN product
          p ON oi.product_id = p.product_id WHERE oi.order_id = ?");
        $stmtItems->bind_param("i", $orderId);
        $stmtItems->execute();
        $resultItems = $stmtItems->get_result();

        if ($resultItems->num_rows > 0) {
            while ($row = $resultItems->fetch_assoc()) {
                $orderItems[] = $row;
            }
        }

        $order['items'] = $orderItems;
    }

    echo json_encode(["status" => "success", "orders" => $orders]);
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? null;

    if (!$action) {
        echo json_encode(["status" => "error", "message" => "Invalid action. Please refresh page and try again."]);
        exit();
    }

    if ($action === 'addReview') {
        $comment = $data['comment'] ?? null;
        $productId = $data['productId'] ?? null;

        if (!$comment || !$productId) {
            echo json_encode(["status" => "error", "message" => "Missing comment or productId"]);
            exit();
        }

        $product_review_date = date("Y-m-d H:i:s");
        $sql = "INSERT INTO product_review(`comment`, `product_id`, `cust_id`, `product_review_date`) VALUES(?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siis", $comment, $productId, $current_user_id, $product_review_date);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Review has been inserted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Inserting review failed. Please try again and refresh page."]);
        }
    } elseif ($action === 'cancelOrder') {
        $orderId = $data['orderId'] ?? null;

        if (!$orderId) {
            echo json_encode(["status" => "error", "message" => "Missing orderId for cancellation"]);
            exit();
        }

        $sql = "UPDATE `order` SET order_status = 'cancelled' WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $orderId);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Order has been cancelled successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Cancelling order failed. Please try again and refresh page."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Cancelling order failed. Please try again and refresh page."]);
    }
}
