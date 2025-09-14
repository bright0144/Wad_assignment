<?php
include('./config.php');

$insertSql = "
INSERT INTO `product_category` (`category_name`) VALUES ('Pokemon');


INSERT INTO `product_category` (`category_name`) VALUES ('Magic: The Gathering');


INSERT INTO `product_category` (`category_name`) VALUES ('Yu-Gi-Oh!');


INSERT INTO `product_category` (`category_name`) VALUES ('Cardfight!! Vanguard');


INSERT INTO `product_category` (`category_name`) VALUES ('Force of Will');


INSERT INTO `product_category` (`category_name`) VALUES ('Flesh and Blood');

INSERT INTO `product_category` (`category_name`) VALUES ('Pokemon');


INSERT INTO `product_category` (`category_name`) VALUES ('Magic: The Gathering');


INSERT INTO `product_category` (`category_name`) VALUES ('Yu-Gi-Oh!');


INSERT INTO `product_category` (`category_name`) VALUES ('Cardfight!! Vanguard');


INSERT INTO `product_category` (`category_name`) VALUES ('Force of Will');


INSERT INTO `product_category` (`category_name`) VALUES ('Flesh and Blood');



INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Charizard VMAX', 'A powerful VMAX card featuring Charizard from the Champions Path expansion.', 'charizard_vmax.png', 150.00, 10, 1);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Pikachu VMAX', 'A rare and valuable VMAX card of the iconic Pikachu.', 'pikachu_vmax.png', 120.00, 8, 1);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Mewtwo & Mew GX', 'A tag team GX card that combines the power of two legendary psychic Pokemon.', 'mewtwo_mew_gx.png', 65.00, 15, 1);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Umbreon V Alt Art', 'A highly sought-after alternate art card of Umbreon V from the Evolving Skies set.', 'umbreon_v_alt.jpg', 90.00, 7, 1);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Shiny Charizard V', 'A full-art shiny version of Charizard from the Shining Fates expansion.', 'shiny_charizard.jpg', 180.00, 5, 1);

INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Black Lotus', 'A legendary and highly sought-after card from the Alpha set.', 'black_lotus.jpg', 20000.00, 1, 2);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Mana Crypt', 'An incredibly powerful artifact that provides colorless mana.', 'mana_crypt.webp', 250.00, 3, 2);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Force of Will', 'A powerful counterspell that can be cast without mana.', 'force_of_will.png', 85.00, 25, 2);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Sol Ring', 'An essential artifact for mana acceleration in various formats.', 'sol_ring.webp', 15.00, 100, 2);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Lightning Bolt', 'A classic and efficient red spell for dealing damage.', 'lightning_bolt.webp', 2.50, 200, 2);

INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Dark Magician', 'A classic monster card, a signature of the main character from the original series.', 'dark_magician.jpg', 25.50, 50, 3);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Blue-Eyes White Dragon', 'The iconic rival monster card from the original series.', 'blue_eyes_white_dragon.jpg', 40.00, 40, 3);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Exodia the Forbidden One', 'A powerful monster that wins the duel when all five pieces are in hand.', 'exodia.jpg', 300.00, 2, 3);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Pot of Greed', 'A spell card that allows the player to draw two cards.', 'pot_of_greed.webp', 5.00, 150, 3);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Stardust Dragon', 'A famous Synchro monster from the 5D''s era.', 'stardust_dragon.jpg', 18.00, 60, 3);

INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Blaster Blade', 'A core card for the Royal Paladin clan.', 'blaster_blade.png', 12.00, 30, 4);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Dragonic Overlord', 'The signature ace unit of the Kagero clan.', 'dragonic_overlord.jpg', 22.00, 25, 4);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('V Series Trial Deck', 'A pre-constructed deck perfect for new players.', 'v_series_deck.jpg', 15.00, 50, 4);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Imaginary Gift Protect', 'A gift marker that gives a unit the Protect keyword.', 'gift_protect.jpg', 1.50, 300, 4);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Tidal Assault', 'A card that allows for additional attacks.', 'tidal_assault.jpg', 7.00, 40, 4);

INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Pricia, True Beastmaster', 'A powerful resonator card with multiple abilities.', 'pricia.jpg', 8.75, 20, 5);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Lapis, The Demon Blade', 'A powerful Ruler card from the Lapis Cluster.', 'lapis_ruler.jpg', 15.00, 18, 5);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Riza, the Resonator of Wind', 'A staple wind resonator with an enter-the-field ability.', 'riza_resonator.webp', 3.25, 50, 5);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Crimson-Red Stone', 'A special magic stone that provides multiple colors of mana.', 'crimson_stone.png', 6.00, 35, 5);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Valentina, the Princess of Love', 'A popular Ruler card with a powerful judgment ability.', 'valentina.jpg', 9.50, 22, 5);

INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Bravo, Showstopper', 'A hero card from the first edition of the set.', 'bravo.jpg', 55.00, 15, 6);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Arcane Rising Booster Box', 'A sealed booster box from the second expansion set.', 'arcane_rising_box.jpg', 120.00, 5, 6);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Command and Conquer', 'A rare and powerful attack action card.', 'command_and_conquer.webp', 80.00, 10, 6);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Phantasmal Haze', 'A defensive card with a powerful illusionist ability.', 'phantasmal_haze.jpg', 4.00, 60, 6);
INSERT INTO `product` (`product_name`, `product_desc`, `product_img`, `product_price`, `stock_qty`, `category_id`)
VALUES ('Tectonic Plating', 'A majestic equipment card for the Guardian class.', 'tectonic_plating.jpg', 35.00, 20, 6);
";

if ($conn->multi_query($insertSql) === TRUE) {
  echo "Product and category inserted successfully";
} else {
  echo "Error: " . $conn->error;
}
