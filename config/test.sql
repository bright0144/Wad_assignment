-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
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
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`cart_item_id`, `cart_item_qty`, `cart_item_price`, `cart_status`, `cust_id`, `product_id`) VALUES
(6, 4, 150.00, 'active', 1, 1),
(7, 4, 150.00, 'active', 1, 1),
(8, 3, 150.00, 'active', 1, 1),
(9, 3, 120.00, 'active', 1, 2),
(14, 4, 120.00, 'active', 1, 2);

-- --------------------------------------------------------

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
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cust_id`, `password`, `username`, `email`, `phone_number`) VALUES
(1, '$2y$10$mzi5ChNinqIxCrSKSJPDWeIKfQeMOZWC68fsaV9ZJJ6RQeiAXCnF2', 'test', 'hoekit77@gmail.com', '0165502914');

-- --------------------------------------------------------

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

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `address`, `order_date`, `order_status`, `total_amt`, `cust_id`) VALUES
(2, 'asodjpasopdjp', '2025-09-06 22:40:14', 'pending', 600.00, 1),
(3, 'asodjpasopdjp', '2025-09-06 23:34:34', 'pending', 900.00, 1),
(4, 'asodjpasopdjp', '2025-09-06 23:35:22', 'completed', 900.00, 1),
(5, 'asodjpasopdjp', '2025-09-06 23:36:42', 'cancelled', 600.00, 1),
(6, 'asodjpasopdjp', '2025-09-07 00:15:57', 'pending', 750.00, 1),
(7, 'asodjpasopdjp', '2025-09-07 05:08:57', 'pending', 450.00, 1);

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
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `order_item_qty`, `order_item_price`, `order_id`, `product_id`) VALUES
(2, 4, 150.00, 2, 1),
(3, 6, 150.00, 3, 1),
(4, 6, 150.00, 4, 1),
(5, 4, 150.00, 5, 1),
(6, 5, 150.00, 6, 1),
(7, 2, 150.00, 7, 1),
(8, 1, 150.00, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `pay_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pay_amt` decimal(13,2) NOT NULL,
  `pay_method` varchar(45) NOT NULL,
  `transaction_status` varchar(45) NOT NULL,
  `order_id` int NOT NULL,
  `cust_id` int NOT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `payment_id_UNIQUE` (`payment_id`),
  KEY `fk_payment_order1_idx` (`order_id`),
  KEY `fk_payment_customer1_idx` (`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

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

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`) VALUES
(1, 'Charizard VMAX', 'A powerful VMAX card featuring Charizard from the Champions Path expansion.', 'charizard_vmax.jpg', 150.00, -2, 1),
(2, 'Pikachu VMAX', 'A rare and valuable VMAX card of the iconic Pikachu.', 'pikachu_vmax.jpg', 120.00, 8, 1),
(3, 'Mewtwo & Mew GX', 'A tag team GX card that combines the power of two legendary psychic Pokemon.', 'mewtwo_mew_gx.jpg', 65.00, 15, 1),
(4, 'Umbreon V Alt Art', 'A highly sought-after alternate art card of Umbreon V from the Evolving Skies set.', 'umbreon_v_alt.jpg', 90.00, 7, 1),
(5, 'Shiny Charizard V', 'A full-art shiny version of Charizard from the Shining Fates expansion.', 'shiny_charizard.jpg', 180.00, 5, 1),
(6, 'Black Lotus', 'A legendary and highly sought-after card from the Alpha set.', 'black_lotus.jpg', 20000.00, 1, 2),
(7, 'Mana Crypt', 'An incredibly powerful artifact that provides colorless mana.', 'mana_crypt.jpg', 250.00, 3, 2),
(8, 'Force of Will', 'A powerful counterspell that can be cast without mana.', 'force_of_will.jpg', 85.00, 25, 2),
(9, 'Sol Ring', 'An essential artifact for mana acceleration in various formats.', 'sol_ring.jpg', 15.00, 100, 2),
(10, 'Lightning Bolt', 'A classic and efficient red spell for dealing damage.', 'lightning_bolt.jpg', 2.50, 200, 2),
(11, 'Dark Magician', 'A classic monster card, a signature of the main character from the original series.', 'dark_magician.jpg', 25.50, 50, 3),
(12, 'Blue-Eyes White Dragon', 'The iconic rival monster card from the original series.', 'blue_eyes_white_dragon.jpg', 40.00, 40, 3),
(13, 'Exodia the Forbidden One', 'A powerful monster that wins the duel when all five pieces are in hand.', 'exodia.jpg', 300.00, 2, 3),
(14, 'Pot of Greed', 'A spell card that allows the player to draw two cards.', 'pot_of_greed.jpg', 5.00, 150, 3),
(15, 'Stardust Dragon', 'A famous Synchro monster from the 5D\'s era.', 'stardust_dragon.jpg', 18.00, 60, 3),
(16, 'Blaster Blade', 'A core card for the Royal Paladin clan.', 'blaster_blade.jpg', 12.00, 30, 4),
(17, 'Dragonic Overlord', 'The signature ace unit of the Kagero clan.', 'dragonic_overlord.jpg', 22.00, 25, 4),
(18, 'V Series Trial Deck', 'A pre-constructed deck perfect for new players.', 'v_series_deck.jpg', 15.00, 50, 4),
(19, 'Imaginary Gift Protect', 'A gift marker that gives a unit the Protect keyword.', 'gift_protect.jpg', 1.50, 300, 4),
(20, 'Tidal Assault', 'A card that allows for additional attacks.', 'tidal_assault.jpg', 7.00, 40, 4),
(21, 'Pricia, True Beastmaster', 'A powerful resonator card with multiple abilities.', 'pricia.jpg', 8.75, 20, 5),
(22, 'Lapis, The Demon Blade', 'A powerful Ruler card from the Lapis Cluster.', 'lapis_ruler.jpg', 15.00, 18, 5),
(23, 'Riza, the Resonator of Wind', 'A staple wind resonator with an enter-the-field ability.', 'riza_resonator.jpg', 3.25, 50, 5),
(24, 'Crimson-Red Stone', 'A special magic stone that provides multiple colors of mana.', 'crimson_stone.jpg', 6.00, 35, 5),
(25, 'Valentina, the Princess of Love', 'A popular Ruler card with a powerful judgment ability.', 'valentina.jpg', 9.50, 22, 5),
(26, 'Bravo, Showstopper', 'A hero card from the first edition of the set.', 'bravo.jpg', 55.00, 15, 6),
(27, 'Arcane Rising Booster Box', 'A sealed booster box from the second expansion set.', 'arcane_rising_box.jpg', 120.00, 5, 6),
(28, 'Command and Conquer', 'A rare and powerful attack action card.', 'command_and_conquer.jpg', 80.00, 10, 6),
(29, 'Phantasmal Haze', 'A defensive card with a powerful illusionist ability.', 'phantasmal_haze.jpg', 4.00, 60, 6),
(30, 'Tectonic Plating', 'A majestic equipment card for the Guardian class.', 'tectonic_plating.jpg', 35.00, 20, 6);

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

DROP TABLE IF EXISTS `product_category`;
CREATE TABLE IF NOT EXISTS `product_category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(45) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_id_UNIQUE` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`category_id`, `category_name`) VALUES
(1, 'Pokemon'),
(2, 'Magic: The Gathering'),
(3, 'Yu-Gi-Oh!'),
(4, 'Cardfight!! Vanguard'),
(5, 'Force of Will'),
(6, 'Flesh and Blood');

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
-- Dumping data for table `product_review`
--

INSERT INTO `product_review` (`product_review_id`, `comment`, `product_id`, `cust_id`, `product_review_date`) VALUES
(1, 'Great product! It arrived quickly and was exactly as described.', 1, 1, '2025-09-07 07:16:23');

-- --------------------------------------------------------

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
-- Dumping data for table `review_like`
--

INSERT INTO `review_like` (`review_like_id`, `product_review_id`, `cust_id`) VALUES
(1, 1, 1);

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
