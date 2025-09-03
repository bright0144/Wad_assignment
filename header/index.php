<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="/header/styles.css">
</head>
<body>
<header class="navbar">
    <div class="navbar-top">
        <a href="index.php" class="logo">Tusla</a>
        <button class="menu-toggle" id="menu-toggle">&#9776;</button>
    </div>
    <hr class="navbar-horizontal-line">

    <nav>
      <ul class="nav-links" id="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="services.php">Product</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="wishlist.php">Wishlist</a></li>
      </ul>
    </nav>
</header>

<script>
  document.getElementById('menu-toggle').addEventListener('click', function() {
      document.getElementById('nav-links').classList.toggle('show');
  });
</script>
</body>
</html>