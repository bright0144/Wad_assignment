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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

</head>

<body class="order">
    <?php include("../header/index.php"); ?>
    <main>
        <h1 class="orderTitle">Orders</h1>
        <div id="orderContainer" class="orderContainer"></div>
    </main>
    <?php include("../footer/index.php"); ?>
    <script src="order.js"></script>
</body>

</html>