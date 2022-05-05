/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50525
Source Host           : localhost:3306
Source Database       : androidg_droidmarket

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2014-05-11 16:56:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for spams
-- ----------------------------
DROP TABLE IF EXISTS `spams`;
CREATE TABLE `spams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `receiver` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `county_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



ALTER TABLE categories
ADD COLUMN `parent_id` bigint(11)  DEFAULT 0 AFTER `updated_at`;

ALTER TABLE products
ADD COLUMN `cities_id` int(11) NOT NULL AFTER `county_id`;


