<?php
include('./config.php');

$createCustomer = " 
CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `username` varchar(45) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `id_UNIQUE` (`cust_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `phone_number_UNIQUE` (`phone_number`)
  )
ENGINE = InnoDB;
";

$createCustomerAddress = "
CREATE TABLE IF NOT EXISTS `customer_addresses` (
  `address_id` int NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `postcode` varchar(5) NOT NULL,
  `state` varchar(45) NOT NULL,
  `cust_id` int NOT NULL,
  PRIMARY KEY (`address_id`),
  KEY `fk_customer_addresses_customer1_idx` (`cust_id`)
) ENGINE=InnoDB;
";

$createOrder = "
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `address` mediumtext NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `order_status` varchar(45) NOT NULL,
  `total_amt` decimal(13,2) NOT NULL,
  `cust_id` int NOT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_id_UNIQUE` (`order_id`),
  KEY `fk_order_customer1_idx` (`cust_id`)
  )
ENGINE = InnoDB;
";


$createProductCategory = "
CREATE TABLE IF NOT EXISTS `product_category` (
  `category_id` int not null auto_increment,
  `category_name` varchar(45) not null,
  primary key (`category_id`),
  unique key `category_id_unique` (`category_id`)
  )
ENGINE = InnoDB;
";

$createProduct = "
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(45) NOT NULL,
  `product_desc` mediumtext NOT NULL,
  `product_img` varchar(45) NOT NULL,
  `product_price` decimal(13,2) NOT NULL,
  `stock_qty` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_id_UNIQUE` (`product_id`),
  KEY `fk_product_product_category1_idx` (`category_id`)
  )
ENGINE = InnoDB;
";

$createProductReview = "
CREATE TABLE IF NOT EXISTS `product_review` (
  `product_review_id` int NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `product_id` int NOT NULL,
  `cust_id` int NOT NULL,
  `product_review_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_review_id`),
  UNIQUE KEY `product_review_id_UNIQUE` (`product_review_id`),
  KEY `fk_product_review_product1` (`product_id`),
  KEY `fk_product_review_customer1` (`cust_id`)
) ENGINE=InnoDB;
";

$createReviewLike = "
CREATE TABLE IF NOT EXISTS `review_like` (
  `review_like_id` int NOT NULL AUTO_INCREMENT,
  `product_review_id` int NOT NULL,
  `cust_id` int NOT NULL,
  PRIMARY KEY (`review_like_id`),
  UNIQUE KEY `unique_review_like` (`product_review_id`,`cust_id`),
  KEY `fk_review_like_customer` (`cust_id`)
) ENGINE=InnoDB;
";

$createCartItem = "
CREATE TABLE IF NOT EXISTS `cart_item` (
  `cart_item_id` int NOT NULL AUTO_INCREMENT,
  `cart_item_qty` int NOT NULL,
  `cart_item_price` decimal(13,2) NOT NULL,
  `cart_status` varchar(45) NOT NULL,
  `cust_id` int NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`cart_item_id`),
  UNIQUE KEY `cart_item_id_UNIQUE` (`cart_item_id`),
  KEY `fk_cart_item_customer1_idx` (`cust_id`),
  KEY `fk_cart_item_product1_idx` (`product_id`)
  )
ENGINE = InnoDB;
";


$createOrderItem = "
CREATE TABLE IF NOT EXISTS `order_item` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_item_qty` int NOT NULL,
  `order_item_price` decimal(13,2) NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`order_item_id`),
  UNIQUE KEY `order_item_id_UNIQUE` (`order_item_id`),
  KEY `fk_order_item_order1_idx` (`order_id`),
  KEY `fk_order_item_product1_idx` (`product_id`)
  )
ENGINE = InnoDB;
";


if (mysqli_query($conn, $createCustomer)) {
  echo "Table customer created successfully!<br>";
} else {
  echo "Error creating customer table: " . mysqli_error($conn);
  exit();
}

if (mysqli_query($conn, $createCustomerAddress)) {
  echo "Table customer address created successfully!<br>";
} else {
  echo "Error creating customer address table: " . mysqli_error($conn);
  exit();
}

if (mysqli_query($conn, $createOrder)) {
  echo "Table order created successfully!<br>";
} else {
  echo "Error creating order table: " . mysqli_error($conn);
  exit();
}


if (mysqli_query($conn, $createProductCategory)) {
  echo "Table product_category created successfully!<br>";
} else {
  echo "Error creating product_category table: " . mysqli_error($conn);
  exit();
}

if (mysqli_query($conn, $createProduct)) {
  echo "Table product created successfully!<br>";
} else {
  echo "Error creating product table: " . mysqli_error($conn);
  exit();
}

if (mysqli_query($conn, $createProductReview)) {
  echo "Table product review created successfully!<br>";
} else {
  echo "Error creating product review table: " . mysqli_error($conn);
  exit();
}

if (mysqli_query($conn, $createReviewLike)) {
  echo "Table review like created successfully!<br>";
} else {
  echo "Error creating review like table: " . mysqli_error($conn);
  exit();
}

if (mysqli_query($conn, $createCartItem)) {
  echo "Table cart_item created successfully!<br>";
} else {
  echo "Error creating cart_item table: " . mysqli_error($conn);
  exit();
}


if (mysqli_query($conn, $createOrderItem)) {
  echo "Table order_item created successfully!<br>";
} else {
  echo "Error creating order_item table: " . mysqli_error($conn);
  exit();
}

mysqli_close($conn);
