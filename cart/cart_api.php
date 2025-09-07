<?php
// Start the session to access user information.
session_start();

// Database connection details.
// IMPORTANT: Replace these with your actual database credentials.
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "test"; 

// Set the content type to JSON for a consistent response.
header('Content-Type: application/json');

// Check if the user is authenticated.
if (!isset($_SESSION["id"])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

// Create a database connection.
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check the connection.
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$id = $_SESSION["id"];
$method = $_SERVER['REQUEST_METHOD'];

// Handle different request methods
switch ($method) {
    case 'GET': // Read operation
        $sql = "SELECT ci.cart_item_id, p.product_id, p.product_name, ci.cart_item_qty, ci.cart_item_price FROM cart_item AS ci JOIN product AS p ON ci.product_id = p.product_id WHERE ci.cust_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo json_encode(["status" => "error", "message" => "Failed to prepare statement: " . $conn->error]);
            $conn->close();
            exit();
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        
        echo json_encode($items);
        $stmt->close();
        break;

    case 'POST': // Create operation
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if the request is to place an order (contains an array of items)
        if (isset($data['items']) && is_array($data['items'])) {
            $items_to_order = $data['items'];
            $total_amount = 0;
            $cart_item_ids_to_delete = [];

            // Begin transaction for data integrity
            $conn->begin_transaction();

            try {
                // Fetch customer address for the order
                $sql_address = "SELECT address FROM customer WHERE cust_id = ?";
                $stmt_address = $conn->prepare($sql_address);
                $stmt_address->bind_param("i", $id);
                $stmt_address->execute();
                $result_address = $stmt_address->get_result();
                $customer_data = $result_address->fetch_assoc();
                $address = $customer_data['address'] ?? 'N/A';
                $stmt_address->close();

                // Calculate total amount
                foreach ($items_to_order as $item) {
                    $total_amount += ($item['cart_item_qty'] * $item['cart_item_price']);
                }

                // Insert into the Order table
                $order_status = "pending";
                $order_date = date("Y-m-d H:i:s");
                $sql_order = "INSERT INTO `Order` (address, order_date, order_status, total_amt, cust_id) VALUES (?, ?, ?, ?, ?)";
                $stmt_order = $conn->prepare($sql_order);
                $stmt_order->bind_param("sssdi", $address, $order_date, $order_status, $total_amount, $id);
                $stmt_order->execute();
                $order_id = $conn->insert_id;
                $stmt_order->close();

                // Insert into the order_item table and collect cart item IDs to delete
                $sql_order_item = "INSERT INTO order_item (order_item_qty, order_item_price, order_id, product_id) VALUES (?, ?, ?, ?)";
                $stmt_order_item = $conn->prepare($sql_order_item);

                $sql_update_stock = "UPDATE product SET stock_qty = stock_qty - ? WHERE product_id = ?";
                $stmt_update_stock = $conn->prepare($sql_update_stock);

                foreach ($items_to_order as $item) {
                    $order_item_qty = $item['cart_item_qty'];
                    $order_item_price = $item['cart_item_price'];
                    $product_id = $item['product_id'];
                    $cart_item_ids_to_delete[] = $item['cart_item_id'];

                    $stmt_order_item->bind_param("idii", $order_item_qty, $order_item_price, $order_id, $product_id);
                    $stmt_order_item->execute();

                       // Update product stock
                        $stmt_update_stock->bind_param("ii", $order_item_qty, $product_id);
                        $stmt_update_stock->execute();
                }
                $stmt_order_item->close();

                // Delete items from the cart_item table
                $placeholders = implode(',', array_fill(0, count($cart_item_ids_to_delete), '?'));
                $sql_delete = "DELETE FROM cart_item WHERE cart_item_id IN ($placeholders) AND cust_id = ?";
                $stmt_delete = $conn->prepare($sql_delete);
                
                // Construct the types string for bind_param
                $types = str_repeat('i', count($cart_item_ids_to_delete)) . 'i';
                
                // Bind the parameters
                $params = array_merge($cart_item_ids_to_delete, [$id]);
                $stmt_delete->bind_param($types, ...$params);
                $stmt_delete->execute();
                $stmt_delete->close();
                
                // Commit the transaction
                $conn->commit();
                echo json_encode(["status" => "success", "message" => "Order placed successfully!"]);

            } catch (Exception $e) {
                // Rollback on error
                $conn->rollback();
                echo json_encode(["status" => "error", "message" => "Order failed: " . $e->getMessage()]);
            }

        } else {
            // Original 'Add to Cart' functionality
            $productId = filter_var($data['product_id'], FILTER_SANITIZE_NUMBER_INT);
            $productPrice = filter_var($data['product_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $productQty = filter_var($data['product_quantity'], FILTER_SANITIZE_NUMBER_INT);
            $cartStatus = filter_var($data['cart_status'], FILTER_SANITIZE_STRING);

            $sql = "INSERT INTO cart_item (cust_id, product_id, cart_item_qty, cart_item_price, cart_status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                echo json_encode(["status" => "error", "message" => "Failed to prepare statement: " . $conn->error]);
                $conn->close();
                exit();
            }
            
            $stmt->bind_param("iisds", $id, $productId, $productQty, $productPrice, $cartStatus);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Product added to cart successfully!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding product to cart: " . $stmt->error]);
            }
            $stmt->close();
        }
        break;

    case 'PUT': // Update operation
        $data = json_decode(file_get_contents("php://input"), true);
        
        $cartId = filter_var($data['cart_item_id'], FILTER_SANITIZE_NUMBER_INT);
        $newQty = filter_var($data['cart_item_qty'], FILTER_SANITIZE_NUMBER_INT);

        $sql = "UPDATE cart_item SET cart_item_qty = ? WHERE cart_item_id = ? AND cust_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            echo json_encode(["status" => "error", "message" => "Failed to prepare statement: " . $conn->error]);
            $conn->close();
            exit();
        }

        $stmt->bind_param("iii", $newQty, $cartId, $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Cart item updated successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating cart item: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE': // Delete operation
        $data = json_decode(file_get_contents("php://input"), true);
        
        $cartId = filter_var($data['cart_item_id'], FILTER_SANITIZE_NUMBER_INT);

        $sql = "DELETE FROM cart_item WHERE cart_item_id = ? AND cust_id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            echo json_encode(["status" => "error", "message" => "Failed to prepare statement: " . $conn->error]);
            $conn->close();
            exit();
        }

        $stmt->bind_param("ii", $cartId, $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Cart item deleted successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting cart item: " . $stmt->error]);
        }
        $stmt->close();
        break;
        
    default:
        // Handle unsupported methods
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

$conn->close();
?>
