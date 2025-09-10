<?php
//Necessary database connection
include_once("../../config/config.php");

header("Content-Type: application/json");
$response = ["status" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  http_response_code(405);
  $response["message"] = "Method not allowed";
  echo json_encode($response);
  exit;
}


//Sql query for all category 
$allCategoryQuery = "
    SELECT category_id, category_name 
    FROM product_category;
";

$stmt = $conn->prepare($allCategoryQuery);

if (!$stmt->execute()) {
  http_response_code(500);
  $response["message"] = "Server error fetching category";
  echo json_encode($response);
  $stmt->close();
  $conn->close();
  exit;
}

$result = $stmt->get_result();

if ($result->num_rows <= 0) {
  http_response_code(404);
  $response["message"] = "No category found";
  echo json_encode($response);
  $stmt->close();
  $conn->close();
  exit;
}

$allCategory = [];

while ($row = $result->fetch_assoc()) {
  $allCategory[] = $row;
}

http_response_code(200);
$response["message"] = "Category found";
$response["all_category"] = $allCategory;
echo json_encode($response);
$stmt->close();
$conn->close();
exit;
