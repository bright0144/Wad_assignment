<?php

include_once("../../config/config.php");

$sql = "SELECT * FROM product_category";

// Prepare and execute the query
$stmt = $conn->prepare($sql); // <-- $conn must be your DB connection variable
$stmt->execute();
$result = $stmt->get_result();

// Fetch categories
$categories = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Return JSON response
http_response_code(200);
header('Content-Type: application/json');
echo json_encode($categories);
?>
