-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 07, 2025 at 01:25 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

DROP TABLE IF EXISTS `cart_item`;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;


--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;



--
-- Table structure for table `customer_addresses`
--

DROP TABLE IF EXISTS `customer_addresses`;
CREATE TABLE IF NOT EXISTS `customer_addresses` (
  `address_id` int NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `postcode` varchar(5) NOT NULL,
  `state` varchar(45) NOT NULL,
  `cust_id` int NOT NULL,
  PRIMARY KEY (`address_id`),
  KEY `fk_customer_addresses_customer1_idx` (`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;


-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

DROP TABLE IF EXISTS `order_item`;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;



--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb3;


-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

DROP TABLE IF EXISTS `product_category`;
CREATE TABLE IF NOT EXISTS `product_category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(45) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_id_UNIQUE` (`category_id`
  )
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;


-- --------------------------------------------------------

--
-- Table structure for table `product_review`
--

DROP TABLE IF EXISTS `product_review`;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;


--
-- Table structure for table `review_like`
--

DROP TABLE IF EXISTS `review_like`;
CREATE TABLE IF NOT EXISTS `review_like` (
  `review_like_id` int NOT NULL AUTO_INCREMENT,
  `product_review_id` int NOT NULL,
  `cust_id` int NOT NULL,
  PRIMARY KEY (`review_like_id`),
  UNIQUE KEY `unique_review_like` (`product_review_id`,`cust_id`),
  KEY `fk_review_like_customer` (`cust_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;


--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `fk_cart_item_customer1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_item_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD CONSTRAINT `fk_customer_addresses_customer1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_customer1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk_order_item_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_item_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_customer1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`),
  ADD CONSTRAINT `fk_payment_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_product_category1` FOREIGN KEY (`category_id`) REFERENCES `product_category` (`category_id`);

--
-- Constraints for table `product_review`
--
ALTER TABLE `product_review`
  ADD CONSTRAINT `fk_product_review_customer1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`),
  ADD CONSTRAINT `fk_product_review_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `review_like`
--
ALTER TABLE `review_like`
  ADD CONSTRAINT `fk_review_like_customer` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_like_review` FOREIGN KEY (`product_review_id`) REFERENCES `product_review` (`product_review_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
