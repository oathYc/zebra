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

 Date: 05/08/2020 09:48:50
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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_admins
-- ----------------------------
INSERT INTO `ws_admins` VALUES (1, 'admin', '2cd1a1f1f0483ab855328b21ad14e172', '127.0.0.1', 1596528371, 1);
INSERT INTO `ws_admins` VALUES (2, 'oathYc', 'ea8d570ec4d38e7993c48f9af2e69122', '127.0.0.1', 1596015964, 1);

-- ----------------------------
-- Table structure for ws_clock_in
-- ----------------------------
DROP TABLE IF EXISTS `ws_clock_in`;
CREATE TABLE `ws_clock_in`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '描述',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '房间图片',
  `background` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '背景图片',
  `beginTime` int(11) NULL DEFAULT NULL COMMENT '签到开始时间 分钟数',
  `endTime` int(11) NULL DEFAULT NULL COMMENT '签到结束时间 分钟数',
  `beginTimeStr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '签到开始时间',
  `endTimeStr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '签到结束时间',
  `rule` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '挑战规则',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态 1-启用 0-关闭',
  `createTime` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `maxMoney` decimal(10, 2) NULL DEFAULT NULL COMMENT '金额上限',
  `days` int(5) NULL DEFAULT NULL COMMENT '挑战天数',
  `rewardType` tinyint(1) NULL DEFAULT NULL COMMENT '奖励类型 1-固定金额 2-百分比',
  `reward` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '对应rewardType',
  `sort` int(4) NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '打卡设置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_clock_in
-- ----------------------------
INSERT INTO `ws_clock_in` VALUES (3, '23', '3', '', '', 0, 240, '00:00', '04:00', '', 1, 1596347380, 43.00, 34, 1, '343', 43);
INSERT INTO `ws_clock_in` VALUES (2, '打卡1', '打卡', '/uploads/category/20200802/12a9638a6f68e088582debcac82e4815.jpg', '/uploads/category/20200802/86efb9935109660241ddc0168842c3cd.png', 300, 420, '05:00', '07:00', '而噩噩的', 1, 1596344935, 1.00, 2, 2, '0.01', 23);

-- ----------------------------
-- Table structure for ws_clock_in_join
-- ----------------------------
DROP TABLE IF EXISTS `ws_clock_in_join`;
CREATE TABLE `ws_clock_in_join`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `clockInId` int(11) NULL DEFAULT 1 COMMENT '打卡活动id',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态  0-失败 1-参与中 2-已完成',
  `beginTime` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '报名时间',
  `createTime` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `clockNum` int(3) NULL DEFAULT NULL COMMENT '打卡次数',
  `joinMoney` int(11) NULL DEFAULT NULL COMMENT '参与金额',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ws_clock_in_sign
-- ----------------------------
DROP TABLE IF EXISTS `ws_clock_in_sign`;
CREATE TABLE `ws_clock_in_sign`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `clockInId` int(11) NULL DEFAULT NULL COMMENT '打卡活动id',
  `joinId` int(11) NULL DEFAULT NULL COMMENT '报名的id',
  `clockInTime` datetime(0) NULL DEFAULT NULL COMMENT '打卡时间',
  `date` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '打卡日期',
  `createTime` int(11) NULL DEFAULT NULL COMMENT '创建是阿金',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '打卡签到记录' ROW_FORMAT = Dynamic;

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
  `sex` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '性别',
  `age` int(3) NULL DEFAULT NULL COMMENT '年龄',
  `updateTime` int(11) NULL DEFAULT NULL,
  `openid` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `unionid` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `card` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '身份证号',
  `real_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '真实姓名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 30 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_member
-- ----------------------------
INSERT INTO `ws_member` VALUES (29, '', 'e10adc3949ba59abbe56e057f20f883e', 'oathYc', 'oathYc', 1596534199, 18.00, '123456', '/uploads/avatar/20200804/mr.jpg', '0', NULL, NULL, 'fdssvfvdffdbbg', 'fdvvfvf', NULL, NULL);

-- ----------------------------
-- Table structure for ws_room_create
-- ----------------------------
DROP TABLE IF EXISTS `ws_room_create`;
CREATE TABLE `ws_room_create`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `type` tinyint(1) NULL DEFAULT 2 COMMENT '1-保底房间 2-普通房间',
  `sign` tinyint(1) NULL DEFAULT NULL COMMENT '签到方式 1-一键签到 2-发圈签到',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '活动描述',
  `money` int(11) NULL DEFAULT NULL COMMENT '报名金额',
  `number` int(11) NULL DEFAULT 0 COMMENT '活动人数  0-不限制',
  `beginTime` int(11) NULL DEFAULT NULL COMMENT '活动开始时间(首次开始签到时间戳)',
  `day` int(11) NULL DEFAULT 1 COMMENT '活动周期 天',
  `signBegin` int(11) NULL DEFAULT NULL COMMENT '首次签到开始时间 分钟',
  `signEnd` int(11) NULL DEFAULT NULL COMMENT '首次签到结束时间',
  `createTime` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `name` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '房间名',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '状态 0-报名中   1-活动中 2-活动结束',
  `signNum` tinyint(1) NULL DEFAULT 1 COMMENT '签到次数  最多两次',
  `secondBegin` int(11) NULL DEFAULT NULL COMMENT '第二次签到开始时间  分钟',
  `secondEnd` int(11) NULL DEFAULT NULL COMMENT '第二次签到结束时间',
  `beginTimeStr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '首次签到开始时间',
  `endTimeStr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '首次签到结束时间',
  `pattern` tinyint(1) NULL DEFAULT 1 COMMENT '项目模式  1-每日奖励金瓜分 2-平分模式',
  `secondBeginStr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '二次签到开始时间',
  `secondEndStr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '二次签到结束时间',
  `beginDate` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '开始时间日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 10002 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '房间活动-用户发起' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_room_create
-- ----------------------------
INSERT INTO `ws_room_create` VALUES (10000, 29, 1, 1, 'zhe shi yi ge ce shi', 1, 2, 1597114800, 1, 660, 720, 1596532430, 'ceshi', 0, 1, NULL, NULL, '11:00', '12:00', 1, NULL, NULL, '2020-08-11');
INSERT INTO `ws_room_create` VALUES (10001, 29, 1, 1, 'zhe shi yi ge ce shi', 1, 3, 1596536586, 1, 660, 720, 1596532552, 'ceshi2', 1, 2, 780, 1200, '11:00', '12:00', 1, '13:00', '20:00', '2020-08-04');

-- ----------------------------
-- Table structure for ws_room_join
-- ----------------------------
DROP TABLE IF EXISTS `ws_room_join`;
CREATE TABLE `ws_room_join`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '报名者uid',
  `roomId` int(11) NULL DEFAULT NULL COMMENT '房间号id',
  `createTime` int(11) NULL DEFAULT NULL COMMENT '报名时间',
  `type` tinyint(1) NULL DEFAULT 1 COMMENT '1-房间挑战',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '1-参与中 2-已失败 3-已完成',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '房间挑战报名记录' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of ws_room_join
-- ----------------------------
INSERT INTO `ws_room_join` VALUES (1, 29, 10000, 1596532430, 1, 1);
INSERT INTO `ws_room_join` VALUES (2, 21, 10001, 1596532552, 1, 1);
INSERT INTO `ws_room_join` VALUES (3, 21, 10001, 1596535268, 1, 1);
INSERT INTO `ws_room_join` VALUES (4, 29, 10001, 1596535746, 1, 1);

-- ----------------------------
-- Table structure for ws_room_record
-- ----------------------------
DROP TABLE IF EXISTS `ws_room_record`;
CREATE TABLE `ws_room_record`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomId` int(11) NULL DEFAULT NULL COMMENT '房间id',
  `date` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '统计日期',
  `signSuccess` int(11) NULL DEFAULT NULL COMMENT '今日打卡人数',
  `signFail` int(11) NULL DEFAULT NULL COMMENT '今天打卡失败人数',
  `failMoney` decimal(10, 2) NULL DEFAULT NULL COMMENT '失败金金额今日',
  `createTime` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `rewardMoney` decimal(10, 2) NULL DEFAULT NULL COMMENT '每人的奖励金额',
  `finish` tinyint(1) NULL DEFAULT 0 COMMENT '活动结束 0-未结束 1-已结束',
  `finishNum` int(11) NULL DEFAULT 0 COMMENT '完成挑战人数',
  `roomBegin` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '房间挑战开始时间',
  `successUser` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '打卡用户uid集合',
  `failUser` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '打卡失败用户uid集合',
  `finishUser` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '完成挑战用户uid集合',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ws_room_type
-- ----------------------------
DROP TABLE IF EXISTS `ws_room_type`;
CREATE TABLE `ws_room_type`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NULL DEFAULT NULL COMMENT '1-普通房间 2-保底房间',
  `percent` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '奖励金额 百分比',
  `rule` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '跳转规则',
  `createTime` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `maxMoney` decimal(10, 2) NULL DEFAULT NULL COMMENT '金额上限',
  `minMoney` decimal(10, 2) NULL DEFAULT NULL COMMENT '金额下限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '房间类型' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_room_type
-- ----------------------------
INSERT INTO `ws_room_type` VALUES (5, 2, '1', '程序池 传传传 ', 1596450589, 600.00, 5.00);
INSERT INTO `ws_room_type` VALUES (4, 1, '1', '的深V从出发  保底', 1596450583, 123.00, 1.00);

-- ----------------------------
-- Table structure for ws_sign
-- ----------------------------
DROP TABLE IF EXISTS `ws_sign`;
CREATE TABLE `ws_sign`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomId` int(11) NULL DEFAULT NULL COMMENT '房间id',
  `date` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '签到日志',
  `uid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `firstSign` tinyint(1) NULL DEFAULT 0 COMMENT '0-未签到 1-已签到 第一次签到',
  `firstSignTime` datetime(0) NULL DEFAULT NULL COMMENT '第一次签到时间',
  `secondSign` tinyint(1) NULL DEFAULT NULL COMMENT '二次签到 0-未签到 1-已签到',
  `secondSignTime` datetime(0) NULL DEFAULT NULL COMMENT '二次签到时间',
  `createTime` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `updateTime` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `type` tinyint(1) NULL DEFAULT 1 COMMENT '1-房间挑战',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户签到表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of ws_sign
-- ----------------------------
INSERT INTO `ws_sign` VALUES (7, 10001, '2020-08-04', 29, 1, NULL, 1, '2020-08-04 18:46:01', 1596537905, NULL, 1);

-- ----------------------------
-- Table structure for ws_system
-- ----------------------------
DROP TABLE IF EXISTS `ws_system`;
CREATE TABLE `ws_system`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NULL DEFAULT NULL COMMENT '1-关于我们 2-服务协议 3-隐私政策 4-版本升级 5-招聘发布费用 6-取消用车违约金  7-每公里价格',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '内容',
  `createTime` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统设置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_system
-- ----------------------------
INSERT INTO `ws_system` VALUES (1, 1, '关于我们', 1596015905);
INSERT INTO `ws_system` VALUES (2, 2, '帮助中心', 1596015896);
INSERT INTO `ws_system` VALUES (3, 3, '隐私政策32222大V从VC', 1596505832);
INSERT INTO `ws_system` VALUES (4, 3, '升级', 1592616561);
INSERT INTO `ws_system` VALUES (5, 4, '说的都是', 1594464872);
INSERT INTO `ws_system` VALUES (6, 7, '1.1', 1594876253);
INSERT INTO `ws_system` VALUES (7, 8, '123', 1595649846);

-- ----------------------------
-- Table structure for ws_user_money_record
-- ----------------------------
DROP TABLE IF EXISTS `ws_user_money_record`;
CREATE TABLE `ws_user_money_record`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `money` decimal(10, 2) NULL DEFAULT NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `type` tinyint(1) NULL DEFAULT 1,
  `createTime` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 47 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户金额记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ws_user_money_record
-- ----------------------------
INSERT INTO `ws_user_money_record` VALUES (46, 29, 1.00, '参与房间挑战支付挑战费用', 2, 1596535746);
INSERT INTO `ws_user_money_record` VALUES (45, 29, 1.00, '创建房间支付挑战费用', 2, 1596535268);
INSERT INTO `ws_user_money_record` VALUES (44, 29, 1.00, '创建房间支付挑战费用', 2, 1596532552);
INSERT INTO `ws_user_money_record` VALUES (43, 29, 1.00, '创建房间支付挑战费用', 2, 1596532430);
INSERT INTO `ws_user_money_record` VALUES (42, 29, 1.00, '创建房间支付挑战费用', 2, 1596528416);

SET FOREIGN_KEY_CHECKS = 1;
