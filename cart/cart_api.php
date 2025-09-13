<?php
session_start();

//Necessary database connection
include_once("../config/config.php");

// Check user is logged in
if (!isset($_SESSION["id"])) {
  echo json_encode(["status" => "error", "message" => "User not logged in."]);
  exit();
}

//Retrieve user id for session variable
$cust_id = $_SESSION["id"];

//identify request
switch ( $_SERVER['REQUEST_METHOD']) {
  //get cart items
  case 'GET': 
    getCartDataFromDB($cust_id, $conn);
    break;

  //place order
  case 'POST':
  placeOrder($cust_id, $conn);
    break;

  // modify cart item quantity
  case 'PUT':
  modifyCartDataToDB($cust_id, $conn);
    break;

  //delete cart item
  case 'DELETE': 
    deleteCartFromDB($cust_id, $conn);
    break;

  //undefined method
  default:
    echo json_encode(["status" => "error", "message" => "Method undefined and not allowed"]);
    break;
}

function placeOrder($cust_id, $conn){

   $data = json_decode(file_get_contents("php://input"), true);
    
    // delivery address id
    $address_id = $data['address_id'] ;
    if (!$address_id) {
      echo json_encode(["status" => "error", "message" => "No address selected."]);
      exit();
    }


    if (isset($data['items'])) {
      //place order items
      $orderItems = $data['items'];
      //item total amount
      $total_amount = 0;

      // Transaction for insert query avoid interuption when inserting cart item 
      $conn->begin_transaction();

      try {

        // Calculate total amount
        foreach ($orderItems as $item) {
          $total_amount += ($item['cart_item_qty'] * $item['cart_item_price']);
        }

        // Insert into the order table
        $order_status = "pending";
        $order_date = date("Y-m-d H:i:s");
        $sqlOrder = "INSERT INTO `order` (address, order_date, order_status, total_amt, cust_id) VALUES (?, ?, ?, ?, ?)";
        $stmtOrder = $conn->prepare($sqlOrder);
        $stmtOrder->bind_param("issdi", $address_id, $order_date, $order_status, $total_amount, $cust_id);
        $stmtOrder->execute();
        //get auto increment order id 
        $order_id = $conn->insert_id;
        $stmtOrder->close();

        foreach ($orderItems as $item) {
          //retrieve data from cart
          $order_item_qty = $item['cart_item_qty'];
          $order_item_price = $item['cart_item_price'];
          $product_id = $item['product_id'];
          //put cart id into delete array
          $cart_item_delete = $item['cart_item_id'];

          // insert item into each item into table
          $sqlOrderItem = "INSERT INTO order_item (order_item_qty, order_item_price, order_id, product_id) VALUES (?, ?, ?, ?)";
          $stmtOrderItem = $conn->prepare($sqlOrderItem);
          $stmtOrderItem->bind_param("idii", $order_item_qty, $order_item_price, $order_id, $product_id);
          $stmtOrderItem->execute();

          //deduct stock
          $sqlUpdateStock = "UPDATE product SET stock_qty = stock_qty - ? WHERE product_id = ?";
          $stmtUpdateStock = $conn->prepare($sqlUpdateStock);
          $stmtUpdateStock->bind_param("ii", $order_item_qty, $product_id);
          $stmtUpdateStock->execute();

          //delete cart item
          $sqlDeleteCart = "DELETE FROM cart_item WHERE cart_item_id = ?";
          $stmtDeleteCart = $conn->prepare($sqlDeleteCart);
          $stmtDeleteCart->bind_param("i", $cart_item_delete);
          $stmtDeleteCart->execute();
          $stmtDeleteCart->close();  // Close it after execution

        }
        $stmtOrderItem->close();

        //stop transaction
        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Order placed successfully!"]);
      } catch (Exception $e) {
        //rollback transaction if error
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Order failed. Please try again and refresh page "]);
      }
    } else {
      echo json_encode(["status" => "error", "message" => "No items selected. Please try again. " ]);
    }
}

//modify cart quantity
function modifyCartDataToDB($cust_id, $conn){
  
    $data = json_decode(file_get_contents("php://input"), true);

    $cartItemId = $data['cart_item_id'];
    $newQuantity = $data['cart_item_qty'];

    if(empty($cartItemId) || empty($newQuantity)){
      echo json_encode(["status" => "error", "message" => "Invalid cart item or quantity."]);
      exit();
    }

    $sqlModifyCart = "UPDATE cart_item SET cart_item_qty = ? WHERE cart_item_id = ? AND cust_id = ?";
    $stmtModifyCart = $conn->prepare($sqlModifyCart);

    $stmtModifyCart->bind_param("iii", $newQuantity, $cartItemId, $cust_id);

    if ($stmtModifyCart->execute()) {
      echo json_encode(["status" => "success", "message" => "Cart item updated successfully!"]);
    } else {
      echo json_encode(["status" => "error", "message" => "Error updating cart item, Please try again and refresh the page "]);
    }
    $stmtModifyCart->close();
}

//get cart from DB
function getCartDataFromDB($cust_id, $conn){

    $sqlFetchCart = "SELECT ci.cart_item_id, p.product_id, p.product_name, 
    ci.cart_item_qty, ci.cart_item_price FROM cart_item AS ci JOIN product 
    AS p ON ci.product_id = p.product_id WHERE ci.cust_id = ?";
    $stmtFetchCart = $conn->prepare($sqlFetchCart);

    $stmtFetchCart->bind_param("i", $cust_id);
    if($stmtFetchCart->execute()) {
      $result = $stmtFetchCart->get_result();
  
      $items = [];
      while ($row = $result->fetch_assoc()) {
        $items[] = $row;
      }
      echo json_encode(["status" => "success", "message" => "Cart item updated successfully!", "items" => $items]);
    } else {
      echo json_encode(["status" => "error", "message" => "Error fetching data from database. Please refresh the page or try again."]);
    }
    $stmtFetchCart->close();
}

//delete cart item
function deleteCartFromDB($cust_id, $conn){

    $data = json_decode(file_get_contents("php://input"), true);

    $cartId = $data['cart_item_id'];

    if(empty($cartId)){
      echo json_encode(["status" => "error", "message" => "Invalid cart item."]);
      exit();
    }

    $sqlDeleteCart = "DELETE FROM cart_item WHERE cart_item_id = ? AND cust_id = ?";
    $stmtDeleteCart = $conn->prepare($sqlDeleteCart);

    $stmtDeleteCart->bind_param("ii", $cartId, $cust_id);

    if ($stmtDeleteCart->execute()) {
      echo json_encode(["status" => "success", "message" => "Cart item deleted successfully!"]);
    } else {
      echo json_encode(["status" => "error", "message" => "Error deleting cart item, Please try again and refresh the page "]);
    }
    $stmtDeleteCart->close();
}

$conn->close();
