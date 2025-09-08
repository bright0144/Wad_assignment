<!-- Check login status and valid season  -->
<?php
if (isset($_SESSION['id'])) {
    // Logged in and valid season
    $loginStatus = 'success';
} else {
    // Not Logged in or invalid season
    $loginStatus = 'fail';
}
?>

<!-- Application Header -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Header</title>
    <link rel="stylesheet" href="/Wad_assignment/header/styles.css" />
</head>
<body>
    <header class="navbar">
        <div class="navbar-top">
            <!-- Application logo -->
            <a href="/Wad_assignment/index.php" class="logo">Tusla</a>
        </div>
        <!-- horizontal line between logo and navigation links -->
        <hr class="navbar-horizontal-line" />

        <nav>
            <!-- navigation bar -->
            <ul class="nav-links" id="nav-links">
                <!-- Navigate to Home Page -->
                <li><a href="/Wad_assignment/index.php">Home</a></li>

                <!-- User is logged in, display navigation to product page, cart page and logout -->
                <?php if ($loginStatus === 'success'): ?>
                    <li><a href="/Wad_assignment/product/productList/index.php">Product</a></li>
                    <li><a href="/Wad_assignment/cart/index.php">Cart</a></li>
                    <li><a href="/Wad_assignment/order/index.php">Order</a></li>
                    <li>
                        <a href="/Wad_assignment/auth/login/log-out.php" class="logout-link">Logout</a>
                    </li>
                <?php else: ?>
                    <li>
                        <!-- Display login navigation for login if user is not logged in -->
                        <a href="/Wad_assignment/auth/login/index.php" class="login-link">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
</html>
