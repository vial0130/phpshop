CREATE DATABASE IF NOT EXISTS `shopcms`;
USE `shopcms`;
-- 管理员表
DROP TABLE IF EXISTS `shop_admin`;
CREATE TABLE `shop_admin`(
`id` tinyint unsigned auto_increment key,
`username` varchar(20) not null unique,
`password` char(32) not null,
`email` varchar(50) not null unique
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 分类表
DROP TABLE IF EXISTS `shop_cate`;
CREATE TABLE `shop_cate`(
`id` smallint unsigned auto_increment key,
`name` varchar(50) unique
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 商品表
DROP TABLE IF EXISTS `shop_pro`;
CREATE TABLE `shop_pro`(
`id` int unsigned auto_increment key,
`name` varchar(50) not null unique,
`sn` varchar(50) not null,
`num` int unsigned default 1,
`price0` decimal(10,2) not null,
`price1` decimal(10,2) not null,
`desc` text,
`img` varchar(50) not null,
`uptime` int unsigned not null,
`isshow` tinyint(1) default 1,
`ishot` tinyint(1) default 0,
`cid` smallint unsigned not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 用户表
DROP TABLE IF EXISTS `shop_user`;
CREATE TABLE `shop_user`(
`id` int unsigned auto_increment key,
`username` varchar(20) not null unique,
`password` char(32) not null,
`sex` enum('男','女','保密') not null default '保密',
`face` varchar(50) not null,
`regtime` int unsigned not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 相册表
DROP TABLE IF EXISTS `shop_album`;
CREATE TABLE `shop_album`(
`id` int unsigned auto_increment key,
`pid` int unsigned not null,
`path` varchar(50) not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8;