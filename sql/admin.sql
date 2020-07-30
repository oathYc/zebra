/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : zebra

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 29/07/2020 16:44:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ws_admins
-- ----------------------------
DROP TABLE IF EXISTS `ws_admins`;
CREATE TABLE `ws_admins`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '密码',
  `last_login_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_admins
-- ----------------------------
INSERT INTO `ws_admins` VALUES (1, 'admin', '2cd1a1f1f0483ab855328b21ad14e172', '127.0.0.1', 1596010327, 1);

-- ----------------------------
-- Table structure for ws_member
-- ----------------------------
DROP TABLE IF EXISTS `ws_member`;
CREATE TABLE `ws_member`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` char(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `nickname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户名',
  `createTime` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `money` decimal(10, 2) NULL DEFAULT NULL COMMENT '余额',
  `real_pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像地址',
  `sex` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '性别',
  `age` int(3) NULL DEFAULT NULL COMMENT '年龄',
  `updateTime` int(11) NULL DEFAULT NULL,
  `openid` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `unionid` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `card` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '身份证号',
  `real_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '真实姓名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会员表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
