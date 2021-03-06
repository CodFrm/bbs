/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : bbs

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-02-23 09:27:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bbs_area
-- ----------------------------
DROP TABLE IF EXISTS `bbs_area`;
CREATE TABLE `bbs_area` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `remake` varchar(255) NOT NULL,
  `father_aid` int(11) NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bbs_area
-- ----------------------------
INSERT INTO `bbs_area` VALUES ('1', '会员互动', '会员互动的区域', '0');
INSERT INTO `bbs_area` VALUES ('2', '娱乐闲聊', '', '1');
INSERT INTO `bbs_area` VALUES ('3', '日常灌水', '', '1');
INSERT INTO `bbs_area` VALUES ('4', '资源分享', '', '0');
INSERT INTO `bbs_area` VALUES ('5', '音乐', '', '4');
INSERT INTO `bbs_area` VALUES ('6', '游戏', '', '4');
INSERT INTO `bbs_area` VALUES ('7', '软件', '', '4');
INSERT INTO `bbs_area` VALUES ('8', '电影/视频', '', '4');
INSERT INTO `bbs_area` VALUES ('9', '校园生活', '', '1');

-- ----------------------------
-- Table structure for bbs_auth
-- ----------------------------
DROP TABLE IF EXISTS `bbs_auth`;
CREATE TABLE `bbs_auth` (
  `auth_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `auth_interface` varchar(255) NOT NULL COMMENT '权限接口',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bbs_auth
-- ----------------------------
INSERT INTO `bbs_auth` VALUES ('1', 'admin', '管理员页面权限');
INSERT INTO `bbs_auth` VALUES ('2', 'index->index', '首页浏览');
INSERT INTO `bbs_auth` VALUES ('3', 'index->area', '发帖权限');

-- ----------------------------
-- Table structure for bbs_config
-- ----------------------------
DROP TABLE IF EXISTS `bbs_config`;
CREATE TABLE `bbs_config` (
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bbs_config
-- ----------------------------
INSERT INTO `bbs_config` VALUES ('guest_auth', '2');
INSERT INTO `bbs_config` VALUES ('member_auth', '3');
INSERT INTO `bbs_config` VALUES ('pwd_encode_salt', 'aheYdcv');
INSERT INTO `bbs_config` VALUES ('title', '信院小屋');

-- ----------------------------
-- Table structure for bbs_group
-- ----------------------------
DROP TABLE IF EXISTS `bbs_group`;
CREATE TABLE `bbs_group` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '群组id',
  `group_name` varchar(255) NOT NULL,
  `group_auth` int(11) NOT NULL COMMENT '权限级别',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bbs_group
-- ----------------------------
INSERT INTO `bbs_group` VALUES ('1', '管理员', '10');
INSERT INTO `bbs_group` VALUES ('2', '游客', '1');
INSERT INTO `bbs_group` VALUES ('3', '会员', '2');
INSERT INTO `bbs_group` VALUES ('4', '分区管理Max', '7');
INSERT INTO `bbs_group` VALUES ('5', '分区管理', '6');

-- ----------------------------
-- Table structure for bbs_groupauth
-- ----------------------------
DROP TABLE IF EXISTS `bbs_groupauth`;
CREATE TABLE `bbs_groupauth` (
  `group_id` int(11) NOT NULL,
  `auth_id` int(11) NOT NULL,
  KEY `auth_id` (`auth_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bbs_groupauth
-- ----------------------------
INSERT INTO `bbs_groupauth` VALUES ('1', '1');
INSERT INTO `bbs_groupauth` VALUES ('2', '2');
INSERT INTO `bbs_groupauth` VALUES ('3', '3');

-- ----------------------------
-- Table structure for bbs_posts
-- ----------------------------
DROP TABLE IF EXISTS `bbs_posts`;
CREATE TABLE `bbs_posts` (
  `tid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `mkdown` text NOT NULL,
  `html` mediumtext NOT NULL,
  `time` bigint(20) NOT NULL,
  `aid` int(11) NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bbs_posts
-- ----------------------------
INSERT INTO `bbs_posts` VALUES ('1', '1', 'asdasfsafsdfsgwreg', '# test\r\nasfsfsd\r\nd\r\nfd\r\nf\r\ndf\r\nvd\r\nfb\r\ndf\r\nbdfbwer\r\n', '<h1>test</h1>\n\n<p>asfsfsd\nd\nfd\nf\ndf\nvd\nfb\ndf\nbdfbwer</p>\n', '1516600285', '2');
INSERT INTO `bbs_posts` VALUES ('2', '1', '发表文章', '# test\r\n\r\n家哈哈四的\r\n\r\n\r\n么啊大神的v覆盖34人个发表', '<h1>test</h1>\n\n<p>家哈哈四的</p>\n\n<p>么啊大神的v覆盖34人个发表</p>\n', '1517393697', '7');

-- ----------------------------
-- Table structure for bbs_token
-- ----------------------------
DROP TABLE IF EXISTS `bbs_token`;
CREATE TABLE `bbs_token` (
  `token` varchar(128) NOT NULL,
  `value` varchar(64) NOT NULL,
  `time` bigint(20) unsigned NOT NULL,
  `type` int(4) NOT NULL COMMENT '0 user login 1 email',
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bbs_token
-- ----------------------------
INSERT INTO `bbs_token` VALUES ('FWRBhChesdRXIkOBgr0s4KHCqYuNG6dsP1Mc6I2cz5t2qIjDANO6xvgJ3iw01eZr', '958139621@qq.com', '1516590244', '1');
INSERT INTO `bbs_token` VALUES ('KpFtnAt1ln66BlFd', '1', '1516084441', '0');
INSERT INTO `bbs_token` VALUES ('rbqbBlxq8u5DriU5', '1', '1516512579', '0');
INSERT INTO `bbs_token` VALUES ('UyICZpTxTbur8sIJ', '1', '1517393650', '0');

-- ----------------------------
-- Table structure for bbs_usergroup
-- ----------------------------
DROP TABLE IF EXISTS `bbs_usergroup`;
CREATE TABLE `bbs_usergroup` (
  `uid` bigint(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `expire_time` int(11) NOT NULL DEFAULT '-1' COMMENT '用户组到期时间 永久为-1',
  KEY `uid` (`uid`),
  KEY `user_group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bbs_usergroup
-- ----------------------------
INSERT INTO `bbs_usergroup` VALUES ('1', '2', '-1');
INSERT INTO `bbs_usergroup` VALUES ('1', '3', '-1');

-- ----------------------------
-- Table structure for bbs_users
-- ----------------------------
DROP TABLE IF EXISTS `bbs_users`;
CREATE TABLE `bbs_users` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(64) NOT NULL,
  `avatar` varchar(128) NOT NULL,
  `reg_time` bigint(20) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bbs_users
-- ----------------------------
INSERT INTO `bbs_users` VALUES ('1', 'farmer', '39137caeebb38ba349a9c29291ed677608ea2e464c7c069a676d39c149ee85bb', 'code.farmer@qq.com', 'default.png', '1515690369');
