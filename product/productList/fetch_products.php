<?php
// Set headers to allow cross-origin requests and specify JSON content type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// IMPORTANT: Replace with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test"; // Use the name of your database schema

// Get search query and page number from the request
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(array("message" => "Connection failed: " . $conn->connect_error));
    exit();
}

// SQL query to select products with a JOIN on product_category and a LIKE clause for searching
// The JOIN is used to search by category name as well as product name.
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

// Prepare the statement to prevent SQL injection
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    http_response_code(500);
    echo json_encode(array("message" => "Error preparing statement: " . $conn->error));
    exit();
}

// Bind parameters for the prepared statement
// 'ssii' stands for two strings (search terms) and two integers (limit, offset)
$searchTerm = '%' . $searchQuery . '%';
$stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$products_array = array();

if ($result->num_rows > 0) {
    // Loop through results and push each row into the array
    while($row = $result->fetch_assoc()) {
        $products_array[] = $row;
    }
    // Return the product data as a JSON array
    http_response_code(200);
    echo json_encode($products_array);
} else {
    // Return a JSON object if no products are found
    http_response_code(404);
    echo json_encode(array("message" => "No products found."));
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
