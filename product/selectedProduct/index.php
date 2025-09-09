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
    <link rel="stylesheet" href="styles.css"/>

  </head>
  <body>

       <?php include("../../header/index.php"); ?>
       <main>
                <!-- Card details -->
               <h1 class="cardTitle">Product Details</h1>
               <div id="cardDetailsContainer">
               </div>
               <div id="statusMessage"></div>
               <!-- Card reviews -->
                 <h1 class ="reviewTitle">Product Reviews</h1>
                 <div id="productReviewsContainer">
                 </div>
               <!-- Error message -->
       </main>



          <?php include("../../footer/index.php"); ?>



    <script src="script.js"></script>

  </body>
</html>
