/*
MySQL Data Transfer
Source Host: qdm226570157.my3w.com
Source Database: qdm226570157_db
Target Host: qdm226570157.my3w.com
Target Database: qdm226570157_db
Date: 2016/9/23 10:24:53
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for shop_admin
-- ----------------------------
CREATE TABLE `shop_admin` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` char(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for shop_album
-- ----------------------------
CREATE TABLE `shop_album` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `path` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for shop_cate
-- ----------------------------
CREATE TABLE `shop_cate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for shop_pro
-- ----------------------------
CREATE TABLE `shop_pro` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sn` varchar(50) NOT NULL,
  `num` int(10) unsigned DEFAULT '1',
  `price0` decimal(10,2) NOT NULL,
  `price1` decimal(10,2) NOT NULL,
  `desc` text,
  `uptime` int(10) unsigned NOT NULL,
  `isshow` tinyint(1) DEFAULT '1',
  `ishot` tinyint(1) DEFAULT '0',
  `cid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for shop_user
-- ----------------------------
CREATE TABLE `shop_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` char(32) NOT NULL,
  `sex` enum('男','女','保密') NOT NULL DEFAULT '保密',
  `face` varchar(50) NOT NULL,
  `regtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `shop_admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'smallbottle@outlook.com');
