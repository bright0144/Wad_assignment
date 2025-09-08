<?php
//Necessary database connection
include_once("../../config/config.php");

//Get Params from URL (limit 10 in each query)
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

//sql query
$sql = "
    SELECT 
        p.product_id, 
        p.product_name, 
        p.product_desc, 
        p.product_img, 
        p.product_price, 
        p.stock_qty, 
        p.category_id,
        pc.category_name
    FROM 
        product p
    LEFT JOIN 
        product_category pc ON p.category_id = pc.category_id
    WHERE 
        p.product_name LIKE ? OR pc.category_name LIKE ?
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$sqlLike = '%' . $searchQuery . '%';
$stmt->bind_param("ssii", $sqlLike, $sqlLike, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

if ($result-> num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }
    http_response_code(200);
    echo json_encode($cards);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No cards found."));
}

$stmt->close();
$conn->close();
?>
