<!-- Navigate back to Home Page when user is not logged in or session is invalid. -->
<?php
session_start();

if (!isset($_SESSION['id'])) {
  header("Location: /Wad_assignment/auth/login/log-out.php");
  exit();
}
?>

<!-- Product List Page -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Product Listing</title>
  <link rel="stylesheet" href="styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

</head>

<body class="product">

  <?php include("../../header/index.php"); ?>

  <main>
    <!-- Container that handles all the search and filter -->
    <div class="search-container">
      <div class="input-wrapper">
        <input type="text" id="searchInput" class="searchInput" placeholder="Search for cards...">
      </div>
      <div class="filter-container">
        <span class="filter-container-title">Filter</span>
        <div class="input-wrapper">
          <select class="category-select" id="categorySelect">
            <option value="all">All</option>
          </select>
        </div>
        <div class="price-filter-container">
          <input type="number" class="price-filter" id="minPriceInput" placeholder="MIN PRICE" min="1">
          <input type="number" class="price-filter" id="maxPriceInput" placeholder="MAX PRICE" min="1">
        </div>
      </div>
      <div class="sort-container">
        <span class="sort-container-title">Sort</span>
        <div class="input-wrapper sort">
          <select class="sort-item-select" id="sortItemSelect">
            <option value="product_name">Card Name</option>
            <option value="product_price">Card Price</option>
            <option value="stock_qty">Stock Quantity</option>
            <option value="category_name">Category</option>
          </select>
          <select class="sort-type-select" id="sortTypeSelect">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
          </select>
        </div>
      </div>
    </div>

    <div id="products-container" class="products-container">
    </div>

    <div id="pagination-container" class="pagination-container">
    </div>
  </main>

  <?php include("../../footer/index.php"); ?>

  <script src="./product-list.js"></script>

</body>

</html>