-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2020-09-01 17:54:58
-- 服务器版本： 5.6.47-log
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zebra`
--

-- --------------------------------------------------------

--
-- 表的结构 `ws_admins`
--

CREATE TABLE IF NOT EXISTS `ws_admins` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '密码',
  `last_login_ip` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `ws_admins`
--

INSERT INTO `ws_admins` (`id`, `user_name`, `password`, `last_login_ip`, `last_login_time`, `status`) VALUES
(1, 'admin', '2cd1a1f1f0483ab855328b21ad14e172', '14.146.93.66', 1598932610, 1),
(2, 'oathYc', 'ea8d570ec4d38e7993c48f9af2e69122', '127.0.0.1', 1596015964, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ws_clock_in`
--

CREATE TABLE IF NOT EXISTS `ws_clock_in` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `image` varchar(255) DEFAULT NULL COMMENT '房间图片',
  `background` varchar(255) DEFAULT NULL COMMENT '背景图片',
  `beginTime` int(11) DEFAULT NULL COMMENT '签到开始时间 分钟数',
  `endTime` int(11) DEFAULT NULL COMMENT '签到结束时间 分钟数',
  `beginTimeStr` varchar(10) DEFAULT NULL COMMENT '签到开始时间',
  `endTimeStr` varchar(10) DEFAULT NULL COMMENT '签到结束时间',
  `rule` text COMMENT '挑战规则',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1-启用 0-关闭',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `maxMoney` decimal(10,2) DEFAULT NULL COMMENT '金额上限',
  `days` int(5) DEFAULT NULL COMMENT '挑战天数',
  `rewardType` tinyint(1) DEFAULT NULL COMMENT '奖励类型 1-固定金额 2-百分比',
  `reward` varchar(30) DEFAULT NULL COMMENT '对应rewardType',
  `sort` int(4) DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='打卡设置';

--
-- 转存表中的数据 `ws_clock_in`
--

INSERT INTO `ws_clock_in` (`id`, `name`, `desc`, `image`, `background`, `beginTime`, `endTime`, `beginTimeStr`, `endTimeStr`, `rule`, `status`, `createTime`, `maxMoney`, `days`, `rewardType`, `reward`, `sort`) VALUES
(5, '三点打卡', '三点到三点十五分打卡', '/uploads/category/20200828/dc21de622302bccee871ce940575e5e1.jpg', '/uploads/category/20200828/3bb7b92afad573714225418a39ac12b8.jpg', 900, 915, '15:00', '15:15', '三点十五分之前打卡', 1, 1598588868, '12.00', 5, 2, '10', 9),
(38, 'banma1008', 'xdcc', '/uploads/category/20200901/1856cfa9c1a84f2e51eefd09bcc0aa8e.png', '/uploads/category/20200901/41e7fd83220b6f5097039719eeb83978.png', 1051, 1052, '17:31', '17:32', '555', 1, 1598952572, '10.00', 1, 1, '10', 1),
(31, '测试111', '凄凄切切', '/uploads/category/20200901/3c45d89de61dbbd36ea5b58569ca2a80.jpg', '/uploads/category/20200901/8c58e0c7a556d7ba24289f6f8e4d67c3.jpg', 990, 992, '16:30', '16:32', '少时诵诗书', 1, 1598948859, '12.00', 3, 2, '10', 12),
(32, 'banma9999', '2dedef', '/uploads/category/20200901/e1d806f340963e3fa6b3390c4b9b349d.png', '/uploads/category/20200901/055045e0fcd8c5606d499e7dc1a7df03.png', 1009, 1010, '16:49', '16:50', 'sccc', 1, 1598950062, '10.00', 1, 1, '10', 1),
(33, 'banma1000', 'csc', '/uploads/category/20200901/0432d32c006553add436759dfc738806.png', '/uploads/category/20200901/55d49876fe5dfa198de8bdc8e9ff9c76.png', 1020, 1021, '17:00', '17:01', 'wde', 1, 1598950707, '10.00', 1, 1, '10', 1),
(34, 'banm1001', 'wsdd', '/uploads/category/20200901/cd229b656c4f174137500515c39e9623.png', '/uploads/category/20200901/0432a43f68e9b3fb711c2d4753c09120.png', 1023, 1024, '17:03', '17:04', 'sdc', 1, 1598950921, '10.00', 1, 1, '10', 1),
(35, 'banma1002', 'wed', '/uploads/category/20200901/374c62ad084376933a25210e52f0a2ee.png', '/uploads/category/20200901/b08446048f0144137a9027de017a0bb5.png', 1027, 1028, '17:07', '17:08', '000', 1, 1598951191, '10.00', 1, 1, '10', 1),
(36, 'banma1005', 'sasxsxs', '/uploads/category/20200901/8358935dbc22a12736330368070cd601.png', '/uploads/category/20200901/afcc1af7a1a602ed434f475bdb11bcf8.png', 1029, 1030, '17:09', '17:10', '', 1, 1598951301, '10.00', 1, 1, '10', 1),
(37, 'banma1007', 'dscd', '/uploads/category/20200901/4202156df7b7870b25a81490d612a746.png', '/uploads/category/20200901/f591fe5696e784ad439f9c5ccd80938f.png', 1046, 1047, '17:26', '17:27', 'dede', 1, 1598952286, '10.00', 1, 1, '10', 1),
(20, 'banma123', 'saxs', '/uploads/category/20200831/f6a4c92d119b8c56e06bdebbcef36b2b.png', '/uploads/category/20200831/df3ffe8717301b9ab924401e62ce4357.png', 1383, 1385, '23:03', '23:05', 'ddcdsvfvfb', 1, 1598886091, '10.00', 2, 1, '10', 1),
(21, 'banma2222', 'qqxsc', '/uploads/category/20200831/7e4209197f28b2f76e3762a08e3fcc78.png', '/uploads/category/20200831/c563c480e69e3af2a07ab6e5500533cd.png', 1388, 1390, '23:08', '23:10', 'cdscdsc', 1, 1598886425, '10.00', 2, 1, '10', 1),
(22, 'banma3333', 'sxsx', '/uploads/category/20200831/e339322b370d9840d99bfd9b39df515a.png', '/uploads/category/20200831/38f9937bbf2752f37d04c63928be7469.png', 1392, 1393, '23:12', '23:13', 'cd', 1, 1598886627, '10.00', 2, 1, '10', 1),
(23, 'banma4444', 'sdfdv', '/uploads/category/20200831/4f6c188b1e205487a3c59f0551bf87f9.png', '/uploads/category/20200831/9f303d2c9d109942ba97d1beacc61ea2.png', 1418, 1419, '23:38', '23:39', 'cdcdc', 1, 1598888233, '10.00', 2, 1, '10', 1),
(24, 'banma6666666', 'ddfvdfv', '/uploads/category/20200901/c6f9fe2ff005be93c2e990852e5ababd.png', '/uploads/category/20200901/77c05351bed98a9c085fc11a025ae5ba.png', 793, 794, '13:13', '13:14', '融入555555555', 1, 1598937118, '1.00', 1, 1, '10', 1),
(25, 'banma7777', '题66', '/uploads/category/20200901/f905f01a5ef2f5274a796b04449c162a.png', '/uploads/category/20200901/7583758ab991962644eb33cca25f8f11.png', 796, 797, '13:16', '13:17', '555555', 1, 1598937309, '10.00', 1, 1, '10', 1),
(26, '测试1点', '测试1点', '/uploads/category/20200901/0fca6be2eb0133d01b88361cc4373788.jpg', '/uploads/category/20200901/90167333d5ea66be3043e600551e817a.jpg', 810, 815, '13:30', '13:35', '测试1点', 1, 1598937748, '10.00', 3, 2, '1', 1),
(27, '打卡测试23', '测试23', '/uploads/category/20200901/565b24a19ba4f3cbf2683d9eb349c675.jpg', '/uploads/category/20200901/6f2ae5b145fe100c4eac2df5d4282b8d.jpg', 917, 965, '15:17', '16:05', '测试', 1, 1598944465, '1.00', 4, 2, '0.1', 4444),
(29, 'banma88888', 'weccdcvv', '/uploads/category/20200901/e71b505491b89728c97669a0e83aacb1.png', '/uploads/category/20200901/bbc2b5d99c17df5cd42cab33bf83ebfa.png', 955, 957, '15:55', '15:57', '00000899', 1, 1598946809, '10.00', 1, 1, '10', 1);

-- --------------------------------------------------------

--
-- 表的结构 `ws_clock_in_join`
--

CREATE TABLE IF NOT EXISTS `ws_clock_in_join` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `clockInId` int(11) DEFAULT '1' COMMENT '打卡活动id',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态  0-失败 1-参与中 2-已完成',
  `beginTime` varchar(11) DEFAULT NULL COMMENT '报名时间',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `clockNum` int(3) DEFAULT NULL COMMENT '打卡次数',
  `joinMoney` int(11) DEFAULT NULL COMMENT '参与金额'
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `ws_clock_in_join`
--

INSERT INTO `ws_clock_in_join` (`id`, `uid`, `clockInId`, `status`, `beginTime`, `createTime`, `clockNum`, `joinMoney`) VALUES
(94, 48, 31, 1, '2020-09-01', 1598949788, 0, 12),
(95, 33, 5, 1, '2020-09-01', 1598949962, 0, 12),
(96, 33, 32, 2, '2020-09-01', 1598950078, 1, 10),
(97, 33, 33, 0, '2020-09-01', 1598950725, 0, 10),
(98, 33, 33, 1, '2020-09-01', 1598950865, 0, 10),
(99, 33, 34, 2, '2020-09-01', 1598950934, 1, 10),
(100, 33, 36, 2, '2020-09-01', 1598951315, 1, 10),
(101, 33, 37, 2, '2020-09-01', 1598952302, 1, 10),
(102, 33, 38, 2, '2020-09-01', 1598952595, 1, 10),
(103, 33, 38, 1, '2020-09-01', 1598952732, 0, 10),
(93, 48, 31, 0, '2020-09-01', 1598948875, 0, 12);

-- --------------------------------------------------------

--
-- 表的结构 `ws_clock_in_price`
--

CREATE TABLE IF NOT EXISTS `ws_clock_in_price` (
  `id` int(11) NOT NULL,
  `clockInId` int(11) DEFAULT NULL COMMENT '打卡活动id',
  `price` decimal(10,2) DEFAULT NULL COMMENT '报名价格',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='打卡价格记录表';

--
-- 转存表中的数据 `ws_clock_in_price`
--

INSERT INTO `ws_clock_in_price` (`id`, `clockInId`, `price`, `createTime`) VALUES
(6, 4, '1.00', NULL),
(7, 4, '4.00', NULL),
(8, 2, '2.00', NULL),
(9, 2, '7.00', NULL),
(10, 5, '12.00', 1598588868),
(11, 5, '24.00', 1598588868),
(12, 5, '36.00', 1598588868),
(13, 6, '10.00', 1598639508),
(14, 6, '20.00', 1598639508),
(15, 7, '1.00', 1598639606),
(16, 8, '12.00', 1598789295),
(17, 8, '4.00', 1598789295),
(18, 8, '5.00', 1598789295),
(19, 9, '10.00', 1598844264),
(20, 9, '50.00', 1598844264),
(21, 10, '10.00', 1598850115),
(22, 11, '10.00', 1598856781),
(23, 12, '10.00', 1598858168),
(24, 13, '10.00', 1598859183),
(25, 14, '1.00', 1598860633),
(26, 15, '10.00', 1598861671),
(27, 16, '10.00', 1598862806),
(28, 17, '10.00', 1598884755),
(29, 18, '10.00', 1598885911),
(30, 19, '10.00', 1598886005),
(31, 19, '1.00', 1598886005),
(32, 20, '10.00', 1598886091),
(33, 20, '20.00', 1598886091),
(34, 21, '10.00', 1598886425),
(35, 22, '10.00', 1598886627),
(36, 23, '10.00', 1598888233),
(37, 24, '1.00', 1598937118),
(38, 25, '10.00', 1598937309),
(39, 26, '10.00', 1598937748),
(40, 27, '1.00', 1598944465),
(41, 27, '12.00', 1598944465),
(42, 28, '1.00', 1598945050),
(43, 29, '10.00', 1598946809),
(44, 30, '12.00', 1598947902),
(45, 31, '12.00', 1598948859),
(46, 32, '10.00', 1598950062),
(47, 33, '10.00', 1598950707),
(48, 34, '10.00', 1598950921),
(49, 35, '10.00', 1598951191),
(50, 36, '10.00', 1598951301),
(51, 37, '10.00', 1598952286),
(52, 38, '10.00', 1598952572);

-- --------------------------------------------------------

--
-- 表的结构 `ws_clock_in_sign`
--

CREATE TABLE IF NOT EXISTS `ws_clock_in_sign` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `clockInId` int(11) DEFAULT NULL COMMENT '打卡活动id',
  `joinId` int(11) DEFAULT NULL COMMENT '报名的id',
  `clockInTime` datetime DEFAULT NULL COMMENT '打卡时间',
  `date` varchar(11) DEFAULT NULL COMMENT '打卡日期',
  `createTime` int(11) DEFAULT NULL COMMENT '创建是阿金'
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='打卡签到记录';

--
-- 转存表中的数据 `ws_clock_in_sign`
--

INSERT INTO `ws_clock_in_sign` (`id`, `uid`, `clockInId`, `joinId`, `clockInTime`, `date`, `createTime`) VALUES
(1, 29, 4, 4, '2020-08-06 16:26:02', '2020-08-06', 1596702362),
(2, 33, 4, 11, '2020-08-30 20:01:41', '2020-08-30', 1598788901),
(3, 33, 4, 12, '2020-08-30 20:02:27', '2020-08-30', 1598788947),
(4, 33, 4, 13, '2020-08-30 20:04:43', '2020-08-30', 1598789083),
(5, 33, 8, 14, '2020-08-30 20:09:35', '2020-08-30', 1598789375),
(6, 33, 4, 15, '2020-08-30 20:35:26', '2020-08-30', 1598790926),
(7, 33, 4, 16, '2020-08-30 20:36:16', '2020-08-30', 1598790976),
(8, 33, 4, 17, '2020-08-30 20:36:59', '2020-08-30', 1598791019),
(9, 33, 4, 18, '2020-08-30 20:38:06', '2020-08-30', 1598791086),
(10, 33, 4, 19, '2020-08-30 20:43:33', '2020-08-30', 1598791413),
(11, 33, 4, 21, '2020-08-30 22:21:30', '2020-08-30', 1598797290),
(12, 33, 4, 23, '2020-08-30 22:28:15', '2020-08-30', 1598797695),
(13, 33, 4, 24, '2020-08-30 22:32:27', '2020-08-30', 1598797947),
(14, 33, 4, 25, '2020-08-31 00:53:24', '2020-08-31', 1598806404),
(15, 33, 4, 26, '2020-08-31 00:53:49', '2020-08-31', 1598806429),
(16, 33, 4, 27, '2020-08-31 11:51:59', '2020-08-31', 1598845919),
(17, 33, 4, 34, '2020-08-31 11:55:53', '2020-08-31', 1598846153),
(18, 33, 4, 35, '2020-08-31 12:48:02', '2020-08-31', 1598849282),
(19, 33, 4, 38, '2020-08-31 13:46:32', '2020-08-31', 1598852792),
(20, 33, 4, 39, '2020-08-31 13:48:20', '2020-08-31', 1598852900),
(21, 33, 4, 41, '2020-08-31 14:11:37', '2020-08-31', 1598854297),
(22, 29, 14, 46, '2020-08-31 16:08:20', '2020-08-31', 1598861300),
(23, 47, 16, 48, '2020-08-31 16:40:20', '2020-08-31', 1598863220),
(24, 47, 16, 49, '2020-08-31 16:40:54', '2020-08-31', 1598863254),
(25, 47, 16, 50, '2020-08-31 16:40:59', '2020-08-31', 1598863259),
(26, 47, 16, 51, '2020-08-31 16:41:15', '2020-08-31', 1598863275),
(27, 47, 16, 52, '2020-08-31 16:42:16', '2020-08-31', 1598863336),
(28, 47, 16, 53, '2020-08-31 16:42:54', '2020-08-31', 1598863374),
(29, 36, 17, 64, '2020-08-31 22:50:07', '2020-08-31', 1598885407),
(30, 36, 17, 64, '2020-08-31 22:50:18', '2020-08-31', 1598885418),
(31, 36, 17, 65, '2020-08-31 22:50:21', '2020-08-31', 1598885421),
(32, 33, 21, 71, '2020-08-31 23:08:16', '2020-08-31', 1598886496),
(33, 33, 22, 72, '2020-08-31 23:12:06', '2020-08-31', 1598886726),
(34, 47, 22, 72, '2020-08-31 23:12:09', '2020-08-31', 1598886729),
(35, 47, 22, 73, '2020-08-31 23:12:14', '2020-08-31', 1598886734),
(36, 47, 22, 73, '2020-08-31 23:12:15', '2020-08-31', 1598886735),
(37, 33, 23, 76, '2020-08-31 23:38:04', '2020-08-31', 1598888284),
(38, 33, 23, 76, '2020-08-31 23:38:05', '2020-08-31', 1598888285),
(39, 33, 8, 14, '2020-09-01 12:58:00', '2020-09-01', 1598936280),
(40, 33, 10, 36, '2020-09-01 13:10:02', '2020-09-01', 1598937002),
(41, 33, 10, 37, '2020-09-01 13:10:19', '2020-09-01', 1598937019),
(42, 33, 25, 84, '2020-09-01 13:16:05', '2020-09-01', 1598937365),
(43, 47, 26, 86, '2020-09-01 13:30:06', '2020-09-01', 1598938206),
(44, 48, 27, 88, '2020-09-01 15:18:11', '2020-09-01', 1598944691),
(45, 48, 28, 89, '2020-09-01 15:27:03', '2020-09-01', 1598945223),
(46, 33, 32, 96, '2020-09-01 16:49:03', '2020-09-01', 1598950143),
(47, 33, 34, 99, '2020-09-01 17:03:02', '2020-09-01', 1598950982),
(48, 33, 36, 100, '2020-09-01 17:09:01', '2020-09-01', 1598951341),
(49, 33, 37, 101, '2020-09-01 17:26:03', '2020-09-01', 1598952363),
(50, 33, 38, 102, '2020-09-01 17:31:36', '2020-09-01', 1598952696);

-- --------------------------------------------------------

--
-- 表的结构 `ws_clock_reward`
--

CREATE TABLE IF NOT EXISTS `ws_clock_reward` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `clockInId` int(11) DEFAULT NULL COMMENT '打卡活动id',
  `joinId` int(11) DEFAULT NULL COMMENT '报名id',
  `date` char(11) DEFAULT NULL COMMENT '日期',
  `money` decimal(10,2) DEFAULT NULL COMMENT '奖励金额',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='打卡收益记录表';

--
-- 转存表中的数据 `ws_clock_reward`
--

INSERT INTO `ws_clock_reward` (`id`, `uid`, `clockInId`, `joinId`, `date`, `money`, `createTime`) VALUES
(1, 33, 4, 11, '2020-08-30', '0.01', 1598788901),
(2, 33, 4, 12, '2020-08-30', '0.01', 1598788947),
(3, 33, 4, 13, '2020-08-30', '0.01', 1598789083),
(4, 33, 8, 14, '2020-08-30', '0.10', 1598789375),
(5, 33, 4, 15, '2020-08-30', '0.01', 1598790926),
(6, 33, 4, 16, '2020-08-30', '0.01', 1598790976),
(7, 33, 4, 17, '2020-08-30', '0.01', 1598791019),
(8, 33, 4, 18, '2020-08-30', '0.01', 1598791086),
(9, 33, 4, 19, '2020-08-30', '0.01', 1598791413),
(10, 33, 4, 21, '2020-08-30', '0.01', 1598797290),
(11, 33, 4, 23, '2020-08-30', '0.01', 1598797695),
(12, 33, 4, 24, '2020-08-30', '0.01', 1598797947),
(13, 33, 4, 25, '2020-08-31', '0.01', 1598806404),
(14, 33, 4, 26, '2020-08-31', '0.01', 1598806429),
(15, 33, 4, 27, '2020-08-31', '0.01', 1598845919),
(16, 33, 4, 34, '2020-08-31', '0.01', 1598846153),
(17, 33, 4, 35, '2020-08-31', '0.01', 1598849282),
(18, 33, 4, 38, '2020-08-31', '0.01', 1598852792),
(19, 33, 4, 39, '2020-08-31', '0.01', 1598852900),
(20, 33, 4, 41, '2020-08-31', '0.01', 1598854297),
(21, 29, 14, 46, '2020-08-31', '0.10', 1598861300),
(22, 47, 16, 48, '2020-08-31', '10.00', 1598863220),
(23, 47, 16, 49, '2020-08-31', '10.00', 1598863254),
(24, 47, 16, 50, '2020-08-31', '10.00', 1598863259),
(25, 47, 16, 51, '2020-08-31', '10.00', 1598863275),
(26, 47, 16, 52, '2020-08-31', '10.00', 1598863336),
(27, 47, 16, 53, '2020-08-31', '10.00', 1598863374),
(28, 36, 17, 64, '2020-08-31', '10.00', 1598885407),
(29, 36, 17, 64, '2020-08-31', '10.00', 1598885418),
(30, 36, 17, 65, '2020-08-31', '10.00', 1598885421),
(31, 33, 21, 71, '2020-08-31', '10.00', 1598886496),
(32, 33, 22, 72, '2020-08-31', '10.00', 1598886726),
(33, 47, 22, 72, '2020-08-31', '10.00', 1598886729),
(34, 47, 22, 73, '2020-08-31', '10.00', 1598886734),
(35, 47, 22, 73, '2020-08-31', '10.00', 1598886735),
(36, 33, 23, 76, '2020-08-31', '10.00', 1598888284),
(37, 33, 23, 76, '2020-08-31', '10.00', 1598888285),
(38, 33, 8, 14, '2020-09-01', '0.10', 1598936280),
(39, 33, 10, 36, '2020-09-01', '10.00', 1598937002),
(40, 33, 10, 37, '2020-09-01', '10.00', 1598937019),
(41, 33, 25, 84, '2020-09-01', '10.00', 1598937365),
(42, 47, 26, 86, '2020-09-01', '10.00', 1598938206),
(43, 48, 27, 88, '2020-09-01', '1.20', 1598944691),
(44, 48, 28, 89, '2020-09-01', '0.10', 1598945223),
(45, 33, 32, 96, '2020-09-01', '10.00', 1598950143),
(46, 33, 34, 99, '2020-09-01', '10.00', 1598950982),
(47, 33, 36, 100, '2020-09-01', '10.00', 1598951341),
(48, 33, 37, 101, '2020-09-01', '10.00', 1598952363),
(49, 33, 38, 102, '2020-09-01', '10.00', 1598952696);

-- --------------------------------------------------------

--
-- 表的结构 `ws_member`
--

CREATE TABLE IF NOT EXISTS `ws_member` (
  `id` int(11) NOT NULL,
  `phone` char(12) DEFAULT NULL COMMENT '手机号',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `money` decimal(10,2) DEFAULT NULL COMMENT '余额',
  `real_pass` varchar(255) DEFAULT NULL,
  `avatar` text COMMENT '头像地址',
  `sex` varchar(10) DEFAULT NULL COMMENT '性别',
  `age` int(3) DEFAULT NULL COMMENT '年龄',
  `updateTime` int(11) DEFAULT NULL,
  `openid` varchar(80) DEFAULT NULL,
  `unionid` varchar(80) DEFAULT NULL,
  `card` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `real_name` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `inviteCode` varchar(20) DEFAULT NULL COMMENT '我的邀请码',
  `inviterCode` varchar(20) DEFAULT NULL COMMENT '邀请人的邀请码',
  `check` tinyint(1) NOT NULL DEFAULT '0' COMMENT '实名认证审核状态 0-未提交 1-待审核 2-审核通过 3-审核失败'
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员表';

--
-- 转存表中的数据 `ws_member`
--

INSERT INTO `ws_member` (`id`, `phone`, `password`, `nickname`, `username`, `createTime`, `money`, `real_pass`, `avatar`, `sex`, `age`, `updateTime`, `openid`, `unionid`, `card`, `real_name`, `inviteCode`, `inviterCode`, `check`) VALUES
(29, '', 'e10adc3949ba59abbe56e057f20f883e', 'oathYc', 'oathYc', 1597481586, '66.11', '123456', '/uploads/avatar/20200804/mr.jpg', '0', NULL, 1598860756, 'fdssvfvdffdbbg', 'fdvvfvf', NULL, NULL, 'asdfghjkdddd', 'asdfghjk', 0),
(31, '', 'e10adc3949ba59abbe56e057f20f883e', 'oathYc', 'oathYc1', 1597481586, '19.01', '123456', '/uploads/avatar/20200804/mr.jpg', '0', NULL, 1598860756, 'fdssvfvdffdbbg', 'fdvvfvf', NULL, NULL, 'asdfghjk', 'asdfghjkdddd', 0),
(33, '', 'e10adc3949ba59abbe56e057f20f883e', '夏天的风', '夏天的风', 1597569440, '8823.39', '123456', 'https://pic.cnblogs.com/face/504386/20141226121302.png', '男', NULL, 1598949912, '3beuidbudekbdue', '33333', '1111', '华兴', 'TTU2LGDK', NULL, 2),
(34, '', 'e10adc3949ba59abbe56e057f20f883e', '夏天的风123', '夏天的风123', 1598161343, '0.00', '123456', 'https://pic.cnblogs.com/face/504386/20141226121302.png', NULL, NULL, NULL, '3beuidb555bdue', '444443', NULL, NULL, 'NBA6SLNC', NULL, 0),
(35, '', 'e10adc3949ba59abbe56e057f20f883e', '❤Gan华兴', '❤Gan华兴', 1598171804, '9900.03', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/CEYdnWdqeKO4MSUVFRPmPuQlnlURNKWS9xbceZS0cQdgYroTKgV2U09L8kIHqEjsRqQA4l98kC8ghUibYRolZIA/132', NULL, NULL, 1598894881, 'oVIYc5_9vddJyPhe8qwk-Psly0ag', 'ovbsr5_H7JAdyIRoE2fLLhJ8p4ZM', '255', '小名', 'IE882YBX', NULL, 1),
(36, '', 'e10adc3949ba59abbe56e057f20f883e', '宜兴市喜之来建材店陆健', '宜兴市喜之来建材店陆健', 1598178001, '9706.00', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83erRl7HJKr3NP71CMtAJ6uyq2hMccibMtcC6CCN6Z4XglwUeKwiaYCoLcl63sBJsdIyxFvcLWeibjQBBA/132', '男', NULL, 1598938086, 'oVIYc5zOnEqpphjajEtPS8U6_BT0', 'ovbsr557Z4DMem3pXgZZ2SJWFQxI', '320282198602150513', '陆健', 'BJJKOUD9', NULL, 1),
(37, '', 'e10adc3949ba59abbe56e057f20f883e', '英雄杀', '英雄杀', 1598178257, '9860.00', '123456', 'https://thirdwx.qlogo.cn/mmopen/vi_32/0XibgeBOwhKgz5dIEcvWOEt9hHSr50iaGM8b1EDibgYQsZs7WWELufcoBLqM0aMQkM2SUeYWbEtibhNVF4ia6S1HxsQ/132', NULL, NULL, 1598885522, 'oVIYc53bPcK8SjM46RApMIE07H64', 'ovbsr590OMTCtMb4ubnBzEK2JgBc', NULL, NULL, 'XY6LWADK', NULL, 0),
(38, '', 'e10adc3949ba59abbe56e057f20f883e', 'AC', 'AC', 1598183557, '0.00', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/8t3ibGJQF1pFCGpJGVHd0QthF0genLtxtt0GbKKWzFJCCicRwMHwcNAFMUibDXwG9nVVLRPNI7LZnK1rT0qtdIpVg/132', NULL, NULL, 1598563315, 'oVIYc5w9SD5WVcb3RnsTvY55zKlU', 'ovbsr508jntP2DMd71zXaPKmg4cU', NULL, NULL, 'AEYATZOM', NULL, 0),
(39, '', 'e10adc3949ba59abbe56e057f20f883e', '白茶', '白茶', 1598237909, '0.00', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/2EVribEPpHKHVEEicf4YlCkE5TSk9z0H8j3OAJ3man92gbrPT1OAjTGYVHMZnVeRiby122T52HicxN9a0ibWyVuRDNw/132', NULL, NULL, NULL, 'oVIYc5wwLk-n7WhCaf7G3JY0p-a8', 'ovbsr55phMNC_Hbc4BLe_QPSLkOc', NULL, NULL, 'HY3OIFUW', NULL, 0),
(40, '', 'e10adc3949ba59abbe56e057f20f883e', '白茶', '白茶', 1598237909, '0.00', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/2EVribEPpHKHVEEicf4YlCkE5TSk9z0H8j3OAJ3man92gbrPT1OAjTGYVHMZnVeRiby122T52HicxN9a0ibWyVuRDNw/132', NULL, NULL, NULL, 'oVIYc5wwLk-n7WhCaf7G3JY0p-a8', 'ovbsr55phMNC_Hbc4BLe_QPSLkOc', NULL, NULL, 'NBAY658N', NULL, 0),
(41, '', 'e10adc3949ba59abbe56e057f20f883e', 'goldenRetriEvEr', '布丁', 1598344822, '9989.00', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DOCDIdXvexSicR9VELQnSFgRFr61tQKK5pfHXkXicYDLRGZn1drPA5pEuBJBuDlDRamMz1lqDTZWiaiaI5Y1oH8Kibw/132', NULL, NULL, 1598588448, 'oVIYc50eFZAAmXmwoK63staXkrXk', 'ovbsr55VQyRQ-WSOB7szBq-IVAxY', NULL, NULL, 'UVEUQDL4', NULL, 0),
(42, '', 'e10adc3949ba59abbe56e057f20f883e', '过好每一天', '过好每一天', 1598500411, '0.00', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83epiaKkRCw27GlYeTRiaIhurvHCaPe8lTYOOib6VYicoOICuib0gIiakNUhsWD8TIBZkbPdyA0icHp6u1pXQg/132', NULL, NULL, 1598920650, 'oVIYc52HITvNk6eb2_GzTrvGqmhk', 'ovbsr56NMXJ2O5L-V9mXmiMXNr3Q', NULL, NULL, 'XS1VOZOH', 'XS1VOZOH', 0),
(43, '', 'e10adc3949ba59abbe56e057f20f883e', '安高朗', '安高朗', 1598535912, '998.00', '123456', '/uploads/avatar/20200812/3a0c6de5a7042c44e88b2e9a4120dbe3.jpg', NULL, NULL, 1598544439, 'o8_Pjs6RYMJApc7U-3ww0LJBIC28', 'oEPUL503jl6WPxwH1oYcguTcQkJ8', NULL, NULL, 'D0IC2CGY', 'D0IC2CGY', 0),
(44, '', 'e10adc3949ba59abbe56e057f20f883e', '刘海威', '刘海威', 1598546221, '0.00', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLYMVgaumib5hibuSvFzNmhtRNEOxcSKy70Iz0hq1P2IiaZqjqoR6LqVAZbiaianLv6p6IlNGN3ef4Xb6Q/132', NULL, NULL, NULL, 'oRrdQtwwcZM_6L3Z8t491x3IedF4', 'oU5YytzplNcjqGZNvS-tjZdjlQQ8', NULL, NULL, 'G8CSG39W', 'G8CSG39W', 0),
(45, '', 'e10adc3949ba59abbe56e057f20f883e', '似曾相识', '似曾相识', 1598759618, '2000.00', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLeFJCaq7za6zRaIJibNjZiaOJic8RH9AUXqct7noud2yahNyd272Hz2WtnrOicWh8nrs7YbrUHSd1Emg/132', NULL, NULL, NULL, 'oVIYc5wMY187qe0XdUJ3ViyjJz2k', 'ovbsr5zzgoUZDKpRZEmqp8CtG0Lc', NULL, NULL, '1O3AG8Z4', NULL, 0),
(46, '', 'e10adc3949ba59abbe56e057f20f883e', '真真', '真真', 1598759619, '3000.00', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/t3ibhVUDBSrAorTFqKFB3SkcaHsYTAWlBEcHiaw0bK2toH83keuTnDBAsmhwu6IwSUkAIAV0CD1sD25w7RWIQjPg/132', NULL, NULL, NULL, 'oVIYc50qeD3CQMPZksuS03N-w78c', 'ovbsr5xdY0HDMbCgJUkLx4WaYc_I', NULL, NULL, '8QWJUBAC', NULL, 0),
(47, '', 'e10adc3949ba59abbe56e057f20f883e', 'zoz', 'zoz', 1598764020, '10029.00', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DJdzDj9ia6TX1ocCozVswdpTFWc5eW8aO8oWGm5ax9NLnHDSicpByHCn90R874mUqZgDUCKKkUwhqZuiby1umicOeQ/132', NULL, NULL, 1598937569, 'oVIYc5-K45xGMxCvJiEcS7nBU_JQ', 'ovbsr59y9IRoGPD9PUS8OV1A390k', NULL, NULL, 'L5MJG1ZL', NULL, 0),
(48, '15828043607', 'e10adc3949ba59abbe56e057f20f883e', 'oathYc', 'oathYc', 1598944230, '9922.30', '123456', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Qyh32nBEdRJ1iauFvBA2N3QqCa3O3mKSicuaZy0TgBZl5MRqZWyxHk4RJxDcaARwKmrTKmWnWTcGCKS3KR7SlEQg/132', NULL, NULL, 1598953965, 'oVIYc5_pK3TcRitJqPMAZ8WdixIU', 'ovbsr50wWtl-OFRdF8ar7X8_vKAQ', NULL, NULL, '0YGFL7FN', NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ws_money_get`
--

CREATE TABLE IF NOT EXISTS `ws_money_get` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1-打卡 2-房间挑战 3-闯关',
  `moneyGet` decimal(10,2) DEFAULT NULL COMMENT '收益金额',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updateTime` datetime DEFAULT NULL COMMENT '更新时间'
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='收益统计表';

--
-- 转存表中的数据 `ws_money_get`
--

INSERT INTO `ws_money_get` (`id`, `uid`, `type`, `moneyGet`, `createTime`, `updateTime`) VALUES
(1, 29, 3, '1.00', 1597486346, '2020-08-15 18:12:26'),
(2, 29, 1, '11.10', 1597486346, NULL),
(3, 29, 2, '22.00', 1597486346, NULL),
(4, 33, 1, '120.39', 1598788901, '2020-08-30 20:01:41'),
(5, 47, 1, '100.00', 1598863220, '2020-08-31 16:40:20'),
(6, 36, 1, '30.00', 1598885407, '2020-08-31 22:50:07'),
(7, 48, 1, '1.30', 1598944691, '2020-09-01 15:18:11');

-- --------------------------------------------------------

--
-- 表的结构 `ws_money_recharge`
--

CREATE TABLE IF NOT EXISTS `ws_money_recharge` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL COMMENT '充值金额',
  `createTime` int(11) DEFAULT NULL COMMENT '充值时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 0-充值中 1-充值成功',
  `payTime` int(11) DEFAULT NULL COMMENT '支付回调时间',
  `orderNo` varchar(40) DEFAULT NULL COMMENT '订单号',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-微信 2-支付宝'
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='余额充值';

--
-- 转存表中的数据 `ws_money_recharge`
--

INSERT INTO `ws_money_recharge` (`id`, `uid`, `money`, `createTime`, `status`, `payTime`, `orderNo`, `type`) VALUES
(3, 2, '11.00', 1595126193, 0, NULL, 'RE15951261937236', 1),
(4, 2, '11.00', 1595126259, 0, NULL, 'RE15951262592108', 1),
(5, 2, '11.00', 1595126303, 0, NULL, 'RE15951263034706', 1),
(6, 2, '11.00', 1595126314, 0, NULL, 'RE15951263144278', 1),
(7, 2, '11.00', 1595126332, 0, NULL, 'RE15951263326387', 1),
(8, 2, '11.00', 1595126389, 0, NULL, 'RE15951263891918', 1),
(9, 2, '11.00', 1595126409, 0, NULL, 'RE15951264093006', 1),
(10, 2, '11.00', 1595126438, 0, NULL, 'RE15951264385569', 1),
(11, 2, '11.00', 1595126456, 0, NULL, 'RE15951264561857', 1),
(12, 2, '11.00', 1595126480, 0, NULL, 'RE15951264802651', 1),
(13, 2, '11.00', 1595126536, 0, NULL, 'RE15951265363956', 1),
(14, 2, '11.00', 1595128549, 0, NULL, 'RE15951285491794', 1),
(15, 33, '20.00', 1598461768, 0, NULL, 'CG15984617689124', 2),
(16, 33, '10.00', 1598461822, 0, NULL, 'CG15984618222958', 1),
(17, 33, '10.00', 1598461847, 0, NULL, 'CG15984618474890', 2),
(18, 35, '3000.00', 1598464404, 0, NULL, 'CG15984644042927', 1),
(19, 35, '3000.00', 1598464449, 0, NULL, 'CG15984644491348', 1),
(20, 35, '3000.00', 1598464453, 0, NULL, 'CG15984644537062', 2),
(21, 35, '20.00', 1598464462, 0, NULL, 'CG15984644627670', 1),
(22, 35, '10.00', 1598492822, 0, NULL, 'CG15984928223755', 1),
(23, 35, '10.00', 1598492829, 0, NULL, 'CG15984928298272', 1),
(24, 35, '10.00', 1598492931, 0, NULL, 'CG15984929316482', 2),
(25, 36, '10.00', 1598494194, 0, NULL, 'CG15984941943486', 1),
(26, 36, '10.00', 1598494448, 0, NULL, 'CG15984944482379', 2),
(27, 36, '10.00', 1598494882, 0, NULL, 'CG15984948822891', 1),
(28, 36, '10.00', 1598504072, 0, NULL, 'CG15985040725112', 1),
(29, 36, '10.00', 1598504074, 0, NULL, 'CG15985040743685', 2),
(30, 36, '25.00', 1598504524, 0, NULL, 'CG15985045241773', 1),
(31, 36, '25.00', 1598504526, 0, NULL, 'CG15985045265428', 2),
(32, 42, '100.00', 1598519222, 0, NULL, 'CG15985192227906', 1),
(33, 42, '500.00', 1598519226, 0, NULL, 'CG15985192261814', 2),
(34, 38, '10.00', 1598538379, 0, NULL, 'CG15985383792120', 2),
(35, 38, '10.00', 1598538392, 0, NULL, 'CG15985383922157', 1),
(36, 38, '10.00', 1598538395, 0, NULL, 'CG15985383952778', 2),
(37, 41, '10.00', 1598541262, 0, NULL, 'CG15985412625180', 2),
(38, 44, '10.00', 1598546658, 0, NULL, 'CG15985466585752', 1),
(39, 35, '10.00', 1598548449, 0, NULL, 'CG15985484492131', 1),
(40, 35, '10.00', 1598548452, 0, NULL, 'CG15985484528391', 1),
(41, 35, '10.00', 1598548456, 0, NULL, 'CG15985484562056', 1),
(42, 35, '10.00', 1598548471, 0, NULL, 'CG15985484717031', 1),
(43, 35, '10.00', 1598548494, 0, NULL, 'CG15985484941673', 1),
(44, 35, '10.00', 1598548583, 0, NULL, 'CG15985485832230', 1),
(45, 35, '10.00', 1598548586, 0, NULL, 'CG15985485869333', 1),
(46, 35, '10.00', 1598548590, 0, NULL, 'CG15985485903941', 1),
(47, 35, '10.00', 1598548597, 0, NULL, 'CG15985485977145', 1),
(48, 35, '10.00', 1598548600, 0, NULL, 'CG15985486005559', 1),
(49, 35, '10.00', 1598553102, 0, NULL, 'CG15985531029853', 1),
(50, 35, '10.00', 1598553453, 0, NULL, 'CG15985534533158', 1),
(51, 35, '10.00', 1598553528, 0, NULL, 'CG15985535282184', 2),
(52, 35, '10.00', 1598553556, 0, NULL, 'CG15985535563264', 2),
(53, 33, '10.00', 1598553955, 0, NULL, 'CG15985539554136', 2),
(54, 33, '10.00', 1598553967, 0, NULL, 'CG15985539677349', 2),
(55, 33, '10.00', 1598554242, 0, NULL, 'CG15985542429545', 2),
(56, 42, '100.00', 1598578717, 0, NULL, 'CG15985787177512', 1),
(57, 33, '10.00', 1598645030, 0, NULL, 'CG15986450306152', 1),
(58, 33, '10.00', 1598645167, 0, NULL, 'CG15986451674534', 1),
(59, 33, '10.00', 1598645315, 0, NULL, 'CG15986453155303', 1),
(60, 33, '10.00', 1598645356, 0, NULL, 'CG15986453563237', 1),
(61, 33, '10.00', 1598645570, 0, NULL, 'CG15986455708471', 1),
(62, 35, '10.00', 1598645937, 0, NULL, 'CG15986459379251', 1),
(63, 35, '10.00', 1598646114, 0, NULL, 'CG15986461142969', 2),
(64, 35, '10.00', 1598646464, 0, NULL, 'CG15986464641153', 1),
(65, 35, '10.00', 1598646479, 0, NULL, 'CG15986464791638', 1),
(66, 35, '10.00', 1598646500, 0, NULL, 'CG15986465008737', 1),
(67, 35, '10.00', 1598646504, 0, NULL, 'CG15986465045202', 1),
(68, 35, '10.00', 1598646713, 0, NULL, 'CG15986467135058', 1),
(69, 35, '10.00', 1598646836, 0, NULL, 'CG15986468367224', 1),
(70, 35, '0.01', 1598646881, 0, NULL, 'CG15986468812231', 1),
(71, 35, '0.01', 1598646889, 0, NULL, 'CG15986468898713', 1),
(72, 35, '0.01', 1598646901, 0, NULL, 'CG15986469012751', 1),
(73, 35, '0.01', 1598646947, 0, NULL, 'CG15986469478702', 1),
(74, 35, '0.01', 1598646973, 0, NULL, 'CG15986469733672', 1),
(75, 35, '0.01', 1598647007, 1, 1598647049, 'CG15986470075682', 2),
(76, 35, '0.01', 1598647088, 0, NULL, 'CG15986470884191', 1),
(77, 35, '0.01', 1598647091, 0, NULL, 'CG15986470918739', 1),
(78, 35, '0.01', 1598647095, 0, NULL, 'CG15986470956060', 1),
(79, 35, '0.01', 1598647099, 0, NULL, 'CG15986470991542', 1),
(80, 35, '10.00', 1598647190, 0, NULL, 'CG15986471908455', 1),
(81, 35, '0.01', 1598647223, 1, 1598647237, 'CG15986472234459', 2),
(82, 35, '0.01', 1598647651, 1, 1598647664, 'CG15986476513927', 2),
(83, 36, '10.00', 1598651493, 0, NULL, 'CG15986514932030', 1),
(84, 36, '1.00', 1598651515, 0, NULL, 'CG15986515154870', 1),
(85, 36, '10.00', 1598660406, 0, NULL, 'CG15986604066985', 1),
(86, 36, '10.00', 1598664699, 0, NULL, 'CG15986646998303', 1),
(87, 33, '10.00', 1598729857, 0, NULL, 'CG15987298571840', 1),
(88, 33, '10.00', 1598729865, 0, NULL, 'CG15987298656379', 1),
(89, 33, '10.00', 1598729870, 0, NULL, 'CG15987298703190', 1),
(90, 33, '10.00', 1598729918, 0, NULL, 'CG15987299186488', 2),
(91, 33, '99999999.99', 1598730082, 0, NULL, 'CG15987300826023', 2),
(92, 33, '10.00', 1598730092, 0, NULL, 'CG15987300922590', 2),
(93, 37, '10.00', 1598736983, 0, NULL, 'CG15987369831709', 1),
(94, 36, '10.00', 1598742679, 0, NULL, 'CG15987426792614', 1),
(95, 47, '1.00', 1598764636, 1, 1598764655, 'CG15987646362546', 2),
(96, 42, '100.00', 1598884066, 0, NULL, 'CG15988840661991', 1),
(97, 48, '10.00', 1598949016, 0, NULL, 'CG15989490167711', 2);

-- --------------------------------------------------------

--
-- 表的结构 `ws_pass`
--

CREATE TABLE IF NOT EXISTS `ws_pass` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `desc` text COMMENT '描述',
  `hour` decimal(10,1) DEFAULT '2.5' COMMENT '挑战时长',
  `beginTimeStr` varchar(20) DEFAULT NULL COMMENT '报名的开始时间 ',
  `endTimeStr` varchar(20) DEFAULT NULL COMMENT '报名的结束时间',
  `beginTime` int(11) DEFAULT NULL COMMENT '报名开始时间 分钟数',
  `endTime` int(11) DEFAULT NULL COMMENT '报名结束时间  分钟数',
  `number` int(11) DEFAULT NULL COMMENT '期数',
  `money` decimal(10,2) DEFAULT NULL COMMENT '报名金额',
  `rewardType` tinyint(1) DEFAULT NULL COMMENT '奖励类型 1-失败金额瓜分百分比 2-固定金额  3-报名百分比',
  `reward` varchar(255) DEFAULT NULL COMMENT '对应rewardType',
  `challenge` int(2) DEFAULT '10' COMMENT '挑战轮数 默认10',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 0-下架 1-活动中',
  `image` varchar(255) DEFAULT NULL COMMENT '闯关图片',
  `background` varchar(255) DEFAULT NULL COMMENT '背景图片',
  `rule` text COMMENT '闯关规则',
  `passEndTime` datetime NOT NULL,
  `isEnd` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否结算 0-未结算 1-已结算 默认0'
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='闯关活动';

--
-- 转存表中的数据 `ws_pass`
--

INSERT INTO `ws_pass` (`id`, `name`, `desc`, `hour`, `beginTimeStr`, `endTimeStr`, `beginTime`, `endTime`, `number`, `money`, `rewardType`, `reward`, `challenge`, `createTime`, `status`, `image`, `background`, `rule`, `passEndTime`, `isEnd`) VALUES
(1, '发布', '菜市场', '2.5', '00:00', '05:00', 0, 300, 1, '12.00', 1, '11', 10, 1596786586, 0, '/uploads/category/20200807/06ff6b5750980d868064b0764ef7251b.jpg', '/uploads/category/20200807/5849e79ee5d0014527206ebbfbfe9a77.jpg', '大V存储', '2020-08-29 00:00:00', 0),
(3, '发布', 'ss', '2.5', '04:00', '22:50', 240, 1370, 4, '1.00', 2, '1', 10, 1597480024, 0, '/uploads/category/20200815/f3e4192218f16cf43bd2f49373289731.jpg', '/uploads/category/20200815/db1eba43fa2702771e420b88f91a1739.jpg', '', '2020-08-29 00:00:00', 0),
(4, '闯关挑战', '闯关挑战', '2.5', '05:00', '19:00', 300, 1140, 2, '12.00', 2, '1', 10, 1597480467, 0, '/uploads/category/20200815/ae7252b9788d76c8a43a19d4bef8689d.jpg', '/uploads/category/20200815/746916c5fbe47abc1c531e4b7bfecd93.jpg', '闯关挑战', '2020-08-29 00:00:00', 0),
(5, '闯关挑战2', '闯关挑战2', '2.5', '05:00', '23:50', 300, 1430, 5, '5.00', 2, '0.1', 10, 1597763160, 0, '/uploads/category/20200818/8828f8c0fda5769f7bb991bc9d0c8a09.jpg', '/uploads/category/20200818/1b2c0790b802235acdb69cd8780484b1.jpg', '闯关活动', '2020-08-29 00:00:00', 0),
(6, '一次闯关', '只有一次签到机会', '0.1', '00:00', '23:50', 0, 1430, 6, '2.00', 2, '0.1', 1, 1598535788, 0, '/uploads/category/20200827/78e98bdb1c6b03d2fb1a5abc20227404.jpg', '/uploads/category/20200827/59b18c8336f24675fcf93197244d9e02.jpg', '测试  一轮签到机会', '2020-08-28 05:00:00', 0),
(7, '斑马挑战测试', '快来赚钱啦', '2.5', '00:00', '23:00', 0, 1380, 20, '10.00', 1, '10', 10, 1598636760, 0, '/uploads/category/20200829/9be019cdb24554c451330f653bc300fb.png', '/uploads/category/20200829/d0665478b7be94d1de2ef6562917f336.png', '', '2020-08-30 00:00:00', 0),
(8, '斑马挑战测试9999', '斑马挑战测试9999斑马挑战测试9999斑马挑战测试9999', '2.5', '00:00', '15:00', 0, 900, 21, '1.00', 3, '50', 10, 1598643855, 0, '/uploads/category/20200829/aa8f710112e275c7e7388d3a6b23ffc7.png', '/uploads/category/20200829/8de3b059412fd0811e50a982a25abb08.png', 'vhhhhhhhhhhhjvhjvhjkj', '2020-08-30 00:00:00', 0),
(9, '斑马挑战测试2222222', '斑马挑战测试2222222斑马挑战测试2222222斑马挑战测试2222222', '3.0', '00:00', '23:00', 0, 1380, 22, '10.00', 1, '10', 10, 1598644138, 0, '/uploads/category/20200829/79fb837c8b33fb4164f798dcb17bc32b.png', '/uploads/category/20200829/64f2f20f8e4bd620086b07ef184ec35c.png', '', '2020-08-31 00:00:00', 0),
(10, '测试1', '测试', '2.5', '05:00', '22:00', 300, 1320, 9, '12.00', 2, '1', 10, 1598678198, 0, '/uploads/category/20200829/0b3186744d83463249da1721115e7603.jpg', '/uploads/category/20200829/262b856cc3f9019620cc3f35e711c648.jpg', '哒哒哒哒哒哒', '2020-08-30 00:00:00', 0),
(11, '斑马挑战测试', '2sw22xexew', '3.0', '00:00', '09:00', 0, 540, 25, '10.00', 2, '10', 5, 1598806720, 0, '/uploads/category/20200831/318db60c98ef7f2b0793f196d8706cb1.png', '/uploads/category/20200831/c5bdc0f05e23ff8a5d0a524f035aca44.png', 'wexwcedsxsxdcc', '2020-09-01 00:00:00', 0),
(12, '斑马挑战测试3333333', 'dfgrbthe', '3.0', '00:00', '20:50', 0, 1250, 26, '10.00', 2, '10', 5, 1598809349, 0, '/uploads/category/20200831/1711a0fc8cf6ed194eb73872196849f9.png', '/uploads/category/20200831/2180c6098e2d17da6f4521bd921a42ce.png', 'dfffffffv999999999', '2020-09-01 00:00:00', 0),
(13, '按摩19522', '按摩1', '2.5', '23:00', '23:50', 1380, 1430, 59, '10.00', 2, '1', 3, 1598888531, 0, '/uploads/category/20200831/b6eb419c7bfd56c258e7c9a000030990.jpg', '/uploads/category/20200831/e1027749a7c820715f53ea4d1ea1cb48.jpg', '1', '2020-09-01 00:00:00', 0),
(14, '斑马挑战测试1111', 'dcdccc', '3.0', '00:00', '22:00', 0, 1320, 28, '10.00', 1, '10', 2, 1598891208, 1, '/uploads/category/20200901/1d86ba01ac4ac618f2542e390f83bc99.png', '/uploads/category/20200901/07265af3c8d7ed61f68a284f745205b4.png', 'sfefrefrrg', '2020-09-02 00:00:00', 0),
(15, 'banma123', 'dcdvdvd', '3.0', '12:00', '12:20', 720, 740, 29, '10.00', 1, '10', 5, 1598932746, 1, '/uploads/category/20200901/e8be049b3859af919b2d0c80e4a636bb.png', '/uploads/category/20200901/5d45f473648274691842603c8232888a.png', '7igbuinjk', '2020-09-02 00:00:00', 0),
(16, '闯关挑战（6分钟两轮）', '闯关挑战', '0.1', '00:00', '23:00', 0, 1380, 12, '10.00', 3, '0.1', 2, 1598952016, 1, '/uploads/category/20200901/4032ec8d7885e6d0763f022701c8b42f.jpg', '/uploads/category/20200901/ed56d9be4b41377da6899f0255607883.jpg', '六分钟挑战两次签到', '2020-09-02 08:00:00', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ws_pass_join`
--

CREATE TABLE IF NOT EXISTS `ws_pass_join` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `passId` int(11) DEFAULT NULL,
  `joinTime` datetime DEFAULT NULL COMMENT '报名时间',
  `joinMoney` decimal(10,2) DEFAULT NULL COMMENT '报名金额',
  `status` tinyint(1) DEFAULT '0' COMMENT '参加状态  0-参与中 1-已完成 2-未完成',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `endTime` datetime DEFAULT NULL COMMENT '结束时间',
  `isReward` tinyint(1) DEFAULT '0' COMMENT '是否发送奖励 0-未发送 1-已发送',
  `signStatus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='用户闯关报名';

--
-- 转存表中的数据 `ws_pass_join`
--

INSERT INTO `ws_pass_join` (`id`, `uid`, `passId`, `joinTime`, `joinMoney`, `status`, `createTime`, `endTime`, `isReward`, `signStatus`) VALUES
(1, 29, 4, '2020-08-15 17:52:40', '12.00', 2, 1597485160, '2020-08-15 20:22:40', 0, 0),
(2, 29, 4, '2020-08-15 17:54:35', '12.00', 1, 1597485275, '2020-08-15 20:24:35', 1, 0),
(3, 29, 4, '2020-08-15 18:13:31', '12.00', 0, 1597486411, '2020-08-15 20:43:31', 0, 0),
(4, 33, 5, '2020-08-24 17:10:45', '5.00', 2, 1598260245, '2020-08-24 19:40:45', 0, 0),
(5, 33, 5, '2020-08-24 20:03:59', '5.00', 2, 1598270639, '2020-08-24 22:33:59', 0, 0),
(6, 33, 3, '2020-08-24 20:13:00', '1.00', 2, 1598271180, '2020-08-24 22:43:00', 0, 0),
(7, 33, 5, '2020-08-24 21:55:24', '5.00', 2, 1598277324, '2020-08-25 00:25:24', 0, 0),
(8, 33, 5, '2020-08-24 22:08:38', '5.00', 2, 1598278118, '2020-08-25 00:38:38', 0, 0),
(9, 33, 5, '2020-08-24 22:48:28', '5.00', 2, 1598280508, '2020-08-25 01:18:28', 0, 0),
(10, 33, 5, '2020-08-24 23:26:59', '5.00', 2, 1598282819, '2020-08-25 01:56:59', 0, 0),
(11, 36, 5, '2020-08-27 13:57:20', '5.00', 2, 1598507840, '2020-08-27 16:27:20', 0, 0),
(12, 36, 5, '2020-08-27 14:22:43', '5.00', 2, 1598509363, '2020-08-27 16:52:43', 0, 0),
(13, 36, 5, '2020-08-27 14:34:22', '5.00', 2, 1598510062, '2020-08-27 17:04:22', 0, 0),
(14, 36, 5, '2020-08-27 14:46:28', '5.00', 2, 1598510788, '2020-08-27 17:16:28', 0, 0),
(15, 36, 5, '2020-08-27 15:05:01', '5.00', 2, 1598511901, '2020-08-27 17:35:01', 0, 0),
(16, 36, 5, '2020-08-27 16:10:41', '5.00', 2, 1598515841, '2020-08-27 18:40:41', 0, 0),
(17, 36, 4, '2020-08-27 16:36:36', '12.00', 2, 1598517396, '2020-08-27 19:06:36', 0, 0),
(18, 36, 3, '2020-08-27 16:36:54', '1.00', 2, 1598517414, '2020-08-27 19:06:54', 0, 0),
(19, 36, 5, '2020-08-27 19:50:32', '5.00', 2, 1598529032, '2020-08-27 22:20:32', 0, 0),
(20, 43, 6, '2020-08-27 21:47:27', '2.00', 2, 1598536047, '2020-08-27 21:53:27', 0, 0),
(21, 33, 6, '2020-08-28 04:29:14', '6.00', 0, 1598560154, '2020-08-28 04:35:14', 0, 0),
(22, 33, 3, '2020-08-28 04:30:47', '3.00', 2, 1598560247, '2020-08-28 07:00:47', 0, 0),
(23, 36, 5, '2020-08-28 10:38:43', '5.00', 2, 1598582323, '2020-08-28 13:08:43', 0, 0),
(24, 36, 5, '2020-08-28 11:03:03', '7.00', 2, 1598583783, '2020-08-28 13:33:03', 0, 0),
(25, 36, 5, '2020-08-28 12:02:23', '7.00', 2, 1598587343, '2020-08-28 14:32:23', 0, 0),
(26, 36, 5, '2020-08-28 15:37:24', '7.00', 0, 1598600244, '2020-08-28 18:07:24', 0, 0),
(27, 33, 7, '2020-08-29 02:48:17', '20.00', 2, 1598640497, '2020-08-29 05:18:17', 0, 0),
(28, 33, 7, '2020-08-29 02:52:14', '10.00', 2, 1598640734, '2020-08-29 05:22:14', 0, 0),
(29, 33, 7, '2020-08-29 03:02:20', '10.00', 2, 1598641340, '2020-08-29 05:32:20', 0, 0),
(30, 33, 7, '2020-08-29 03:03:45', '10.00', 2, 1598641425, '2020-08-29 05:33:45', 0, 0),
(31, 33, 7, '2020-08-29 03:10:56', '10.00', 2, 1598641856, '2020-08-29 05:40:56', 0, 0),
(32, 33, 7, '2020-08-29 03:21:18', '10.00', 2, 1598642478, '2020-08-29 05:51:18', 0, 0),
(33, 33, 7, '2020-08-29 03:32:45', '10.00', 2, 1598643165, '2020-08-29 06:02:45', 0, 0),
(34, 33, 7, '2020-08-29 03:42:48', '10.00', 2, 1598643768, '2020-08-29 06:12:48', 0, 0),
(35, 33, 8, '2020-08-29 03:44:26', '1.00', 2, 1598643866, '2020-08-29 06:14:26', 0, 0),
(36, 33, 9, '2020-08-29 03:49:45', '10.00', 2, 1598644185, '2020-08-29 06:49:45', 0, 0),
(37, 36, 7, '2020-08-29 05:50:12', '30.00', 2, 1598651412, '2020-08-29 08:20:12', 0, 0),
(38, 36, 7, '2020-08-29 06:03:51', '30.00', 2, 1598652231, '2020-08-29 08:33:51', 0, 0),
(39, 36, 9, '2020-08-29 08:54:16', '10.00', 2, 1598662456, '2020-08-29 11:54:16', 0, 0),
(40, 36, 9, '2020-08-29 08:58:23', '10.00', 2, 1598662703, '2020-08-29 11:58:23', 0, 0),
(41, 36, 8, '2020-08-29 08:58:56', '20.00', 2, 1598662736, '2020-08-29 11:28:56', 0, 0),
(42, 36, 7, '2020-08-29 08:59:09', '30.00', 2, 1598662749, '2020-08-29 11:29:09', 0, 0),
(43, 36, 8, '2020-08-29 09:13:03', '20.00', 2, 1598663583, '2020-08-29 11:43:03', 0, 0),
(44, 35, 9, '2020-08-29 13:10:00', '10.00', 2, 1598677800, '2020-08-29 16:10:00', 0, 0),
(45, 35, 9, '2020-08-29 13:46:33', '10.00', 2, 1598679993, '2020-08-29 16:46:33', 0, 0),
(46, 36, 9, '2020-08-29 15:38:56', '10.00', 2, 1598686736, '2020-08-29 18:38:56', 0, 0),
(47, 37, 9, '2020-08-29 15:39:47', '10.00', 2, 1598686787, '2020-08-29 18:39:47', 0, 0),
(48, 37, 7, '2020-08-29 15:41:36', '30.00', 0, 1598686896, '2020-08-29 18:11:36', 0, 0),
(49, 33, 9, '2020-08-30 03:08:14', '10.00', 2, 1598728094, '2020-08-30 06:08:14', 0, 0),
(50, 37, 9, '2020-08-30 05:33:43', '10.00', 2, 1598736823, '2020-08-30 08:33:43', 0, 0),
(51, 37, 9, '2020-08-30 06:22:54', '10.00', 2, 1598739774, '2020-08-30 09:22:54', 0, 0),
(52, 37, 9, '2020-08-30 06:34:01', '10.00', 2, 1598740441, '2020-08-30 09:34:01', 0, 0),
(53, 37, 9, '2020-08-30 07:14:52', '10.00', 2, 1598742892, '2020-08-30 10:14:52', 0, 0),
(54, 37, 9, '2020-08-30 08:03:26', '10.00', 2, 1598745806, '2020-08-30 11:03:26', 0, 0),
(55, 36, 9, '2020-08-30 11:33:33', '10.00', 2, 1598758413, '2020-08-30 14:33:33', 0, 0),
(56, 37, 9, '2020-08-30 11:34:49', '10.00', 2, 1598758489, '2020-08-30 14:34:49', 0, 0),
(57, 36, 9, '2020-08-30 12:09:33', '10.00', 2, 1598760573, '2020-08-30 15:09:33', 0, 0),
(58, 47, 9, '2020-08-30 13:31:32', '10.00', 2, 1598765492, '2020-08-30 16:31:32', 0, 0),
(59, 36, 9, '2020-08-30 14:46:06', '10.00', 2, 1598769966, '2020-08-30 17:46:06', 0, 0),
(60, 47, 9, '2020-08-30 15:41:11', '10.00', 2, 1598773271, '2020-08-30 18:41:11', 0, 0),
(61, 33, 9, '2020-08-30 16:54:20', '10.00', 2, 1598777660, '2020-08-30 19:54:20', 0, 0),
(62, 33, 9, '2020-08-30 16:58:52', '10.00', 0, 1598777932, '2020-08-30 19:58:52', 0, 0),
(63, 36, 9, '2020-08-30 18:52:58', '10.00', 2, 1598784778, '2020-08-30 21:52:58', 0, 0),
(64, 47, 9, '2020-08-30 20:52:30', '10.00', 0, 1598791950, '2020-08-30 23:52:30', 0, 0),
(65, 37, 9, '2020-08-30 20:52:54', '10.00', 0, 1598791974, '2020-08-30 23:52:54', 0, 0),
(66, 36, 9, '2020-08-30 20:54:44', '10.00', 2, 1598792084, '2020-08-30 23:54:44', 0, 0),
(67, 36, 9, '2020-08-30 21:06:45', '10.00', 0, 1598792805, '2020-08-31 00:06:45', 0, 0),
(68, 33, 12, '2020-08-31 01:42:51', '1.00', 2, 1598809371, '2020-08-31 04:42:51', 0, 2),
(69, 47, 12, '2020-08-31 02:05:34', '10.00', 2, 1598810734, '2020-08-31 05:05:34', 0, 2),
(70, 36, 12, '2020-08-31 08:15:59', '10.00', 2, 1598832959, '2020-08-31 11:15:59', 0, 0),
(71, 37, 12, '2020-08-31 08:17:22', '10.00', 2, 1598833042, '2020-08-31 11:17:22', 0, 0),
(72, 47, 12, '2020-08-31 11:12:18', '10.00', 2, 1598843538, '2020-08-31 14:12:18', 0, 2),
(73, 33, 12, '2020-08-31 11:49:13', '1.00', 2, 1598845753, '2020-08-31 14:49:13', 0, 2),
(74, 47, 12, '2020-08-31 14:49:47', '1.00', 2, 1598856587, '2020-08-31 17:49:47', 0, 2),
(75, 33, 12, '2020-08-31 15:54:15', '1.00', 2, 1598860455, '2020-08-31 18:54:15', 0, 2),
(76, 47, 13, '2020-08-31 23:43:59', '10.00', 0, 1598888639, '2020-09-01 02:13:59', 0, 2),
(77, 33, 14, '2020-09-01 00:50:39', '10.00', 2, 1598892639, '2020-09-01 03:50:39', 0, 2),
(78, 35, 14, '2020-09-01 01:28:08', '10.00', 2, 1598894888, '2020-09-01 04:28:08', 0, 2),
(79, 35, 14, '2020-09-01 01:33:40', '10.00', 2, 1598895220, '2020-09-01 04:33:40', 0, 2),
(80, 35, 14, '2020-09-01 01:33:47', '10.00', 2, 1598895227, '2020-09-01 04:33:47', 0, 2),
(81, 35, 14, '2020-09-01 01:34:00', '10.00', 2, 1598895240, '2020-09-01 04:34:00', 0, 2),
(82, 35, 14, '2020-09-01 01:34:05', '10.00', 2, 1598895245, '2020-09-01 04:34:05', 0, 2),
(83, 47, 14, '2020-09-01 01:49:34', '10.00', 2, 1598896174, '2020-09-01 04:49:34', 0, 2),
(84, 47, 14, '2020-09-01 01:49:51', '10.00', 2, 1598896191, '2020-09-01 04:49:51', 0, 2),
(85, 47, 14, '2020-09-01 01:49:57', '10.00', 2, 1598896197, '2020-09-01 04:49:57', 0, 2),
(86, 33, 14, '2020-09-01 09:55:27', '10.00', 2, 1598925327, '2020-09-01 12:55:27', 0, 2),
(87, 47, 14, '2020-09-01 09:59:10', '20.00', 2, 1598925550, '2020-09-01 12:59:10', 0, 2),
(88, 33, 14, '2020-09-01 11:27:24', '10.00', 2, 1598930844, '2020-09-01 14:27:24', 0, 2),
(89, 33, 14, '2020-09-01 11:48:19', '10.00', 2, 1598932099, '2020-09-01 14:48:19', 0, 2),
(90, 33, 15, '2020-09-01 12:00:17', '10.00', 2, 1598932817, '2020-09-01 15:00:17', 0, 2),
(91, 48, 14, '2020-09-01 17:18:03', '10.00', 0, 1598951883, '2020-09-01 20:18:03', 0, 2),
(92, 48, 16, '2020-09-01 17:20:28', '8.00', 2, 1598952028, '2020-09-01 17:26:28', 0, 2);

-- --------------------------------------------------------

--
-- 表的结构 `ws_pass_price`
--

CREATE TABLE IF NOT EXISTS `ws_pass_price` (
  `id` int(11) NOT NULL,
  `passId` int(11) DEFAULT NULL COMMENT '闯关活动id',
  `price` decimal(10,2) DEFAULT NULL COMMENT '报名价格',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='打卡价格记录表';

--
-- 转存表中的数据 `ws_pass_price`
--

INSERT INTO `ws_pass_price` (`id`, `passId`, `price`, `createTime`) VALUES
(9, 1, '3.00', NULL),
(10, 1, '12.00', NULL),
(11, 3, '3.00', NULL),
(12, 3, '22.00', NULL),
(13, 4, '4.00', NULL),
(14, 4, '12.00', NULL),
(15, 5, '5.00', NULL),
(16, 5, '7.00', NULL),
(17, 6, '6.00', NULL),
(18, 6, '11.00', NULL),
(19, 7, '10.00', 1598636760),
(20, 7, '20.00', 1598636760),
(21, 7, '30.00', 1598636760),
(22, 8, '1.00', 1598643855),
(23, 8, '10.00', 1598643855),
(24, 8, '20.00', 1598643855),
(25, 9, '10.00', 1598644138),
(26, 10, '12.00', 1598678198),
(27, 10, '24.00', 1598678198),
(28, 7, '10.00', 1598806720),
(29, 12, '10.00', 1598809349),
(30, 12, '1.00', 1598809349),
(31, 13, '10.00', 1598888531),
(32, 14, '10.00', 1598891208),
(33, 14, '20.00', 1598891208),
(34, 15, '10.00', 1598932746),
(35, 16, '10.00', 1598952016),
(36, 16, '8.00', 1598952016);

-- --------------------------------------------------------

--
-- 表的结构 `ws_pass_reward`
--

CREATE TABLE IF NOT EXISTS `ws_pass_reward` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `passId` int(11) DEFAULT NULL COMMENT '打卡活动id',
  `joinId` int(11) DEFAULT NULL COMMENT '报名id',
  `date` char(11) DEFAULT NULL COMMENT '日期',
  `money` decimal(10,2) DEFAULT NULL COMMENT '奖励金额',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='闯关收益记录表';

-- --------------------------------------------------------

--
-- 表的结构 `ws_pass_sign`
--

CREATE TABLE IF NOT EXISTS `ws_pass_sign` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `passId` int(11) DEFAULT NULL COMMENT '活动id',
  `joinId` int(11) DEFAULT NULL COMMENT '报名的id',
  `status` tinyint(1) DEFAULT NULL COMMENT '打卡状态  0-未打卡 1-已打卡',
  `number` int(2) DEFAULT NULL COMMENT '第几轮打卡',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `signTimeBegin` datetime DEFAULT NULL COMMENT '打卡开始时间',
  `signTimeEnd` datetime DEFAULT NULL COMMENT '打卡结束时间',
  `signTime` datetime DEFAULT NULL COMMENT '签到时间'
) ENGINE=MyISAM AUTO_INCREMENT=668 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='用户闯关签到记录';

--
-- 转存表中的数据 `ws_pass_sign`
--

INSERT INTO `ws_pass_sign` (`id`, `uid`, `passId`, `joinId`, `status`, `number`, `createTime`, `signTimeBegin`, `signTimeEnd`, `signTime`) VALUES
(1, 29, 4, 2, 1, 1, 1597485275, '2020-08-15 17:09:35', '2020-08-15 17:12:34', '2020-08-15 17:57:21'),
(2, 29, 4, 2, 1, 2, 1597485275, '2020-08-15 17:15:35', '2020-08-15 17:13:34', '2020-08-15 17:58:01'),
(3, 29, 4, 2, 1, 3, 1597485275, '2020-08-15 17:30:35', '2020-08-15 17:33:34', '2020-08-15 18:10:06'),
(4, 29, 4, 2, 1, 4, 1597485275, '2020-08-15 18:50:35', '2020-08-15 17:53:34', '2020-08-15 18:10:52'),
(5, 29, 4, 2, 1, 5, 1597485275, '2020-08-15 19:03:35', '2020-08-15 17:53:34', '2020-08-15 18:10:55'),
(6, 29, 4, 2, 1, 6, 1597485275, '2020-08-15 19:11:35', '2020-08-15 17:53:34', '2020-08-15 18:10:58'),
(7, 29, 4, 2, 1, 7, 1597485275, '2020-08-15 19:33:35', '2020-08-15 17:53:34', '2020-08-15 18:11:01'),
(8, 29, 4, 2, 1, 8, 1597485275, '2020-08-15 19:40:35', '2020-08-15 17:53:34', '2020-08-15 18:11:04'),
(9, 29, 4, 2, 1, 9, 1597485275, '2020-08-15 19:56:35', '2020-08-15 17:53:34', '2020-08-15 18:11:06'),
(10, 29, 4, 2, 1, 10, 1597485275, '2020-08-15 17:22:35', '2020-08-15 20:25:34', '2020-08-15 18:12:26'),
(11, 29, 4, 3, 0, 1, 1597486411, '2020-08-15 18:18:31', '2020-08-15 18:21:30', NULL),
(12, 29, 4, 3, 0, 2, 1597486411, '2020-08-15 18:43:31', '2020-08-15 18:46:30', NULL),
(13, 29, 4, 3, 0, 3, 1597486411, '2020-08-15 18:45:31', '2020-08-15 18:48:30', NULL),
(14, 29, 4, 3, 0, 4, 1597486411, '2020-08-15 19:13:31', '2020-08-15 19:16:30', NULL),
(15, 29, 4, 3, 0, 5, 1597486411, '2020-08-15 19:26:31', '2020-08-15 19:29:30', NULL),
(16, 29, 4, 3, 0, 6, 1597486411, '2020-08-15 19:41:31', '2020-08-15 19:44:30', NULL),
(17, 29, 4, 3, 0, 7, 1597486411, '2020-08-15 19:46:31', '2020-08-15 19:49:30', NULL),
(18, 29, 4, 3, 0, 8, 1597486411, '2020-08-15 20:01:31', '2020-08-15 20:04:30', NULL),
(19, 29, 4, 3, 0, 9, 1597486411, '2020-08-15 20:18:31', '2020-08-15 20:21:30', NULL),
(20, 29, 4, 3, 0, 10, 1597486411, '2020-08-15 20:29:31', '2020-08-15 20:32:30', NULL),
(21, 33, 5, 4, 0, 1, 1598260245, '2020-08-24 17:19:45', '2020-08-24 17:22:44', NULL),
(22, 33, 5, 4, 0, 2, 1598260245, '2020-08-24 17:37:45', '2020-08-24 17:40:44', NULL),
(23, 33, 5, 4, 0, 3, 1598260245, '2020-08-24 17:52:45', '2020-08-24 17:55:44', NULL),
(24, 33, 5, 4, 0, 4, 1598260245, '2020-08-24 18:09:45', '2020-08-24 18:12:44', NULL),
(25, 33, 5, 4, 0, 5, 1598260245, '2020-08-24 18:13:45', '2020-08-24 18:16:44', NULL),
(26, 33, 5, 4, 0, 6, 1598260245, '2020-08-24 18:30:45', '2020-08-24 18:33:44', NULL),
(27, 33, 5, 4, 0, 7, 1598260245, '2020-08-24 18:42:45', '2020-08-24 18:45:44', NULL),
(28, 33, 5, 4, 0, 8, 1598260245, '2020-08-24 19:02:45', '2020-08-24 19:05:44', NULL),
(29, 33, 5, 4, 0, 9, 1598260245, '2020-08-24 19:16:45', '2020-08-24 19:19:44', NULL),
(30, 33, 5, 4, 0, 10, 1598260245, '2020-08-24 19:32:45', '2020-08-24 19:35:44', NULL),
(31, 33, 5, 5, 0, 1, 1598270639, '2020-08-24 20:13:59', '2020-08-24 20:16:58', NULL),
(32, 33, 5, 5, 0, 2, 1598270639, '2020-08-24 20:32:59', '2020-08-24 20:35:58', NULL),
(33, 33, 5, 5, 0, 3, 1598270639, '2020-08-24 20:43:59', '2020-08-24 20:46:58', NULL),
(34, 33, 5, 5, 0, 4, 1598270639, '2020-08-24 20:52:59', '2020-08-24 20:55:58', NULL),
(35, 33, 5, 5, 0, 5, 1598270639, '2020-08-24 21:06:59', '2020-08-24 21:09:58', NULL),
(36, 33, 5, 5, 0, 6, 1598270639, '2020-08-24 21:30:59', '2020-08-24 21:33:58', NULL),
(37, 33, 5, 5, 0, 7, 1598270639, '2020-08-24 21:42:59', '2020-08-24 21:45:58', NULL),
(38, 33, 5, 5, 0, 8, 1598270639, '2020-08-24 21:54:59', '2020-08-24 21:57:58', NULL),
(39, 33, 5, 5, 0, 9, 1598270639, '2020-08-24 22:05:59', '2020-08-24 22:08:58', NULL),
(40, 33, 5, 5, 0, 10, 1598270639, '2020-08-24 22:25:59', '2020-08-24 22:28:58', NULL),
(41, 33, 3, 6, 0, 1, 1598271180, '2020-08-24 20:27:00', '2020-08-24 20:27:59', NULL),
(42, 33, 3, 6, 0, 2, 1598271180, '2020-08-24 20:32:00', '2020-08-24 20:33:59', NULL),
(43, 33, 3, 6, 0, 3, 1598271180, '2020-08-24 20:55:00', '2020-08-24 20:57:59', NULL),
(44, 33, 3, 6, 0, 4, 1598271180, '2020-08-24 21:06:00', '2020-08-24 21:09:59', NULL),
(45, 33, 3, 6, 0, 5, 1598271180, '2020-08-24 21:25:00', '2020-08-24 21:29:59', NULL),
(46, 33, 3, 6, 0, 6, 1598271180, '2020-08-24 21:41:00', '2020-08-24 21:46:59', NULL),
(47, 33, 3, 6, 0, 7, 1598271180, '2020-08-24 21:57:00', '2020-08-24 22:03:59', NULL),
(48, 33, 3, 6, 0, 8, 1598271180, '2020-08-24 22:02:00', '2020-08-24 22:09:59', NULL),
(49, 33, 3, 6, 0, 9, 1598271180, '2020-08-24 22:25:00', '2020-08-24 22:33:59', NULL),
(50, 33, 3, 6, 0, 10, 1598271180, '2020-08-24 22:40:00', '2020-08-24 22:42:59', NULL),
(51, 33, 5, 7, 0, 1, 1598277324, '2020-08-24 22:05:24', '2020-08-24 22:08:23', NULL),
(52, 33, 5, 7, 0, 2, 1598277324, '2020-08-24 22:17:24', '2020-08-24 22:20:23', NULL),
(53, 33, 5, 7, 0, 3, 1598277324, '2020-08-24 22:38:24', '2020-08-24 22:41:23', NULL),
(54, 33, 5, 7, 0, 4, 1598277324, '2020-08-24 22:41:24', '2020-08-24 22:44:23', NULL),
(55, 33, 5, 7, 0, 5, 1598277324, '2020-08-24 22:58:24', '2020-08-24 23:01:23', NULL),
(56, 33, 5, 7, 0, 6, 1598277324, '2020-08-24 23:23:24', '2020-08-24 23:26:23', NULL),
(57, 33, 5, 7, 0, 7, 1598277324, '2020-08-24 23:35:24', '2020-08-24 23:38:23', NULL),
(58, 33, 5, 7, 0, 8, 1598277324, '2020-08-24 23:48:24', '2020-08-24 23:51:23', NULL),
(59, 33, 5, 7, 0, 9, 1598277324, '2020-08-25 00:01:24', '2020-08-25 00:04:23', NULL),
(60, 33, 5, 7, 0, 10, 1598277324, '2020-08-25 00:17:24', '2020-08-25 00:20:23', NULL),
(61, 33, 5, 8, 0, 1, 1598278118, '2020-08-24 22:14:38', '2020-08-24 22:17:37', NULL),
(62, 33, 5, 8, 0, 2, 1598278118, '2020-08-24 22:26:38', '2020-08-24 22:29:37', NULL),
(63, 33, 5, 8, 0, 3, 1598278118, '2020-08-24 22:50:38', '2020-08-24 22:53:37', NULL),
(64, 33, 5, 8, 0, 4, 1598278118, '2020-08-24 22:54:38', '2020-08-24 22:57:37', NULL),
(65, 33, 5, 8, 0, 5, 1598278118, '2020-08-24 23:20:38', '2020-08-24 23:23:37', NULL),
(66, 33, 5, 8, 0, 6, 1598278118, '2020-08-24 23:32:38', '2020-08-24 23:35:37', NULL),
(67, 33, 5, 8, 0, 7, 1598278118, '2020-08-24 23:45:38', '2020-08-24 23:48:37', NULL),
(68, 33, 5, 8, 0, 8, 1598278118, '2020-08-24 23:59:38', '2020-08-25 00:02:37', NULL),
(69, 33, 5, 8, 0, 9, 1598278118, '2020-08-25 00:09:38', '2020-08-25 00:12:37', NULL),
(70, 33, 5, 8, 0, 10, 1598278118, '2020-08-25 00:24:38', '2020-08-25 00:27:37', NULL),
(71, 33, 5, 9, 0, 1, 1598280508, '2020-08-24 23:02:28', '2020-08-24 23:05:27', NULL),
(72, 33, 5, 9, 0, 2, 1598280508, '2020-08-24 23:10:28', '2020-08-24 23:13:27', NULL),
(73, 33, 5, 9, 0, 3, 1598280508, '2020-08-24 23:32:28', '2020-08-24 23:35:27', NULL),
(74, 33, 5, 9, 0, 4, 1598280508, '2020-08-24 23:46:28', '2020-08-24 23:49:27', NULL),
(75, 33, 5, 9, 0, 5, 1598280508, '2020-08-25 00:03:28', '2020-08-25 00:06:27', NULL),
(76, 33, 5, 9, 0, 6, 1598280508, '2020-08-25 00:16:28', '2020-08-25 00:19:27', NULL),
(77, 33, 5, 9, 0, 7, 1598280508, '2020-08-25 00:25:28', '2020-08-25 00:28:27', NULL),
(78, 33, 5, 9, 0, 8, 1598280508, '2020-08-25 00:41:28', '2020-08-25 00:44:27', NULL),
(79, 33, 5, 9, 0, 9, 1598280508, '2020-08-25 01:03:28', '2020-08-25 01:06:27', NULL),
(80, 33, 5, 9, 0, 10, 1598280508, '2020-08-25 01:18:28', '2020-08-25 01:21:27', NULL),
(81, 33, 5, 10, 1, 1, 1598282819, '2020-08-24 23:28:59', '2020-08-24 23:31:58', '2020-08-24 23:29:01'),
(82, 33, 5, 10, 0, 2, 1598282819, '2020-08-24 23:48:59', '2020-08-24 23:51:58', NULL),
(83, 33, 5, 10, 0, 3, 1598282819, '2020-08-25 00:07:59', '2020-08-25 00:10:58', NULL),
(84, 33, 5, 10, 0, 4, 1598282819, '2020-08-25 00:15:59', '2020-08-25 00:18:58', NULL),
(85, 33, 5, 10, 0, 5, 1598282819, '2020-08-25 00:34:59', '2020-08-25 00:37:58', NULL),
(86, 33, 5, 10, 0, 6, 1598282819, '2020-08-25 00:55:59', '2020-08-25 00:58:58', NULL),
(87, 33, 5, 10, 0, 7, 1598282819, '2020-08-25 00:57:59', '2020-08-25 01:00:58', NULL),
(88, 33, 5, 10, 0, 8, 1598282819, '2020-08-25 01:14:59', '2020-08-25 01:17:58', NULL),
(89, 33, 5, 10, 0, 9, 1598282819, '2020-08-25 01:41:59', '2020-08-25 01:44:58', NULL),
(90, 33, 5, 10, 0, 10, 1598282819, '2020-08-25 01:50:59', '2020-08-25 01:53:58', NULL),
(91, 36, 5, 11, 0, 1, 1598507840, '2020-08-27 14:02:20', '2020-08-27 14:05:19', NULL),
(92, 36, 5, 11, 0, 2, 1598507840, '2020-08-27 14:15:20', '2020-08-27 14:18:19', NULL),
(93, 36, 5, 11, 0, 3, 1598507840, '2020-08-27 14:35:20', '2020-08-27 14:38:19', NULL),
(94, 36, 5, 11, 0, 4, 1598507840, '2020-08-27 14:56:20', '2020-08-27 14:59:19', NULL),
(95, 36, 5, 11, 0, 5, 1598507840, '2020-08-27 15:00:20', '2020-08-27 15:03:19', NULL),
(96, 36, 5, 11, 0, 6, 1598507840, '2020-08-27 15:16:20', '2020-08-27 15:19:19', NULL),
(97, 36, 5, 11, 0, 7, 1598507840, '2020-08-27 15:31:20', '2020-08-27 15:34:19', NULL),
(98, 36, 5, 11, 0, 8, 1598507840, '2020-08-27 15:51:20', '2020-08-27 15:54:19', NULL),
(99, 36, 5, 11, 0, 9, 1598507840, '2020-08-27 16:10:20', '2020-08-27 16:13:19', NULL),
(100, 36, 5, 11, 0, 10, 1598507840, '2020-08-27 16:18:20', '2020-08-27 16:21:19', NULL),
(101, 36, 5, 12, 0, 1, 1598509363, '2020-08-27 14:29:43', '2020-08-27 14:32:42', NULL),
(102, 36, 5, 12, 0, 2, 1598509363, '2020-08-27 14:45:43', '2020-08-27 14:48:42', NULL),
(103, 36, 5, 12, 0, 3, 1598509363, '2020-08-27 14:57:43', '2020-08-27 15:00:42', NULL),
(104, 36, 5, 12, 0, 4, 1598509363, '2020-08-27 15:16:43', '2020-08-27 15:19:42', NULL),
(105, 36, 5, 12, 0, 5, 1598509363, '2020-08-27 15:25:43', '2020-08-27 15:28:42', NULL),
(106, 36, 5, 12, 0, 6, 1598509363, '2020-08-27 15:47:43', '2020-08-27 15:50:42', NULL),
(107, 36, 5, 12, 0, 7, 1598509363, '2020-08-27 15:58:43', '2020-08-27 16:01:42', NULL),
(108, 36, 5, 12, 0, 8, 1598509363, '2020-08-27 16:21:43', '2020-08-27 16:24:42', NULL),
(109, 36, 5, 12, 0, 9, 1598509363, '2020-08-27 16:36:43', '2020-08-27 16:39:42', NULL),
(110, 36, 5, 12, 0, 10, 1598509363, '2020-08-27 16:43:43', '2020-08-27 16:46:42', NULL),
(111, 36, 5, 13, 0, 1, 1598510062, '2020-08-27 14:43:22', '2020-08-27 14:46:21', NULL),
(112, 36, 5, 13, 0, 2, 1598510062, '2020-08-27 15:01:22', '2020-08-27 15:04:21', NULL),
(113, 36, 5, 13, 0, 3, 1598510062, '2020-08-27 15:19:22', '2020-08-27 15:22:21', NULL),
(114, 36, 5, 13, 0, 4, 1598510062, '2020-08-27 15:25:22', '2020-08-27 15:28:21', NULL),
(115, 36, 5, 13, 0, 5, 1598510062, '2020-08-27 15:38:22', '2020-08-27 15:41:21', NULL),
(116, 36, 5, 13, 0, 6, 1598510062, '2020-08-27 16:01:22', '2020-08-27 16:04:21', NULL),
(117, 36, 5, 13, 0, 7, 1598510062, '2020-08-27 16:09:22', '2020-08-27 16:12:21', NULL),
(118, 36, 5, 13, 0, 8, 1598510062, '2020-08-27 16:28:22', '2020-08-27 16:31:21', NULL),
(119, 36, 5, 13, 0, 9, 1598510062, '2020-08-27 16:37:22', '2020-08-27 16:40:21', NULL),
(120, 36, 5, 13, 0, 10, 1598510062, '2020-08-27 17:00:22', '2020-08-27 17:03:21', NULL),
(121, 36, 5, 14, 0, 1, 1598510788, '2020-08-27 15:01:28', '2020-08-27 15:04:27', NULL),
(122, 36, 5, 14, 0, 2, 1598510788, '2020-08-27 15:06:28', '2020-08-27 15:09:27', NULL),
(123, 36, 5, 14, 0, 3, 1598510788, '2020-08-27 15:30:28', '2020-08-27 15:33:27', NULL),
(124, 36, 5, 14, 0, 4, 1598510788, '2020-08-27 15:40:28', '2020-08-27 15:43:27', NULL),
(125, 36, 5, 14, 0, 5, 1598510788, '2020-08-27 15:55:28', '2020-08-27 15:58:27', NULL),
(126, 36, 5, 14, 0, 6, 1598510788, '2020-08-27 16:05:28', '2020-08-27 16:08:27', NULL),
(127, 36, 5, 14, 0, 7, 1598510788, '2020-08-27 16:25:28', '2020-08-27 16:28:27', NULL),
(128, 36, 5, 14, 0, 8, 1598510788, '2020-08-27 16:36:28', '2020-08-27 16:39:27', NULL),
(129, 36, 5, 14, 0, 9, 1598510788, '2020-08-27 16:49:28', '2020-08-27 16:52:27', NULL),
(130, 36, 5, 14, 0, 10, 1598510788, '2020-08-27 17:13:28', '2020-08-27 17:16:27', NULL),
(131, 36, 5, 15, 0, 1, 1598511901, '2020-08-27 15:11:01', '2020-08-27 15:14:00', NULL),
(132, 36, 5, 15, 0, 2, 1598511901, '2020-08-27 15:21:01', '2020-08-27 15:24:00', NULL),
(133, 36, 5, 15, 0, 3, 1598511901, '2020-08-27 15:37:01', '2020-08-27 15:40:00', NULL),
(134, 36, 5, 15, 0, 4, 1598511901, '2020-08-27 15:56:01', '2020-08-27 15:59:00', NULL),
(135, 36, 5, 15, 0, 5, 1598511901, '2020-08-27 16:10:01', '2020-08-27 16:13:00', NULL),
(136, 36, 5, 15, 0, 6, 1598511901, '2020-08-27 16:33:01', '2020-08-27 16:36:00', NULL),
(137, 36, 5, 15, 0, 7, 1598511901, '2020-08-27 16:49:01', '2020-08-27 16:52:00', NULL),
(138, 36, 5, 15, 0, 8, 1598511901, '2020-08-27 16:57:01', '2020-08-27 17:00:00', NULL),
(139, 36, 5, 15, 0, 9, 1598511901, '2020-08-27 17:17:01', '2020-08-27 17:20:00', NULL),
(140, 36, 5, 15, 0, 10, 1598511901, '2020-08-27 17:25:01', '2020-08-27 17:28:00', NULL),
(141, 36, 5, 16, 0, 1, 1598515841, '2020-08-27 16:16:41', '2020-08-27 16:19:40', NULL),
(142, 36, 5, 16, 0, 2, 1598515841, '2020-08-27 16:35:41', '2020-08-27 16:38:40', NULL),
(143, 36, 5, 16, 0, 3, 1598515841, '2020-08-27 16:44:41', '2020-08-27 16:47:40', NULL),
(144, 36, 5, 16, 0, 4, 1598515841, '2020-08-27 17:08:41', '2020-08-27 17:11:40', NULL),
(145, 36, 5, 16, 0, 5, 1598515841, '2020-08-27 17:14:41', '2020-08-27 17:17:40', NULL),
(146, 36, 5, 16, 0, 6, 1598515841, '2020-08-27 17:37:41', '2020-08-27 17:40:40', NULL),
(147, 36, 5, 16, 0, 7, 1598515841, '2020-08-27 17:49:41', '2020-08-27 17:52:40', NULL),
(148, 36, 5, 16, 0, 8, 1598515841, '2020-08-27 18:04:41', '2020-08-27 18:07:40', NULL),
(149, 36, 5, 16, 0, 9, 1598515841, '2020-08-27 18:21:41', '2020-08-27 18:24:40', NULL),
(150, 36, 5, 16, 0, 10, 1598515841, '2020-08-27 18:31:41', '2020-08-27 18:34:40', NULL),
(151, 36, 4, 17, 0, 1, 1598517396, '2020-08-27 16:41:36', '2020-08-27 16:44:35', NULL),
(152, 36, 4, 17, 0, 2, 1598517396, '2020-08-27 17:00:36', '2020-08-27 17:03:35', NULL),
(153, 36, 4, 17, 0, 3, 1598517396, '2020-08-27 17:15:36', '2020-08-27 17:18:35', NULL),
(154, 36, 4, 17, 0, 4, 1598517396, '2020-08-27 17:32:36', '2020-08-27 17:35:35', NULL),
(155, 36, 4, 17, 0, 5, 1598517396, '2020-08-27 17:45:36', '2020-08-27 17:48:35', NULL),
(156, 36, 4, 17, 0, 6, 1598517396, '2020-08-27 17:53:36', '2020-08-27 17:56:35', NULL),
(157, 36, 4, 17, 0, 7, 1598517396, '2020-08-27 18:20:36', '2020-08-27 18:23:35', NULL),
(158, 36, 4, 17, 0, 8, 1598517396, '2020-08-27 18:23:36', '2020-08-27 18:26:35', NULL),
(159, 36, 4, 17, 0, 9, 1598517396, '2020-08-27 18:50:36', '2020-08-27 18:53:35', NULL),
(160, 36, 4, 17, 0, 10, 1598517396, '2020-08-27 19:05:36', '2020-08-27 19:08:35', NULL),
(161, 36, 3, 18, 0, 1, 1598517414, '2020-08-27 16:48:54', '2020-08-27 16:49:53', NULL),
(162, 36, 3, 18, 0, 2, 1598517414, '2020-08-27 16:54:54', '2020-08-27 16:56:53', NULL),
(163, 36, 3, 18, 0, 3, 1598517414, '2020-08-27 17:20:54', '2020-08-27 17:23:53', NULL),
(164, 36, 3, 18, 0, 4, 1598517414, '2020-08-27 17:23:54', '2020-08-27 17:27:53', NULL),
(165, 36, 3, 18, 0, 5, 1598517414, '2020-08-27 17:42:54', '2020-08-27 17:47:53', NULL),
(166, 36, 3, 18, 0, 6, 1598517414, '2020-08-27 17:59:54', '2020-08-27 18:05:53', NULL),
(167, 36, 3, 18, 0, 7, 1598517414, '2020-08-27 18:10:54', '2020-08-27 18:17:53', NULL),
(168, 36, 3, 18, 0, 8, 1598517414, '2020-08-27 18:32:54', '2020-08-27 18:40:53', NULL),
(169, 36, 3, 18, 0, 9, 1598517414, '2020-08-27 18:44:54', '2020-08-27 18:53:53', NULL),
(170, 36, 3, 18, 0, 10, 1598517414, '2020-08-27 18:52:54', '2020-08-27 18:55:53', NULL),
(171, 36, 5, 19, 0, 1, 1598529032, '2020-08-27 19:55:32', '2020-08-27 19:58:31', NULL),
(172, 36, 5, 19, 0, 2, 1598529032, '2020-08-27 20:20:32', '2020-08-27 20:23:31', NULL),
(173, 36, 5, 19, 0, 3, 1598529032, '2020-08-27 20:27:32', '2020-08-27 20:30:31', NULL),
(174, 36, 5, 19, 0, 4, 1598529032, '2020-08-27 20:38:32', '2020-08-27 20:41:31', NULL),
(175, 36, 5, 19, 0, 5, 1598529032, '2020-08-27 20:53:32', '2020-08-27 20:56:31', NULL),
(176, 36, 5, 19, 0, 6, 1598529032, '2020-08-27 21:15:32', '2020-08-27 21:18:31', NULL),
(177, 36, 5, 19, 0, 7, 1598529032, '2020-08-27 21:24:32', '2020-08-27 21:27:31', NULL),
(178, 36, 5, 19, 0, 8, 1598529032, '2020-08-27 21:38:32', '2020-08-27 21:41:31', NULL),
(179, 36, 5, 19, 0, 9, 1598529032, '2020-08-27 21:52:32', '2020-08-27 21:55:31', NULL),
(180, 36, 5, 19, 0, 10, 1598529032, '2020-08-27 22:15:32', '2020-08-27 22:18:31', NULL),
(181, 43, 6, 20, 0, 1, 1598536047, '2020-08-27 21:53:27', '2020-08-27 21:55:26', NULL),
(182, 33, 6, 21, 0, 1, 1598560154, '2020-08-28 04:31:14', '2020-08-28 04:33:13', NULL),
(183, 33, 3, 22, 1, 1, 1598560247, '2020-08-28 04:34:47', '2020-08-28 04:35:46', '2020-08-28 04:34:49'),
(184, 33, 3, 22, 0, 2, 1598560247, '2020-08-28 04:47:47', '2020-08-28 04:49:46', NULL),
(185, 33, 3, 22, 0, 3, 1598560247, '2020-08-28 05:15:47', '2020-08-28 05:18:46', NULL),
(186, 33, 3, 22, 0, 4, 1598560247, '2020-08-28 05:26:47', '2020-08-28 05:30:46', NULL),
(187, 33, 3, 22, 0, 5, 1598560247, '2020-08-28 05:35:47', '2020-08-28 05:40:46', NULL),
(188, 33, 3, 22, 0, 6, 1598560247, '2020-08-28 05:50:47', '2020-08-28 05:56:46', NULL),
(189, 33, 3, 22, 0, 7, 1598560247, '2020-08-28 06:10:47', '2020-08-28 06:17:46', NULL),
(190, 33, 3, 22, 0, 8, 1598560247, '2020-08-28 06:16:47', '2020-08-28 06:24:46', NULL),
(191, 33, 3, 22, 0, 9, 1598560247, '2020-08-28 06:43:47', '2020-08-28 06:52:46', NULL),
(192, 33, 3, 22, 0, 10, 1598560247, '2020-08-28 06:59:47', '2020-08-28 07:02:46', NULL),
(193, 36, 5, 23, 1, 1, 1598582323, '2020-08-28 10:39:43', '2020-08-28 10:42:42', '2020-08-28 10:39:45'),
(194, 36, 5, 23, 0, 2, 1598582323, '2020-08-28 10:57:43', '2020-08-28 11:00:42', NULL),
(195, 36, 5, 23, 0, 3, 1598582323, '2020-08-28 11:14:43', '2020-08-28 11:17:42', NULL),
(196, 36, 5, 23, 0, 4, 1598582323, '2020-08-28 11:37:43', '2020-08-28 11:40:42', NULL),
(197, 36, 5, 23, 0, 5, 1598582323, '2020-08-28 11:49:43', '2020-08-28 11:52:42', NULL),
(198, 36, 5, 23, 0, 6, 1598582323, '2020-08-28 12:02:43', '2020-08-28 12:05:42', NULL),
(199, 36, 5, 23, 0, 7, 1598582323, '2020-08-28 12:11:43', '2020-08-28 12:14:42', NULL),
(200, 36, 5, 23, 0, 8, 1598582323, '2020-08-28 12:25:43', '2020-08-28 12:28:42', NULL),
(201, 36, 5, 23, 0, 9, 1598582323, '2020-08-28 12:50:43', '2020-08-28 12:53:42', NULL),
(202, 36, 5, 23, 0, 10, 1598582323, '2020-08-28 12:54:43', '2020-08-28 12:57:42', NULL),
(203, 36, 5, 24, 1, 1, 1598583783, '2020-08-28 11:17:03', '2020-08-28 11:20:02', '2020-08-28 11:17:06'),
(204, 36, 5, 24, 1, 2, 1598583783, '2020-08-28 11:27:03', '2020-08-28 11:30:02', '2020-08-28 11:27:06'),
(205, 36, 5, 24, 1, 3, 1598583783, '2020-08-28 11:44:03', '2020-08-28 11:47:02', '2020-08-28 11:44:17'),
(206, 36, 5, 24, 0, 4, 1598583783, '2020-08-28 11:57:03', '2020-08-28 12:00:02', NULL),
(207, 36, 5, 24, 0, 5, 1598583783, '2020-08-28 12:11:03', '2020-08-28 12:14:02', NULL),
(208, 36, 5, 24, 0, 6, 1598583783, '2020-08-28 12:33:03', '2020-08-28 12:36:02', NULL),
(209, 36, 5, 24, 0, 7, 1598583783, '2020-08-28 12:45:03', '2020-08-28 12:48:02', NULL),
(210, 36, 5, 24, 0, 8, 1598583783, '2020-08-28 12:57:03', '2020-08-28 13:00:02', NULL),
(211, 36, 5, 24, 0, 9, 1598583783, '2020-08-28 13:17:03', '2020-08-28 13:20:02', NULL),
(212, 36, 5, 24, 0, 10, 1598583783, '2020-08-28 13:33:03', '2020-08-28 13:36:02', NULL),
(213, 36, 5, 25, 1, 1, 1598587343, '2020-08-28 12:13:23', '2020-08-28 12:16:22', '2020-08-28 12:13:24'),
(214, 36, 5, 25, 0, 2, 1598587343, '2020-08-28 12:20:23', '2020-08-28 12:23:22', NULL),
(215, 36, 5, 25, 0, 3, 1598587343, '2020-08-28 12:38:23', '2020-08-28 12:41:22', NULL),
(216, 36, 5, 25, 0, 4, 1598587343, '2020-08-28 12:53:23', '2020-08-28 12:56:22', NULL),
(217, 36, 5, 25, 0, 5, 1598587343, '2020-08-28 13:05:23', '2020-08-28 13:08:22', NULL),
(218, 36, 5, 25, 0, 6, 1598587343, '2020-08-28 13:31:23', '2020-08-28 13:34:22', NULL),
(219, 36, 5, 25, 0, 7, 1598587343, '2020-08-28 13:39:23', '2020-08-28 13:42:22', NULL),
(220, 36, 5, 25, 0, 8, 1598587343, '2020-08-28 14:02:23', '2020-08-28 14:05:22', NULL),
(221, 36, 5, 25, 0, 9, 1598587343, '2020-08-28 14:04:23', '2020-08-28 14:07:22', NULL),
(222, 36, 5, 25, 0, 10, 1598587343, '2020-08-28 14:26:23', '2020-08-28 14:29:22', NULL),
(223, 36, 5, 26, 0, 1, 1598600244, '2020-08-28 15:48:24', '2020-08-28 15:51:23', NULL),
(224, 36, 5, 26, 0, 2, 1598600244, '2020-08-28 15:55:24', '2020-08-28 15:58:23', NULL),
(225, 36, 5, 26, 0, 3, 1598600244, '2020-08-28 16:16:24', '2020-08-28 16:19:23', NULL),
(226, 36, 5, 26, 0, 4, 1598600244, '2020-08-28 16:25:24', '2020-08-28 16:28:23', NULL),
(227, 36, 5, 26, 0, 5, 1598600244, '2020-08-28 16:45:24', '2020-08-28 16:48:23', NULL),
(228, 36, 5, 26, 0, 6, 1598600244, '2020-08-28 16:53:24', '2020-08-28 16:56:23', NULL),
(229, 36, 5, 26, 0, 7, 1598600244, '2020-08-28 17:16:24', '2020-08-28 17:19:23', NULL),
(230, 36, 5, 26, 0, 8, 1598600244, '2020-08-28 17:34:24', '2020-08-28 17:37:23', NULL),
(231, 36, 5, 26, 0, 9, 1598600244, '2020-08-28 17:41:24', '2020-08-28 17:44:23', NULL),
(232, 36, 5, 26, 0, 10, 1598600244, '2020-08-28 18:04:24', '2020-08-28 18:07:23', NULL),
(233, 33, 7, 27, 0, 1, 1598640497, '2020-08-29 02:50:17', '2020-08-29 02:50:16', NULL),
(234, 33, 7, 27, 0, 2, 1598640497, '2020-08-29 03:12:17', '2020-08-29 03:12:16', NULL),
(235, 33, 7, 27, 0, 3, 1598640497, '2020-08-29 03:30:17', '2020-08-29 03:30:16', NULL),
(236, 33, 7, 27, 0, 4, 1598640497, '2020-08-29 03:37:17', '2020-08-29 03:37:16', NULL),
(237, 33, 7, 27, 0, 5, 1598640497, '2020-08-29 04:03:17', '2020-08-29 04:03:16', NULL),
(238, 33, 7, 27, 0, 6, 1598640497, '2020-08-29 04:05:17', '2020-08-29 04:05:16', NULL),
(239, 33, 7, 27, 0, 7, 1598640497, '2020-08-29 04:28:17', '2020-08-29 04:28:16', NULL),
(240, 33, 7, 27, 0, 8, 1598640497, '2020-08-29 04:40:17', '2020-08-29 04:40:16', NULL),
(241, 33, 7, 27, 0, 9, 1598640497, '2020-08-29 04:58:17', '2020-08-29 04:58:16', NULL),
(242, 33, 7, 27, 0, 10, 1598640497, '2020-08-29 05:09:17', '2020-08-29 05:09:16', NULL),
(243, 33, 7, 28, 0, 1, 1598640734, '2020-08-29 03:00:14', '2020-08-29 03:00:13', NULL),
(244, 33, 7, 28, 0, 2, 1598640734, '2020-08-29 03:20:14', '2020-08-29 03:20:13', NULL),
(245, 33, 7, 28, 0, 3, 1598640734, '2020-08-29 03:23:14', '2020-08-29 03:23:13', NULL),
(246, 33, 7, 28, 0, 4, 1598640734, '2020-08-29 03:48:14', '2020-08-29 03:48:13', NULL),
(247, 33, 7, 28, 0, 5, 1598640734, '2020-08-29 03:57:14', '2020-08-29 03:57:13', NULL),
(248, 33, 7, 28, 0, 6, 1598640734, '2020-08-29 04:22:14', '2020-08-29 04:22:13', NULL),
(249, 33, 7, 28, 0, 7, 1598640734, '2020-08-29 04:28:14', '2020-08-29 04:28:13', NULL),
(250, 33, 7, 28, 0, 8, 1598640734, '2020-08-29 04:39:14', '2020-08-29 04:39:13', NULL),
(251, 33, 7, 28, 0, 9, 1598640734, '2020-08-29 04:59:14', '2020-08-29 04:59:13', NULL),
(252, 33, 7, 28, 0, 10, 1598640734, '2020-08-29 05:18:14', '2020-08-29 05:18:13', NULL),
(253, 33, 7, 29, 0, 1, 1598641340, '2020-08-29 03:03:20', '2020-08-29 03:03:19', NULL),
(254, 33, 7, 29, 0, 2, 1598641340, '2020-08-29 03:26:20', '2020-08-29 03:26:19', NULL),
(255, 33, 7, 29, 0, 3, 1598641340, '2020-08-29 03:38:20', '2020-08-29 03:38:19', NULL),
(256, 33, 7, 29, 0, 4, 1598641340, '2020-08-29 03:57:20', '2020-08-29 03:57:19', NULL),
(257, 33, 7, 29, 0, 5, 1598641340, '2020-08-29 04:14:20', '2020-08-29 04:14:19', NULL),
(258, 33, 7, 29, 0, 6, 1598641340, '2020-08-29 04:28:20', '2020-08-29 04:28:19', NULL),
(259, 33, 7, 29, 0, 7, 1598641340, '2020-08-29 04:45:20', '2020-08-29 04:45:19', NULL),
(260, 33, 7, 29, 0, 8, 1598641340, '2020-08-29 05:00:20', '2020-08-29 05:00:19', NULL),
(261, 33, 7, 29, 0, 9, 1598641340, '2020-08-29 05:17:20', '2020-08-29 05:17:19', NULL),
(262, 33, 7, 29, 0, 10, 1598641340, '2020-08-29 05:32:20', '2020-08-29 05:32:19', NULL),
(263, 33, 7, 30, 0, 1, 1598641425, '2020-08-29 03:09:45', '2020-08-29 03:09:44', NULL),
(264, 33, 7, 30, 0, 2, 1598641425, '2020-08-29 03:27:45', '2020-08-29 03:27:44', NULL),
(265, 33, 7, 30, 0, 3, 1598641425, '2020-08-29 03:44:45', '2020-08-29 03:44:44', NULL),
(266, 33, 7, 30, 0, 4, 1598641425, '2020-08-29 03:50:45', '2020-08-29 03:50:44', NULL),
(267, 33, 7, 30, 0, 5, 1598641425, '2020-08-29 04:06:45', '2020-08-29 04:06:44', NULL),
(268, 33, 7, 30, 0, 6, 1598641425, '2020-08-29 04:25:45', '2020-08-29 04:25:44', NULL),
(269, 33, 7, 30, 0, 7, 1598641425, '2020-08-29 04:48:45', '2020-08-29 04:48:44', NULL),
(270, 33, 7, 30, 0, 8, 1598641425, '2020-08-29 04:56:45', '2020-08-29 04:56:44', NULL),
(271, 33, 7, 30, 0, 9, 1598641425, '2020-08-29 05:14:45', '2020-08-29 05:14:44', NULL),
(272, 33, 7, 30, 0, 10, 1598641425, '2020-08-29 05:28:45', '2020-08-29 05:28:44', NULL),
(273, 33, 7, 31, 0, 1, 1598641856, '2020-08-29 03:19:56', '2020-08-29 03:19:55', NULL),
(274, 33, 7, 31, 0, 2, 1598641856, '2020-08-29 03:33:56', '2020-08-29 03:33:55', NULL),
(275, 33, 7, 31, 0, 3, 1598641856, '2020-08-29 03:41:56', '2020-08-29 03:41:55', NULL),
(276, 33, 7, 31, 0, 4, 1598641856, '2020-08-29 03:58:56', '2020-08-29 03:58:55', NULL),
(277, 33, 7, 31, 0, 5, 1598641856, '2020-08-29 04:25:56', '2020-08-29 04:25:55', NULL),
(278, 33, 7, 31, 0, 6, 1598641856, '2020-08-29 04:26:56', '2020-08-29 04:26:55', NULL),
(279, 33, 7, 31, 0, 7, 1598641856, '2020-08-29 04:55:56', '2020-08-29 04:55:55', NULL),
(280, 33, 7, 31, 0, 8, 1598641856, '2020-08-29 05:08:56', '2020-08-29 05:08:55', NULL),
(281, 33, 7, 31, 0, 9, 1598641856, '2020-08-29 05:15:56', '2020-08-29 05:15:55', NULL),
(282, 33, 7, 31, 0, 10, 1598641856, '2020-08-29 05:28:56', '2020-08-29 05:28:55', NULL),
(283, 33, 7, 32, 0, 1, 1598642478, '2020-08-29 03:27:18', '2020-08-29 03:27:17', NULL),
(284, 33, 7, 32, 0, 2, 1598642478, '2020-08-29 03:45:18', '2020-08-29 03:45:17', NULL),
(285, 33, 7, 32, 0, 3, 1598642478, '2020-08-29 03:59:18', '2020-08-29 03:59:17', NULL),
(286, 33, 7, 32, 0, 4, 1598642478, '2020-08-29 04:19:18', '2020-08-29 04:19:17', NULL),
(287, 33, 7, 32, 0, 5, 1598642478, '2020-08-29 04:32:18', '2020-08-29 04:32:17', NULL),
(288, 33, 7, 32, 0, 6, 1598642478, '2020-08-29 04:42:18', '2020-08-29 04:42:17', NULL),
(289, 33, 7, 32, 0, 7, 1598642478, '2020-08-29 05:04:18', '2020-08-29 05:04:17', NULL),
(290, 33, 7, 32, 0, 8, 1598642478, '2020-08-29 05:14:18', '2020-08-29 05:14:17', NULL),
(291, 33, 7, 32, 0, 9, 1598642478, '2020-08-29 05:31:18', '2020-08-29 05:31:17', NULL),
(292, 33, 7, 32, 0, 10, 1598642478, '2020-08-29 05:41:18', '2020-08-29 05:41:17', NULL),
(293, 33, 7, 33, 0, 1, 1598643165, '2020-08-29 03:42:45', '2020-08-29 03:42:44', NULL),
(294, 33, 7, 33, 0, 2, 1598643165, '2020-08-29 04:02:45', '2020-08-29 04:02:44', NULL),
(295, 33, 7, 33, 0, 3, 1598643165, '2020-08-29 04:07:45', '2020-08-29 04:07:44', NULL),
(296, 33, 7, 33, 0, 4, 1598643165, '2020-08-29 04:24:45', '2020-08-29 04:24:44', NULL),
(297, 33, 7, 33, 0, 5, 1598643165, '2020-08-29 04:33:45', '2020-08-29 04:33:44', NULL),
(298, 33, 7, 33, 0, 6, 1598643165, '2020-08-29 04:51:45', '2020-08-29 04:51:44', NULL),
(299, 33, 7, 33, 0, 7, 1598643165, '2020-08-29 05:08:45', '2020-08-29 05:08:44', NULL),
(300, 33, 7, 33, 0, 8, 1598643165, '2020-08-29 05:27:45', '2020-08-29 05:27:44', NULL),
(301, 33, 7, 33, 0, 9, 1598643165, '2020-08-29 05:34:45', '2020-08-29 05:34:44', NULL),
(302, 33, 7, 33, 0, 10, 1598643165, '2020-08-29 05:50:45', '2020-08-29 05:50:44', NULL),
(303, 33, 7, 34, 0, 1, 1598643768, '2020-08-29 03:46:48', '2020-08-29 03:46:47', NULL),
(304, 33, 7, 34, 0, 2, 1598643768, '2020-08-29 04:00:48', '2020-08-29 04:00:47', NULL),
(305, 33, 7, 34, 0, 3, 1598643768, '2020-08-29 04:18:48', '2020-08-29 04:18:47', NULL),
(306, 33, 7, 34, 0, 4, 1598643768, '2020-08-29 04:35:48', '2020-08-29 04:35:47', NULL),
(307, 33, 7, 34, 0, 5, 1598643768, '2020-08-29 04:54:48', '2020-08-29 04:54:47', NULL),
(308, 33, 7, 34, 0, 6, 1598643768, '2020-08-29 05:12:48', '2020-08-29 05:12:47', NULL),
(309, 33, 7, 34, 0, 7, 1598643768, '2020-08-29 05:25:48', '2020-08-29 05:25:47', NULL),
(310, 33, 7, 34, 0, 8, 1598643768, '2020-08-29 05:41:48', '2020-08-29 05:41:47', NULL),
(311, 33, 7, 34, 0, 9, 1598643768, '2020-08-29 05:54:48', '2020-08-29 05:54:47', NULL),
(312, 33, 7, 34, 0, 10, 1598643768, '2020-08-29 06:11:48', '2020-08-29 06:11:47', NULL),
(313, 33, 8, 35, 0, 1, 1598643866, '2020-08-29 03:54:26', '2020-08-29 03:54:25', NULL),
(314, 33, 8, 35, 0, 2, 1598643866, '2020-08-29 04:12:26', '2020-08-29 04:12:25', NULL),
(315, 33, 8, 35, 0, 3, 1598643866, '2020-08-29 04:23:26', '2020-08-29 04:23:25', NULL),
(316, 33, 8, 35, 0, 4, 1598643866, '2020-08-29 04:30:26', '2020-08-29 04:30:25', NULL),
(317, 33, 8, 35, 0, 5, 1598643866, '2020-08-29 04:58:26', '2020-08-29 04:58:25', NULL),
(318, 33, 8, 35, 0, 6, 1598643866, '2020-08-29 05:02:26', '2020-08-29 05:02:25', NULL),
(319, 33, 8, 35, 0, 7, 1598643866, '2020-08-29 05:29:26', '2020-08-29 05:29:25', NULL),
(320, 33, 8, 35, 0, 8, 1598643866, '2020-08-29 05:38:26', '2020-08-29 05:38:25', NULL),
(321, 33, 8, 35, 0, 9, 1598643866, '2020-08-29 05:53:26', '2020-08-29 05:53:25', NULL),
(322, 33, 8, 35, 0, 10, 1598643866, '2020-08-29 06:01:26', '2020-08-29 06:01:25', NULL),
(323, 33, 9, 36, 0, 1, 1598644185, '2020-08-29 03:50:45', '2020-08-29 03:50:44', NULL),
(324, 33, 9, 36, 0, 2, 1598644185, '2020-08-29 04:16:45', '2020-08-29 04:16:44', NULL),
(325, 33, 9, 36, 0, 3, 1598644185, '2020-08-29 04:35:45', '2020-08-29 04:35:44', NULL),
(326, 33, 9, 36, 0, 4, 1598644185, '2020-08-29 04:46:45', '2020-08-29 04:46:44', NULL),
(327, 33, 9, 36, 0, 5, 1598644185, '2020-08-29 05:14:45', '2020-08-29 05:14:44', NULL),
(328, 33, 9, 36, 0, 6, 1598644185, '2020-08-29 05:24:45', '2020-08-29 05:24:44', NULL),
(329, 33, 9, 36, 0, 7, 1598644185, '2020-08-29 05:51:45', '2020-08-29 05:51:44', NULL),
(330, 33, 9, 36, 0, 8, 1598644185, '2020-08-29 06:04:45', '2020-08-29 06:04:44', NULL),
(331, 33, 9, 36, 0, 9, 1598644185, '2020-08-29 06:16:45', '2020-08-29 06:16:44', NULL),
(332, 33, 9, 36, 0, 10, 1598644185, '2020-08-29 06:48:45', '2020-08-29 06:48:44', NULL),
(333, 36, 7, 37, 0, 1, 1598651412, '2020-08-29 06:03:12', '2020-08-29 06:03:11', NULL),
(334, 36, 7, 37, 0, 2, 1598651412, '2020-08-29 06:19:12', '2020-08-29 06:19:11', NULL),
(335, 36, 7, 37, 0, 3, 1598651412, '2020-08-29 06:21:12', '2020-08-29 06:21:11', NULL),
(336, 36, 7, 37, 0, 4, 1598651412, '2020-08-29 06:44:12', '2020-08-29 06:44:11', NULL),
(337, 36, 7, 37, 0, 5, 1598651412, '2020-08-29 06:53:12', '2020-08-29 06:53:11', NULL),
(338, 36, 7, 37, 0, 6, 1598651412, '2020-08-29 07:09:12', '2020-08-29 07:09:11', NULL),
(339, 36, 7, 37, 0, 7, 1598651412, '2020-08-29 07:26:12', '2020-08-29 07:26:11', NULL),
(340, 36, 7, 37, 0, 8, 1598651412, '2020-08-29 07:41:12', '2020-08-29 07:41:11', NULL),
(341, 36, 7, 37, 0, 9, 1598651412, '2020-08-29 08:00:12', '2020-08-29 08:00:11', NULL),
(342, 36, 7, 37, 0, 10, 1598651412, '2020-08-29 08:15:12', '2020-08-29 08:15:11', NULL),
(343, 36, 7, 38, 0, 1, 1598652231, '2020-08-29 06:11:51', '2020-08-29 06:11:50', NULL),
(344, 36, 7, 38, 0, 2, 1598652231, '2020-08-29 06:25:51', '2020-08-29 06:25:50', NULL),
(345, 36, 7, 38, 0, 3, 1598652231, '2020-08-29 06:39:51', '2020-08-29 06:39:50', NULL),
(346, 36, 7, 38, 0, 4, 1598652231, '2020-08-29 06:55:51', '2020-08-29 06:55:50', NULL),
(347, 36, 7, 38, 0, 5, 1598652231, '2020-08-29 07:04:51', '2020-08-29 07:04:50', NULL),
(348, 36, 7, 38, 0, 6, 1598652231, '2020-08-29 07:23:51', '2020-08-29 07:23:50', NULL),
(349, 36, 7, 38, 0, 7, 1598652231, '2020-08-29 07:45:51', '2020-08-29 07:45:50', NULL),
(350, 36, 7, 38, 0, 8, 1598652231, '2020-08-29 08:03:51', '2020-08-29 08:03:50', NULL),
(351, 36, 7, 38, 0, 9, 1598652231, '2020-08-29 08:07:51', '2020-08-29 08:07:50', NULL),
(352, 36, 7, 38, 0, 10, 1598652231, '2020-08-29 08:31:51', '2020-08-29 08:31:50', NULL),
(353, 36, 9, 39, 0, 1, 1598662456, '2020-08-29 08:55:16', '2020-08-29 08:55:15', NULL),
(354, 36, 9, 39, 0, 2, 1598662456, '2020-08-29 09:24:16', '2020-08-29 09:24:15', NULL),
(355, 36, 9, 39, 0, 3, 1598662456, '2020-08-29 09:40:16', '2020-08-29 09:40:15', NULL),
(356, 36, 9, 39, 0, 4, 1598662456, '2020-08-29 10:06:16', '2020-08-29 10:06:15', NULL),
(357, 36, 9, 39, 0, 5, 1598662456, '2020-08-29 10:14:16', '2020-08-29 10:14:15', NULL),
(358, 36, 9, 39, 0, 6, 1598662456, '2020-08-29 10:38:16', '2020-08-29 10:38:15', NULL),
(359, 36, 9, 39, 0, 7, 1598662456, '2020-08-29 10:46:16', '2020-08-29 10:46:15', NULL),
(360, 36, 9, 39, 0, 8, 1598662456, '2020-08-29 11:05:16', '2020-08-29 11:05:15', NULL),
(361, 36, 9, 39, 0, 9, 1598662456, '2020-08-29 11:28:16', '2020-08-29 11:28:15', NULL),
(362, 36, 9, 39, 0, 10, 1598662456, '2020-08-29 11:54:16', '2020-08-29 11:54:15', NULL),
(363, 36, 9, 40, 0, 1, 1598662703, '2020-08-29 09:13:23', '2020-08-29 09:13:22', NULL),
(364, 36, 9, 40, 0, 2, 1598662703, '2020-08-29 09:31:23', '2020-08-29 09:31:22', NULL),
(365, 36, 9, 40, 0, 3, 1598662703, '2020-08-29 09:49:23', '2020-08-29 09:49:22', NULL),
(366, 36, 9, 40, 0, 4, 1598662703, '2020-08-29 10:09:23', '2020-08-29 10:09:22', NULL),
(367, 36, 9, 40, 0, 5, 1598662703, '2020-08-29 10:17:23', '2020-08-29 10:17:22', NULL),
(368, 36, 9, 40, 0, 6, 1598662703, '2020-08-29 10:45:23', '2020-08-29 10:45:22', NULL),
(369, 36, 9, 40, 0, 7, 1598662703, '2020-08-29 11:01:23', '2020-08-29 11:01:22', NULL),
(370, 36, 9, 40, 0, 8, 1598662703, '2020-08-29 11:12:23', '2020-08-29 11:12:22', NULL),
(371, 36, 9, 40, 0, 9, 1598662703, '2020-08-29 11:35:23', '2020-08-29 11:35:22', NULL),
(372, 36, 9, 40, 0, 10, 1598662703, '2020-08-29 11:57:23', '2020-08-29 11:57:22', NULL),
(373, 36, 8, 41, 0, 1, 1598662736, '2020-08-29 09:12:56', '2020-08-29 09:12:55', NULL),
(374, 36, 8, 41, 0, 2, 1598662736, '2020-08-29 09:27:56', '2020-08-29 09:27:55', NULL),
(375, 36, 8, 41, 0, 3, 1598662736, '2020-08-29 09:39:56', '2020-08-29 09:39:55', NULL),
(376, 36, 8, 41, 0, 4, 1598662736, '2020-08-29 09:45:56', '2020-08-29 09:45:55', NULL),
(377, 36, 8, 41, 0, 5, 1598662736, '2020-08-29 10:12:56', '2020-08-29 10:12:55', NULL),
(378, 36, 8, 41, 0, 6, 1598662736, '2020-08-29 10:17:56', '2020-08-29 10:17:55', NULL),
(379, 36, 8, 41, 0, 7, 1598662736, '2020-08-29 10:39:56', '2020-08-29 10:39:55', NULL),
(380, 36, 8, 41, 0, 8, 1598662736, '2020-08-29 10:52:56', '2020-08-29 10:52:55', NULL),
(381, 36, 8, 41, 0, 9, 1598662736, '2020-08-29 11:09:56', '2020-08-29 11:09:55', NULL),
(382, 36, 8, 41, 0, 10, 1598662736, '2020-08-29 11:23:56', '2020-08-29 11:23:55', NULL),
(383, 36, 7, 42, 0, 1, 1598662749, '2020-08-29 09:09:09', '2020-08-29 09:09:08', NULL),
(384, 36, 7, 42, 0, 2, 1598662749, '2020-08-29 09:28:09', '2020-08-29 09:28:08', NULL),
(385, 36, 7, 42, 0, 3, 1598662749, '2020-08-29 09:42:09', '2020-08-29 09:42:08', NULL),
(386, 36, 7, 42, 0, 4, 1598662749, '2020-08-29 09:50:09', '2020-08-29 09:50:08', NULL),
(387, 36, 7, 42, 0, 5, 1598662749, '2020-08-29 10:09:09', '2020-08-29 10:09:08', NULL),
(388, 36, 7, 42, 0, 6, 1598662749, '2020-08-29 10:18:09', '2020-08-29 10:18:08', NULL),
(389, 36, 7, 42, 0, 7, 1598662749, '2020-08-29 10:30:09', '2020-08-29 10:30:08', NULL),
(390, 36, 7, 42, 0, 8, 1598662749, '2020-08-29 10:45:09', '2020-08-29 10:45:08', NULL),
(391, 36, 7, 42, 0, 9, 1598662749, '2020-08-29 11:06:09', '2020-08-29 11:06:08', NULL),
(392, 36, 7, 42, 0, 10, 1598662749, '2020-08-29 11:26:09', '2020-08-29 11:26:08', NULL),
(393, 36, 8, 43, 0, 1, 1598663583, '2020-08-29 09:26:03', '2020-08-29 09:26:02', NULL),
(394, 36, 8, 43, 0, 2, 1598663583, '2020-08-29 09:33:03', '2020-08-29 09:33:02', NULL),
(395, 36, 8, 43, 0, 3, 1598663583, '2020-08-29 09:57:03', '2020-08-29 09:57:02', NULL),
(396, 36, 8, 43, 0, 4, 1598663583, '2020-08-29 10:02:03', '2020-08-29 10:02:02', NULL),
(397, 36, 8, 43, 0, 5, 1598663583, '2020-08-29 10:20:03', '2020-08-29 10:20:02', NULL),
(398, 36, 8, 43, 0, 6, 1598663583, '2020-08-29 10:32:03', '2020-08-29 10:32:02', NULL),
(399, 36, 8, 43, 0, 7, 1598663583, '2020-08-29 10:44:03', '2020-08-29 10:44:02', NULL),
(400, 36, 8, 43, 0, 8, 1598663583, '2020-08-29 11:10:03', '2020-08-29 11:10:02', NULL),
(401, 36, 8, 43, 0, 9, 1598663583, '2020-08-29 11:16:03', '2020-08-29 11:16:02', NULL),
(402, 36, 8, 43, 0, 10, 1598663583, '2020-08-29 11:32:03', '2020-08-29 11:32:02', NULL),
(403, 35, 9, 44, 0, 1, 1598677800, '2020-08-29 13:22:00', '2020-08-29 13:21:59', NULL),
(404, 35, 9, 44, 0, 2, 1598677800, '2020-08-29 13:30:00', '2020-08-29 13:29:59', NULL),
(405, 35, 9, 44, 0, 3, 1598677800, '2020-08-29 14:03:00', '2020-08-29 14:02:59', NULL),
(406, 35, 9, 44, 0, 4, 1598677800, '2020-08-29 14:21:00', '2020-08-29 14:20:59', NULL),
(407, 35, 9, 44, 0, 5, 1598677800, '2020-08-29 14:33:00', '2020-08-29 14:32:59', NULL),
(408, 35, 9, 44, 0, 6, 1598677800, '2020-08-29 14:43:00', '2020-08-29 14:42:59', NULL),
(409, 35, 9, 44, 0, 7, 1598677800, '2020-08-29 15:04:00', '2020-08-29 15:03:59', NULL),
(410, 35, 9, 44, 0, 8, 1598677800, '2020-08-29 15:28:00', '2020-08-29 15:27:59', NULL),
(411, 35, 9, 44, 0, 9, 1598677800, '2020-08-29 15:37:00', '2020-08-29 15:36:59', NULL),
(412, 35, 9, 44, 0, 10, 1598677800, '2020-08-29 16:04:00', '2020-08-29 16:03:59', NULL),
(413, 35, 9, 45, 0, 1, 1598679993, '2020-08-29 13:55:33', '2020-08-29 13:58:32', NULL),
(414, 35, 9, 45, 0, 2, 1598679993, '2020-08-29 14:20:33', '2020-08-29 14:23:32', NULL),
(415, 35, 9, 45, 0, 3, 1598679993, '2020-08-29 14:25:33', '2020-08-29 14:28:32', NULL),
(416, 35, 9, 45, 0, 4, 1598679993, '2020-08-29 14:55:33', '2020-08-29 14:58:32', NULL),
(417, 35, 9, 45, 0, 5, 1598679993, '2020-08-29 15:02:33', '2020-08-29 15:05:32', NULL),
(418, 35, 9, 45, 0, 6, 1598679993, '2020-08-29 15:17:33', '2020-08-29 15:20:32', NULL),
(419, 35, 9, 45, 0, 7, 1598679993, '2020-08-29 15:52:33', '2020-08-29 15:55:32', NULL),
(420, 35, 9, 45, 0, 8, 1598679993, '2020-08-29 16:00:33', '2020-08-29 16:03:32', NULL),
(421, 35, 9, 45, 0, 9, 1598679993, '2020-08-29 16:23:33', '2020-08-29 16:26:32', NULL),
(422, 35, 9, 45, 0, 10, 1598679993, '2020-08-29 16:38:33', '2020-08-29 16:41:32', NULL),
(423, 36, 9, 46, 0, 1, 1598686736, '2020-08-29 15:51:56', '2020-08-29 15:54:55', NULL),
(424, 36, 9, 46, 0, 2, 1598686736, '2020-08-29 15:58:56', '2020-08-29 16:01:55', NULL),
(425, 36, 9, 46, 0, 3, 1598686736, '2020-08-29 16:20:56', '2020-08-29 16:23:55', NULL),
(426, 36, 9, 46, 0, 4, 1598686736, '2020-08-29 16:37:56', '2020-08-29 16:40:55', NULL),
(427, 36, 9, 46, 0, 5, 1598686736, '2020-08-29 17:00:56', '2020-08-29 17:03:55', NULL),
(428, 36, 9, 46, 0, 6, 1598686736, '2020-08-29 17:10:56', '2020-08-29 17:13:55', NULL),
(429, 36, 9, 46, 0, 7, 1598686736, '2020-08-29 17:35:56', '2020-08-29 17:38:55', NULL),
(430, 36, 9, 46, 0, 8, 1598686736, '2020-08-29 17:49:56', '2020-08-29 17:52:55', NULL),
(431, 36, 9, 46, 0, 9, 1598686736, '2020-08-29 18:13:56', '2020-08-29 18:16:55', NULL),
(432, 36, 9, 46, 0, 10, 1598686736, '2020-08-29 18:31:56', '2020-08-29 18:34:55', NULL),
(433, 37, 9, 47, 0, 1, 1598686787, '2020-08-29 15:45:47', '2020-08-29 15:48:46', NULL),
(434, 37, 9, 47, 0, 2, 1598686787, '2020-08-29 16:11:47', '2020-08-29 16:14:46', NULL),
(435, 37, 9, 47, 0, 3, 1598686787, '2020-08-29 16:21:47', '2020-08-29 16:24:46', NULL),
(436, 37, 9, 47, 0, 4, 1598686787, '2020-08-29 16:36:47', '2020-08-29 16:39:46', NULL),
(437, 37, 9, 47, 0, 5, 1598686787, '2020-08-29 16:54:47', '2020-08-29 16:57:46', NULL),
(438, 37, 9, 47, 0, 6, 1598686787, '2020-08-29 17:20:47', '2020-08-29 17:23:46', NULL),
(439, 37, 9, 47, 0, 7, 1598686787, '2020-08-29 17:29:47', '2020-08-29 17:32:46', NULL),
(440, 37, 9, 47, 0, 8, 1598686787, '2020-08-29 17:47:47', '2020-08-29 17:50:46', NULL),
(441, 37, 9, 47, 0, 9, 1598686787, '2020-08-29 18:20:47', '2020-08-29 18:23:46', NULL),
(442, 37, 9, 47, 0, 10, 1598686787, '2020-08-29 18:35:47', '2020-08-29 18:38:46', NULL),
(443, 37, 7, 48, 0, 1, 1598686896, '2020-08-29 15:54:36', '2020-08-29 15:57:35', NULL),
(444, 37, 7, 48, 0, 2, 1598686896, '2020-08-29 16:07:36', '2020-08-29 16:10:35', NULL),
(445, 37, 7, 48, 0, 3, 1598686896, '2020-08-29 16:26:36', '2020-08-29 16:29:35', NULL),
(446, 37, 7, 48, 0, 4, 1598686896, '2020-08-29 16:37:36', '2020-08-29 16:40:35', NULL),
(447, 37, 7, 48, 0, 5, 1598686896, '2020-08-29 16:45:36', '2020-08-29 16:48:35', NULL),
(448, 37, 7, 48, 0, 6, 1598686896, '2020-08-29 17:05:36', '2020-08-29 17:08:35', NULL),
(449, 37, 7, 48, 0, 7, 1598686896, '2020-08-29 17:26:36', '2020-08-29 17:29:35', NULL),
(450, 37, 7, 48, 0, 8, 1598686896, '2020-08-29 17:29:36', '2020-08-29 17:32:35', NULL),
(451, 37, 7, 48, 0, 9, 1598686896, '2020-08-29 17:55:36', '2020-08-29 17:58:35', NULL),
(452, 37, 7, 48, 0, 10, 1598686896, '2020-08-29 18:10:36', '2020-08-29 18:13:35', NULL),
(453, 33, 9, 49, 1, 1, 1598728094, '2020-08-30 03:26:14', '2020-08-30 03:29:13', '2020-08-30 03:26:16'),
(454, 33, 9, 49, 1, 2, 1598728094, '2020-08-30 03:37:14', '2020-08-30 03:40:13', '2020-08-30 03:37:16'),
(455, 33, 9, 49, 1, 3, 1598728094, '2020-08-30 03:56:14', '2020-08-30 03:59:13', '2020-08-30 03:58:39'),
(456, 33, 9, 49, 1, 4, 1598728094, '2020-08-30 04:03:14', '2020-08-30 04:06:13', '2020-08-30 04:05:51'),
(457, 33, 9, 49, 1, 5, 1598728094, '2020-08-30 04:28:14', '2020-08-30 04:31:13', '2020-08-30 04:30:23'),
(458, 33, 9, 49, 1, 6, 1598728094, '2020-08-30 04:54:14', '2020-08-30 04:57:13', '2020-08-30 04:57:02'),
(459, 33, 9, 49, 1, 7, 1598728094, '2020-08-30 05:10:14', '2020-08-30 05:13:13', '2020-08-30 05:10:48'),
(460, 33, 9, 49, 0, 8, 1598728094, '2020-08-30 05:26:14', '2020-08-30 05:29:13', NULL),
(461, 33, 9, 49, 0, 9, 1598728094, '2020-08-30 05:47:14', '2020-08-30 05:50:13', NULL),
(462, 33, 9, 49, 0, 10, 1598728094, '2020-08-30 06:05:14', '2020-08-30 06:08:13', NULL),
(463, 37, 9, 50, 1, 1, 1598736823, '2020-08-30 05:34:43', '2020-08-30 05:37:42', '2020-08-30 05:34:52'),
(464, 37, 9, 50, 0, 2, 1598736823, '2020-08-30 06:07:43', '2020-08-30 06:10:42', NULL),
(465, 37, 9, 50, 0, 3, 1598736823, '2020-08-30 06:16:43', '2020-08-30 06:19:42', NULL),
(466, 37, 9, 50, 0, 4, 1598736823, '2020-08-30 06:45:43', '2020-08-30 06:48:42', NULL),
(467, 37, 9, 50, 0, 5, 1598736823, '2020-08-30 06:52:43', '2020-08-30 06:55:42', NULL),
(468, 37, 9, 50, 0, 6, 1598736823, '2020-08-30 07:07:43', '2020-08-30 07:10:42', NULL),
(469, 37, 9, 50, 0, 7, 1598736823, '2020-08-30 07:38:43', '2020-08-30 07:41:42', NULL),
(470, 37, 9, 50, 0, 8, 1598736823, '2020-08-30 07:44:43', '2020-08-30 07:47:42', NULL),
(471, 37, 9, 50, 0, 9, 1598736823, '2020-08-30 08:07:43', '2020-08-30 08:10:42', NULL),
(472, 37, 9, 50, 0, 10, 1598736823, '2020-08-30 08:23:43', '2020-08-30 08:26:42', NULL),
(473, 37, 9, 51, 0, 1, 1598739774, '2020-08-30 06:27:54', '2020-08-30 06:30:53', NULL),
(474, 37, 9, 51, 0, 2, 1598739774, '2020-08-30 06:53:54', '2020-08-30 06:56:53', NULL),
(475, 37, 9, 51, 0, 3, 1598739774, '2020-08-30 07:15:54', '2020-08-30 07:18:53', NULL),
(476, 37, 9, 51, 0, 4, 1598739774, '2020-08-30 07:27:54', '2020-08-30 07:30:53', NULL),
(477, 37, 9, 51, 0, 5, 1598739774, '2020-08-30 07:44:54', '2020-08-30 07:47:53', NULL),
(478, 37, 9, 51, 0, 6, 1598739774, '2020-08-30 08:04:54', '2020-08-30 08:07:53', NULL),
(479, 37, 9, 51, 0, 7, 1598739774, '2020-08-30 08:17:54', '2020-08-30 08:20:53', NULL),
(480, 37, 9, 51, 0, 8, 1598739774, '2020-08-30 08:32:54', '2020-08-30 08:35:53', NULL),
(481, 37, 9, 51, 0, 9, 1598739774, '2020-08-30 09:04:54', '2020-08-30 09:07:53', NULL),
(482, 37, 9, 51, 0, 10, 1598739774, '2020-08-30 09:14:54', '2020-08-30 09:17:53', NULL),
(483, 37, 9, 52, 0, 1, 1598740441, '2020-08-30 06:37:01', '2020-08-30 06:40:00', NULL),
(484, 37, 9, 52, 0, 2, 1598740441, '2020-08-30 06:55:01', '2020-08-30 06:58:00', NULL),
(485, 37, 9, 52, 0, 3, 1598740441, '2020-08-30 07:13:01', '2020-08-30 07:16:00', NULL),
(486, 37, 9, 52, 0, 4, 1598740441, '2020-08-30 07:44:01', '2020-08-30 07:47:00', NULL),
(487, 37, 9, 52, 0, 5, 1598740441, '2020-08-30 07:48:01', '2020-08-30 07:51:00', NULL),
(488, 37, 9, 52, 0, 6, 1598740441, '2020-08-30 08:05:01', '2020-08-30 08:08:00', NULL),
(489, 37, 9, 52, 0, 7, 1598740441, '2020-08-30 08:33:01', '2020-08-30 08:36:00', NULL),
(490, 37, 9, 52, 0, 8, 1598740441, '2020-08-30 08:45:01', '2020-08-30 08:48:00', NULL),
(491, 37, 9, 52, 0, 9, 1598740441, '2020-08-30 09:01:01', '2020-08-30 09:04:00', NULL),
(492, 37, 9, 52, 0, 10, 1598740441, '2020-08-30 09:24:01', '2020-08-30 09:27:00', NULL),
(493, 37, 9, 53, 0, 1, 1598742892, '2020-08-30 07:32:52', '2020-08-30 07:35:51', NULL),
(494, 37, 9, 53, 0, 2, 1598742892, '2020-08-30 07:50:52', '2020-08-30 07:53:51', NULL),
(495, 37, 9, 53, 0, 3, 1598742892, '2020-08-30 07:53:52', '2020-08-30 07:56:51', NULL),
(496, 37, 9, 53, 0, 4, 1598742892, '2020-08-30 08:12:52', '2020-08-30 08:15:51', NULL),
(497, 37, 9, 53, 0, 5, 1598742892, '2020-08-30 08:40:52', '2020-08-30 08:43:51', NULL),
(498, 37, 9, 53, 0, 6, 1598742892, '2020-08-30 08:45:52', '2020-08-30 08:48:51', NULL),
(499, 37, 9, 53, 0, 7, 1598742892, '2020-08-30 09:08:52', '2020-08-30 09:11:51', NULL),
(500, 37, 9, 53, 0, 8, 1598742892, '2020-08-30 09:34:52', '2020-08-30 09:37:51', NULL),
(501, 37, 9, 53, 0, 9, 1598742892, '2020-08-30 09:43:52', '2020-08-30 09:46:51', NULL),
(502, 37, 9, 53, 0, 10, 1598742892, '2020-08-30 10:11:52', '2020-08-30 10:14:51', NULL),
(503, 37, 9, 54, 1, 1, 1598745806, '2020-08-30 08:21:26', '2020-08-30 08:24:25', '2020-08-30 08:21:32'),
(504, 37, 9, 54, 0, 2, 1598745806, '2020-08-30 08:23:26', '2020-08-30 08:26:25', NULL),
(505, 37, 9, 54, 0, 3, 1598745806, '2020-08-30 08:47:26', '2020-08-30 08:50:25', NULL),
(506, 37, 9, 54, 0, 4, 1598745806, '2020-08-30 09:04:26', '2020-08-30 09:07:25', NULL),
(507, 37, 9, 54, 0, 5, 1598745806, '2020-08-30 09:16:26', '2020-08-30 09:19:25', NULL),
(508, 37, 9, 54, 0, 6, 1598745806, '2020-08-30 09:40:26', '2020-08-30 09:43:25', NULL),
(509, 37, 9, 54, 0, 7, 1598745806, '2020-08-30 09:57:26', '2020-08-30 10:00:25', NULL),
(510, 37, 9, 54, 0, 8, 1598745806, '2020-08-30 10:19:26', '2020-08-30 10:22:25', NULL),
(511, 37, 9, 54, 0, 9, 1598745806, '2020-08-30 10:44:26', '2020-08-30 10:47:25', NULL),
(512, 37, 9, 54, 0, 10, 1598745806, '2020-08-30 11:03:26', '2020-08-30 11:06:25', NULL),
(513, 36, 9, 55, 1, 1, 1598758413, '2020-08-30 11:38:33', '2020-08-30 11:41:32', '2020-08-30 11:38:38'),
(514, 36, 9, 55, 0, 2, 1598758413, '2020-08-30 12:05:33', '2020-08-30 12:08:32', NULL),
(515, 36, 9, 55, 0, 3, 1598758413, '2020-08-30 12:21:33', '2020-08-30 12:24:32', NULL),
(516, 36, 9, 55, 0, 4, 1598758413, '2020-08-30 12:35:33', '2020-08-30 12:38:32', NULL),
(517, 36, 9, 55, 0, 5, 1598758413, '2020-08-30 12:54:33', '2020-08-30 12:57:32', NULL),
(518, 36, 9, 55, 0, 6, 1598758413, '2020-08-30 13:11:33', '2020-08-30 13:14:32', NULL),
(519, 36, 9, 55, 0, 7, 1598758413, '2020-08-30 13:24:33', '2020-08-30 13:27:32', NULL),
(520, 36, 9, 55, 0, 8, 1598758413, '2020-08-30 13:54:33', '2020-08-30 13:57:32', NULL),
(521, 36, 9, 55, 0, 9, 1598758413, '2020-08-30 14:12:33', '2020-08-30 14:15:32', NULL),
(522, 36, 9, 55, 0, 10, 1598758413, '2020-08-30 14:25:33', '2020-08-30 14:28:32', NULL),
(523, 37, 9, 56, 1, 1, 1598758489, '2020-08-30 11:39:49', '2020-08-30 11:42:48', '2020-08-30 11:39:52'),
(524, 37, 9, 56, 1, 2, 1598758489, '2020-08-30 12:08:49', '2020-08-30 12:11:48', '2020-08-30 12:09:15'),
(525, 37, 9, 56, 0, 3, 1598758489, '2020-08-30 12:18:49', '2020-08-30 12:21:48', NULL),
(526, 37, 9, 56, 0, 4, 1598758489, '2020-08-30 12:32:49', '2020-08-30 12:35:48', NULL),
(527, 37, 9, 56, 0, 5, 1598758489, '2020-08-30 13:02:49', '2020-08-30 13:05:48', NULL),
(528, 37, 9, 56, 0, 6, 1598758489, '2020-08-30 13:22:49', '2020-08-30 13:25:48', NULL),
(529, 37, 9, 56, 0, 7, 1598758489, '2020-08-30 13:32:49', '2020-08-30 13:35:48', NULL),
(530, 37, 9, 56, 0, 8, 1598758489, '2020-08-30 13:54:49', '2020-08-30 13:57:48', NULL),
(531, 37, 9, 56, 0, 9, 1598758489, '2020-08-30 14:04:49', '2020-08-30 14:07:48', NULL),
(532, 37, 9, 56, 0, 10, 1598758489, '2020-08-30 14:29:49', '2020-08-30 14:32:48', NULL),
(533, 36, 9, 57, 1, 1, 1598760573, '2020-08-30 12:24:33', '2020-08-30 12:27:32', '2020-08-30 12:24:35'),
(534, 36, 9, 57, 0, 2, 1598760573, '2020-08-30 12:40:33', '2020-08-30 12:43:32', NULL),
(535, 36, 9, 57, 0, 3, 1598760573, '2020-08-30 12:49:33', '2020-08-30 12:52:32', NULL),
(536, 36, 9, 57, 0, 4, 1598760573, '2020-08-30 13:12:33', '2020-08-30 13:15:32', NULL),
(537, 36, 9, 57, 0, 5, 1598760573, '2020-08-30 13:28:33', '2020-08-30 13:31:32', NULL),
(538, 36, 9, 57, 0, 6, 1598760573, '2020-08-30 13:51:33', '2020-08-30 13:54:32', NULL),
(539, 36, 9, 57, 0, 7, 1598760573, '2020-08-30 14:03:33', '2020-08-30 14:06:32', NULL),
(540, 36, 9, 57, 0, 8, 1598760573, '2020-08-30 14:33:33', '2020-08-30 14:36:32', NULL),
(541, 36, 9, 57, 0, 9, 1598760573, '2020-08-30 14:51:33', '2020-08-30 14:54:32', NULL),
(542, 36, 9, 57, 0, 10, 1598760573, '2020-08-30 15:06:33', '2020-08-30 15:09:32', NULL),
(543, 47, 9, 58, 1, 1, 1598765492, '2020-08-30 13:45:32', '2020-08-30 13:48:31', '2020-08-30 13:48:16'),
(544, 47, 9, 58, 1, 2, 1598765492, '2020-08-30 14:04:32', '2020-08-30 14:07:31', '2020-08-30 14:04:52'),
(545, 47, 9, 58, 1, 3, 1598765492, '2020-08-30 14:19:32', '2020-08-30 14:22:31', '2020-08-30 14:21:11'),
(546, 47, 9, 58, 1, 4, 1598765492, '2020-08-30 14:37:32', '2020-08-30 14:40:31', '2020-08-30 14:37:47'),
(547, 47, 9, 58, 1, 5, 1598765492, '2020-08-30 14:53:32', '2020-08-30 14:56:31', '2020-08-30 14:53:39'),
(548, 47, 9, 58, 1, 6, 1598765492, '2020-08-30 15:14:32', '2020-08-30 15:17:31', '2020-08-30 15:16:01'),
(549, 47, 9, 58, 0, 7, 1598765492, '2020-08-30 15:32:32', '2020-08-30 15:35:31', NULL),
(550, 47, 9, 58, 0, 8, 1598765492, '2020-08-30 15:54:32', '2020-08-30 15:57:31', NULL),
(551, 47, 9, 58, 0, 9, 1598765492, '2020-08-30 16:02:32', '2020-08-30 16:05:31', NULL),
(552, 47, 9, 58, 0, 10, 1598765492, '2020-08-30 16:21:32', '2020-08-30 16:24:31', NULL),
(553, 36, 9, 59, 0, 1, 1598769966, '2020-08-30 14:56:06', '2020-08-30 14:59:05', NULL),
(554, 36, 9, 59, 0, 2, 1598769966, '2020-08-30 15:05:06', '2020-08-30 15:08:05', NULL),
(555, 36, 9, 59, 0, 3, 1598769966, '2020-08-30 15:33:06', '2020-08-30 15:36:05', NULL),
(556, 36, 9, 59, 0, 4, 1598769966, '2020-08-30 15:54:06', '2020-08-30 15:57:05', NULL),
(557, 36, 9, 59, 0, 5, 1598769966, '2020-08-30 16:04:06', '2020-08-30 16:07:05', NULL),
(558, 36, 9, 59, 0, 6, 1598769966, '2020-08-30 16:31:06', '2020-08-30 16:34:05', NULL),
(559, 36, 9, 59, 0, 7, 1598769966, '2020-08-30 16:43:06', '2020-08-30 16:46:05', NULL),
(560, 36, 9, 59, 0, 8, 1598769966, '2020-08-30 17:03:06', '2020-08-30 17:06:05', NULL),
(561, 36, 9, 59, 0, 9, 1598769966, '2020-08-30 17:11:06', '2020-08-30 17:14:05', NULL),
(562, 36, 9, 59, 0, 10, 1598769966, '2020-08-30 17:41:06', '2020-08-30 17:44:05', NULL),
(563, 47, 9, 60, 0, 1, 1598773271, '2020-08-30 15:45:11', '2020-08-30 15:48:10', NULL),
(564, 47, 9, 60, 0, 2, 1598773271, '2020-08-30 16:15:11', '2020-08-30 16:18:10', NULL),
(565, 47, 9, 60, 0, 3, 1598773271, '2020-08-30 16:23:11', '2020-08-30 16:26:10', NULL),
(566, 47, 9, 60, 0, 4, 1598773271, '2020-08-30 16:40:11', '2020-08-30 16:43:10', NULL),
(567, 47, 9, 60, 0, 5, 1598773271, '2020-08-30 17:08:11', '2020-08-30 17:11:10', NULL),
(568, 47, 9, 60, 0, 6, 1598773271, '2020-08-30 17:22:11', '2020-08-30 17:25:10', NULL),
(569, 47, 9, 60, 0, 7, 1598773271, '2020-08-30 17:41:11', '2020-08-30 17:44:10', NULL),
(570, 47, 9, 60, 0, 8, 1598773271, '2020-08-30 17:52:11', '2020-08-30 17:55:10', NULL),
(571, 47, 9, 60, 0, 9, 1598773271, '2020-08-30 18:06:11', '2020-08-30 18:09:10', NULL),
(572, 47, 9, 60, 0, 10, 1598773271, '2020-08-30 18:27:11', '2020-08-30 18:30:10', NULL),
(573, 33, 9, 61, 0, 1, 1598777660, '2020-08-30 16:55:20', '2020-08-30 16:58:19', NULL),
(574, 33, 9, 61, 0, 2, 1598777660, '2020-08-30 17:28:20', '2020-08-30 17:31:19', NULL);
INSERT INTO `ws_pass_sign` (`id`, `uid`, `passId`, `joinId`, `status`, `number`, `createTime`, `signTimeBegin`, `signTimeEnd`, `signTime`) VALUES
(575, 33, 9, 61, 0, 3, 1598777660, '2020-08-30 17:38:20', '2020-08-30 17:41:19', NULL),
(576, 33, 9, 61, 0, 4, 1598777660, '2020-08-30 17:53:20', '2020-08-30 17:56:19', NULL),
(577, 33, 9, 61, 0, 5, 1598777660, '2020-08-30 18:07:20', '2020-08-30 18:10:19', NULL),
(578, 33, 9, 61, 0, 6, 1598777660, '2020-08-30 18:42:20', '2020-08-30 18:45:19', NULL),
(579, 33, 9, 61, 0, 7, 1598777660, '2020-08-30 18:50:20', '2020-08-30 18:53:19', NULL),
(580, 33, 9, 61, 0, 8, 1598777660, '2020-08-30 19:02:20', '2020-08-30 19:05:19', NULL),
(581, 33, 9, 61, 0, 9, 1598777660, '2020-08-30 19:24:20', '2020-08-30 19:27:19', NULL),
(582, 33, 9, 61, 0, 10, 1598777660, '2020-08-30 19:37:20', '2020-08-30 19:40:19', NULL),
(583, 33, 9, 62, 1, 1, 1598777932, '2020-08-30 17:07:52', '2020-08-30 17:10:51', '2020-08-30 17:08:23'),
(584, 33, 9, 62, 1, 2, 1598777932, '2020-08-30 17:22:52', '2020-08-30 17:25:51', '2020-08-30 17:22:58'),
(585, 33, 9, 62, 0, 3, 1598777932, '2020-08-30 17:48:52', '2020-08-30 17:51:51', NULL),
(586, 33, 9, 62, 0, 4, 1598777932, '2020-08-30 17:58:52', '2020-08-30 18:01:51', NULL),
(587, 33, 9, 62, 0, 5, 1598777932, '2020-08-30 18:27:52', '2020-08-30 18:30:51', NULL),
(588, 33, 9, 62, 0, 6, 1598777932, '2020-08-30 18:44:52', '2020-08-30 18:47:51', NULL),
(589, 33, 9, 62, 0, 7, 1598777932, '2020-08-30 18:59:52', '2020-08-30 19:02:51', NULL),
(590, 33, 9, 62, 0, 8, 1598777932, '2020-08-30 19:11:52', '2020-08-30 19:14:51', NULL),
(591, 33, 9, 62, 0, 9, 1598777932, '2020-08-30 19:37:52', '2020-08-30 19:40:51', NULL),
(592, 33, 9, 62, 0, 10, 1598777932, '2020-08-30 19:50:52', '2020-08-30 19:53:51', NULL),
(593, 36, 9, 63, 0, 1, 1598784778, '2020-08-30 19:04:58', '2020-08-30 19:07:57', NULL),
(594, 36, 9, 63, 0, 2, 1598784778, '2020-08-30 19:14:58', '2020-08-30 19:17:57', NULL),
(595, 36, 9, 63, 0, 3, 1598784778, '2020-08-30 19:34:58', '2020-08-30 19:37:57', NULL),
(596, 36, 9, 63, 0, 4, 1598784778, '2020-08-30 19:48:58', '2020-08-30 19:51:57', NULL),
(597, 36, 9, 63, 0, 5, 1598784778, '2020-08-30 20:08:58', '2020-08-30 20:11:57', NULL),
(598, 36, 9, 63, 0, 6, 1598784778, '2020-08-30 20:35:58', '2020-08-30 20:38:57', NULL),
(599, 36, 9, 63, 0, 7, 1598784778, '2020-08-30 20:52:58', '2020-08-30 20:55:57', NULL),
(600, 36, 9, 63, 0, 8, 1598784778, '2020-08-30 21:05:58', '2020-08-30 21:08:57', NULL),
(601, 36, 9, 63, 0, 9, 1598784778, '2020-08-30 21:17:58', '2020-08-30 21:20:57', NULL),
(602, 36, 9, 63, 0, 10, 1598784778, '2020-08-30 21:49:58', '2020-08-30 21:52:57', NULL),
(603, 47, 9, 64, 0, 1, 1598791950, '2020-08-30 21:00:30', '2020-08-30 21:03:29', NULL),
(604, 47, 9, 64, 0, 2, 1598791950, '2020-08-30 21:12:30', '2020-08-30 21:15:29', NULL),
(605, 47, 9, 64, 0, 3, 1598791950, '2020-08-30 21:41:30', '2020-08-30 21:44:29', NULL),
(606, 47, 9, 64, 0, 4, 1598791950, '2020-08-30 22:04:30', '2020-08-30 22:07:29', NULL),
(607, 47, 9, 64, 0, 5, 1598791950, '2020-08-30 22:09:30', '2020-08-30 22:12:29', NULL),
(608, 47, 9, 64, 0, 6, 1598791950, '2020-08-30 22:25:30', '2020-08-30 22:28:29', NULL),
(609, 47, 9, 64, 0, 7, 1598791950, '2020-08-30 22:55:30', '2020-08-30 22:58:29', NULL),
(610, 47, 9, 64, 0, 8, 1598791950, '2020-08-30 23:00:30', '2020-08-30 23:03:29', NULL),
(611, 47, 9, 64, 0, 9, 1598791950, '2020-08-30 23:21:30', '2020-08-30 23:24:29', NULL),
(612, 47, 9, 64, 0, 10, 1598791950, '2020-08-30 23:49:30', '2020-08-30 23:52:29', NULL),
(613, 37, 9, 65, 1, 1, 1598791974, '2020-08-30 21:07:54', '2020-08-30 21:10:53', '2020-08-30 21:08:02'),
(614, 37, 9, 65, 0, 2, 1598791974, '2020-08-30 21:24:54', '2020-08-30 21:27:53', NULL),
(615, 37, 9, 65, 0, 3, 1598791974, '2020-08-30 21:37:54', '2020-08-30 21:40:53', NULL),
(616, 37, 9, 65, 0, 4, 1598791974, '2020-08-30 21:58:54', '2020-08-30 22:01:53', NULL),
(617, 37, 9, 65, 0, 5, 1598791974, '2020-08-30 22:17:54', '2020-08-30 22:20:53', NULL),
(618, 37, 9, 65, 0, 6, 1598791974, '2020-08-30 22:31:54', '2020-08-30 22:34:53', NULL),
(619, 37, 9, 65, 0, 7, 1598791974, '2020-08-30 22:58:54', '2020-08-30 23:01:53', NULL),
(620, 37, 9, 65, 0, 8, 1598791974, '2020-08-30 23:15:54', '2020-08-30 23:18:53', NULL),
(621, 37, 9, 65, 0, 9, 1598791974, '2020-08-30 23:24:54', '2020-08-30 23:27:53', NULL),
(622, 37, 9, 65, 0, 10, 1598791974, '2020-08-30 23:43:54', '2020-08-30 23:46:53', NULL),
(623, 36, 9, 66, 0, 1, 1598792084, '2020-08-30 20:55:44', '2020-08-30 20:58:43', NULL),
(624, 36, 9, 66, 0, 2, 1598792084, '2020-08-30 21:16:44', '2020-08-30 21:19:43', NULL),
(625, 36, 9, 66, 0, 3, 1598792084, '2020-08-30 21:39:44', '2020-08-30 21:42:43', NULL),
(626, 36, 9, 66, 0, 4, 1598792084, '2020-08-30 22:03:44', '2020-08-30 22:06:43', NULL),
(627, 36, 9, 66, 0, 5, 1598792084, '2020-08-30 22:24:44', '2020-08-30 22:27:43', NULL),
(628, 36, 9, 66, 0, 6, 1598792084, '2020-08-30 22:25:44', '2020-08-30 22:28:43', NULL),
(629, 36, 9, 66, 0, 7, 1598792084, '2020-08-30 22:47:44', '2020-08-30 22:50:43', NULL),
(630, 36, 9, 66, 0, 8, 1598792084, '2020-08-30 23:18:44', '2020-08-30 23:21:43', NULL),
(631, 36, 9, 66, 0, 9, 1598792084, '2020-08-30 23:23:44', '2020-08-30 23:26:43', NULL),
(632, 36, 9, 66, 0, 10, 1598792084, '2020-08-30 23:39:44', '2020-08-30 23:42:43', NULL),
(633, 36, 9, 67, 0, 1, 1598792805, '2020-08-30 21:23:45', '2020-08-30 21:26:44', NULL),
(634, 36, 9, 67, 0, 2, 1598792805, '2020-08-30 21:33:45', '2020-08-30 21:36:44', NULL),
(635, 36, 9, 67, 0, 3, 1598792805, '2020-08-30 21:53:45', '2020-08-30 21:56:44', NULL),
(636, 36, 9, 67, 0, 4, 1598792805, '2020-08-30 22:02:45', '2020-08-30 22:05:44', NULL),
(637, 36, 9, 67, 0, 5, 1598792805, '2020-08-30 22:26:45', '2020-08-30 22:29:44', NULL),
(638, 36, 9, 67, 0, 6, 1598792805, '2020-08-30 22:37:45', '2020-08-30 22:40:44', NULL),
(639, 36, 9, 67, 0, 7, 1598792805, '2020-08-30 22:59:45', '2020-08-30 23:02:44', NULL),
(640, 36, 9, 67, 0, 8, 1598792805, '2020-08-30 23:30:45', '2020-08-30 23:33:44', NULL),
(641, 36, 9, 67, 0, 9, 1598792805, '2020-08-30 23:48:45', '2020-08-30 23:51:44', NULL),
(642, 36, 9, 67, 0, 10, 1598792805, '2020-08-30 23:52:45', '2020-08-30 23:55:44', NULL),
(643, 33, 12, 68, 0, 1, 1598809371, '2020-08-31 02:10:51', '2020-08-31 02:13:50', NULL),
(644, 47, 12, 69, 0, 1, 1598810734, '2020-08-31 02:34:34', '2020-08-31 02:37:33', NULL),
(645, 36, 12, 70, 1, 1, 1598832959, '2020-08-31 08:42:59', '2020-08-31 08:45:58', '2020-08-31 08:43:12'),
(646, 37, 12, 71, 1, 1, 1598833042, '2020-08-31 08:30:22', '2020-08-31 08:33:21', '2020-08-31 08:31:16'),
(647, 47, 12, 72, 0, 1, 1598843538, '2020-08-31 11:27:18', '2020-08-31 11:30:17', NULL),
(648, 33, 12, 73, 0, 1, 1598845753, '2020-08-31 12:10:13', '2020-08-31 12:13:12', NULL),
(649, 47, 12, 74, 0, 1, 1598856587, '2020-08-31 15:22:47', '2020-08-31 15:25:46', NULL),
(650, 33, 12, 75, 0, 1, 1598860455, '2020-08-31 16:17:15', '2020-08-31 16:20:14', NULL),
(651, 47, 13, 76, 0, 1, 1598888639, '2020-09-01 00:17:59', '2020-09-01 00:20:58', NULL),
(652, 33, 14, 77, 0, 1, 1598892639, '2020-09-01 01:12:39', '2020-09-01 01:15:38', NULL),
(653, 35, 14, 78, 0, 1, 1598894888, '2020-09-01 01:30:08', '2020-09-01 01:33:07', NULL),
(654, 35, 14, 79, 0, 1, 1598895220, '2020-09-01 01:41:40', '2020-09-01 01:44:39', NULL),
(655, 35, 14, 80, 0, 1, 1598895227, '2020-09-01 01:36:47', '2020-09-01 01:39:46', NULL),
(656, 35, 14, 81, 0, 1, 1598895240, '2020-09-01 01:41:00', '2020-09-01 01:43:59', NULL),
(657, 35, 14, 82, 0, 1, 1598895245, '2020-09-01 01:52:05', '2020-09-01 01:55:04', NULL),
(658, 47, 14, 83, 0, 1, 1598896174, '2020-09-01 02:25:34', '2020-09-01 02:28:33', NULL),
(659, 47, 14, 84, 0, 1, 1598896191, '2020-09-01 02:56:51', '2020-09-01 02:59:50', NULL),
(660, 47, 14, 85, 0, 1, 1598896197, '2020-09-01 03:13:57', '2020-09-01 03:16:56', NULL),
(661, 33, 14, 86, 0, 1, 1598925327, '2020-09-01 10:27:27', '2020-09-01 10:30:26', NULL),
(662, 47, 14, 87, 0, 1, 1598925550, '2020-09-01 10:02:10', '2020-09-01 10:05:09', NULL),
(663, 33, 14, 88, 0, 1, 1598930844, '2020-09-01 11:30:24', '2020-09-01 11:33:23', NULL),
(664, 33, 14, 89, 0, 1, 1598932099, '2020-09-01 12:55:19', '2020-09-01 12:58:18', NULL),
(665, 33, 15, 90, 0, 1, 1598932817, '2020-09-01 12:02:17', '2020-09-01 12:05:16', NULL),
(666, 48, 14, 91, 0, 1, 1598951883, '2020-09-01 18:25:03', '2020-09-01 18:28:02', NULL),
(667, 48, 16, 92, 0, 1, 1598952028, '2020-09-01 17:22:28', '2020-09-01 17:25:27', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ws_pass_time`
--

CREATE TABLE IF NOT EXISTS `ws_pass_time` (
  `id` int(11) NOT NULL,
  `passId` int(11) DEFAULT NULL COMMENT '活动id',
  `one` int(2) DEFAULT '5' COMMENT '第一轮签到分钟数  默认五分钟',
  `two` int(2) DEFAULT NULL COMMENT '第二轮',
  `three` int(2) DEFAULT NULL COMMENT '第三轮',
  `four` int(2) DEFAULT NULL COMMENT '第四轮',
  `five` int(2) DEFAULT NULL COMMENT '第五轮',
  `six` int(2) DEFAULT NULL COMMENT '第六轮',
  `seven` int(2) DEFAULT NULL COMMENT '第七轮',
  `night` int(2) DEFAULT NULL COMMENT '第九轮',
  `eight` int(2) DEFAULT NULL COMMENT '第八轮',
  `ten` int(2) DEFAULT NULL COMMENT '第十轮',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='闯关活动打卡时间';

--
-- 转存表中的数据 `ws_pass_time`
--

INSERT INTO `ws_pass_time` (`id`, `passId`, `one`, `two`, `three`, `four`, `five`, `six`, `seven`, `night`, `eight`, `ten`, `createTime`) VALUES
(1, 3, 1, 2, 3, 4, 5, 6, 7, 9, 8, 3, 1597480024),
(2, 4, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1597480467),
(3, 5, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1597763160),
(4, 6, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1598535788),
(5, 19, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1598636760),
(6, 22, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1598643855),
(7, 7, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1598644138),
(8, 8, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1598678198),
(9, 9, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1598678198),
(10, 11, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1598806720),
(11, 12, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1598809349),
(12, 13, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1598888531),
(13, 14, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1598891208),
(14, 15, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1598932746),
(15, 16, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1598952016);

-- --------------------------------------------------------

--
-- 表的结构 `ws_room_create`
--

CREATE TABLE IF NOT EXISTS `ws_room_create` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '2' COMMENT '1-保底房间 2-普通房间',
  `sign` tinyint(1) DEFAULT NULL COMMENT '签到方式 1-一键签到 2-发圈签到',
  `desc` varchar(255) DEFAULT NULL COMMENT '活动描述',
  `money` int(11) DEFAULT NULL COMMENT '报名金额',
  `number` int(11) DEFAULT '0' COMMENT '活动人数  0-不限制',
  `beginTime` int(11) DEFAULT NULL COMMENT '活动开始时间(首次开始签到时间戳)',
  `day` int(11) DEFAULT '1' COMMENT '活动周期 天',
  `signBegin` int(11) DEFAULT NULL COMMENT '首次签到开始时间 分钟',
  `signEnd` int(11) DEFAULT NULL COMMENT '首次签到结束时间',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `name` varchar(70) DEFAULT NULL COMMENT '房间名',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 0-报名中   1-活动中 2-活动结束',
  `signNum` tinyint(1) DEFAULT '1' COMMENT '签到次数  最多两次',
  `secondBegin` int(11) DEFAULT NULL COMMENT '第二次签到开始时间  分钟',
  `secondEnd` int(11) DEFAULT NULL COMMENT '第二次签到结束时间',
  `beginTimeStr` varchar(10) DEFAULT NULL COMMENT '首次签到开始时间',
  `endTimeStr` varchar(10) DEFAULT NULL COMMENT '首次签到结束时间',
  `pattern` tinyint(1) DEFAULT '1' COMMENT '项目模式  1-每日奖励金瓜分 2-平分模式',
  `secondBeginStr` varchar(10) DEFAULT NULL COMMENT '二次签到开始时间',
  `secondEndStr` varchar(10) DEFAULT NULL COMMENT '二次签到结束时间',
  `beginDate` varchar(10) DEFAULT NULL COMMENT '开始时间日期'
) ENGINE=MyISAM AUTO_INCREMENT=10014 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='房间活动-用户发起';

--
-- 转存表中的数据 `ws_room_create`
--

INSERT INTO `ws_room_create` (`id`, `uid`, `type`, `sign`, `desc`, `money`, `number`, `beginTime`, `day`, `signBegin`, `signEnd`, `createTime`, `name`, `status`, `signNum`, `secondBegin`, `secondEnd`, `beginTimeStr`, `endTimeStr`, `pattern`, `secondBeginStr`, `secondEndStr`, `beginDate`) VALUES
(10000, 29, 1, 1, 'zhe shi yi ge ce shi', 1, 2, 1597114800, 1, 660, 720, 1596532430, 'ceshi', 2, 1, NULL, NULL, '11:00', '12:00', 1, NULL, NULL, '2020-08-11'),
(10001, 29, 1, 1, 'zhe shi yi ge ce shi', 1, 3, 1596536586, 1, 660, 720, 1596532552, 'ceshi2', 1, 2, 780, 1200, '11:00', '12:00', 1, '13:00', '20:00', '2020-08-04'),
(10002, 33, 2, 1, '坚持到底就是胜利，努力就能获得收益', 10, 1000, 1597708800, 10, 480, 542, 1597655492, '挑战', 2, 1, NULL, NULL, '08:00', '09:02', 1, NULL, NULL, '2020/8/18'),
(10003, 33, 2, 1, '坚持到底就是胜利，努力就能获得收益', 100, 500, 1597705200, 10, 420, 660, 1597655957, '挑战3', 2, 2, 1020, 1140, '07:00', '11:00', 1, '17:00', '19:00', '2020/8/18'),
(10004, 33, 2, 1, '坚持到底就是胜利，努力就能获得收益', 555, 1000, 1597798800, 99, 540, 780, 1597758569, '挑战4', 1, 1, NULL, NULL, '09:00', '13:00', 1, NULL, NULL, '2020/8/19'),
(10005, 33, 2, 1, 'sdhdfhfkfkmkmkmkm', 20, 500, 1597878000, 10, 420, 540, 1597759041, '挑战6', 2, 1, NULL, NULL, '07:00', '09:00', 1, NULL, NULL, '2020/8/20'),
(10006, 33, 2, 1, '坚持到底就是胜利，努力就能获得收益', 10, 10, 1597791600, 1, 420, 420, 1597759476, '挑战8', 2, 1, NULL, NULL, '07:00', '07:00', 1, NULL, NULL, '2020/8/19'),
(10007, 33, 2, 1, '坚持到底就是胜利，努力就能获得收益', 10, 10, 1598161020, 10, 817, 1020, 1598161001, '今日打卡', 1, 1, NULL, NULL, '13:37', '17:00', 1, NULL, NULL, '2020/8/23'),
(10008, 33, 2, 1, '坚持到底就是胜利，努力就能获得收益', 100, 10, 1598482800, 10, 420, 540, 1598421766, '11111', 1, 1, NULL, NULL, '07:00', '09:00', 1, NULL, NULL, '2020/8/27'),
(10009, 35, 2, 1, '坚持到底就是胜利，努力就能获得收益', 30, 20, 1598569200, 2, 420, 1440, 1598539431, '测试1', 2, 1, NULL, NULL, '07:00', '24:00', 1, NULL, NULL, '2020/8/28'),
(10010, 41, 2, 1, '坚持到底就是胜利，努力就能获得收益', 11, 11, 1598655600, 11, 420, 422, 1598541418, '11', 1, 1, NULL, NULL, '07:00', '07:02', 1, NULL, NULL, '2020/8/29'),
(10011, 37, 1, 1, '坚持到底就是胜利，努力就能获得收益', 20, 3, 1598828400, 7, 420, 480, 1598737209, '黑色', 1, 1, NULL, NULL, '07:00', '08:00', 1, NULL, NULL, '2020/8/31'),
(10012, 33, 2, 1, '坚持到底就是胜利，努力就能获得收益', 100, 1222, 1598800560, 10, 1396, 1433, 1598800544, '挑战333', 1, 1, NULL, NULL, '23:16', '23:53', 1, NULL, NULL, '2020/8/30'),
(10013, 33, 2, 1, '坚持到底就是胜利，努力就能获得收益', 100, 10, 1598802900, 20, 1435, 1439, 1598802802, '测试12344', 1, 1, NULL, NULL, '23:55', '23:59', 1, NULL, NULL, '2020/8/30');

-- --------------------------------------------------------

--
-- 表的结构 `ws_room_join`
--

CREATE TABLE IF NOT EXISTS `ws_room_join` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '报名者uid',
  `roomId` int(11) DEFAULT NULL COMMENT '房间号id',
  `createTime` int(11) DEFAULT NULL COMMENT '报名时间',
  `type` tinyint(1) DEFAULT '1' COMMENT '1-房间挑战',
  `status` tinyint(1) DEFAULT '1' COMMENT '1-参与中 2-已失败 3-已完成'
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='房间挑战报名记录';

--
-- 转存表中的数据 `ws_room_join`
--

INSERT INTO `ws_room_join` (`id`, `uid`, `roomId`, `createTime`, `type`, `status`) VALUES
(1, 29, 10000, 1596532430, 1, 1),
(2, 21, 10001, 1596532552, 1, 1),
(3, 21, 10001, 1596535268, 1, 1),
(4, 29, 10001, 1596535746, 1, 1),
(5, 33, 10002, 1597655492, 1, 1),
(6, 33, 10003, 1597655957, 1, 1),
(7, 33, 10004, 1597758569, 1, 1),
(8, 33, 10005, 1597759041, 1, 1),
(9, 33, 10006, 1597759476, 1, 1),
(10, 33, 10007, 1598161001, 1, 1),
(11, 33, 10008, 1598421766, 1, 1),
(12, 35, 10009, 1598539431, 1, 1),
(13, 41, 10010, 1598541418, 1, 1),
(14, 37, 10011, 1598737209, 1, 1),
(15, 33, 10011, 1598798114, 1, 1),
(16, 33, 10012, 1598800544, 1, 1),
(17, 33, 10013, 1598802802, 1, 1),
(18, 47, 10011, 1598810791, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ws_room_record`
--

CREATE TABLE IF NOT EXISTS `ws_room_record` (
  `id` int(11) NOT NULL,
  `roomId` int(11) DEFAULT NULL COMMENT '房间id',
  `date` varchar(11) DEFAULT NULL COMMENT '统计日期',
  `signSuccess` int(11) DEFAULT NULL COMMENT '今日打卡人数',
  `signFail` int(11) DEFAULT NULL COMMENT '今天打卡失败人数',
  `failMoney` decimal(10,2) DEFAULT NULL COMMENT '失败金金额今日',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `rewardMoney` decimal(10,2) DEFAULT NULL COMMENT '每人的奖励金额',
  `finish` tinyint(1) DEFAULT '0' COMMENT '活动结束 0-未结束 1-已结束',
  `finishNum` int(11) DEFAULT '0' COMMENT '完成挑战人数',
  `roomBegin` varchar(11) DEFAULT NULL COMMENT '房间挑战开始时间',
  `successUser` text COMMENT '打卡用户uid集合',
  `failUser` text COMMENT '打卡失败用户uid集合',
  `finishUser` text COMMENT '完成挑战用户uid集合'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `ws_room_reward`
--

CREATE TABLE IF NOT EXISTS `ws_room_reward` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `roomId` int(11) DEFAULT NULL COMMENT '打卡活动id',
  `date` char(11) DEFAULT NULL COMMENT '日期',
  `money` decimal(10,2) DEFAULT NULL COMMENT '奖励金额',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `joinId` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='房间挑战收益记录表';

-- --------------------------------------------------------

--
-- 表的结构 `ws_room_type`
--

CREATE TABLE IF NOT EXISTS `ws_room_type` (
  `id` int(11) NOT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1-普通房间 2-保底房间',
  `percent` varchar(20) DEFAULT NULL COMMENT '奖励金额 百分比',
  `rule` text COMMENT '跳转规则',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `maxMoney` decimal(10,2) DEFAULT NULL COMMENT '金额上限',
  `minMoney` decimal(10,2) DEFAULT NULL COMMENT '金额下限'
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='房间类型';

--
-- 转存表中的数据 `ws_room_type`
--

INSERT INTO `ws_room_type` (`id`, `type`, `percent`, `rule`, `createTime`, `maxMoney`, `minMoney`) VALUES
(5, 2, '1', '程序池 传传传 ', 1596450589, '600.00', '5.00'),
(4, 1, '1', '的深V从出发  保底', 1596450583, '123.00', '1.00');

-- --------------------------------------------------------

--
-- 表的结构 `ws_share_reward`
--

CREATE TABLE IF NOT EXISTS `ws_share_reward` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '奖励用户id',
  `shareUid` int(11) DEFAULT NULL COMMENT '邀请用户uid',
  `type` tinyint(1) DEFAULT NULL COMMENT '1-打卡 2-房间挑战  3-闯关 ',
  `money` decimal(10,2) DEFAULT NULL COMMENT '奖励金额',
  `parentUid` int(11) DEFAULT NULL COMMENT '邀请用户的邀请人id',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `objectId` int(11) DEFAULT NULL COMMENT '参加的活动id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- 表的结构 `ws_sign`
--

CREATE TABLE IF NOT EXISTS `ws_sign` (
  `id` int(11) NOT NULL,
  `roomId` int(11) DEFAULT NULL COMMENT '房间id',
  `date` char(10) DEFAULT NULL COMMENT '签到日志',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `firstSign` tinyint(1) DEFAULT '0' COMMENT '0-未签到 1-已签到 第一次签到',
  `firstSignTime` datetime DEFAULT NULL COMMENT '第一次签到时间',
  `secondSign` tinyint(1) DEFAULT NULL COMMENT '二次签到 0-未签到 1-已签到',
  `secondSignTime` datetime DEFAULT NULL COMMENT '二次签到时间',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updateTime` int(11) DEFAULT NULL COMMENT '更新时间',
  `type` tinyint(1) DEFAULT '1' COMMENT '1-房间挑战'
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='用户签到表';

--
-- 转存表中的数据 `ws_sign`
--

INSERT INTO `ws_sign` (`id`, `roomId`, `date`, `uid`, `firstSign`, `firstSignTime`, `secondSign`, `secondSignTime`, `createTime`, `updateTime`, `type`) VALUES
(7, 10001, '2020-08-04', 29, 1, NULL, 1, '2020-08-04 18:46:01', 1596537905, NULL, 1),
(8, 10012, '2020-08-30', 33, 1, '2020-08-30 23:23:47', NULL, NULL, 1598801027, NULL, 1),
(9, 10013, '2020-08-30', 33, 1, '2020-08-30 23:55:08', NULL, NULL, 1598802908, NULL, 1),
(10, 10013, '2020-08-31', 33, 0, NULL, NULL, NULL, 1598806093, NULL, 1),
(11, 10008, '2020-08-31', 33, 0, NULL, NULL, NULL, 1598806097, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ws_system`
--

CREATE TABLE IF NOT EXISTS `ws_system` (
  `id` int(11) NOT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1-关于我们 2-服务协议 3-隐私政策 4-版本升级 5-招聘发布费用 6-取消用车违约金  7-每公里价格',
  `content` text COMMENT '内容',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `title` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统设置';

--
-- 转存表中的数据 `ws_system`
--

INSERT INTO `ws_system` (`id`, `type`, `content`, `createTime`, `title`) VALUES
(1, 1, '关于我们', 1596015905, ''),
(2, 2, '帮助中心', 1598953825, '帮助中心'),
(3, 3, '隐私政策32222大V从VC', 1596505832, ''),
(4, 3, '升级', 1592616561, ''),
(5, 4, '说的都是', 1594464872, ''),
(6, 7, '1.1', 1594876253, ''),
(7, 8, '123', 1595649846, ''),
(9, 2, 'NICI测出', 1598953835, '你猜');

-- --------------------------------------------------------

--
-- 表的结构 `ws_user_money_record`
--

CREATE TABLE IF NOT EXISTS `ws_user_money_record` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1',
  `createTime` int(11) DEFAULT NULL,
  `moneyType` tinyint(1) DEFAULT '0' COMMENT '0-充值 1-打卡 2-房间挑战 3-闯关'
) ENGINE=MyISAM AUTO_INCREMENT=277 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户金额记录';

--
-- 转存表中的数据 `ws_user_money_record`
--

INSERT INTO `ws_user_money_record` (`id`, `uid`, `money`, `remark`, `type`, `createTime`, `moneyType`) VALUES
(48, 29, '1.00', '打卡活动本金退还', 1, 1596702362, 0),
(47, 29, '0.01', '打卡活动每日奖励', 1, 1596702362, 0),
(46, 29, '1.00', '参与房间挑战支付挑战费用', 2, 1596535746, 0),
(45, 29, '1.00', '创建房间支付挑战费用', 2, 1596535268, 0),
(44, 29, '1.00', '创建房间支付挑战费用', 2, 1596532552, 0),
(43, 29, '1.00', '创建房间支付挑战费用', 2, 1596532430, 0),
(42, 29, '1.00', '创建房间支付挑战费用', 2, 1596528416, 0),
(49, 29, '12.00', '闯关报名费扣除', 2, 1597485160, 3),
(50, 29, '12.00', '闯关报名费扣除', 2, 1597485275, 3),
(51, 29, '1.00', '闯关奖励发送', 1, 1597486346, 3),
(52, 29, '12.00', '闯关本金退还', 1, 1597486346, 3),
(53, 29, '12.00', '闯关报名费扣除', 2, 1597486411, 3),
(54, 33, '10.00', '参与房间挑战支付挑战费用', 2, 1597655492, 2),
(55, 33, '100.00', '参与房间挑战支付挑战费用', 2, 1597655957, 2),
(56, 33, '555.00', '参与房间挑战支付挑战费用', 2, 1597758569, 2),
(57, 33, '20.00', '参与房间挑战支付挑战费用', 2, 1597759041, 2),
(58, 33, '10.00', '参与房间挑战支付挑战费用', 2, 1597759476, 2),
(59, 33, '10.00', '参与房间挑战支付挑战费用', 2, 1598161001, 2),
(60, 33, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598260245, 3),
(61, 33, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598270639, 3),
(62, 33, '1.00', '闯关报名费扣除-发布', 2, 1598271180, 3),
(63, 33, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598277324, 3),
(64, 33, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598278118, 3),
(65, 33, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598280508, 3),
(66, 33, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598282819, 3),
(67, 33, '100.00', '参与房间挑战支付挑战费用-11111', 2, 1598421766, 2),
(68, 36, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598507840, 3),
(69, 36, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598509363, 3),
(70, 36, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598510062, 3),
(71, 36, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598510788, 3),
(72, 36, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598511901, 3),
(73, 36, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598515841, 3),
(74, 36, '12.00', '闯关报名费扣除-闯关挑战', 2, 1598517396, 3),
(75, 36, '1.00', '闯关报名费扣除-发布', 2, 1598517414, 3),
(76, 36, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598529032, 3),
(77, 43, '2.00', '闯关报名费扣除-一次闯关', 2, 1598536047, 3),
(78, 35, '30.00', '参与房间挑战支付挑战费用-测试1', 2, 1598539431, 2),
(79, 41, '11.00', '参与房间挑战支付挑战费用-11', 2, 1598541418, 2),
(80, 33, '6.00', '闯关报名费扣除-一次闯关', 2, 1598560154, 3),
(81, 33, '3.00', '闯关报名费扣除-发布', 2, 1598560247, 3),
(82, 36, '5.00', '闯关报名费扣除-闯关挑战2', 2, 1598582323, 3),
(83, 36, '7.00', '闯关报名费扣除-闯关挑战2', 2, 1598583783, 3),
(84, 36, '7.00', '闯关报名费扣除-闯关挑战2', 2, 1598587343, 3),
(85, 36, '7.00', '闯关报名费扣除-闯关挑战2', 2, 1598600244, 3),
(86, 33, '20.00', '闯关报名费扣除-斑马挑战测试', 2, 1598640497, 3),
(87, 33, '10.00', '闯关报名费扣除-斑马挑战测试', 2, 1598640734, 3),
(88, 33, '10.00', '闯关报名费扣除-斑马挑战测试', 2, 1598641340, 3),
(89, 33, '10.00', '闯关报名费扣除-斑马挑战测试', 2, 1598641425, 3),
(90, 33, '10.00', '闯关报名费扣除-斑马挑战测试', 2, 1598641856, 3),
(91, 33, '10.00', '闯关报名费扣除-斑马挑战测试', 2, 1598642478, 3),
(92, 33, '10.00', '闯关报名费扣除-斑马挑战测试', 2, 1598643165, 3),
(93, 33, '10.00', '闯关报名费扣除-斑马挑战测试', 2, 1598643768, 3),
(94, 33, '1.00', '闯关报名费扣除-斑马挑战测试9999', 2, 1598643866, 3),
(95, 33, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598644185, 3),
(96, 35, '0.01', '余额充值-支付宝', 1, 1598647049, 0),
(97, 35, '0.01', '余额充值-支付宝', 1, 1598647237, 0),
(98, 35, '0.01', '余额充值-支付宝', 1, 1598647664, 0),
(99, 36, '30.00', '闯关报名费扣除-斑马挑战测试', 2, 1598651412, 3),
(100, 36, '30.00', '闯关报名费扣除-斑马挑战测试', 2, 1598652231, 3),
(101, 36, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598662456, 3),
(102, 36, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598662703, 3),
(103, 36, '20.00', '闯关报名费扣除-斑马挑战测试9999', 2, 1598662736, 3),
(104, 36, '30.00', '闯关报名费扣除-斑马挑战测试', 2, 1598662749, 3),
(105, 36, '20.00', '闯关报名费扣除-斑马挑战测试9999', 2, 1598663583, 3),
(106, 35, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598677800, 3),
(107, 35, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598679993, 3),
(108, 36, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598686736, 3),
(109, 37, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598686787, 3),
(110, 37, '30.00', '闯关报名费扣除-斑马挑战测试', 2, 1598686896, 3),
(111, 33, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598728094, 3),
(112, 37, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598736823, 3),
(113, 37, '20.00', '参与房间挑战支付挑战费用-黑色', 2, 1598737209, 2),
(114, 37, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598739774, 3),
(115, 37, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598740441, 3),
(116, 37, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598742892, 3),
(117, 37, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598745806, 3),
(118, 36, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598758413, 3),
(119, 37, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598758489, 3),
(120, 36, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598760573, 3),
(121, 47, '1.00', '余额充值-支付宝', 1, 1598764655, 0),
(122, 47, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598765492, 3),
(123, 36, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598769966, 3),
(124, 47, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598773271, 3),
(125, 33, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598777660, 3),
(126, 33, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598777932, 3),
(127, 36, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598784778, 3),
(128, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598788901, 1),
(129, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598788901, 1),
(130, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598788947, 1),
(131, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598788947, 1),
(132, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598789083, 1),
(133, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598789083, 1),
(134, 33, '0.10', '打卡活动每日奖励-打卡测试', 1, 1598789375, 1),
(135, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598790926, 1),
(136, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598790926, 1),
(137, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598790976, 1),
(138, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598790976, 1),
(139, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598791019, 1),
(140, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598791019, 1),
(141, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598791086, 1),
(142, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598791086, 1),
(143, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598791413, 1),
(144, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598791413, 1),
(145, 47, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598791950, 3),
(146, 37, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598791974, 3),
(147, 36, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598792084, 3),
(148, 36, '10.00', '闯关报名费扣除-斑马挑战测试2222222', 2, 1598792805, 3),
(149, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598797290, 1),
(150, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598797290, 1),
(151, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598797695, 1),
(152, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598797695, 1),
(153, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598797947, 1),
(154, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598797947, 1),
(155, 33, '20.00', '参与房间挑战支付挑战费用-黑色', 2, 1598798114, 2),
(156, 33, '100.00', '参与房间挑战支付挑战费用-挑战333', 2, 1598800544, 2),
(157, 33, '100.00', '参与房间挑战支付挑战费用-测试12344', 2, 1598802802, 2),
(158, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598806404, 1),
(159, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598806404, 1),
(160, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598806429, 1),
(161, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598806429, 1),
(162, 33, '1.00', '闯关报名费扣除-斑马挑战测试3333333', 2, 1598809371, 3),
(163, 47, '10.00', '闯关报名费扣除-斑马挑战测试3333333', 2, 1598810734, 3),
(164, 47, '20.00', '参与房间挑战支付挑战费用-黑色', 2, 1598810791, 2),
(165, 36, '10.00', '闯关报名费扣除-斑马挑战测试3333333', 2, 1598832959, 3),
(166, 37, '10.00', '闯关报名费扣除-斑马挑战测试3333333', 2, 1598833042, 3),
(167, 47, '10.00', '闯关报名费扣除-斑马挑战测试3333333', 2, 1598843538, 3),
(168, 33, '1.00', '闯关报名费扣除-斑马挑战测试3333333', 2, 1598845753, 3),
(169, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598845919, 1),
(170, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598845919, 1),
(171, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598846153, 1),
(172, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598846153, 1),
(173, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598849282, 1),
(174, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598849282, 1),
(175, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598852792, 1),
(176, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598852792, 1),
(177, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598852900, 1),
(178, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598852900, 1),
(179, 33, '0.01', '打卡活动每日奖励-打卡获取', 1, 1598854297, 1),
(180, 33, '1.00', '打卡活动本金退还-打卡获取', 1, 1598854297, 1),
(181, 47, '1.00', '闯关报名费扣除-斑马挑战测试3333333', 2, 1598856587, 3),
(182, 33, '1.00', '闯关报名费扣除-斑马挑战测试3333333', 2, 1598860455, 3),
(183, 29, '0.10', '打卡活动每日奖励-测试', 1, 1598861300, 1),
(184, 47, '10.00', '打卡活动每日奖励-测试0906', 1, 1598863220, 1),
(185, 47, '10.00', '打卡活动本金退还-测试0906', 1, 1598863220, 1),
(186, 47, '10.00', '打卡活动每日奖励-测试0906', 1, 1598863254, 1),
(187, 47, '10.00', '打卡活动本金退还-测试0906', 1, 1598863254, 1),
(188, 47, '10.00', '打卡活动每日奖励-测试0906', 1, 1598863259, 1),
(189, 47, '10.00', '打卡活动本金退还-测试0906', 1, 1598863259, 1),
(190, 47, '10.00', '打卡活动每日奖励-测试0906', 1, 1598863275, 1),
(191, 47, '10.00', '打卡活动本金退还-测试0906', 1, 1598863275, 1),
(192, 47, '10.00', '打卡活动每日奖励-测试0906', 1, 1598863336, 1),
(193, 47, '10.00', '打卡活动本金退还-测试0906', 1, 1598863336, 1),
(194, 47, '10.00', '打卡活动每日奖励-测试0906', 1, 1598863374, 1),
(195, 47, '10.00', '打卡活动本金退还-测试0906', 1, 1598863374, 1),
(196, 36, '10.00', '打卡活动每日奖励-晚睡挑战', 1, 1598885407, 1),
(197, 36, '10.00', '打卡活动每日奖励-晚睡挑战', 1, 1598885418, 1),
(198, 36, '10.00', '打卡活动本金退还-晚睡挑战', 1, 1598885418, 1),
(199, 36, '10.00', '打卡活动每日奖励-晚睡挑战', 1, 1598885421, 1),
(200, 33, '10.00', '打卡活动每日奖励-banma2222', 1, 1598886496, 1),
(201, 33, '10.00', '打卡活动每日奖励-banma3333', 1, 1598886726, 1),
(202, 47, '10.00', '打卡活动每日奖励-banma3333', 1, 1598886729, 1),
(203, 47, '10.00', '打卡活动本金退还-banma3333', 1, 1598886729, 1),
(204, 47, '10.00', '打卡活动每日奖励-banma3333', 1, 1598886734, 1),
(205, 47, '10.00', '打卡活动每日奖励-banma3333', 1, 1598886735, 1),
(206, 47, '10.00', '打卡活动本金退还-banma3333', 1, 1598886735, 1),
(207, 33, '1.00', '打卡活动报名费扣除-斑马挑战1111', 2, 1598887379, 1),
(208, 33, '10.00', '打卡活动报名费扣除-banma3333', 2, 1598888161, 1),
(209, 33, '10.00', '打卡活动报名费扣除-banma4444', 2, 1598888249, 1),
(210, 33, '10.00', '打卡活动每日奖励-banma4444', 1, 1598888284, 1),
(211, 33, '10.00', '打卡活动每日奖励-banma4444', 1, 1598888285, 1),
(212, 33, '10.00', '打卡活动本金退还-banma4444', 1, 1598888285, 1),
(213, 33, '10.00', '打卡活动报名费扣除-banma4444', 2, 1598888361, 1),
(214, 33, '1.00', '打卡活动报名费扣除-斑马挑战测试123', 2, 1598888600, 1),
(215, 47, '10.00', '闯关报名费扣除-按摩19522', 2, 1598888639, 3),
(216, 33, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598892639, 3),
(217, 35, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598894888, 3),
(218, 35, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598895220, 3),
(219, 35, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598895227, 3),
(220, 35, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598895240, 3),
(221, 35, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598895245, 3),
(222, 47, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598896174, 3),
(223, 47, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598896191, 3),
(224, 47, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598896197, 3),
(225, 33, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598925327, 3),
(226, 47, '20.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598925550, 3),
(227, 33, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598930844, 3),
(228, 36, '10.00', '打卡活动报名费扣除-banma4444', 2, 1598931675, 1),
(229, 36, '10.00', '打卡活动报名费扣除-banma3333', 2, 1598931698, 1),
(230, 33, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598932099, 3),
(231, 33, '10.00', '闯关报名费扣除-banma123', 2, 1598932817, 3),
(232, 33, '0.10', '打卡活动每日奖励-打卡测试', 1, 1598936280, 1),
(233, 33, '2.00', '打卡活动报名费扣除-打卡1', 2, 1598936673, 1),
(234, 33, '10.00', '打卡活动报名费扣除-ceshi10000', 2, 1598936707, 1),
(235, 33, '10.00', '打卡活动每日奖励-斑马5', 1, 1598937002, 1),
(236, 33, '10.00', '打卡活动本金退还-斑马5', 1, 1598937002, 1),
(237, 33, '10.00', '打卡活动每日奖励-斑马5', 1, 1598937019, 1),
(238, 33, '10.00', '打卡活动本金退还-斑马5', 1, 1598937019, 1),
(239, 33, '1.00', '打卡活动报名费扣除-banma6666666', 2, 1598937152, 1),
(240, 33, '10.00', '打卡活动报名费扣除-banma7777', 2, 1598937327, 1),
(241, 33, '10.00', '打卡活动每日奖励-banma7777', 1, 1598937365, 1),
(242, 33, '10.00', '打卡活动本金退还-banma7777', 1, 1598937365, 1),
(243, 47, '10.00', '打卡活动报名费扣除-banma7777', 2, 1598937584, 1),
(244, 47, '10.00', '打卡活动报名费扣除-测试1点', 2, 1598937770, 1),
(245, 36, '10.00', '打卡活动报名费扣除-测试1点', 2, 1598938096, 1),
(246, 47, '10.00', '打卡活动每日奖励-测试1点', 1, 1598938206, 1),
(247, 48, '12.00', '打卡活动报名费扣除-打卡测试23', 2, 1598944498, 1),
(248, 48, '1.20', '打卡活动每日奖励-打卡测试23', 1, 1598944691, 1),
(249, 48, '1.00', '打卡活动报名费扣除-测试12', 2, 1598945187, 1),
(250, 48, '0.10', '打卡活动每日奖励-测试12', 1, 1598945223, 1),
(251, 33, '10.00', '打卡活动报名费扣除-banma88888', 2, 1598946830, 1),
(252, 48, '12.00', '打卡活动报名费扣除-测试22', 2, 1598947912, 1),
(253, 48, '12.00', '打卡活动报名费扣除-测试22', 2, 1598948637, 1),
(254, 48, '12.00', '打卡活动报名费扣除-测试111', 2, 1598948875, 1),
(255, 48, '12.00', '打卡活动报名费扣除-测试111', 2, 1598949788, 1),
(256, 33, '12.00', '打卡活动报名费扣除-三点打卡', 2, 1598949962, 1),
(257, 33, '10.00', '打卡活动报名费扣除-banma9999', 2, 1598950078, 1),
(258, 33, '10.00', '打卡活动每日奖励-banma9999', 1, 1598950143, 1),
(259, 33, '10.00', '打卡活动本金退还-banma9999', 1, 1598950144, 1),
(260, 33, '10.00', '打卡活动报名费扣除-banma1000', 2, 1598950725, 1),
(261, 33, '10.00', '打卡活动报名费扣除-banma1000', 2, 1598950865, 1),
(262, 33, '10.00', '打卡活动报名费扣除-banm1001', 2, 1598950934, 1),
(263, 33, '10.00', '打卡活动每日奖励-banm1001', 1, 1598950982, 1),
(264, 33, '10.00', '打卡活动本金退还-banm1001', 1, 1598950982, 1),
(265, 33, '10.00', '打卡活动报名费扣除-banma1005', 2, 1598951315, 1),
(266, 33, '10.00', '打卡活动每日奖励-banma1005', 1, 1598951341, 1),
(267, 33, '10.00', '打卡活动本金退还-banma1005', 1, 1598951341, 1),
(268, 48, '10.00', '闯关报名费扣除-斑马挑战测试1111', 2, 1598951883, 3),
(269, 48, '8.00', '闯关报名费扣除-闯关挑战（6分钟两轮）', 2, 1598952028, 3),
(270, 33, '10.00', '打卡活动报名费扣除-banma1007', 2, 1598952302, 1),
(271, 33, '10.00', '打卡活动每日奖励-banma1007', 1, 1598952363, 1),
(272, 33, '10.00', '打卡活动本金退还-banma1007', 1, 1598952363, 1),
(273, 33, '10.00', '打卡活动报名费扣除-banma1008', 2, 1598952595, 1),
(274, 33, '10.00', '打卡活动每日奖励-banma1008', 1, 1598952696, 1),
(275, 33, '10.00', '打卡活动本金退还-banma1008', 1, 1598952696, 1),
(276, 33, '10.00', '打卡活动报名费扣除-banma1008', 2, 1598952732, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ws_user_return`
--

CREATE TABLE IF NOT EXISTS `ws_user_return` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL COMMENT '提现金额',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 0-提现中 1-已提现',
  `createTime` int(11) DEFAULT NULL COMMENT '申请时间',
  `returnTime` int(11) DEFAULT NULL COMMENT '提现时间',
  `procedures` decimal(10,2) DEFAULT '0.00' COMMENT '手续费',
  `type` tinyint(1) DEFAULT NULL COMMENT '提现渠道 1-微信 2-支付宝',
  `phone` char(12) DEFAULT NULL COMMENT '提现手机号',
  `orderNo` varchar(30) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='用户提现';

--
-- 转存表中的数据 `ws_user_return`
--

INSERT INTO `ws_user_return` (`id`, `uid`, `money`, `status`, `createTime`, `returnTime`, `procedures`, `type`, `phone`, `orderNo`) VALUES
(1, 29, '12.00', 0, 1598240923, NULL, '0.00', 1, '', ''),
(2, 33, '200.00', 0, 1598457519, NULL, '0.00', 1, '', ''),
(3, 33, '200.00', 0, 1598457558, NULL, '0.00', 1, '', ''),
(4, 33, '200.00', 0, 1598457593, NULL, '0.00', 1, '', ''),
(5, 33, '200.00', 0, 1598457606, NULL, '0.00', 1, '', ''),
(6, 33, '200.00', 0, 1598457646, NULL, '0.00', 2, '1511714390', ''),
(7, 33, '200.00', 0, 1598460816, NULL, '0.00', 1, '', ''),
(8, 33, '200.00', 0, 1598460935, NULL, '0.00', 1, '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ws_admins`
--
ALTER TABLE `ws_admins`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_clock_in`
--
ALTER TABLE `ws_clock_in`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_clock_in_join`
--
ALTER TABLE `ws_clock_in_join`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_clock_in_price`
--
ALTER TABLE `ws_clock_in_price`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_clock_in_sign`
--
ALTER TABLE `ws_clock_in_sign`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_clock_reward`
--
ALTER TABLE `ws_clock_reward`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_member`
--
ALTER TABLE `ws_member`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_money_get`
--
ALTER TABLE `ws_money_get`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_money_recharge`
--
ALTER TABLE `ws_money_recharge`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_pass`
--
ALTER TABLE `ws_pass`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_pass_join`
--
ALTER TABLE `ws_pass_join`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_pass_price`
--
ALTER TABLE `ws_pass_price`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_pass_reward`
--
ALTER TABLE `ws_pass_reward`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_pass_sign`
--
ALTER TABLE `ws_pass_sign`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_pass_time`
--
ALTER TABLE `ws_pass_time`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_room_create`
--
ALTER TABLE `ws_room_create`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_room_join`
--
ALTER TABLE `ws_room_join`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_room_record`
--
ALTER TABLE `ws_room_record`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_room_reward`
--
ALTER TABLE `ws_room_reward`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_room_type`
--
ALTER TABLE `ws_room_type`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_share_reward`
--
ALTER TABLE `ws_share_reward`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_sign`
--
ALTER TABLE `ws_sign`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_system`
--
ALTER TABLE `ws_system`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_user_money_record`
--
ALTER TABLE `ws_user_money_record`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `ws_user_return`
--
ALTER TABLE `ws_user_return`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ws_admins`
--
ALTER TABLE `ws_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ws_clock_in`
--
ALTER TABLE `ws_clock_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `ws_clock_in_join`
--
ALTER TABLE `ws_clock_in_join`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=104;
--
-- AUTO_INCREMENT for table `ws_clock_in_price`
--
ALTER TABLE `ws_clock_in_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `ws_clock_in_sign`
--
ALTER TABLE `ws_clock_in_sign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `ws_clock_reward`
--
ALTER TABLE `ws_clock_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `ws_member`
--
ALTER TABLE `ws_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `ws_money_get`
--
ALTER TABLE `ws_money_get`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `ws_money_recharge`
--
ALTER TABLE `ws_money_recharge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=98;
--
-- AUTO_INCREMENT for table `ws_pass`
--
ALTER TABLE `ws_pass`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `ws_pass_join`
--
ALTER TABLE `ws_pass_join`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=93;
--
-- AUTO_INCREMENT for table `ws_pass_price`
--
ALTER TABLE `ws_pass_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `ws_pass_reward`
--
ALTER TABLE `ws_pass_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ws_pass_sign`
--
ALTER TABLE `ws_pass_sign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=668;
--
-- AUTO_INCREMENT for table `ws_pass_time`
--
ALTER TABLE `ws_pass_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `ws_room_create`
--
ALTER TABLE `ws_room_create`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10014;
--
-- AUTO_INCREMENT for table `ws_room_join`
--
ALTER TABLE `ws_room_join`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `ws_room_record`
--
ALTER TABLE `ws_room_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ws_room_reward`
--
ALTER TABLE `ws_room_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ws_room_type`
--
ALTER TABLE `ws_room_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ws_share_reward`
--
ALTER TABLE `ws_share_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ws_sign`
--
ALTER TABLE `ws_sign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `ws_system`
--
ALTER TABLE `ws_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `ws_user_money_record`
--
ALTER TABLE `ws_user_money_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=277;
--
-- AUTO_INCREMENT for table `ws_user_return`
--
ALTER TABLE `ws_user_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
