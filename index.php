<!-- Check User login Status -->
<?php
session_start();
$loginStatus = isset($_SESSION['id']) ? 'success' : 'fail';
?>

<!-- Home Page / Entry Point -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles.css" />
    <title>Home Page</title>
</head>
<body>

    <?php include("header/index.php"); ?>

    <main>
        <div class="container">
            <!-- Home Page Title -->
            <h1 class ="title">Online Card Store</h1>
            <?php if ($loginStatus === 'success'): ?>
                <!-- Display Start Shopping button when user has logged in -->
                <a class="homepage-button" href="product/productList/index.php">Start Shopping</a>
            <?php else: ?>
                <!-- Display Login button when user is not logged in -->
                <a class="homepage-button" id="login-button" href="auth/login/index.php">Login !</a>
            <?php endif; ?>
        </div>
    </main>

    <?php include("footer/index.php"); ?>

</body>
</html>

