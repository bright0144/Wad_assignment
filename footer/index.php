<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <link rel="stylesheet" href="/Wad_assignment/footer/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">



    <link rel="stylesheet" href="/Wad_assignment/footer/styles.css" />

    <!-- email, location and phone icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body>
    <footer>
        <div class="footerContainer">

            <!-- footer up -->
            <div class="infoContainer">

                <!-- phone -->
                <div class="phoneContainer">
                    <button style="font-size: 48px">
                        <i class="fa fa-phone"></i>
                    </button>
                    <span>012-345 6789</span>
                </div>

                <!-- Email -->
                <div class="emailContainer">
                    <button style="font-size: 48px">
                        <i class="fa fa-envelope-square"></i>
                    </button>
                    <a href="mailto:xx@gmail.com">
                        xx@gmail.com
                    </a>
                </div>

                <!-- Location -->
                <div class="locationContainer">
                    <button style="font-size: 48px">
                        <i class="fa fa-map-marker"></i>
                    </button>
                    <a href="https://maps.app.goo.gl/SjKEXSCfjWpkiLJj6"
                        target="_blank">
                        View Location
                    </a>
                </div>

            </div>
            <!-- bottom footer part -->
            <div class="footerBottom">
                <div class="logo">Tusla</div>
                <nav>
                    <!-- navigation bar -->
                    <ul class="nav-links" id="nav-links">
                        <!-- Navigate to Home Page and contact page -->
                        <li><a href="/Wad_assignment/index.php">Home</a></li>
                        <li><a href="/Wad_assignment/contact/index.php">Contact</a></li>

                        <!-- User is logged in, display navigation to product page, cart page and logout -->
                        <?php if ($loginStatus === 'success'): ?>
                            <li><a href="/Wad_assignment/product/productList/index.php">Product</a></li>
                            <li><a href="/Wad_assignment/cart/index.php">Cart</a></li>
                            <li><a href="/Wad_assignment/order/index.php">Order</a></li>
                            <li>
                                <a href="/Wad_assignment/manage-profile/index.php">Manage Profile</a>
                            </li>
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
            </div>
        </div>
    </footer>
</body>

</html>