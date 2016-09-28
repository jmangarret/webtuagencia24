DROP TABLE IF EXISTS #__fee_groups;

CREATE TABLE #__fee_groups (
  id int(11) NOT NULL auto_increment,
  usergroupid INTEGER NOT NULL,
  discount DECIMAL(4,2) NOT NULL,
  feetype ENUM('P', 'V') NOT NULL,
  fee DECIMAL(10, 2) NOT NULL,
  saldo DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS #__fee_adminfare;

CREATE TABLE #__fee_adminfare (
  id int(11) NOT NULL auto_increment,
  airline CHAR(2) NOT NULL,
  `all` tinyint(1) NOT NULL,
  valuetype ENUM('P', 'V') NOT NULL,
  published SMALLINT NOT NULL,
  
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS #__fee_values;

CREATE TABLE #__fee_values (
  id int(11) NOT NULL auto_increment,
  fare_id INTEGER NOT NULL,
  trip ENUM('ON', 'RN', 'OI', 'RI') NOT NULL,
  minfare DECIMAL(10, 2) NOT NULL,
  maxfare DECIMAL(10, 2) NOT NULL,
  
  charge_adult DECIMAL(10, 2) NOT NULL,
  charge_senior DECIMAL(10, 2) NOT NULL,
  charge_child DECIMAL(10, 2) NOT NULL,
  charge_infant DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;