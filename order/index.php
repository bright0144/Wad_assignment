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
  <meta charset="UTF-8">
  <title>Order List</title>
      <link rel="stylesheet" href="styles.css" />

</head>
<body>
             <?php include("../header/index.php"); ?>
          
   <h1 class ="orderTitle">Orders</h1>
   <div id = "orderContainer" class="orderContainer"></div>
             <?php include("../footer/index.php"); ?>


    <script src="script.js"></script>


</body>
</html>
