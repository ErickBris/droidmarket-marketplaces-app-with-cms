ALTER TABLE `countries` RENAME COLUMN currrency_symbol currency_symbol;
ALTER TABLE `rating` ADD COLUMN `comment` varchar(500) DEFAULT NULL;
ALTER TABLE `products` ADD COLUMN `lat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL;
ALTER TABLE `products` ADD COLUMN `lng` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL;
