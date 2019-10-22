/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50717
Source Host           : 127.0.0.1:3306
Source Database       : canhbao

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2017-02-22 00:27:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for warning_level
-- ----------------------------
DROP TABLE IF EXISTS `warning_level`;
CREATE TABLE `warning_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_number` int(11) DEFAULT NULL,
  `to_number` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of warning_level
-- ----------------------------
INSERT INTO `warning_level` VALUES ('1', '1', 'Mức độ cảnh báo 1', 'FFFFFF', '200', '100', '2017-02-21 17:36:48', '2017-02-21 10:36:48');
INSERT INTO `warning_level` VALUES ('2', '2', 'Mức độ cảnh báo 2', 'FFFF63', '100', '200', '2017-02-21 17:45:02', '2017-02-21 10:45:02');
INSERT INTO `warning_level` VALUES ('3', '3', 'Mức độ cảnh báo 3', 'FF002B', '200', '300', '2017-02-21 17:45:02', '2017-02-21 10:45:02');
