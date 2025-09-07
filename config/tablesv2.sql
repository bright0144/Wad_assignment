-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema test
-- -----------------------------------------------------


-- -----------------------------------------------------
-- Schema test
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema test
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `test` DEFAULT CHARACTER SET utf8mb3 ;
USE `test` ;

-- -----------------------------------------------------
-- Table `test`.`product_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`product_category` (
  `category_id` INT NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE INDEX `category_id_UNIQUE` (`category_id` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `test`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`product` (
  `product_id` INT NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(45) NOT NULL,
  `product_desc` MEDIUMTEXT NOT NULL,
  `product_img` VARCHAR(45) NOT NULL,
  `product_price` DECIMAL(13,2) NOT NULL,
  `stock_qty` INT NOT NULL,
  `category_id` INT NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE INDEX `product_id_UNIQUE` (`product_id` ASC) VISIBLE,
  INDEX `fk_product_product_category1_idx` (`category_id` ASC) VISIBLE,
  CONSTRAINT `fk_product_product_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `test`.`product_category` (`category_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 32
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `test`.`customer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`customer` (
  `cust_id` INT NOT NULL AUTO_INCREMENT,
  `password` VARCHAR(255) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_number` VARCHAR(11) NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE INDEX `id_UNIQUE` (`cust_id` ASC) VISIBLE,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  UNIQUE INDEX `phone_number_UNIQUE` (`phone_number` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `test`.`product_review`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`product_review` (
  `product_review_id` INT NOT NULL AUTO_INCREMENT,
  `comment` TEXT NOT NULL,
  `product_review_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `product_id` INT NOT NULL,
  `cust_id` INT NOT NULL,
  PRIMARY KEY (`product_review_id`),
  INDEX `product_id_fk_idx` (`product_id` ASC) VISIBLE,
  INDEX `cust_id_fk_idx` (`cust_id` ASC) VISIBLE,
  CONSTRAINT `product_id_fk`
    FOREIGN KEY (`product_id`)
    REFERENCES `test`.`product` (`product_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `cust_id_fk`
    FOREIGN KEY (`cust_id`)
    REFERENCES `test`.`customer` (`cust_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -- -----------------------------------------------------
-- -- Table `test`.`customer_addresses`
-- -- -----------------------------------------------------
-- CREATE TABLE IF NOT EXISTS `test`.`customer_addresses` (
--   `address_id` INT NOT NULL AUTO_INCREMENT,
--   `address` VARCHAR(255) NOT NULL,
--   `postcode` VARCHAR(5) NOT NULL,
--   `state` VARCHAR(45) NOT NULL,
--   `cust_id` INT NOT NULL,
--   PRIMARY KEY (`address_id`),
--   INDEX `fk_customer_addresses_customer1_idx` (`cust_id` ASC) VISIBLE,
--   CONSTRAINT `fk_customer_addresses_customer1`
--     FOREIGN KEY (`cust_id`)
--     REFERENCES `test`.`customer` (`cust_id`)
--     ON DELETE NO ACTION
--     ON UPDATE NO ACTION)
-- ENGINE = InnoDB;

USE `test` ;

-- -- -----------------------------------------------------
-- -- Table `test`.`customer`
-- -- -----------------------------------------------------
-- CREATE TABLE IF NOT EXISTS `test`.`customer` (
--   `cust_id` INT NOT NULL AUTO_INCREMENT,
--   `password` VARCHAR(255) NOT NULL,
--   `username` VARCHAR(45) NOT NULL,
--   `email` VARCHAR(100) NOT NULL,
--   `phone_number` VARCHAR(11) NOT NULL,
--   PRIMARY KEY (`cust_id`),
--   UNIQUE INDEX `id_UNIQUE` (`cust_id` ASC) VISIBLE,
--   UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE,
--   UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
--   UNIQUE INDEX `phone_number_UNIQUE` (`phone_number` ASC) VISIBLE)
-- ENGINE = InnoDB
-- AUTO_INCREMENT = 12
-- DEFAULT CHARACTER SET = utf8mb4
-- COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `test`.`product_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`product_category` (
  `category_id` INT NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE INDEX `category_id_UNIQUE` (`category_id` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `test`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`product` (
  `product_id` INT NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(45) NOT NULL,
  `product_desc` MEDIUMTEXT NOT NULL,
  `product_img` VARCHAR(45) NOT NULL,
  `product_price` DECIMAL(13,2) NOT NULL,
  `stock_qty` INT NOT NULL,
  `category_id` INT NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE INDEX `product_id_UNIQUE` (`product_id` ASC) VISIBLE,
  INDEX `fk_product_product_category1_idx` (`category_id` ASC) VISIBLE,
  CONSTRAINT `fk_product_product_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `test`.`product_category` (`category_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 32
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `test`.`cart_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`cart_item` (
  `cart_item_id` INT NOT NULL AUTO_INCREMENT,
  `cart_item_qty` INT NOT NULL,
  `cart_item_price` DECIMAL(13,2) NOT NULL,
  `cart_status` VARCHAR(45) NOT NULL,
  `cust_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  PRIMARY KEY (`cart_item_id`),
  UNIQUE INDEX `cart_item_id_UNIQUE` (`cart_item_id` ASC) VISIBLE,
  INDEX `fk_cart_item_customer1_idx` (`cust_id` ASC) VISIBLE,
  INDEX `fk_cart_item_product1_idx` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_cart_item_customer1`
    FOREIGN KEY (`cust_id`)
    REFERENCES `test`.`customer` (`cust_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_cart_item_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `test`.`product` (`product_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `test`.`customer_address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`customer_address` (
  `address_id` INT NOT NULL AUTO_INCREMENT,
  `address` VARCHAR(255) NOT NULL,
  `postcode` VARCHAR(5) NOT NULL,
  `state` VARCHAR(45) NOT NULL,
  `cust_id` INT NOT NULL,
  PRIMARY KEY (`address_id`),
  INDEX `fk_customer_addresses_customer1_idx` (`cust_id` ASC) VISIBLE,
  CONSTRAINT `fk_customer_addresses_customer1`
    FOREIGN KEY (`cust_id`)
    REFERENCES `test`.`customer` (`cust_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `test`.`order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`order` (
  `order_id` INT NOT NULL AUTO_INCREMENT,
  `address` MEDIUMTEXT NOT NULL,
  `order_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `order_status` VARCHAR(45) NOT NULL,
  `total_amt` DECIMAL(13,2) NOT NULL,
  `cust_id` INT NOT NULL,
  `customer_addresses_address_id` INT NOT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE INDEX `order_id_UNIQUE` (`order_id` ASC) VISIBLE,
  INDEX `fk_order_customer1_idx` (`cust_id` ASC) VISIBLE,
  INDEX `fk_order_customer_addresses1_idx` (`customer_addresses_address_id` ASC) VISIBLE,
  CONSTRAINT `fk_order_customer1`
    FOREIGN KEY (`cust_id`)
    REFERENCES `test`.`customer` (`cust_id`),
  CONSTRAINT `fk_order_customer_addresses1`
    FOREIGN KEY (`customer_addresses_address_id`)
    REFERENCES `test`.`customer_addresses` (`address_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `test`.`order_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`order_item` (
  `order_item_id` INT NOT NULL AUTO_INCREMENT,
  `order_item_qty` INT NOT NULL,
  `order_item_price` DECIMAL(13,2) NOT NULL,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  PRIMARY KEY (`order_item_id`),
  UNIQUE INDEX `order_item_id_UNIQUE` (`order_item_id` ASC) VISIBLE,
  INDEX `fk_order_item_order1_idx` (`order_id` ASC) VISIBLE,
  INDEX `fk_order_item_product1_idx` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_order_item_order1`
    FOREIGN KEY (`order_id`)
    REFERENCES `test`.`order` (`order_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_order_item_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `test`.`product` (`product_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `test`.`payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`payment` (
  `payment_id` INT NOT NULL AUTO_INCREMENT,
  `pay_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `pay_amt` DECIMAL(13,2) NOT NULL,
  `pay_method` VARCHAR(45) NOT NULL,
  `transaction_status` VARCHAR(45) NOT NULL,
  `order_id` INT NOT NULL,
  `cust_id` INT NOT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE INDEX `payment_id_UNIQUE` (`payment_id` ASC) VISIBLE,
  INDEX `fk_payment_order1_idx` (`order_id` ASC) VISIBLE,
  INDEX `fk_payment_customer1_idx` (`cust_id` ASC) VISIBLE,
  CONSTRAINT `fk_payment_customer1`
    FOREIGN KEY (`cust_id`)
    REFERENCES `test`.`customer` (`cust_id`),
  CONSTRAINT `fk_payment_order1`
    FOREIGN KEY (`order_id`)
    REFERENCES `test`.`order` (`order_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -- -----------------------------------------------------
-- -- Table `test`.`product_review`
-- -- -----------------------------------------------------
-- CREATE TABLE IF NOT EXISTS `test`.`product_review` (
--   `product_review_id` INT NOT NULL AUTO_INCREMENT,
--   `comment` TEXT NOT NULL,
--   `product_review_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
--   `product_id` INT NOT NULL,
--   `cust_id` INT NOT NULL,
--   PRIMARY KEY (`product_review_id`),
--   INDEX `product_id_fk_idx` (`product_id` ASC) VISIBLE,
--   INDEX `cust_id_fk_idx` (`cust_id` ASC) VISIBLE,
--   CONSTRAINT `cust_id_fk`
--     FOREIGN KEY (`cust_id`)
--     REFERENCES `test`.`customer` (`cust_id`),
--   CONSTRAINT `product_id_fk`
--     FOREIGN KEY (`product_id`)
--     REFERENCES `test`.`product` (`product_id`))
-- ENGINE = InnoDB
-- DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `test`.`wishlist_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `test`.`wishlist_item` (
  `wishlist_item_id` INT NOT NULL AUTO_INCREMENT,
  `wishlist_item_qty` INT NULL DEFAULT NULL,
  `cust_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  PRIMARY KEY (`wishlist_item_id`),
  UNIQUE INDEX `wishlist_item_id_UNIQUE` (`wishlist_item_id` ASC) VISIBLE,
  INDEX `fk_wishlist_item_customer1_idx` (`cust_id` ASC) VISIBLE,
  INDEX `fk_wishlist_item_product1_idx` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_wishlist_item_customer1`
    FOREIGN KEY (`cust_id`)
    REFERENCES `test`.`customer` (`cust_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_wishlist_item_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `test`.`product` (`product_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
