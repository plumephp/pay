/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 80000
Source Host           : localhost:3306
Source Database       : al_pay

Target Server Type    : MYSQL
Target Server Version : 80000
File Encoding         : 65001

Date: 2017-03-14 10:41:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pay_log
-- ----------------------------
DROP TABLE IF EXISTS `pay_log`;
CREATE TABLE `pay_log` (
  `id` char(32) NOT NULL COMMENT '主键',
  `source` varchar(50) NOT NULL COMMENT '数据来源',
  `type` tinyint(2) NOT NULL COMMENT '支付类型：10表示微信支付，20表示支付宝支付',
  `content` longtext NOT NULL COMMENT '请求源数据',
  `result` longtext COMMENT '请求结果',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
