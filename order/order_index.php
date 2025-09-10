<?php
include_once("../../config/config.php");

if (!isset($_SESSION["id"])) {
  echo json_encode(["status" => "error", "message" => "User not logged in."]);
  exit();
}

$cust_id = $_SESSION["id"];


try {
  if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Prepare a SQL statement to fetch all orders for the logged-in user.
    $sql = "SELECT 
    o.*, 
    ca.address -- or ca.address_name if that's the column with the address text
FROM `Order` o
LEFT JOIN customer_addresses ca ON o.address = ca.address_id
WHERE o.cust_id = ?
ORDER BY o.order_date DESC;
";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
      throw new Exception("Failed to prepare statement: " . $conn->error);
    }
    $stmt->bind_param("i", $cust_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
      // Check if the order can be cancelled (less than 1 day old and not already cancelled).
      $orderDate = new DateTime($row['order_date']);
      $currentDate = new DateTime();
      $interval = $orderDate->diff($currentDate);
      $row['cancellable'] = ($interval->days < 1 && $row['order_status'] !== 'cancelled');
      $orders[] = $row;
    }

    $stmt->close();
    echo json_encode(["status" => "success", "orders" => $orders]);
  } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle order cancellation request.
    $data = json_decode(file_get_contents("php://input"), true);
    $order_id = $data['order_id'] ?? null;

    if (!$order_id) {
      echo json_encode(["status" => "error", "message" => "Invalid order ID."]);
      exit();
    }

    // Prepare a SQL statement to update the order status to cancelled,
    // but only if it's less than 1 day old.
    $sql_cancel = "UPDATE `Order` SET order_status = 'cancelled' WHERE order_id = ? AND cust_id = ? AND TIMESTAMPDIFF(HOUR, order_date, NOW()) < 24";
    $stmt_cancel = $conn->prepare($sql_cancel);
    if (!$stmt_cancel) {
      throw new Exception("Failed to prepare statement: " . $conn->error);
    }
    $stmt_cancel->bind_param("ii", $order_id, $cust_id);
    $stmt_cancel->execute();

    if ($stmt_cancel->affected_rows > 0) {
      echo json_encode(["status" => "success", "message" => "Order successfully cancelled."]);
    } else {
      echo json_encode(["status" => "error", "message" => "Failed to cancel order. It might be too late to cancel."]);
    }
    $stmt_cancel->close();
  }
} catch (Exception $e) {
  echo json_encode(["status" => "error", "message" => "An error occurred: " . $e->getMessage()]);
}

$conn->close();
