# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.19-1~exp1ubuntu2)
# Database: homestead
# Generation Time: 2015-08-05 15:20:20 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table blog_ls_authors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog_ls_authors`;

CREATE TABLE `blog_ls_authors` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `author_str` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table blog_ls_posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog_ls_posts`;

CREATE TABLE `blog_ls_posts` (
  `blog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) DEFAULT NULL,
  `title_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_str` tinyblob,
  `date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`blog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table card_ls_conditions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `card_ls_conditions`;

CREATE TABLE `card_ls_conditions` (
  `condition_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `condition_str` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`condition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table card_ls_shipping
# ------------------------------------------------------------

DROP TABLE IF EXISTS `card_ls_shipping`;

CREATE TABLE `card_ls_shipping` (
  `shipping_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `min_quantity` int(11) unsigned NOT NULL,
  `max_quantity` int(11) unsigned NOT NULL,
  `estimate` int(11) unsigned NOT NULL,
  PRIMARY KEY (`shipping_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table card_ls_specials
# ------------------------------------------------------------

DROP TABLE IF EXISTS `card_ls_specials`;

CREATE TABLE `card_ls_specials` (
  `special_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `special_str` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`special_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_card_prices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_card_prices`;

CREATE TABLE `mtg_card_prices` (
  `card_id` int(11) unsigned DEFAULT NULL,
  `source_id` int(11) unsigned DEFAULT NULL,
  `price` int(11) unsigned DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_artists
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_artists`;

CREATE TABLE `mtg_ls_artists` (
  `artist_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `artist_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`artist_id`),
  UNIQUE KEY `UNIQUE` (`artist_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_colors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_colors`;

CREATE TABLE `mtg_ls_colors` (
  `color_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `color_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`color_id`),
  UNIQUE KEY `UNIQUE` (`color_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_flavors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_flavors`;

CREATE TABLE `mtg_ls_flavors` (
  `flavor_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `flavor_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`flavor_id`),
  UNIQUE KEY `UNIQUE` (`flavor_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_images`;

CREATE TABLE `mtg_ls_images` (
  `image_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `img_src` longblob NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_manas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_manas`;

CREATE TABLE `mtg_ls_manas` (
  `mana_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mana_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `cmc_amt` int(11) DEFAULT NULL,
  PRIMARY KEY (`mana_id`),
  UNIQUE KEY `UNIQUE` (`mana_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_names
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_names`;

CREATE TABLE `mtg_ls_names` (
  `name_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_str` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`name_id`),
  UNIQUE KEY `UNIQUE` (`name_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_rares
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_rares`;

CREATE TABLE `mtg_ls_rares` (
  `rare_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rare_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rare_id`),
  UNIQUE KEY `UNIQUE` (`rare_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_sets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_sets`;

CREATE TABLE `mtg_ls_sets` (
  `set_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `set_str` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `set_abbreviation` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date` date NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`set_id`),
  UNIQUE KEY `UNIQUE SET` (`set_str`),
  UNIQUE KEY `UNIQUE CODE` (`set_abbreviation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_subtypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_subtypes`;

CREATE TABLE `mtg_ls_subtypes` (
  `subtype_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subtype_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`subtype_id`),
  UNIQUE KEY `UNIQUE` (`subtype_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_texts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_texts`;

CREATE TABLE `mtg_ls_texts` (
  `text_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`text_id`),
  UNIQUE KEY `UNIQUE` (`text_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_ls_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_ls_types`;

CREATE TABLE `mtg_ls_types` (
  `type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `UNIQUE` (`type_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_rel_cards
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_rel_cards`;

CREATE TABLE `mtg_rel_cards` (
  `card_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `set_id` int(11) unsigned DEFAULT NULL,
  `name_id` int(11) unsigned DEFAULT NULL,
  `mana_id` int(11) unsigned DEFAULT NULL,
  `color_id` int(11) unsigned DEFAULT NULL,
  `type_id` int(11) unsigned DEFAULT NULL,
  `subtype_id` int(11) unsigned DEFAULT NULL,
  `text_id` int(11) unsigned DEFAULT NULL,
  `power` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `toughness` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rare_id` int(11) unsigned DEFAULT NULL,
  `artist_id` int(11) unsigned DEFAULT NULL,
  `flavor_id` int(11) unsigned DEFAULT NULL,
  `image_id` int(11) unsigned DEFAULT NULL,
  `multiverse_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`card_id`),
  UNIQUE KEY `set_id` (`set_id`,`name_id`,`mana_id`,`color_id`,`type_id`,`subtype_id`,`text_id`,`power`,`toughness`,`rare_id`,`artist_id`,`flavor_id`,`image_id`,`multiverse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table mtg_rel_inventory
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mtg_rel_inventory`;

CREATE TABLE `mtg_rel_inventory` (
  `item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `market_id` int(11) unsigned NOT NULL DEFAULT '1',
  `condition_id` int(11) unsigned DEFAULT NULL,
  `special_id` int(11) unsigned DEFAULT NULL,
  `card_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `UNIQUE` (`condition_id`,`special_id`,`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table ord_ls_addresses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ord_ls_addresses`;

CREATE TABLE `ord_ls_addresses` (
  `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street1_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street2_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode_int` int(11) unsigned DEFAULT NULL,
  `phone_int` int(11) unsigned DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table ord_ls_feedback
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ord_ls_feedback`;

CREATE TABLE `ord_ls_feedback` (
  `feedback_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) unsigned DEFAULT NULL,
  `seller_id` int(11) unsigned DEFAULT NULL,
  `order_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `feedback_amt` int(3) unsigned DEFAULT NULL,
  `feedback_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issue` int(1) unsigned DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`feedback_id`),
  UNIQUE KEY `UNIQUE` (`buyer_id`,`seller_id`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table ord_ls_orderitems
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ord_ls_orderitems`;

CREATE TABLE `ord_ls_orderitems` (
  `orderitem_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inventory_id` int(11) unsigned DEFAULT NULL,
  `price` int(11) unsigned DEFAULT NULL,
  `quantity` int(11) unsigned DEFAULT NULL,
  `shipped` int(1) unsigned DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`orderitem_id`),
  UNIQUE KEY `UNIQUE` (`order_id`,`inventory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table ord_ls_orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ord_ls_orders`;

CREATE TABLE `ord_ls_orders` (
  `order_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `buyer_id` int(11) unsigned DEFAULT NULL,
  `address_id` int(11) unsigned DEFAULT NULL,
  `speed_id` int(11) unsigned DEFAULT NULL,
  `insurance` int(1) unsigned DEFAULT NULL,
  `shipping_amt` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table ord_ls_payment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ord_ls_payment`;

CREATE TABLE `ord_ls_payment` (
  `pay_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` int(11) unsigned DEFAULT NULL,
  `currency` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`pay_id`),
  UNIQUE KEY `payment_id` (`payment_id`),
  UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table ord_ls_payouts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ord_ls_payouts`;

CREATE TABLE `ord_ls_payouts` (
  `payout_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) unsigned DEFAULT NULL,
  `payout_amt` int(11) unsigned DEFAULT NULL,
  `approved` int(1) unsigned DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table ord_ls_shipments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ord_ls_shipments`;

CREATE TABLE `ord_ls_shipments` (
  `shipment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_str` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tracking_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`shipment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table password_resets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table pkmn_ls_names
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pkmn_ls_names`;

CREATE TABLE `pkmn_ls_names` (
  `name_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`name_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table pkmn_ls_sets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pkmn_ls_sets`;

CREATE TABLE `pkmn_ls_sets` (
  `set_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `set_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table pkmn_rel_cards
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pkmn_rel_cards`;

CREATE TABLE `pkmn_rel_cards` (
  `card_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `set_id` int(11) unsigned DEFAULT NULL,
  `name_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table pkmn_rel_inventory
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pkmn_rel_inventory`;

CREATE TABLE `pkmn_rel_inventory` (
  `item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `market_id` int(11) unsigned NOT NULL DEFAULT '2',
  `condition_id` int(11) unsigned DEFAULT NULL,
  `special_id` int(11) unsigned DEFAULT NULL,
  `card_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `UNIQUE` (`condition_id`,`special_id`,`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table site_ls_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_ls_categories`;

CREATE TABLE `site_ls_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_str` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table site_ls_contactus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_ls_contactus`;

CREATE TABLE `site_ls_contactus` (
  `contact_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name_str` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `message_str` blob,
  `user_id` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table site_ls_markets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_ls_markets`;

CREATE TABLE `site_ls_markets` (
  `market_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `market_str` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`market_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table site_ls_messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_ls_messages`;

CREATE TABLE `site_ls_messages` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from_id` int(11) DEFAULT NULL,
  `to_id` int(11) DEFAULT NULL,
  `message_str` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table site_ls_sellers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_ls_sellers`;

CREATE TABLE `site_ls_sellers` (
  `user_id` int(11) unsigned NOT NULL,
  `seller_str` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `method_id` int(3) unsigned DEFAULT NULL,
  `email_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cycle_id` int(3) unsigned DEFAULT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `seller_str` (`seller_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table site_ls_sources
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_ls_sources`;

CREATE TABLE `site_ls_sources` (
  `source_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `source_str` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table site_rel_addresses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_rel_addresses`;

CREATE TABLE `site_rel_addresses` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `address_id` int(11) unsigned DEFAULT NULL,
  UNIQUE KEY `UNIQUE` (`user_id`,`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table site_rel_cart
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_rel_cart`;

CREATE TABLE `site_rel_cart` (
  `cart_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) unsigned DEFAULT NULL,
  `buyer_id` int(11) unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` int(11) unsigned DEFAULT NULL,
  `quantity` int(11) unsigned DEFAULT NULL,
  `checkout` int(1) unsigned DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  UNIQUE KEY `user` (`inventory_id`,`buyer_id`),
  UNIQUE KEY `session` (`inventory_id`,`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table site_rel_inventory
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_rel_inventory`;

CREATE TABLE `site_rel_inventory` (
  `inventory_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `market_id` int(11) unsigned DEFAULT NULL,
  `item_id` int(11) unsigned DEFAULT NULL,
  `seller_id` int(11) unsigned DEFAULT NULL,
  `price` int(11) unsigned DEFAULT NULL,
  `quantity` int(11) unsigned DEFAULT NULL,
  `green_amt` int(1) unsigned DEFAULT '0',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`inventory_id`),
  UNIQUE KEY `UNIQUE` (`market_id`,`item_id`,`seller_id`,`green_amt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table sys_ls_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_ls_permissions`;

CREATE TABLE `sys_ls_permissions` (
  `permission_id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `permission_str` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='CREATE VIEW `permission_view` AS\nSELECT `u`.`user_id`, `u`.`username`, `g`.`group_id`, `p`.`permission_id`, `x`.`permission` FROM `sys_users` AS `u`\n	JOIN `sys_rel_groups` AS `g` ON `u`.`user_id`=`g`.`user_id`\n	JOIN `sys_rel_permissions` AS `p` ON `g`.`group_id`=`p`.`group_id`\n	JOIN `sys_ls_permissions` AS `x` ON `x`.`permission_id`=`p`.`permission_id`';



# Dump of table sys_rel_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_rel_groups`;

CREATE TABLE `sys_rel_groups` (
  `rel_id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(7) unsigned NOT NULL,
  `user_id` int(7) unsigned NOT NULL,
  PRIMARY KEY (`rel_id`),
  UNIQUE KEY `group_id` (`group_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table sys_rel_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_rel_permissions`;

CREATE TABLE `sys_rel_permissions` (
  `rel_id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(7) unsigned DEFAULT NULL,
  `group_id` int(7) unsigned DEFAULT NULL,
  PRIMARY KEY (`rel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_name_unique` (`name`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
