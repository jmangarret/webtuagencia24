
DROP TABLE IF EXISTS `#__aom_orders`;

CREATE TABLE `#__aom_orders` (
  `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INTEGER NOT NULL,
  `recloc` CHAR(10) NOT NULL,
  `firstname` VARCHAR(32) NULL,
  `lastname` VARCHAR(32) NULL,
  `email` VARCHAR(256) NULL,
  `phone` VARCHAR(256) NULL,
  `total` DECIMAL(12,2) NOT NULL,
  `fare` DECIMAL(12,2) NOT NULL,
  `taxes` DECIMAL(12,2) NOT NULL,
  `fare_ta` DECIMAL(12,2) NOT NULL,
  `taxes_ta` DECIMAL(12,2) NOT NULL,
  `provider` VARCHAR(64) NULL,
  `extra` VARCHAR(128) NULL,
  `note` VARCHAR(512) NULL,
  `status` INTEGER NOT NULL,
  `fecsis` DATETIME NOT NULL
) ENGINE=MyISAM CHARSET=utf8;

DROP TABLE IF EXISTS `#__aom_source`;

CREATE TABLE `#__aom_source` (
  `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id` INTEGER NOT NULL,
  `data` MEDIUMTEXT NULL
) ENGINE=MyISAM CHARSET=utf8;

DROP TABLE IF EXISTS `#__aom_history`;

CREATE TABLE `#__aom_history` (
  `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id` INTEGER NOT NULL,
  `user_id` INTEGER NOT NULL,
  `note` VARCHAR(512) NULL,
  `status` INTEGER NOT NULL,
  `fecsis` DATETIME NOT NULL
) ENGINE=MyISAM CHARSET=utf8;

DROP TABLE IF EXISTS `#__aom_payments`;

CREATE TABLE `#__aom_payments` (
  `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id` INTEGER NOT NULL,
  `company` VARCHAR(128) NULL,
  `nit` VARCHAR(16) NULL,
  `reason` VARCHAR(256) NULL,
  `fare` DECIMAL(12, 2) NULL,
  `airportfare` DECIMAL(12, 2) NULL,
  `fee` DECIMAL(12, 2) NULL,
  `currency` CHAR(3) NULL,
  `franchise` VARCHAR(32) NULL,
  `franchiseName` VARCHAR(64) NULL,
  `creditCardNumber` CHAR(4) NULL,
  `bank` VARCHAR(128) NULL,
  `cus` CHAR(16) NULL,
  `receipt` CHAR(16) NULL,
  `reference` CHAR(16) NULL,
  `description` VARCHAR(128) NULL,
  `ip` VARCHAR(32) NULL,
  `customer` VARCHAR(128) NULL,
  `customerEmail` VARCHAR(128) NULL,
  `status` VARCHAR(32) NULL,
  `statusCode` INTEGER NULL,
  `fectrans` DATETIME NOT NULL
) ENGINE=MyISAM CHARSET=utf8;

DROP TABLE IF EXISTS `#__aom_statuses`;

CREATE TABLE `#__aom_statuses` (
  `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(128) NOT NULL,
  `color` CHAR(7) NOT NULL,
  `default_status` SMALLINT NOT NULL
) ENGINE=MyISAM CHARSET=utf8;

INSERT INTO `#__aom_statuses` VALUES
(1, 'Activo', '#0b67e7', 1),
(2, 'Emitido', '#148717', 0),
(3, 'Cancelado', '#aa0000', 0),
(4, 'Pagado', '#84bd1c', 0),
(5, 'Rechazado', '#d5444f', 0),
(6, 'Pendiente de Aprobación', '#dfa500', 0);

