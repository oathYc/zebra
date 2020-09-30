-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2020-09-30 13:00:24
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
(1, 'admin', '2cd1a1f1f0483ab855328b21ad14e172', '117.175.130.22', 1601386092, 1),
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
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='打卡设置';

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
) ENGINE=MyISAM AUTO_INCREMENT=176 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `ws_clock_in_price`
--

CREATE TABLE IF NOT EXISTS `ws_clock_in_price` (
  `id` int(11) NOT NULL,
  `clockInId` int(11) DEFAULT NULL COMMENT '打卡活动id',
  `price` decimal(10,2) DEFAULT NULL COMMENT '报名价格',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='打卡价格记录表';

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
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='打卡签到记录';

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
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='打卡收益记录表';

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
  `check` tinyint(1) NOT NULL DEFAULT '0' COMMENT '实名认证审核状态 0-未提交 1-待审核 2-审核通过 3-审核失败',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-活跃 0-冻结'
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员表';

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
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='收益统计表';

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
) ENGINE=MyISAM AUTO_INCREMENT=165 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='余额充值';

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
  `isEnd` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否结算 0-未结算 1-已结算 默认0',
  `min` varchar(40) NOT NULL COMMENT '最小签到时间 小时',
  `max` varchar(40) NOT NULL COMMENT '最大签到时间 小时'
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='闯关活动';

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
  `signStatus` tinyint(1) NOT NULL DEFAULT '0',
  `number` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM AUTO_INCREMENT=244 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='用户闯关报名';

-- --------------------------------------------------------

--
-- 表的结构 `ws_pass_price`
--

CREATE TABLE IF NOT EXISTS `ws_pass_price` (
  `id` int(11) NOT NULL,
  `passId` int(11) DEFAULT NULL COMMENT '闯关活动id',
  `price` decimal(10,2) DEFAULT NULL COMMENT '报名价格',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='打卡价格记录表';

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
) ENGINE=MyISAM AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='闯关收益记录表';

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
) ENGINE=MyISAM AUTO_INCREMENT=1030 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='用户闯关签到记录';

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
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='闯关活动打卡时间';

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
  `beginDate` varchar(10) DEFAULT NULL COMMENT '开始时间日期',
  `isEnd` tinyint(1) DEFAULT '0' COMMENT '是否结算  0-未结算 1-已结算',
  `successMoney` decimal(10,2) DEFAULT NULL COMMENT '成功金额',
  `successCount` int(11) DEFAULT NULL COMMENT '成功人数',
  `failCount` int(11) DEFAULT NULL COMMENT '失败人数',
  `failMoney` decimal(10,2) DEFAULT NULL COMMENT '失败金额'
) ENGINE=MyISAM AUTO_INCREMENT=10035 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='房间活动-用户发起';

-- --------------------------------------------------------

--
-- 表的结构 `ws_room_join`
--

CREATE TABLE IF NOT EXISTS `ws_room_join` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '报名者uid',
  `roomId` int(11) DEFAULT NULL COMMENT '房间号id',
  `createTime` int(11) DEFAULT NULL COMMENT '报名时间',
  `type` tinyint(1) DEFAULT '1' COMMENT '1-保底 2-普通',
  `status` tinyint(1) DEFAULT '1' COMMENT '1-参与中 2-已失败 3-已完成',
  `joinMoney` decimal(10,2) DEFAULT NULL COMMENT '参与金额',
  `getMoney` decimal(10,2) DEFAULT '0.00' COMMENT '获得奖励',
  `getPercent` decimal(10,2) DEFAULT NULL COMMENT '占比'
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='房间挑战报名记录';

-- --------------------------------------------------------

--
-- 表的结构 `ws_room_price`
--

CREATE TABLE IF NOT EXISTS `ws_room_price` (
  `id` int(11) NOT NULL,
  `roomId` int(11) DEFAULT NULL COMMENT '房间id',
  `price` decimal(10,2) DEFAULT NULL COMMENT '报名价格',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间'
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='打卡价格记录表';

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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='房间挑战收益记录表';

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='房间类型';

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- 表的结构 `ws_sign`
--

CREATE TABLE IF NOT EXISTS `ws_sign` (
  `id` int(11) NOT NULL,
  `roomId` int(11) DEFAULT NULL COMMENT '房间id',
  `date` char(10) DEFAULT NULL COMMENT '签到日期',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `firstSign` tinyint(1) DEFAULT '0' COMMENT '0-未签到 1-已签到 第一次签到',
  `firstSignTime` datetime DEFAULT NULL COMMENT '第一次签到时间',
  `secondSign` tinyint(1) DEFAULT NULL COMMENT '二次签到 0-未签到 1-已签到',
  `secondSignTime` datetime DEFAULT NULL COMMENT '二次签到时间',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updateTime` int(11) DEFAULT NULL COMMENT '更新时间',
  `type` tinyint(1) DEFAULT '1' COMMENT '1-房间挑战',
  `signNum` tinyint(1) DEFAULT '1' COMMENT '签到次数 默认1次'
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='用户签到表';

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统设置';

--
-- 转存表中的数据 `ws_system`
--

INSERT INTO `ws_system` (`id`, `type`, `content`, `createTime`, `title`) VALUES
(12, 5, '1.5', 1601434413, '');

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
  `moneyType` tinyint(1) DEFAULT '0' COMMENT '0-充值 1-打卡 2-房间挑战 3-闯关',
  `isReward` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-不是 1-是'
) ENGINE=MyISAM AUTO_INCREMENT=879 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户金额记录';

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=FIXED COMMENT='用户提现';

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
-- Indexes for table `ws_room_price`
--
ALTER TABLE `ws_room_price`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `ws_clock_in_join`
--
ALTER TABLE `ws_clock_in_join`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=176;
--
-- AUTO_INCREMENT for table `ws_clock_in_price`
--
ALTER TABLE `ws_clock_in_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=96;
--
-- AUTO_INCREMENT for table `ws_clock_in_sign`
--
ALTER TABLE `ws_clock_in_sign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT for table `ws_clock_reward`
--
ALTER TABLE `ws_clock_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `ws_member`
--
ALTER TABLE `ws_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT for table `ws_money_get`
--
ALTER TABLE `ws_money_get`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `ws_money_recharge`
--
ALTER TABLE `ws_money_recharge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=165;
--
-- AUTO_INCREMENT for table `ws_pass`
--
ALTER TABLE `ws_pass`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `ws_pass_join`
--
ALTER TABLE `ws_pass_join`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=244;
--
-- AUTO_INCREMENT for table `ws_pass_price`
--
ALTER TABLE `ws_pass_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT for table `ws_pass_reward`
--
ALTER TABLE `ws_pass_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT for table `ws_pass_sign`
--
ALTER TABLE `ws_pass_sign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1030;
--
-- AUTO_INCREMENT for table `ws_pass_time`
--
ALTER TABLE `ws_pass_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=86;
--
-- AUTO_INCREMENT for table `ws_room_create`
--
ALTER TABLE `ws_room_create`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10035;
--
-- AUTO_INCREMENT for table `ws_room_join`
--
ALTER TABLE `ws_room_join`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `ws_room_price`
--
ALTER TABLE `ws_room_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `ws_room_record`
--
ALTER TABLE `ws_room_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ws_room_reward`
--
ALTER TABLE `ws_room_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `ws_room_type`
--
ALTER TABLE `ws_room_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `ws_share_reward`
--
ALTER TABLE `ws_share_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `ws_sign`
--
ALTER TABLE `ws_sign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `ws_system`
--
ALTER TABLE `ws_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `ws_user_money_record`
--
ALTER TABLE `ws_user_money_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=879;
--
-- AUTO_INCREMENT for table `ws_user_return`
--
ALTER TABLE `ws_user_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
