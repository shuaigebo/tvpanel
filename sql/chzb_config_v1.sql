--
-- MySQL database dump
-- Created by DbManage class, Power By yanue. 
-- http://yanue.net 
--
-- 生成日期: 2020 年  02 月 19 日 20:05
-- MySQL版本: 5.5.62-log
-- PHP 版本: 7.2.25

--
-- 数据库: ``
--

-- -------------------------------------------------------

--
-- 表的结构chzb_config
--

DROP TABLE IF EXISTS `chzb_config`;
CREATE TABLE `chzb_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 chzb_config
--

INSERT INTO `chzb_config` VALUES('1','secret_key','');
INSERT INTO `chzb_config` VALUES('2','epg_api_chk','0');
