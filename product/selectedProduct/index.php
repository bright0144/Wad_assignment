<!-- Navigate back to Home Page when user is not logged in or session is invalid. -->
<?php
session_start();

if (!isset($_SESSION['id'])) {
  header("Location: /Wad_assignment/auth/login/log-out.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Product Details</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">


</head>

<body class="product">

  <?php include("../../header/index.php"); ?>
  <main class="pt-[350px] md:pt-[150px]">
    <!-- Card details -->
    <div id="cardDetailsContainer" class="flex"></div>
    <div id="statusMessage" class="mt-4 text-center text-sm text-gray-500"></div>

    <!-- Card reviews -->
    <h1 class="reviewTitle text-2xl font-semibold text-gray-800 mt-12 mb-6 border-b pb-2">
      Product Reviews
    </h1>
    <div id="productReviewsContainer" class="space-y-4 mb-[100px] md:mb-0">
    </div>

    <!-- Error message (hidden by default) -->
    <div id="errorMessage" class="hidden mt-6 text-center text-red-500 font-medium"></div>
  </main>

  <?php include("../../footer/index.php"); ?>

  <script src="script.js"></script>

</body>

</html>