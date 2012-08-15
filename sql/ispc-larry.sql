-- 
-- ISPC-larry 1.x initial MySQL-data
-- ISPConfig-Version: 3.0.5.x
-- 

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `dbispconfig`
--

-- --------------------------------------------------------

--
-- Table structure for table `tpl_ispc_larry`
--

CREATE TABLE IF NOT EXISTS `tpl_ispc_larry` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `logo_url` varchar(255) NOT NULL,
  `sidebar_state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tpl_ispc_larry_apps`
--

CREATE TABLE IF NOT EXISTS `tpl_ispc_larry_apps` (
  `app_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat` int(11) unsigned NOT NULL,
  `title` varchar(64) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `target` varchar(16) NOT NULL,
  `sorting` tinyint(2) NOT NULL,
  `active` enum('N','Y') NOT NULL,
  PRIMARY KEY (`app_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `tpl_ispc_larry_apps`
--

INSERT INTO `tpl_ispc_larry_apps` (`app_id`, `cat`, `title`, `desc`, `image_url`, `link`, `target`, `sorting`, `active`) VALUES
(1, 1, 'phpMyAdmin', 'MySQL-Administration', 'https://d1mpyuettu12if.cloudfront.net/dev/phpmyadmin.png', 'https://mysql.foe-services.de/phpmyadmin/', '_blank', 10, 'N'),
(2, 1, 'AjaXplorer', 'WebFTP-Client', 'https://d1mpyuettu12if.cloudfront.net/dev/ajaxplorer.png', 'https://ftp.foe-services.de/', '_blank', 20, 'N');

-- --------------------------------------------------------

--
-- Table structure for table `tpl_ispc_larry_cats`
--

CREATE TABLE IF NOT EXISTS `tpl_ispc_larry_cats` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `sorting` tinyint(4) NOT NULL,
  `active` enum('N','Y') NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `tpl_ispc_larry_cats`
--

INSERT INTO `tpl_ispc_larry_cats` (`cat_id`, `title`, `sorting`, `active`) VALUES
(1, 'Applications', 15, 'Y'),
(2, 'Foe Services', 20, 'Y');