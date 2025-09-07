-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema test
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `test` DEFAULT CHARACTER SET utf8mb4 COLLATE = utf8mb4_0900_ai_ci;
USE `test`;

-- -----------------------------------------------------
-- Table `product_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_category` (
  `category_id` INT NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE INDEX `category_id_UNIQUE` (`category_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 8 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` INT NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(45) NOT NULL,
  `product_desc` MEDIUMTEXT NOT NULL,
  `product_img` VARCHAR(45) NOT NULL,
  `product_price` DECIMAL(13,2) NOT NULL,
  `stock_qty` INT NOT NULL,
  `category_id` INT NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE INDEX `product_id_UNIQUE` (`product_id`),
  INDEX `fk_product_category_idx` (`category_id`),
  CONSTRAINT `fk_product_category`
    FOREIGN KEY (`category_id`)
    REFERENCES `product_category` (`category_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 32 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `customer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` INT NOT NULL AUTO_INCREMENT,
  `password` VARCHAR(255) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_number` VARCHAR(11) NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE INDEX `id_UNIQUE` (`cust_id`),
  UNIQUE INDEX `username_UNIQUE` (`username`),
  UNIQUE INDEX `email_UNIQUE` (`email`),
  UNIQUE INDEX `phone_number_UNIQUE` (`phone_number`)
) ENGINE = InnoDB AUTO_INCREMENT = 12 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `customer_address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `customer_address` (
  `address_id` INT NOT NULL AUTO_INCREMENT,
  `address` VARCHAR(255) NOT NULL,
  `postcode` VARCHAR(5) NOT NULL,
  `state` VARCHAR(45) NOT NULL,
  `cust_id` INT NOT NULL,
  PRIMARY KEY (`address_id`),
  INDEX `fk_customer_address_customer_idx` (`cust_id`),
  CONSTRAINT `fk_customer_address_customer`
    FOREIGN KEY (`cust_id`)
    REFERENCES `customer` (`cust_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `product_review`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_review` (
  `product_review_id` INT NOT NULL AUTO_INCREMENT,
  `comment` TEXT NOT NULL,
  `product_review_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `product_id` INT NOT NULL,
  `cust_id` INT NOT NULL,
  PRIMARY KEY (`product_review_id`),
  INDEX `fk_review_product_idx` (`product_id`),
  INDEX `fk_review_customer_idx` (`cust_id`),
  CONSTRAINT `fk_review_product`
    FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  CONSTRAINT `fk_review_customer`
    FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `cart_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cart_item` (
  `cart_item_id` INT NOT NULL AUTO_INCREMENT,
  `cart_item_qty` INT NOT NULL,
  `cart_item_price` DECIMAL(13,2) NOT NULL,
  `cart_status` VARCHAR(45) NOT NULL,
  `cust_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  PRIMARY KEY (`cart_item_id`),
  UNIQUE INDEX `cart_item_id_UNIQUE` (`cart_item_id`),
  INDEX `fk_cart_customer_idx` (`cust_id`),
  INDEX `fk_cart_product_idx` (`product_id`),
  CONSTRAINT `fk_cart_customer`
    FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_product`
    FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` INT NOT NULL AUTO_INCREMENT,
  `address` MEDIUMTEXT NOT NULL,
  `order_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `order_status` VARCHAR(45) NOT NULL,
  `total_amt` DECIMAL(13,2) NOT NULL,
  `cust_id` INT NOT NULL,
  `customer_address_id` INT NOT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE INDEX `order_id_UNIQUE` (`order_id`),
  INDEX `fk_order_customer_idx` (`cust_id`),
  INDEX `fk_order_address_idx` (`customer_address_id`),
  CONSTRAINT `fk_order_customer`
    FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`),
  CONSTRAINT `fk_order_address`
    FOREIGN KEY (`customer_address_id`) REFERENCES `customer_address` (`address_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `order_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_item` (
  `order_item_id` INT NOT NULL AUTO_INCREMENT,
  `order_item_qty` INT NOT NULL,
  `order_item_price` DECIMAL(13,2) NOT NULL,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  PRIMARY KEY (`order_item_id`),
  UNIQUE INDEX `order_item_id_UNIQUE` (`order_item_id`),
  INDEX `fk_order_item_order_idx` (`order_id`),
  INDEX `fk_order_item_product_idx` (`product_id`),
  CONSTRAINT `fk_order_item_order`
    FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_item_product`
    FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` INT NOT NULL AUTO_INCREMENT,
  `pay_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `pay_amt` DECIMAL(13,2) NOT NULL,
  `pay_method` VARCHAR(45) NOT NULL,
  `transaction_status` VARCHAR(45) NOT NULL,
  `order_id` INT NOT NULL,
  `cust_id` INT NOT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE INDEX `payment_id_UNIQUE` (`payment_id`),
  INDEX `fk_payment_order_idx` (`order_id`),
  INDEX `fk_payment_customer_idx` (`cust_id`),
  CONSTRAINT `fk_payment_order`
    FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`),
  CONSTRAINT `fk_payment_customer`
    FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `wishlist_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wishlist_item` (
  `wishlist_item_id` INT NOT NULL AUTO_INCREMENT,
  `wishlist_item_qty` INT NULL DEFAULT NULL,
  `cust_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  PRIMARY KEY (`
