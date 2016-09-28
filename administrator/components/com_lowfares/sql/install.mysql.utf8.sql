DROP TABLE IF EXISTS #__lf_flights;

CREATE TABLE #__lf_flights (
  id int(11) NOT NULL auto_increment,
  origin  CHAR(3) NOT NULL,
  destiny  CHAR(3) NOT NULL,
  originname  VARCHAR(60) NULL,
  destinyname  VARCHAR(60) NULL,
  offset INTEGER NULL,
  departure DATE NULL,
  duration SMALLINT NOT NULL,
  value DECIMAL(12,2) DEFAULT 0 NOT NULL,
  category INTEGER NOT NULL,
  published SMALLINT NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

