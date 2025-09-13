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
    <link rel="stylesheet" href="/Wad_assignment/header/header.css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <header class="navbar flex justify-center items-center flex-col gap-1 p-3 w-full">
        <div class="navbar-top">
            <!-- Application logo -->
            <a href="/Wad_assignment/index.php" class="logo">Tusla</a>
        </div>

        <span class="w-full border-b-4 border-[#FFA500] h-3 mb-5"></span>

        <!-- Mobile nav (visible on small screens, hidden on md and up) -->
        <div class="mobile-nav-container md:hidden flex flex-col">
            <button id="hamburger" class="text-white md:hidden p-2 border-2 border-[#FFA500] rounded-md hover:opacity-70 transition-opacity duration-250 mb-3">
                Open Navigation Menu
            </button>


            <nav id="mobileNav" class="md:h-0 max-h-0 opacity-0 transition-[max-height,opacity] duration-500 ease-in-out">
                <ul class="nav-links flex flex-col justify-center items-center gap-3">
                    <li><a href="/Wad_assignment/index.php">Home</a></li>
                    <li><a href="/Wad_assignment/contact/index.php">Contact</a></li>

                    <?php if ($loginStatus === 'success'): ?>
                        <li><a href="/Wad_assignment/product/productList/index.php">Product</a></li>
                        <li><a href="/Wad_assignment/cart/index.php">Cart</a></li>
                        <li><a href="/Wad_assignment/order/index.php">Order</a></li>
                        <li><a href="/Wad_assignment/manage-profile/index.php">Manage Profile</a></li>
                        <li><a href="/Wad_assignment/auth/login/log-out.php" class="logout-link">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

        <!-- Desktop nav (hidden on small, flex on md+) -->
        <nav class="desktop-nav hidden md:flex">
            <ul class="nav-links flex justify-center items-center gap-5 mb-3">
                <li><a href="/Wad_assignment/index.php">Home</a></li>
                <li class="contact"><a href="/Wad_assignment/contact/index.php">Contact</a></li>

                <?php if ($loginStatus === 'success'): ?>
                    <li class="product"><a href="/Wad_assignment/product/productList/index.php">Product</a></li>
                    <li class="cart"><a href="/Wad_assignment/cart/index.php">Cart</a></li>
                    <li class="order"><a href="/Wad_assignment/order/index.php">Order</a></li>
                    <li class="manage-profile"><a href="/Wad_assignment/manage-profile/index.php">Manage Profile</a></li>
                    <li><a href="/Wad_assignment/auth/login/log-out.php" class="logout-link">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
<script src="/Wad_assignment/header/header.js"></script>

</html>