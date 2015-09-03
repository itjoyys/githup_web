/*
Navicat MySQL Data Transfer

Source Server         : wx_shop
Source Server Version : 50532
Source Host           : localhost:3306
Source Database       : wx_shop

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2014-03-29 15:10:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for comm_weddingconfig
-- ----------------------------
DROP TABLE IF EXISTS `comm_weddingconfig`;
CREATE TABLE `comm_weddingconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` varchar(8) NOT NULL,
  `title` varchar(60) DEFAULT NULL,
  `name` varchar(60) DEFAULT NULL,
  `date` varchar(40) DEFAULT NULL,
  `address` varchar(120) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `logo` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`,`siteid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comm_weddingconfig
-- ----------------------------
INSERT INTO `comm_weddingconfig` VALUES ('1', '3', '徐良&缪彩英', '新郎  徐良先生 & 新娘 谬彩英女士', '谨定于2014年4月28日(星期一)17:00', '席设：上虞市东关街道大西庄', 'http://www.zsjiadian.cn/', '/Uploads/wedding/53365f3d6f07f.jpg');
