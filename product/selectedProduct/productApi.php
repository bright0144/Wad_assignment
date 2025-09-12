<?php
session_start();

//Necessary database connection
include_once("../../config/config.php");

// Check user is logged in
if (!isset($_SESSION["id"])) {
  echo json_encode(["status" => "error", "message" => "User not logged in."]);
  exit();
}

//Retrieve user id for session variable
$cust_id = $_SESSION["id"];

//identify request
switch ( $_SERVER['REQUEST_METHOD']) {
  //get comment
  case 'GET': 
    getComment($cust_id, $conn);
    break;

  //add to cart
  case 'POST':
  addToCart($cust_id, $conn);
    break;

  // toogle like
  case 'PUT':
  toggleLike($cust_id, $conn);
    break;

  //undefined method
  default:
    echo json_encode(["status" => "error", "message" => "Method undefined and not allowed"]);
    break;
}

function addToCart($cust_id, $conn) {

  $data = json_decode(file_get_contents('php://input'), true);

  $productId = $data['productId'];
  $productPrice = $data['productPrice'];
  $productQuantity = $data['productQuantity'];

  if(empty($productId) || empty($productPrice) || empty($productQuantity)) {
    echo json_encode(["status" => "error", "message" => "No item selected."]);
    exit();
  }
  try {

    //check item exisited 
    $sqlQueryCart = "SELECT cart_item_id FROM cart_item where cust_id = ? and product_id = ? ";
    $stmtQueryCart = $conn->prepare($sqlQueryCart);
  
    $stmtQueryCart->bind_param("ii",$cust_id, $productId);
    if($stmtQueryCart->execute()){
      $result = $stmtQueryCart->get_result();
      //Item is in cart
      if($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $cartItemId = $row['cart_item_id'];
        $sqlUpdateCart = "UPDATE cart_item SET cart_item_qty = cart_item_qty + ? WHERE cart_item_id = ?";
        $stmtUpdateCart  = $conn->prepare($sqlUpdateCart);
        $stmtUpdateCart->bind_param("ii",$productQuantity, $cartItemId);
        $stmtUpdateCart->execute();
        echo json_encode(["status" => "success", "message" => "Selected card is added to cart successfully! "]);
        $stmtUpdateCart->close();

        //item not in cart
      } else {
        $cartStatus = "active";
        $sqlInsertCart = "INSERT INTO cart_item (cart_item_qty, cart_item_price, cart_status, cust_id, product_id) VALUES (?, ?, ?, ?, ?)";
        $stmtInsertCart = $conn->prepare($sqlInsertCart);
        $stmtInsertCart->bind_param("idsii", $productQuantity, $productPrice, $cartStatus, $cust_id, $productId);
        $stmtInsertCart->execute();
        echo json_encode(["status" => "success", "message" => "Selected card is added to cart successfully! "]);
        $stmtInsertCart->close();
      }
    } 

    $conn->close();
  } catch (Exception $e){
    echo json_encode(["status" => "error", "message" => "Database Error. Please try again and refresh page"]);
    exit();
  }
}

function toggleLike($cust_id, $conn) {

  $data = json_decode(file_get_contents('php://input'), true);

  if (!isset($data['productReviewId'], $data['isLiked'])) {
    echo json_encode(["status" => "error", "message" => "Invalid like data."]);
    exit();
}
  $productReviewId = $data['productReviewId'];
  $isLiked = $data['isLiked'];

  if ($isLiked) {
    $sql = "DELETE FROM review_like WHERE product_review_id = ? AND cust_id = ?";
  } else {
    $sql = "INSERT INTO review_like (product_review_id, cust_id) VALUES (?, ?)";
  }

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $productReviewId, $_SESSION['id']);

  if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => $isLiked ? "Review unliked." : "Review liked."]);
  } else {
    echo json_encode(["status" => "error", "message" => "Database error. Please try again and refresh the page"]);
  }
  $stmt->close();
  $conn->close();
}

function getComment($cust_id, $conn){

    if (!isset($_GET['product_id'])) {
      echo json_encode(["status" => "error", "message" => "Product ID not provided."]);
      exit();
    }

    $product_id = $_GET['product_id'];

    $sqlFetchComment ="SELECT pr.product_review_id, pr.comment, pr.product_review_date, c.username 
        FROM product_review AS pr
        JOIN customer AS c ON pr.cust_id = c.cust_id
        WHERE pr.product_id = ? 
        ORDER BY pr.product_review_date DESC";
    
    $stmtFetchComment = $conn->prepare($sqlFetchComment);

    $stmtFetchComment->bind_param("i", $product_id);

    if($stmtFetchComment->execute()){
      $result = $stmtFetchComment->get_result();
      $reviews = [];
    while ($row = $result->fetch_assoc()) {
      $reviews[] = $row;
    }

    $sqlLike = "SELECT rl.cust_id FROM review_like AS rl WHERE rl.product_review_id = ?";
    $stmtLike = $conn->prepare($sqlLike);

    //in every product reviews fetch its liked users
    foreach ($reviews as &$review) {
      $reviewId = $review['product_review_id'];
      $stmtLike->bind_param("i", $reviewId);
      $stmtLike->execute();
      $likeResult = $stmtLike->get_result();

      $liked_users = [];
      $liked_by_user = false;

    while ($likeRow = $likeResult->fetch_assoc()) {
      $like_id = $likeRow['cust_id'];
      $liked_users[] = $like_id;

    //current user is inside the data, if yes = liked
    if ($like_id == $cust_id) {
      $liked_by_user = true;
    }
  }
  
      $review['like_count'] = count($liked_users);
      $review['liked_by_user'] = $liked_by_user;
  }
  $stmtLike->close();
  $conn->close();
  $stmtFetchComment->close();

     echo json_encode([
      "status" => "success",
      "reviews" => $reviews
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Product Comment not found."]);
    exit();
  }

}
?>