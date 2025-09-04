<?php
$status = include("header/index.php");

if ($status === 'success') {
    header("Location: /Product/productList.php");
    exit();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Home Page</title>
</head>
<body>


    <?php include("header/index.php"); ?>
  

    <a class ="homepage-button" id="login-button" href="/auth/login.php">Login !</a>
    <a class ="homepage-button" id="register-button" href="/Product/productList.php">Register !</a>
    
    
    <?php include("footer/index.php"); ?>

</body>

</html>