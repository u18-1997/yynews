-- --------------------------------------------------------
-- yynews数据库脚本
-- 
--注意：如果您数据库中的表添加了前缀，请自行在新添加的这两个表的表名上添加前缀
-- --------------------------------------------------------



CREATE TABLE IF NOT EXISTS `yynews` (
  `yynews_id` int(11) NOT NULL AUTO_INCREMENT,
  `top` int(1) NOT NULL DEFAULT '0',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `newsdate` datetime NOT NULL,
  `titleimage` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`yynews_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



CREATE TABLE IF NOT EXISTS `yynews_description` (
  `yynews_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `description` text COLLATE utf8_bin NOT NULL,
  `summary` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`yynews_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
