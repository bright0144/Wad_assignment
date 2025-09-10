<?php
//Necessary database connection
include_once("../../config/config.php");

//Get Params from URL (limit 10 in each query)
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

//validate page
if (!is_numeric($page) || $page < 1) {
    http_response_code(400); 
    echo json_encode(array("message" => "Invalid page."));
    exit;
}


//sql query for total row pagination
$totalSql =  "
    SELECT 
       count(*) AS total_rows
    FROM 
        product p
    LEFT JOIN 
        product_category pc ON p.category_id = pc.category_id
    WHERE 
        p.product_name LIKE ? OR pc.category_name LIKE ?
";
$totalStmt = $conn->prepare($totalSql);
$sqlLike = '%' . $searchQuery . '%';
$totalStmt->bind_param("ss", $sqlLike, $sqlLike);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRows = $totalResult->fetch_assoc()['total_rows'];
$totalStmt->close();

//sql query for actual cards
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
        p.stock_qty > 0 AND (p.product_name LIKE ? OR pc.category_name LIKE ?)
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
    $response = [
    "total_rows" => $totalRows,
    "cards" => $cards
    ];
    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No cards found."));
}

$stmt->close();
$conn->close();
?>
