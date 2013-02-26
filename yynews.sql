-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.28-0ubuntu0.12.04.3 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2013-02-17 12:49:19
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping structure for table opencart.yynews
CREATE TABLE IF NOT EXISTS `yynews` (
  `yynews_id` int(11) NOT NULL AUTO_INCREMENT,
  `top` int(1) NOT NULL DEFAULT '0',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `newsdate` datetime NOT NULL,
  `titleimage` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`yynews_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Data exporting was unselected.


-- Dumping structure for table opencart.yynews_description
CREATE TABLE IF NOT EXISTS `yynews_description` (
  `yynews_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `description` text COLLATE utf8_bin NOT NULL,
  `summary` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`yynews_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
