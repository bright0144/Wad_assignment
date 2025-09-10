<?php
//Necessary database connection
include_once("../../config/config.php");

// Item limited to 10 item per query
$limit = 10;

// Get all the query params

// Get the search query
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
// Get the page and validate
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if (!is_numeric($page) || $page < 1) {
  $page = 1;
}
// Get the category
$category = isset($_GET['category']) ? (int) $_GET['category'] : null;
// Get the min and max price and validate
$minPrice = isset($_GET['minPrice']) ? (float) $_GET['minPrice'] : null;
$maxPrice = isset($_GET['maxPrice']) ? (float) $_GET['maxPrice'] : null;


// Make sure min price is < max price
if ($minPrice !== null && $maxPrice !== null && $maxPrice < $minPrice) {
  // Swap them here
  [$minPrice, $maxPrice] = [$maxPrice, $minPrice];
}


// Get sort item and type validate
$sortItem = isset($_GET['sortItem']) ? strtolower($_GET['sortItem']) : 'product_name';
$sortType = isset($_GET['sortType']) ? strtoupper($_GET['sortType']) : 'ASC';

$validSortItem = ["product_name", "product_price", "stock_qty", "category_name"];
$validSortType = ["ASC", "DESC"];

// Set default value for invalid input
if (!in_array($sortItem, $validSortItem)) {
  $sortItem = $validSortItem[0];
}

if (!in_array($sortType, $validSortType)) {
  $sortType = $validSortType[0];
}

// Calculate the offset
$offset = ($page - 1) * $limit;

// SQL query for total row pagination
$totalSql = "
    SELECT 
       count(*) AS total_rows
    FROM 
        product p
    LEFT JOIN 
        product_category pc ON p.category_id = pc.category_id
    WHERE 1=1
";

$params = [];
$types = "";

// Append product name query
if (!empty($searchQuery)) {
  $sqlLike = '%' . $searchQuery . '%';
  $totalSql .= " AND p.product_name LIKE ?";
  $params[] = $sqlLike;
  $types .= "s";
}

// Append category name
if (!empty($category) && $category !== "all") {
  $totalSql .= " AND pc.category_id = ?";
  $params[] = $category;
  $types .= "i";
}

// Append min price
if ($minPrice !== null) {
  $totalSql .= " AND p.product_price >= ?";
  $params[] = $minPrice;
  $types .= "d";
}

// Append max price
if ($maxPrice !== null && $maxPrice > 0) {
  $totalSql .= " AND p.product_price <= ?";
  $params[] = $maxPrice;
  $types .= "d";
}

$totalStmt = $conn->prepare($totalSql);
if (!empty($params)) {
  $totalStmt->bind_param($types, ...$params);
}

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

$params = [];
$types = "";

// Append product name query
if (!empty($searchQuery)) {
  $sqlLike = '%' . $searchQuery . '%';
  $sql .= " AND p.product_name LIKE ?";
  $params[] = $sqlLike;
  $types .= "s";
}

// Append category name
if (!empty($category)) {
  $sql .= " AND pc.category_id = ?";
  $params[] = $category;
  $types .= "i";
}

// Append min price (only if set)
if ($minPrice !== null) {
  $sql .= " AND p.product_price >= ?";
  $params[] = $minPrice;
  $types .= "d"; // better to use "d" for float
}

// Append max price (only if set)
if ($maxPrice !== null && $maxPrice > 0) {
  $sql .= " AND p.product_price <= ?";
  $params[] = $maxPrice;
  $types .= "d"; // float again
}

// Append sort item and type
$sql .= " ORDER BY $sortItem $sortType";

// Add pagination
$sql .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$cards = [];

while ($row = $result->fetch_assoc()) {
  $cards[] = $row;
}
http_response_code(200);
$response = [
  "total_rows" => $totalRows,
  "cards" => $cards
];
echo json_encode($response);

$stmt->close();
$conn->close();
