DROP TABLE IF EXISTS `#__plg_churning`;
CREATE TABLE IF NOT EXISTS `#__plg_churning` (
  `chu_id` int(11) NOT NULL AUTO_INCREMENT,
  `chu_date` datetime NOT NULL,
  `chu_refair` varchar(100) NOT NULL,
  `chu_datpas` text NOT NULL,
  PRIMARY KEY (`chu_id`),
  KEY `chu_date` (`chu_date`,`chu_refair`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;