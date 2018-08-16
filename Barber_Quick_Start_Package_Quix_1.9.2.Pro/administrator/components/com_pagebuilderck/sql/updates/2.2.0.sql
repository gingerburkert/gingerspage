CREATE TABLE IF NOT EXISTS `#__pagebuilderck_elements` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `type` varchar(50) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` int(10) NOT NULL DEFAULT '1',
  `catid` varchar(255) NOT NULL,
  `htmlcode` longtext NOT NULL,
  `checked_out` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;