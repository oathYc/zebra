/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : zhaopi

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 18/06/2020 10:15:12
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
INSERT INTO `ws_admins` VALUES (1, 'admin', '2cd1a1f1f0483ab855328b21ad14e172', '127.0.0.1', 1592389359, 1);

-- ----------------------------
-- Table structure for ws_chat_log
-- ----------------------------
DROP TABLE IF EXISTS `ws_chat_log`;
CREATE TABLE `ws_chat_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '网页用户随机编号(仅为记录参考记录)',
  `from_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '发送者名称',
  `from_avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '发送者头像',
  `to_id` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '接收方',
  `to_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '接受者名称',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '发送的内容',
  `time_line` int(10) NOT NULL COMMENT '记录时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fromid`(`from_id`(4)) USING BTREE,
  INDEX `toid`(`to_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ws_comment
-- ----------------------------
DROP TABLE IF EXISTS `ws_comment`;
CREATE TABLE `ws_comment`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '来源不同的游戏平台',
  `uid` int(10) NULL DEFAULT 0 COMMENT '用户id',
  `reply_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT '回复内容',
  `comment_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '内容',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'IP地址',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL COMMENT '截图',
  `mailbox` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL COMMENT '邮箱',
  `created_at` int(10) NULL DEFAULT NULL,
  `updated_at` int(10) NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 2 COMMENT '状态 1:已回复 2:未回复',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin COMMENT = '留言表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ws_config
-- ----------------------------
DROP TABLE IF EXISTS `ws_config`;
CREATE TABLE `ws_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `msg_time` int(5) NULL DEFAULT NULL COMMENT '留言间隔时间',
  `msg_frequency` int(5) NULL DEFAULT NULL COMMENT '留言一天限制次数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin COMMENT = '配置表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of ws_config
-- ----------------------------
INSERT INTO `ws_config` VALUES (1, 3, 3);

-- ----------------------------
-- Table structure for ws_groups
-- ----------------------------
DROP TABLE IF EXISTS `ws_groups`;
CREATE TABLE `ws_groups`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分组id',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '分组名称',
  `status` tinyint(1) NOT NULL COMMENT '分组状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_groups
-- ----------------------------
INSERT INTO `ws_groups` VALUES (1, '售前组', 1);
INSERT INTO `ws_groups` VALUES (2, '售后组', 1);

-- ----------------------------
-- Table structure for ws_kf_config
-- ----------------------------
DROP TABLE IF EXISTS `ws_kf_config`;
CREATE TABLE `ws_kf_config`  (
  `id` int(11) NOT NULL,
  `max_service` int(11) NOT NULL COMMENT '每个客服最大服务的客户数',
  `change_status` tinyint(1) NOT NULL COMMENT '是否启用转接',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of ws_kf_config
-- ----------------------------
INSERT INTO `ws_kf_config` VALUES (1, 10, 10);

-- ----------------------------
-- Table structure for ws_now_data
-- ----------------------------
DROP TABLE IF EXISTS `ws_now_data`;
CREATE TABLE `ws_now_data`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_talking` int(5) NOT NULL DEFAULT 0 COMMENT '正在咨询的人数',
  `in_queue` int(5) NOT NULL DEFAULT 0 COMMENT '排队等待的人数',
  `online_kf` int(5) NOT NULL COMMENT '在线客服数',
  `success_in` int(5) NOT NULL COMMENT '成功接入用户',
  `total_in` int(5) NOT NULL COMMENT '今日累积接入的用户',
  `now_date` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '当前日期',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `now_date`(`now_date`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_now_data
-- ----------------------------
INSERT INTO `ws_now_data` VALUES (1, 0, 0, 0, 0, 1, '2019-08-14');

-- ----------------------------
-- Table structure for ws_reply
-- ----------------------------
DROP TABLE IF EXISTS `ws_reply`;
CREATE TABLE `ws_reply`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '自动回复的内容',
  `status` tinyint(1) NOT NULL DEFAULT 2 COMMENT '是否自动回复',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_reply
-- ----------------------------
INSERT INTO `ws_reply` VALUES (1, '欢迎来到王者荣耀！敌军还有三秒到达战场！', 1);

-- ----------------------------
-- Table structure for ws_routing
-- ----------------------------
DROP TABLE IF EXISTS `ws_routing`;
CREATE TABLE `ws_routing`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `routing` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '客服系统打开的路由',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin COMMENT = '客服系统首页设置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_routing
-- ----------------------------
INSERT INTO `ws_routing` VALUES (1, 'http://192.168.112.128:81');

-- ----------------------------
-- Table structure for ws_service_data
-- ----------------------------
DROP TABLE IF EXISTS `ws_service_data`;
CREATE TABLE `ws_service_data`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_talking` int(5) NOT NULL DEFAULT 0 COMMENT '正在咨询的人数',
  `in_queue` int(5) NOT NULL DEFAULT 0 COMMENT '排队等待的人数',
  `online_kf` int(5) NOT NULL COMMENT '在线客服数',
  `success_in` int(5) NOT NULL COMMENT '成功接入用户',
  `total_in` int(5) NOT NULL COMMENT '今日累积接入的用户',
  `add_date` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '写入的日期',
  `add_hour` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '写入的小时数',
  `add_minute` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '写入的分钟数',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `add_date,add_hour`(`add_date`, `add_hour`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ws_service_log
-- ----------------------------
DROP TABLE IF EXISTS `ws_service_log`;
CREATE TABLE `ws_service_log`  (
  `user_id` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '会员的id',
  `client_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '会员的客户端标识',
  `user_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '会员名称',
  `user_avatar` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '会员头像',
  `user_ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '会员的ip',
  `kf_id` varchar(55) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '服务的客服id',
  `start_time` int(10) NOT NULL COMMENT '开始服务时间',
  `end_time` int(10) NULL DEFAULT 0 COMMENT '结束服务时间',
  `group_id` int(11) NOT NULL COMMENT '服务的客服的分组id',
  INDEX `user_id,client_id`(`user_id`, `client_id`) USING BTREE,
  INDEX `kf_id,start_time,end_time`(`kf_id`, `start_time`, `end_time`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ws_users
-- ----------------------------
DROP TABLE IF EXISTS `ws_users`;
CREATE TABLE `ws_users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '客服id',
  `user_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '客服名称',
  `user_pwd` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '客服登录密码',
  `user_avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '客服头像',
  `status` tinyint(1) NOT NULL COMMENT '用户状态',
  `online` tinyint(1) NOT NULL DEFAULT 2 COMMENT '是否在线',
  `group_id` int(11) NULL DEFAULT 0 COMMENT '所属分组id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_users
-- ----------------------------
INSERT INTO `ws_users` VALUES (5, 'oath', '2504e99554ce897b79a1a257e5f971d2', '/uploads/20200617/07d0ab53415092cc82de197861b9868e.jpg', 1, 2, 1);
INSERT INTO `ws_users` VALUES (2, '小美', '5bc8c1b2eaa89939b325da5f54e43b30', '/uploads/20190419/4eb84234138339f27018e1e3625afd15.jpg', 1, 2, 1);

-- ----------------------------
-- Table structure for ws_words
-- ----------------------------
DROP TABLE IF EXISTS `ws_words`;
CREATE TABLE `ws_words`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '常用语内容',
  `add_time` datetime(0) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) NOT NULL COMMENT '是否启用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_words
-- ----------------------------
INSERT INTO `ws_words` VALUES (1, '欢迎来到whisper v1.0.0', '2017-10-25 12:51:09', 1);
INSERT INTO `ws_words` VALUES (3, '有什么可以帮您的吗', '2019-04-11 17:00:09', 1);

SET FOREIGN_KEY_CHECKS = 1;
