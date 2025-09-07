<?php
$loginStatus = include("header/index.php");

if ($loginStatus === 'success') {
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

    <a class="homepage-button" id="login-button" href="auth/login/index.php">Login !</a>

    
 <?php include("footer/index.php"); ?>

</body>

</html>