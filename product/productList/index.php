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
    <link rel="stylesheet" href="styles.css"/>
  </head>
  <body>

     <?php include("../../header/index.php"); ?>

     <main>
          <div class="search-container">
            <input type="text" id="searchInput" class="searchInput" placeholder="Search for products...">
          </div> 

          <div id="products-container" class="products-container">
          </div>

          <div id="pagination-container" class ="pagination-container">
          </div>
    </main>

    <?php include("../../footer/index.php"); ?>

    <script src="script.js"></script>

  </body>
</html>
