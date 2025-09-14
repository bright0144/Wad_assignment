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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="/Wad_assignment/cart/cart.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

</head>

<body class="cart">
    <?php include("../header/index.php"); ?>
    <main>
        <h1 class="cartTitle"> Shopping Cart</h1>
        <div id="cartContainer">
            <p>Loading cart items...</p>
        </div>
        <hr>
        <h3 class="totalPriceWrapper">Total: <span id="totalPrice"></span></h3>
        <div class="addressContainer">
            <label for="addressSelection">Select Delivery Address:</label>
            <select id="addressSelection">
                <option value="">Loading addresses...</option>
            </select>
        </div>
        <button id="checkoutButton">Checkout</button>
    </main>
    <?php include("../footer/index.php"); ?>
</body>
<script src="cart.js"></script>

</html>