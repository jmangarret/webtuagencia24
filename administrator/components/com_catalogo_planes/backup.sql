-- MySQL dump 10.11
--
-- Host: localhost    Database: viajescolon2
-- ------------------------------------------------------
-- Server version	5.0.77-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `#__cp_cars_comments`
--

DROP TABLE IF EXISTS `#__cp_cars_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_comments` (
  `comment_id` int(11) NOT NULL auto_increment,
  `order_id` tinyint(4) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `comment_rate` int(11) NOT NULL,
  `comment_text` varchar(400) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  `end_date` date NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_comments`
--

LOCK TABLES `#__cp_cars_comments` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_comments` DISABLE KEYS */;
INSERT INTO `#__cp_cars_comments` (`comment_id`, `order_id`, `product_id`, `product_name`, `comment_rate`, `comment_text`, `created`, `created_by`, `contact_name`, `contact_email`, `lang`, `modified`, `modified_by`, `end_date`, `published`) VALUES (1,127,6,'Tour de prueba 1',10,'comento lo que quiero, soy lo mejor!!!','2013-06-17 21:34:45','','XXXXXX xx','dora.pena@periferia-it.com','es-ES','2013-06-17 21:34:45',NULL,'1969-12-31',2);
/*!40000 ALTER TABLE `#__cp_cars_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_delivery_city`
--

DROP TABLE IF EXISTS `#__cp_cars_delivery_city`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_delivery_city` (
  `product_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_delivery_city`
--

LOCK TABLES `#__cp_cars_delivery_city` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_delivery_city` DISABLE KEYS */;
INSERT INTO `#__cp_cars_delivery_city` (`product_id`, `city_id`) VALUES (2,22),(2,25),(2,30);
/*!40000 ALTER TABLE `#__cp_cars_delivery_city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_files`
--

DROP TABLE IF EXISTS `#__cp_cars_files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_files` (
  `product_id` int(11) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  UNIQUE KEY `cars_files_unique` (`product_id`,`file_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_files`
--

LOCK TABLES `#__cp_cars_files` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_cars_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_info`
--

DROP TABLE IF EXISTS `#__cp_cars_info`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_info` (
  `product_id` int(11) NOT NULL auto_increment,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(10) default NULL,
  `product_desc` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `region_id` int(11) default NULL,
  `city_id` int(11) NOT NULL,
  `zone_id` int(11) default NULL,
  `featured` tinyint(4) NOT NULL,
  `latitude` varchar(255) default NULL,
  `longitude` varchar(255) default NULL,
  `ordering` int(11) NOT NULL default '0',
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `access` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `tag_name1` varchar(255) default NULL,
  `tag_content1` mediumtext,
  `tag_name2` varchar(255) default NULL,
  `tag_content2` mediumtext,
  `tag_name3` varchar(255) default NULL,
  `tag_content3` mediumtext,
  `tag_name4` varchar(255) default NULL,
  `tag_content4` mediumtext,
  `tag_name5` varchar(255) default NULL,
  `tag_content5` mediumtext,
  `tag_name6` varchar(255) default NULL,
  `tag_content6` mediumtext,
  `product_url` mediumtext,
  `supplier_id` int(11) default NULL,
  `average_rating` tinyint(4) default '0',
  `additional_description` varchar(255) default NULL,
  `disclaimer` varchar(500) default NULL,
  PRIMARY KEY  (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Guarda los productos del Catálogo.';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_info`
--

LOCK TABLES `#__cp_cars_info` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_info` DISABLE KEYS */;
INSERT INTO `#__cp_cars_info` (`product_id`, `product_name`, `product_code`, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `zone_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, `created`, `created_by`, `modified`, `modified_by`, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `average_rating`, `additional_description`, `disclaimer`) VALUES (2,'Aveo','34234','s fasdfasf asasf s',1,47,0,2573,0,0,'4.565','-65.091796875',0,'2013-03-18 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'2013-04-12 21:29:50',62,0,1,'Descripción General','','Incluye','','No incluye','','Itinerario','','Pago y condiciones del servicio','','','','',2,0,NULL,'Tarifas incluyen impuestos');
/*!40000 ALTER TABLE `#__cp_cars_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_param1`
--

DROP TABLE IF EXISTS `#__cp_cars_param1`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_param1` (
  `product_id` int(11) NOT NULL,
  `param1_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param1_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_param1`
--

LOCK TABLES `#__cp_cars_param1` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_param1` DISABLE KEYS */;
INSERT INTO `#__cp_cars_param1` (`product_id`, `param1_id`) VALUES (2,1);
/*!40000 ALTER TABLE `#__cp_cars_param1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_param2`
--

DROP TABLE IF EXISTS `#__cp_cars_param2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_param2` (
  `product_id` int(11) NOT NULL,
  `param2_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_param2`
--

LOCK TABLES `#__cp_cars_param2` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_param2` DISABLE KEYS */;
INSERT INTO `#__cp_cars_param2` (`product_id`, `param2_id`) VALUES (2,1),(2,2);
/*!40000 ALTER TABLE `#__cp_cars_param2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_rate`
--

DROP TABLE IF EXISTS `#__cp_cars_rate`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_rate` (
  `rate_id` int(11) NOT NULL auto_increment,
  `season_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `basic_price` double default NULL,
  `previous_value` double default NULL,
  `currency_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY  (`rate_id`),
  KEY `cars_rate_product_id` (`product_id`),
  KEY `cars_rate_season_id` (`season_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_rate`
--

LOCK TABLES `#__cp_cars_rate` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_rate` DISABLE KEYS */;
INSERT INTO `#__cp_cars_rate` (`rate_id`, `season_id`, `product_id`, `basic_price`, `previous_value`, `currency_id`, `created`, `created_by`, `modified`, `modified_by`) VALUES (3,88,2,15000,0,0,'2013-04-22 15:13:27',62,'2013-04-22 15:13:27',62);
/*!40000 ALTER TABLE `#__cp_cars_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_rate_price`
--

DROP TABLE IF EXISTS `#__cp_cars_rate_price`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_rate_price` (
  `rate_id` int(11) NOT NULL,
  `param1` int(11) NOT NULL default '0',
  `param2` int(11) NOT NULL default '0',
  `price` double NOT NULL,
  UNIQUE KEY `cars_rate_price_unique` (`rate_id`,`param1`,`param2`),
  KEY `cars_rate_id` (`rate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_rate_price`
--

LOCK TABLES `#__cp_cars_rate_price` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_rate_price` DISABLE KEYS */;
INSERT INTO `#__cp_cars_rate_price` (`rate_id`, `param1`, `param2`, `price`) VALUES (3,1,1,15000),(3,1,2,15000);
/*!40000 ALTER TABLE `#__cp_cars_rate_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_rate_supplement`
--

DROP TABLE IF EXISTS `#__cp_cars_rate_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_rate_supplement` (
  `rate_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY  (`rate_id`,`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_rate_supplement`
--

LOCK TABLES `#__cp_cars_rate_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_rate_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_cars_rate_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_resume`
--

DROP TABLE IF EXISTS `#__cp_cars_resume`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_resume` (
  `product_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `stock` int(11) NOT NULL,
  `adult_price` double NOT NULL,
  `currency_id` int(11) NOT NULL,
  `previous_price` double NOT NULL,
  UNIQUE KEY `cars_resume_unique_index` (`product_id`,`date`),
  KEY `cars_resume_date` (`date`),
  KEY `cars_resume_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_resume`
--

LOCK TABLES `#__cp_cars_resume` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_resume` DISABLE KEYS */;
INSERT INTO `#__cp_cars_resume` (`product_id`, `date`, `stock`, `adult_price`, `currency_id`, `previous_price`) VALUES (2,'2013-03-22',0,32000,1,0),(2,'2013-03-23',0,32000,1,0),(2,'2013-03-24',0,32000,1,0),(2,'2013-03-29',0,32000,1,0),(2,'2013-03-30',0,32000,1,0),(2,'2013-03-31',0,32000,1,0),(2,'2013-04-05',0,32000,1,0),(2,'2013-04-06',0,32000,1,0),(2,'2013-04-07',0,32000,1,0),(2,'2013-04-12',0,32000,1,0),(2,'2013-04-13',0,32000,1,0),(2,'2013-04-14',0,32000,1,0),(2,'2013-04-19',0,32000,1,0),(2,'2013-04-20',0,32000,1,0),(2,'2013-04-21',0,32000,1,0),(2,'2013-04-22',0,15000,1,0),(2,'2013-04-23',0,15000,1,0),(2,'2013-04-24',0,15000,1,0),(2,'2013-04-25',0,15000,1,0),(2,'2013-04-26',0,15000,1,0),(2,'2013-04-27',0,15000,1,0),(2,'2013-04-28',0,15000,1,0),(2,'2013-04-29',0,15000,1,0),(2,'2013-04-30',0,15000,1,0),(2,'2013-05-01',0,15000,1,0),(2,'2013-05-02',0,15000,1,0),(2,'2013-05-03',0,15000,1,0),(2,'2013-05-04',0,15000,1,0),(2,'2013-05-05',0,15000,1,0),(2,'2013-05-06',0,15000,1,0),(2,'2013-05-07',0,15000,1,0),(2,'2013-05-08',0,15000,1,0),(2,'2013-05-09',0,15000,1,0),(2,'2013-05-10',0,15000,1,0),(2,'2013-05-11',0,15000,1,0),(2,'2013-05-12',0,15000,1,0),(2,'2013-05-13',0,15000,1,0),(2,'2013-05-14',0,15000,1,0),(2,'2013-05-15',0,15000,1,0),(2,'2013-05-16',0,15000,1,0),(2,'2013-05-17',0,15000,1,0),(2,'2013-05-18',0,15000,1,0),(2,'2013-05-19',0,15000,1,0),(2,'2013-05-20',0,15000,1,0),(2,'2013-05-21',0,15000,1,0),(2,'2013-05-22',0,15000,1,0),(2,'2013-05-23',0,15000,1,0),(2,'2013-05-24',0,15000,1,0),(2,'2013-05-25',0,15000,1,0),(2,'2013-05-26',0,15000,1,0),(2,'2013-05-27',0,15000,1,0),(2,'2013-05-28',0,15000,1,0),(2,'2013-05-29',0,15000,1,0),(2,'2013-05-30',0,15000,1,0),(2,'2013-05-31',0,15000,1,0),(2,'2013-06-01',0,15000,1,0),(2,'2013-06-02',0,15000,1,0),(2,'2013-06-03',0,15000,1,0),(2,'2013-06-04',0,15000,1,0),(2,'2013-06-05',0,15000,1,0),(2,'2013-06-06',0,15000,1,0),(2,'2013-06-07',0,15000,1,0),(2,'2013-06-08',0,15000,1,0),(2,'2013-06-09',0,15000,1,0),(2,'2013-06-10',0,15000,1,0),(2,'2013-06-11',0,15000,1,0),(2,'2013-06-12',0,15000,1,0),(2,'2013-06-13',0,15000,1,0),(2,'2013-06-14',0,15000,1,0),(2,'2013-06-15',0,15000,1,0),(2,'2013-06-16',0,15000,1,0),(2,'2013-06-17',0,15000,1,0),(2,'2013-06-18',0,15000,1,0),(2,'2013-06-19',0,15000,1,0),(2,'2013-06-20',0,15000,1,0),(2,'2013-06-21',0,15000,1,0),(2,'2013-06-22',0,15000,1,0),(2,'2013-06-23',0,15000,1,0),(2,'2013-06-24',0,15000,1,0),(2,'2013-06-25',0,15000,1,0),(2,'2013-06-26',0,15000,1,0),(2,'2013-06-27',0,15000,1,0),(2,'2013-06-28',0,15000,1,0),(2,'2013-06-29',0,15000,1,0),(2,'2013-06-30',0,15000,1,0),(2,'2013-07-01',0,15000,1,0),(2,'2013-07-02',0,15000,1,0),(2,'2013-07-03',0,15000,1,0),(2,'2013-07-04',0,15000,1,0),(2,'2013-07-05',0,15000,1,0),(2,'2013-07-06',0,15000,1,0),(2,'2013-07-07',0,15000,1,0),(2,'2013-07-08',0,15000,1,0),(2,'2013-07-09',0,15000,1,0),(2,'2013-07-10',0,15000,1,0),(2,'2013-07-11',0,15000,1,0),(2,'2013-07-12',0,15000,1,0),(2,'2013-07-13',0,15000,1,0),(2,'2013-07-14',0,15000,1,0),(2,'2013-07-15',0,15000,1,0),(2,'2013-07-16',0,15000,1,0),(2,'2013-07-17',0,15000,1,0),(2,'2013-07-18',0,15000,1,0),(2,'2013-07-19',0,15000,1,0),(2,'2013-07-20',0,15000,1,0),(2,'2013-07-21',0,15000,1,0),(2,'2013-07-22',0,15000,1,0),(2,'2013-07-23',0,15000,1,0),(2,'2013-07-24',0,15000,1,0),(2,'2013-07-25',0,15000,1,0),(2,'2013-07-26',0,15000,1,0),(2,'2013-07-27',0,15000,1,0),(2,'2013-07-28',0,15000,1,0),(2,'2013-07-29',0,15000,1,0),(2,'2013-07-30',0,15000,1,0),(2,'2013-07-31',0,15000,1,0),(2,'2013-08-01',0,15000,1,0),(2,'2013-08-02',0,15000,1,0),(2,'2013-08-03',0,15000,1,0),(2,'2013-08-04',0,15000,1,0),(2,'2013-08-05',0,15000,1,0),(2,'2013-08-06',0,15000,1,0),(2,'2013-08-07',0,15000,1,0),(2,'2013-08-08',0,15000,1,0),(2,'2013-08-09',0,15000,1,0),(2,'2013-08-10',0,15000,1,0),(2,'2013-08-11',0,15000,1,0),(2,'2013-08-12',0,15000,1,0),(2,'2013-08-13',0,15000,1,0),(2,'2013-08-14',0,15000,1,0),(2,'2013-08-15',0,15000,1,0),(2,'2013-08-16',0,15000,1,0),(2,'2013-08-17',0,15000,1,0),(2,'2013-08-18',0,15000,1,0),(2,'2013-08-19',0,15000,1,0),(2,'2013-08-20',0,15000,1,0),(2,'2013-08-21',0,15000,1,0),(2,'2013-08-22',0,15000,1,0),(2,'2013-08-23',0,15000,1,0),(2,'2013-08-24',0,15000,1,0),(2,'2013-08-25',0,15000,1,0),(2,'2013-08-26',0,15000,1,0),(2,'2013-08-27',0,15000,1,0),(2,'2013-08-28',0,15000,1,0),(2,'2013-08-29',0,15000,1,0),(2,'2013-08-30',0,15000,1,0),(2,'2013-08-31',0,15000,1,0),(2,'2013-09-01',0,15000,1,0),(2,'2013-09-02',0,15000,1,0),(2,'2013-09-03',0,15000,1,0),(2,'2013-09-04',0,15000,1,0),(2,'2013-09-05',0,15000,1,0),(2,'2013-09-06',0,15000,1,0),(2,'2013-09-07',0,15000,1,0),(2,'2013-09-08',0,15000,1,0),(2,'2013-09-09',0,15000,1,0),(2,'2013-09-10',0,15000,1,0),(2,'2013-09-11',0,15000,1,0),(2,'2013-09-12',0,15000,1,0),(2,'2013-09-13',0,15000,1,0),(2,'2013-09-14',0,15000,1,0),(2,'2013-09-15',0,15000,1,0),(2,'2013-09-16',0,15000,1,0),(2,'2013-09-17',0,15000,1,0),(2,'2013-09-18',0,15000,1,0),(2,'2013-09-19',0,15000,1,0),(2,'2013-09-20',0,15000,1,0),(2,'2013-09-21',0,15000,1,0),(2,'2013-09-22',0,15000,1,0),(2,'2013-09-23',0,15000,1,0),(2,'2013-09-24',0,15000,1,0),(2,'2013-09-25',0,15000,1,0),(2,'2013-09-26',0,15000,1,0),(2,'2013-09-27',0,15000,1,0),(2,'2013-09-28',0,15000,1,0),(2,'2013-09-29',0,15000,1,0),(2,'2013-09-30',0,15000,1,0),(2,'2013-10-01',0,15000,1,0),(2,'2013-10-02',0,15000,1,0),(2,'2013-10-03',0,15000,1,0),(2,'2013-10-04',0,15000,1,0),(2,'2013-10-05',0,15000,1,0),(2,'2013-10-06',0,15000,1,0),(2,'2013-10-07',0,15000,1,0),(2,'2013-10-08',0,15000,1,0),(2,'2013-10-09',0,15000,1,0),(2,'2013-10-10',0,15000,1,0),(2,'2013-10-11',0,15000,1,0),(2,'2013-10-12',0,15000,1,0),(2,'2013-10-13',0,15000,1,0),(2,'2013-10-14',0,15000,1,0),(2,'2013-10-15',0,15000,1,0),(2,'2013-10-16',0,15000,1,0),(2,'2013-10-17',0,15000,1,0),(2,'2013-10-18',0,15000,1,0),(2,'2013-10-19',0,15000,1,0),(2,'2013-10-20',0,15000,1,0),(2,'2013-10-21',0,15000,1,0),(2,'2013-10-22',0,15000,1,0),(2,'2013-10-23',0,15000,1,0),(2,'2013-10-24',0,15000,1,0),(2,'2013-10-25',0,15000,1,0),(2,'2013-10-26',0,15000,1,0),(2,'2013-10-27',0,15000,1,0),(2,'2013-10-28',0,15000,1,0),(2,'2013-10-29',0,15000,1,0),(2,'2013-10-30',0,15000,1,0),(2,'2013-10-31',0,15000,1,0),(2,'2013-11-01',0,15000,1,0),(2,'2013-11-02',0,15000,1,0),(2,'2013-11-03',0,15000,1,0),(2,'2013-11-04',0,15000,1,0),(2,'2013-11-05',0,15000,1,0),(2,'2013-11-06',0,15000,1,0),(2,'2013-11-07',0,15000,1,0),(2,'2013-11-08',0,15000,1,0),(2,'2013-11-09',0,15000,1,0),(2,'2013-11-10',0,15000,1,0),(2,'2013-11-11',0,15000,1,0),(2,'2013-11-12',0,15000,1,0),(2,'2013-11-13',0,15000,1,0),(2,'2013-11-14',0,15000,1,0),(2,'2013-11-15',0,15000,1,0),(2,'2013-11-16',0,15000,1,0),(2,'2013-11-17',0,15000,1,0),(2,'2013-11-18',0,15000,1,0),(2,'2013-11-19',0,15000,1,0),(2,'2013-11-20',0,15000,1,0),(2,'2013-11-21',0,15000,1,0),(2,'2013-11-22',0,15000,1,0),(2,'2013-11-23',0,15000,1,0),(2,'2013-11-24',0,15000,1,0),(2,'2013-11-25',0,15000,1,0),(2,'2013-11-26',0,15000,1,0),(2,'2013-11-27',0,15000,1,0),(2,'2013-11-28',0,15000,1,0),(2,'2013-11-29',0,15000,1,0),(2,'2013-11-30',0,15000,1,0),(2,'2013-12-01',0,15000,1,0),(2,'2013-12-02',0,15000,1,0),(2,'2013-12-03',0,15000,1,0),(2,'2013-12-04',0,15000,1,0),(2,'2013-12-05',0,15000,1,0),(2,'2013-12-06',0,15000,1,0),(2,'2013-12-07',0,15000,1,0),(2,'2013-12-08',0,15000,1,0),(2,'2013-12-09',0,15000,1,0),(2,'2013-12-10',0,15000,1,0),(2,'2013-12-11',0,15000,1,0),(2,'2013-12-12',0,15000,1,0),(2,'2013-12-13',0,15000,1,0),(2,'2013-12-14',0,15000,1,0),(2,'2013-12-15',0,15000,1,0),(2,'2013-12-16',0,15000,1,0),(2,'2013-12-17',0,15000,1,0),(2,'2013-12-18',0,15000,1,0),(2,'2013-12-19',0,15000,1,0),(2,'2013-12-20',0,15000,1,0),(2,'2013-12-21',0,15000,1,0),(2,'2013-12-22',0,15000,1,0),(2,'2013-12-23',0,15000,1,0),(2,'2013-12-24',0,15000,1,0),(2,'2013-12-25',0,15000,1,0),(2,'2013-12-26',0,15000,1,0),(2,'2013-12-27',0,15000,1,0),(2,'2013-12-28',0,15000,1,0),(2,'2013-12-29',0,15000,1,0),(2,'2013-12-30',0,15000,1,0),(2,'2013-12-31',0,15000,1,0),(2,'2014-01-01',0,15000,1,0),(2,'2014-01-02',0,15000,1,0),(2,'2014-01-03',0,15000,1,0),(2,'2014-01-04',0,15000,1,0),(2,'2014-01-05',0,15000,1,0),(2,'2014-01-06',0,15000,1,0),(2,'2014-01-07',0,15000,1,0),(2,'2014-01-08',0,15000,1,0),(2,'2014-01-09',0,15000,1,0),(2,'2014-01-10',0,15000,1,0),(2,'2014-01-11',0,15000,1,0),(2,'2014-01-12',0,15000,1,0),(2,'2014-01-13',0,15000,1,0),(2,'2014-01-14',0,15000,1,0),(2,'2014-01-15',0,15000,1,0),(2,'2014-01-16',0,15000,1,0),(2,'2014-01-17',0,15000,1,0),(2,'2014-01-18',0,15000,1,0),(2,'2014-01-19',0,15000,1,0),(2,'2014-01-20',0,15000,1,0),(2,'2014-01-21',0,15000,1,0),(2,'2014-01-22',0,15000,1,0),(2,'2014-01-23',0,15000,1,0),(2,'2014-01-24',0,15000,1,0),(2,'2014-01-25',0,15000,1,0),(2,'2014-01-26',0,15000,1,0),(2,'2014-01-27',0,15000,1,0),(2,'2014-01-28',0,15000,1,0),(2,'2014-01-29',0,15000,1,0),(2,'2014-01-30',0,15000,1,0),(2,'2014-01-31',0,15000,1,0),(2,'2014-02-01',0,15000,1,0),(2,'2014-02-02',0,15000,1,0),(2,'2014-02-03',0,15000,1,0),(2,'2014-02-04',0,15000,1,0),(2,'2014-02-05',0,15000,1,0),(2,'2014-02-06',0,15000,1,0),(2,'2014-02-07',0,15000,1,0),(2,'2014-02-08',0,15000,1,0),(2,'2014-02-09',0,15000,1,0),(2,'2014-02-10',0,15000,1,0),(2,'2014-02-11',0,15000,1,0),(2,'2014-02-12',0,15000,1,0),(2,'2014-02-13',0,15000,1,0),(2,'2014-02-14',0,15000,1,0),(2,'2014-02-15',0,15000,1,0),(2,'2014-02-16',0,15000,1,0),(2,'2014-02-17',0,15000,1,0),(2,'2014-02-18',0,15000,1,0),(2,'2014-02-19',0,15000,1,0),(2,'2014-02-20',0,15000,1,0),(2,'2014-02-21',0,15000,1,0),(2,'2014-02-22',0,15000,1,0),(2,'2014-02-23',0,15000,1,0),(2,'2014-02-24',0,15000,1,0),(2,'2014-02-25',0,15000,1,0),(2,'2014-02-26',0,15000,1,0),(2,'2014-02-27',0,15000,1,0),(2,'2014-02-28',0,15000,1,0),(2,'2014-03-01',0,15000,1,0),(2,'2014-03-02',0,15000,1,0),(2,'2014-03-03',0,15000,1,0),(2,'2014-03-04',0,15000,1,0),(2,'2014-03-05',0,15000,1,0),(2,'2014-03-06',0,15000,1,0),(2,'2014-03-07',0,15000,1,0),(2,'2014-03-08',0,15000,1,0),(2,'2014-03-09',0,15000,1,0),(2,'2014-03-10',0,15000,1,0),(2,'2014-03-11',0,15000,1,0),(2,'2014-03-12',0,15000,1,0),(2,'2014-03-13',0,15000,1,0),(2,'2014-03-14',0,15000,1,0),(2,'2014-03-15',0,15000,1,0),(2,'2014-03-16',0,15000,1,0),(2,'2014-03-17',0,15000,1,0),(2,'2014-03-18',0,15000,1,0),(2,'2014-03-19',0,15000,1,0),(2,'2014-03-20',0,15000,1,0),(2,'2014-03-21',0,15000,1,0),(2,'2014-03-22',0,15000,1,0),(2,'2014-03-23',0,15000,1,0),(2,'2014-03-24',0,15000,1,0),(2,'2014-03-25',0,15000,1,0),(2,'2014-03-26',0,15000,1,0),(2,'2014-03-27',0,15000,1,0),(2,'2014-03-28',0,15000,1,0),(2,'2014-03-29',0,15000,1,0),(2,'2014-03-30',0,15000,1,0),(2,'2014-03-31',0,15000,1,0),(2,'2014-04-01',0,15000,1,0),(2,'2014-04-02',0,15000,1,0),(2,'2014-04-03',0,15000,1,0),(2,'2014-04-04',0,15000,1,0),(2,'2014-04-05',0,15000,1,0),(2,'2014-04-06',0,15000,1,0),(2,'2014-04-07',0,15000,1,0),(2,'2014-04-08',0,15000,1,0),(2,'2014-04-09',0,15000,1,0),(2,'2014-04-10',0,15000,1,0),(2,'2014-04-11',0,15000,1,0),(2,'2014-04-12',0,15000,1,0),(2,'2014-04-13',0,15000,1,0),(2,'2014-04-14',0,15000,1,0),(2,'2014-04-15',0,15000,1,0),(2,'2014-04-16',0,15000,1,0),(2,'2014-04-17',0,15000,1,0),(2,'2014-04-18',0,15000,1,0),(2,'2014-04-19',0,15000,1,0),(2,'2014-04-20',0,15000,1,0),(2,'2014-04-21',0,15000,1,0),(2,'2014-04-22',0,15000,1,0);
/*!40000 ALTER TABLE `#__cp_cars_resume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_stock`
--

DROP TABLE IF EXISTS `#__cp_cars_stock`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_stock` (
  `product_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `quantity` int(11) NOT NULL,
  UNIQUE KEY `cars_stock_unique_index` (`product_id`,`param_id`,`day`),
  KEY `cars_stock_product_id_index` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_stock`
--

LOCK TABLES `#__cp_cars_stock` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_stock` DISABLE KEYS */;
INSERT INTO `#__cp_cars_stock` (`product_id`, `param_id`, `day`, `quantity`) VALUES (2,1,'2013-03-20',10),(2,1,'2013-03-21',10),(2,1,'2013-03-22',10),(2,1,'2013-03-23',10),(2,1,'2013-03-24',10),(2,1,'2013-03-25',10),(2,1,'2013-03-26',10),(2,1,'2013-03-27',10),(2,1,'2013-03-28',10),(2,1,'2013-03-29',10),(2,1,'2013-03-30',10),(2,1,'2013-03-31',10),(2,1,'2013-04-01',10),(2,1,'2013-04-02',10),(2,1,'2013-04-03',10),(2,1,'2013-04-04',10),(2,1,'2013-04-05',10),(2,1,'2013-04-06',10),(2,1,'2013-04-07',10),(2,1,'2013-04-08',10),(2,1,'2013-04-09',10),(2,1,'2013-04-10',10),(2,1,'2013-04-11',10),(2,1,'2013-04-12',10),(2,1,'2013-04-13',10),(2,1,'2013-04-14',10),(2,1,'2013-04-15',10),(2,1,'2013-04-16',10),(2,1,'2013-04-17',10),(2,1,'2013-04-18',10),(2,1,'2013-04-19',10),(2,1,'2013-04-20',9),(2,1,'2013-04-21',9),(2,1,'2013-04-22',10),(2,1,'2013-04-23',10),(2,1,'2013-04-24',10),(2,1,'2013-04-25',10),(2,1,'2013-04-26',10),(2,1,'2013-04-27',10),(2,1,'2013-04-28',10),(2,1,'2013-04-29',10),(2,1,'2013-04-30',10),(2,1,'2013-05-01',10),(2,1,'2013-05-02',10),(2,1,'2013-05-03',10),(2,1,'2013-05-04',10),(2,1,'2013-05-05',10),(2,1,'2013-05-06',10),(2,1,'2013-05-07',10),(2,1,'2013-05-08',10),(2,1,'2013-05-09',10),(2,1,'2013-05-10',10),(2,1,'2013-05-11',10),(2,1,'2013-05-12',10),(2,1,'2013-05-13',10),(2,1,'2013-05-14',10),(2,1,'2013-05-15',10),(2,1,'2013-05-16',10),(2,1,'2013-05-17',10),(2,1,'2013-05-18',10),(2,1,'2013-05-19',10),(2,1,'2013-05-20',10),(2,1,'2013-05-21',10),(2,1,'2013-05-22',10),(2,1,'2013-05-23',10),(2,1,'2013-05-24',10),(2,1,'2013-05-25',10),(2,1,'2013-05-26',10),(2,1,'2013-05-27',10),(2,1,'2013-05-28',10),(2,1,'2013-05-29',10),(2,1,'2013-05-30',10),(2,1,'2013-05-31',10),(2,1,'2013-06-01',10),(2,1,'2013-06-02',10),(2,1,'2013-06-03',10),(2,1,'2013-06-04',10),(2,1,'2013-06-05',10),(2,1,'2013-06-06',10),(2,1,'2013-06-07',10),(2,1,'2013-06-08',10),(2,1,'2013-06-09',10),(2,1,'2013-06-10',10),(2,1,'2013-06-11',10),(2,1,'2013-06-12',10),(2,1,'2013-06-13',10),(2,1,'2013-06-14',10),(2,1,'2013-06-15',10),(2,1,'2013-06-16',10),(2,1,'2013-06-17',10),(2,1,'2013-06-18',10),(2,1,'2013-06-19',10),(2,1,'2013-06-20',10),(2,1,'2013-06-21',10),(2,1,'2013-06-22',10),(2,1,'2013-06-23',10),(2,1,'2013-06-24',10),(2,1,'2013-06-25',10),(2,1,'2013-06-26',10),(2,1,'2013-06-27',10),(2,1,'2013-06-28',10),(2,1,'2013-06-29',10),(2,1,'2013-06-30',10),(2,1,'2013-07-01',10),(2,1,'2013-07-02',10),(2,1,'2013-07-03',10),(2,1,'2013-07-04',10),(2,1,'2013-07-05',10),(2,1,'2013-07-06',10),(2,1,'2013-07-07',10),(2,1,'2013-07-08',10),(2,1,'2013-07-09',10),(2,1,'2013-07-10',10),(2,1,'2013-07-11',10),(2,1,'2013-07-12',10),(2,1,'2013-07-13',10),(2,1,'2013-07-14',10),(2,1,'2013-07-15',10),(2,1,'2013-07-16',10),(2,1,'2013-07-17',10),(2,1,'2013-07-18',10),(2,1,'2013-07-19',10),(2,1,'2013-07-20',10),(2,1,'2013-07-21',10),(2,1,'2013-07-22',10),(2,1,'2013-07-23',10),(2,1,'2013-07-24',10),(2,1,'2013-07-25',10),(2,1,'2013-07-26',10),(2,1,'2013-07-27',10),(2,1,'2013-07-28',10),(2,1,'2013-07-29',10),(2,1,'2013-07-30',10),(2,1,'2013-07-31',10),(2,1,'2013-08-01',10),(2,1,'2013-08-02',10),(2,1,'2013-08-03',10),(2,1,'2013-08-04',10),(2,1,'2013-08-05',10),(2,1,'2013-08-06',10),(2,1,'2013-08-07',10),(2,1,'2013-08-08',10),(2,1,'2013-08-09',10),(2,1,'2013-08-10',10),(2,1,'2013-08-11',10),(2,1,'2013-08-12',10),(2,1,'2013-08-13',10),(2,1,'2013-08-14',10),(2,1,'2013-08-15',10),(2,1,'2013-08-16',10),(2,1,'2013-08-17',10),(2,1,'2013-08-18',10),(2,1,'2013-08-19',10),(2,1,'2013-08-20',10),(2,1,'2013-08-21',10),(2,1,'2013-08-22',10),(2,1,'2013-08-23',10),(2,1,'2013-08-24',10),(2,1,'2013-08-25',10),(2,1,'2013-08-26',10),(2,1,'2013-08-27',10),(2,1,'2013-08-28',10),(2,1,'2013-08-29',10),(2,1,'2013-08-30',10),(2,1,'2013-08-31',10),(2,1,'2013-09-01',10),(2,1,'2013-09-02',10),(2,1,'2013-09-03',10),(2,1,'2013-09-04',10),(2,1,'2013-09-05',10),(2,1,'2013-09-06',10),(2,1,'2013-09-07',10),(2,1,'2013-09-08',10),(2,1,'2013-09-09',10),(2,1,'2013-09-10',10),(2,1,'2013-09-11',10),(2,1,'2013-09-12',10),(2,1,'2013-09-13',10),(2,1,'2013-09-14',10),(2,1,'2013-09-15',10),(2,1,'2013-09-16',10),(2,1,'2013-09-17',10),(2,1,'2013-09-18',10),(2,1,'2013-09-19',10),(2,1,'2013-09-20',10),(2,1,'2013-09-21',10),(2,1,'2013-09-22',10),(2,1,'2013-09-23',10),(2,1,'2013-09-24',10),(2,1,'2013-09-25',10),(2,1,'2013-09-26',10),(2,1,'2013-09-27',10),(2,1,'2013-09-28',10),(2,1,'2013-09-29',10),(2,1,'2013-09-30',10),(2,1,'2013-10-01',10),(2,1,'2013-10-02',10),(2,1,'2013-10-03',10),(2,1,'2013-10-04',10),(2,1,'2013-10-05',10),(2,1,'2013-10-06',10),(2,1,'2013-10-07',10),(2,1,'2013-10-08',10),(2,1,'2013-10-09',10),(2,1,'2013-10-10',10),(2,1,'2013-10-11',10),(2,1,'2013-10-12',10),(2,1,'2013-10-13',10),(2,1,'2013-10-14',10),(2,1,'2013-10-15',10),(2,1,'2013-10-16',10),(2,1,'2013-10-17',10),(2,1,'2013-10-18',10),(2,1,'2013-10-19',10),(2,1,'2013-10-20',10),(2,1,'2013-10-21',10),(2,1,'2013-10-22',10),(2,1,'2013-10-23',10),(2,1,'2013-10-24',10),(2,1,'2013-10-25',10),(2,1,'2013-10-26',10),(2,1,'2013-10-27',10),(2,1,'2013-10-28',10),(2,1,'2013-10-29',10),(2,1,'2013-10-30',10),(2,1,'2013-10-31',10),(2,1,'2013-11-01',10),(2,1,'2013-11-02',10),(2,1,'2013-11-03',10),(2,1,'2013-11-04',10),(2,1,'2013-11-05',10),(2,1,'2013-11-06',10),(2,1,'2013-11-07',10),(2,1,'2013-11-08',10),(2,1,'2013-11-09',10),(2,1,'2013-11-10',10),(2,1,'2013-11-11',10),(2,1,'2013-11-12',10),(2,1,'2013-11-13',10),(2,1,'2013-11-14',10),(2,1,'2013-11-15',10),(2,1,'2013-11-16',10),(2,1,'2013-11-17',10),(2,1,'2013-11-18',10),(2,1,'2013-11-19',10),(2,1,'2013-11-20',10),(2,1,'2013-11-21',10),(2,1,'2013-11-22',10),(2,1,'2013-11-23',10),(2,1,'2013-11-24',10),(2,1,'2013-11-25',10),(2,1,'2013-11-26',10),(2,1,'2013-11-27',10),(2,1,'2013-11-28',10),(2,1,'2013-11-29',10),(2,1,'2013-11-30',10),(2,1,'2013-12-01',10),(2,1,'2013-12-02',10),(2,1,'2013-12-03',10),(2,1,'2013-12-04',10),(2,1,'2013-12-05',10),(2,1,'2013-12-06',10),(2,1,'2013-12-07',10),(2,1,'2013-12-08',10),(2,1,'2013-12-09',10),(2,1,'2013-12-10',10),(2,1,'2013-12-11',10),(2,1,'2013-12-12',10),(2,1,'2013-12-13',10),(2,1,'2013-12-14',10),(2,1,'2013-12-15',10),(2,1,'2013-12-16',10),(2,1,'2013-12-17',10),(2,1,'2013-12-18',10),(2,1,'2013-12-19',10),(2,1,'2013-12-20',10),(2,1,'2013-12-21',10),(2,1,'2013-12-22',10),(2,1,'2013-12-23',10),(2,1,'2013-12-24',10),(2,1,'2013-12-25',10),(2,1,'2013-12-26',10),(2,1,'2013-12-27',10),(2,1,'2013-12-28',10),(2,1,'2013-12-29',10),(2,1,'2013-12-30',10),(2,1,'2013-12-31',10),(2,1,'2014-01-01',10),(2,1,'2014-01-02',10),(2,1,'2014-01-03',10),(2,1,'2014-01-04',10),(2,1,'2014-01-05',10),(2,1,'2014-01-06',10),(2,1,'2014-01-07',10),(2,1,'2014-01-08',10),(2,1,'2014-01-09',10),(2,1,'2014-01-10',10),(2,1,'2014-01-11',10),(2,1,'2014-01-12',10),(2,1,'2014-01-13',10),(2,1,'2014-01-14',10),(2,1,'2014-01-15',10),(2,1,'2014-01-16',10),(2,1,'2014-01-17',10),(2,1,'2014-01-18',10),(2,1,'2014-01-19',10),(2,1,'2014-01-20',10),(2,1,'2014-01-21',10),(2,1,'2014-01-22',10),(2,1,'2014-01-23',10),(2,1,'2014-01-24',10),(2,1,'2014-01-25',10),(2,1,'2014-01-26',10),(2,1,'2014-01-27',10),(2,1,'2014-01-28',10),(2,1,'2014-01-29',10),(2,1,'2014-01-30',10),(2,1,'2014-01-31',10),(2,1,'2014-02-01',10),(2,1,'2014-02-02',10),(2,1,'2014-02-03',10),(2,1,'2014-02-04',10),(2,1,'2014-02-05',10),(2,1,'2014-02-06',10),(2,1,'2014-02-07',10),(2,1,'2014-02-08',10),(2,1,'2014-02-09',10),(2,1,'2014-02-10',10),(2,1,'2014-02-11',10),(2,1,'2014-02-12',10),(2,1,'2014-02-13',10),(2,1,'2014-02-14',10),(2,1,'2014-02-15',10),(2,1,'2014-02-16',10),(2,1,'2014-02-17',10),(2,1,'2014-02-18',10),(2,1,'2014-02-19',10),(2,1,'2014-02-20',10),(2,1,'2014-02-21',10),(2,1,'2014-02-22',10),(2,1,'2014-02-23',10),(2,1,'2014-02-24',10),(2,1,'2014-02-25',10),(2,1,'2014-02-26',10),(2,1,'2014-02-27',10),(2,1,'2014-02-28',10),(2,1,'2014-03-01',10),(2,1,'2014-03-02',10),(2,1,'2014-03-03',10),(2,1,'2014-03-04',10),(2,1,'2014-03-05',10),(2,1,'2014-03-06',10),(2,1,'2014-03-07',10),(2,1,'2014-03-08',10),(2,1,'2014-03-09',10),(2,1,'2014-03-10',10),(2,1,'2014-03-11',10),(2,1,'2014-03-12',10),(2,1,'2014-03-13',10),(2,1,'2014-03-14',10),(2,1,'2014-03-15',10),(2,1,'2014-03-16',10),(2,1,'2014-03-17',10),(2,1,'2014-03-18',10),(2,1,'2014-03-19',10),(2,1,'2014-03-20',10);
/*!40000 ALTER TABLE `#__cp_cars_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_supplement`
--

DROP TABLE IF EXISTS `#__cp_cars_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_supplement` (
  `product_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_supplement`
--

LOCK TABLES `#__cp_cars_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_cars_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_supplement_tax`
--

DROP TABLE IF EXISTS `#__cp_cars_supplement_tax`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_supplement_tax` (
  `product_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`supplement_id`,`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_supplement_tax`
--

LOCK TABLES `#__cp_cars_supplement_tax` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_supplement_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_cars_supplement_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_cars_taxes`
--

DROP TABLE IF EXISTS `#__cp_cars_taxes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_cars_taxes` (
  `product_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_cars_taxes`
--

LOCK TABLES `#__cp_cars_taxes` WRITE;
/*!40000 ALTER TABLE `#__cp_cars_taxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_cars_taxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_ext_tabs_category`
--

DROP TABLE IF EXISTS `#__cp_ext_tabs_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_ext_tabs_category` (
  `category_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `image` varchar(100) default NULL,
  `published` tinyint(4) default NULL,
  `ordering` int(11) default NULL,
  `creation_date` date default NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_ext_tabs_category`
--

LOCK TABLES `#__cp_ext_tabs_category` WRITE;
/*!40000 ALTER TABLE `#__cp_ext_tabs_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_ext_tabs_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_ext_tabs_category_product`
--

DROP TABLE IF EXISTS `#__cp_ext_tabs_category_product`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_ext_tabs_category_product` (
  `category_id` int(11) NOT NULL,
  `tabs_product_id` int(11) NOT NULL,
  `ordering` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_ext_tabs_category_product`
--

LOCK TABLES `#__cp_ext_tabs_category_product` WRITE;
/*!40000 ALTER TABLE `#__cp_ext_tabs_category_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_ext_tabs_category_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_ext_tabs_product`
--

DROP TABLE IF EXISTS `#__cp_ext_tabs_product`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_ext_tabs_product` (
  `tabs_product_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `image` varchar(45) NOT NULL,
  `published` tinyint(4) default NULL,
  `ordering` int(11) default NULL,
  `product_type` varchar(45) default NULL,
  `url` varchar(200) default NULL,
  `name` varchar(100) default NULL,
  `code` varchar(45) default NULL,
  `adition_date` datetime default NULL,
  PRIMARY KEY  (`tabs_product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_ext_tabs_product`
--

LOCK TABLES `#__cp_ext_tabs_product` WRITE;
/*!40000 ALTER TABLE `#__cp_ext_tabs_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_ext_tabs_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_amenity`
--

DROP TABLE IF EXISTS `#__cp_hotels_amenity`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_amenity` (
  `product_id` int(11) NOT NULL,
  `amenity_id` int(11) NOT NULL,
  PRIMARY KEY  (`amenity_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_amenity`
--

LOCK TABLES `#__cp_hotels_amenity` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_amenity` DISABLE KEYS */;
INSERT INTO `#__cp_hotels_amenity` (`product_id`, `amenity_id`) VALUES (1,1);
/*!40000 ALTER TABLE `#__cp_hotels_amenity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_comments`
--

DROP TABLE IF EXISTS `#__cp_hotels_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_comments` (
  `comment_id` int(11) NOT NULL auto_increment,
  `order_id` tinyint(4) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `comment_rate` int(11) NOT NULL,
  `comment_text` varchar(400) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  `end_date` date NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_comments`
--

LOCK TABLES `#__cp_hotels_comments` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_hotels_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_files`
--

DROP TABLE IF EXISTS `#__cp_hotels_files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_files` (
  `product_id` int(11) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  UNIQUE KEY `hotels_files_unique` (`product_id`,`file_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_files`
--

LOCK TABLES `#__cp_hotels_files` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_hotels_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_info`
--

DROP TABLE IF EXISTS `#__cp_hotels_info`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_info` (
  `product_id` int(11) NOT NULL auto_increment,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(10) default NULL,
  `product_desc` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `region_id` int(11) default NULL,
  `city_id` int(11) NOT NULL,
  `zone_id` int(11) default NULL,
  `featured` tinyint(4) NOT NULL,
  `latitude` varchar(255) default NULL,
  `longitude` varchar(255) default NULL,
  `ordering` int(11) NOT NULL default '0',
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `access` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `tag_name1` varchar(255) default NULL,
  `tag_content1` mediumtext,
  `tag_name2` varchar(255) default NULL,
  `tag_content2` mediumtext,
  `tag_name3` varchar(255) default NULL,
  `tag_content3` mediumtext,
  `tag_name4` varchar(255) default NULL,
  `tag_content4` mediumtext,
  `tag_name5` varchar(255) default NULL,
  `tag_content5` mediumtext,
  `tag_name6` varchar(255) default NULL,
  `tag_content6` mediumtext,
  `product_url` mediumtext,
  `supplier_id` int(11) default NULL,
  `stars` tinyint(4) default NULL,
  `average_rating` tinyint(4) default '0',
  `additional_description` varchar(255) default NULL,
  `disclaimer` varchar(500) default NULL,
  PRIMARY KEY  (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Guarda los productos del Catálogo.';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_info`
--

LOCK TABLES `#__cp_hotels_info` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_info` DISABLE KEYS */;
INSERT INTO `#__cp_hotels_info` (`product_id`, `product_name`, `product_code`, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `zone_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, `created`, `created_by`, `modified`, `modified_by`, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `stars`, `average_rating`, `additional_description`, `disclaimer`) VALUES (1,'Primer Hotel ejemplo, días, a través, próximo, después, papá, mamá','0000000002','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis,',1,47,245,2564,1,1,'-10.375442315264081','-56.337890625',0,'2013-08-29 00:00:00','0000-00-00 00:00:00','2013-08-29 18:37:39',62,'2013-08-29 18:37:39',62,0,1,'Descripción General','','Incluye','','No incluye','','Itinerario','','Pago y condiciones del servicio','','','','',1,0,0,'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis,','Tarifas incluyen impuestos');
/*!40000 ALTER TABLE `#__cp_hotels_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_param1`
--

DROP TABLE IF EXISTS `#__cp_hotels_param1`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_param1` (
  `product_id` int(11) NOT NULL,
  `param1_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param1_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_param1`
--

LOCK TABLES `#__cp_hotels_param1` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_param1` DISABLE KEYS */;
INSERT INTO `#__cp_hotels_param1` (`product_id`, `param1_id`) VALUES (1,1);
/*!40000 ALTER TABLE `#__cp_hotels_param1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_param2`
--

DROP TABLE IF EXISTS `#__cp_hotels_param2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_param2` (
  `product_id` int(11) NOT NULL,
  `param2_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_param2`
--

LOCK TABLES `#__cp_hotels_param2` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_param2` DISABLE KEYS */;
INSERT INTO `#__cp_hotels_param2` (`product_id`, `param2_id`) VALUES (1,1);
/*!40000 ALTER TABLE `#__cp_hotels_param2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_param3`
--

DROP TABLE IF EXISTS `#__cp_hotels_param3`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_param3` (
  `product_id` int(11) NOT NULL,
  `param3_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param3_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_param3`
--

LOCK TABLES `#__cp_hotels_param3` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_param3` DISABLE KEYS */;
INSERT INTO `#__cp_hotels_param3` (`product_id`, `param3_id`) VALUES (1,1);
/*!40000 ALTER TABLE `#__cp_hotels_param3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_rate`
--

DROP TABLE IF EXISTS `#__cp_hotels_rate`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_rate` (
  `rate_id` int(11) NOT NULL auto_increment,
  `season_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `basic_price` double default NULL,
  `previous_value` double default NULL,
  `currency_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY  (`rate_id`),
  KEY `hotels_rate_product_id` (`product_id`),
  KEY `hotels_rate_season_id` (`season_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_rate`
--

LOCK TABLES `#__cp_hotels_rate` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_rate` DISABLE KEYS */;
INSERT INTO `#__cp_hotels_rate` (`rate_id`, `season_id`, `product_id`, `basic_price`, `previous_value`, `currency_id`, `created`, `created_by`, `modified`, `modified_by`) VALUES (1,1,1,100,50,0,'2013-08-29 18:38:40',62,'2013-08-29 18:38:40',62);
/*!40000 ALTER TABLE `#__cp_hotels_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_rate_price`
--

DROP TABLE IF EXISTS `#__cp_hotels_rate_price`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_rate_price` (
  `rate_id` int(11) NOT NULL,
  `param1` int(11) NOT NULL default '0',
  `param2` int(11) NOT NULL default '0',
  `param3` int(11) NOT NULL default '0',
  `price` double NOT NULL,
  `is_child` tinyint(4) NOT NULL default '0',
  UNIQUE KEY `hotels_rate_price_unique` (`rate_id`,`param1`,`param3`,`param2`),
  KEY `hotels_rate_id` (`rate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_rate_price`
--

LOCK TABLES `#__cp_hotels_rate_price` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_rate_price` DISABLE KEYS */;
INSERT INTO `#__cp_hotels_rate_price` (`rate_id`, `param1`, `param2`, `param3`, `price`, `is_child`) VALUES (1,1,1,1,10,0),(1,0,0,1,10,1);
/*!40000 ALTER TABLE `#__cp_hotels_rate_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_rate_supplement`
--

DROP TABLE IF EXISTS `#__cp_hotels_rate_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_rate_supplement` (
  `rate_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY  (`rate_id`,`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_rate_supplement`
--

LOCK TABLES `#__cp_hotels_rate_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_rate_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_hotels_rate_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_resume`
--

DROP TABLE IF EXISTS `#__cp_hotels_resume`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_resume` (
  `product_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `stock` int(11) NOT NULL,
  `adult_price` double NOT NULL,
  `currency_id` int(11) NOT NULL,
  `child_price` double NOT NULL,
  `previous_price` double NOT NULL,
  UNIQUE KEY `hotels_resume_unique_index` (`product_id`,`date`),
  KEY `hotels_resume_date` (`date`),
  KEY `hotels_resume_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_resume`
--

LOCK TABLES `#__cp_hotels_resume` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_resume` DISABLE KEYS */;
INSERT INTO `#__cp_hotels_resume` (`product_id`, `date`, `stock`, `adult_price`, `currency_id`, `child_price`, `previous_price`) VALUES (1,'2013-08-29',0,100,2,10,50),(1,'2013-08-30',0,100,2,10,50),(1,'2013-08-31',0,100,2,10,50),(1,'2013-09-01',0,100,2,10,50),(1,'2013-09-02',0,100,2,10,50),(1,'2013-09-03',0,100,2,10,50),(1,'2013-09-04',0,100,2,10,50),(1,'2013-09-05',0,100,2,10,50),(1,'2013-09-06',0,100,2,10,50),(1,'2013-09-07',0,100,2,10,50),(1,'2013-09-08',0,100,2,10,50),(1,'2013-09-09',0,100,2,10,50),(1,'2013-09-10',0,100,2,10,50),(1,'2013-09-11',0,100,2,10,50),(1,'2013-09-12',0,100,2,10,50),(1,'2013-09-13',0,100,2,10,50),(1,'2013-09-14',0,100,2,10,50),(1,'2013-09-15',0,100,2,10,50),(1,'2013-09-16',0,100,2,10,50),(1,'2013-09-17',0,100,2,10,50),(1,'2013-09-18',0,100,2,10,50),(1,'2013-09-19',0,100,2,10,50),(1,'2013-09-20',0,100,2,10,50),(1,'2013-09-21',0,100,2,10,50),(1,'2013-09-22',0,100,2,10,50),(1,'2013-09-23',0,100,2,10,50),(1,'2013-09-24',0,100,2,10,50),(1,'2013-09-25',0,100,2,10,50),(1,'2013-09-26',0,100,2,10,50),(1,'2013-09-27',0,100,2,10,50),(1,'2013-09-28',0,100,2,10,50),(1,'2013-09-29',0,100,2,10,50),(1,'2013-09-30',0,100,2,10,50);
/*!40000 ALTER TABLE `#__cp_hotels_resume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_stock`
--

DROP TABLE IF EXISTS `#__cp_hotels_stock`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_stock` (
  `product_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `quantity` int(11) NOT NULL,
  UNIQUE KEY `hotels_stock_unique_index` (`product_id`,`param_id`,`day`),
  KEY `hotels_stock_product_id_index` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_stock`
--

LOCK TABLES `#__cp_hotels_stock` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_stock` DISABLE KEYS */;
INSERT INTO `#__cp_hotels_stock` (`product_id`, `param_id`, `day`, `quantity`) VALUES (1,1,'2013-08-29',50),(1,1,'2013-08-30',49),(1,1,'2013-08-31',49),(1,1,'2013-09-01',49),(1,1,'2013-09-02',49),(1,1,'2013-09-03',49),(1,1,'2013-09-04',49),(1,1,'2013-09-05',49),(1,1,'2013-09-06',50),(1,1,'2013-09-07',50),(1,1,'2013-09-08',50),(1,1,'2013-09-09',50),(1,1,'2013-09-10',50),(1,1,'2013-09-11',50),(1,1,'2013-09-12',50),(1,1,'2013-09-13',50),(1,1,'2013-09-14',50),(1,1,'2013-09-15',50),(1,1,'2013-09-16',50),(1,1,'2013-09-17',50),(1,1,'2013-09-18',50),(1,1,'2013-09-19',50),(1,1,'2013-09-20',50),(1,1,'2013-09-21',50),(1,1,'2013-09-22',50),(1,1,'2013-09-23',50),(1,1,'2013-09-24',50),(1,1,'2013-09-25',50),(1,1,'2013-09-26',50),(1,1,'2013-09-27',50),(1,1,'2013-09-28',50),(1,1,'2013-09-29',50),(1,1,'2013-09-30',50),(1,1,'2013-10-01',50),(1,1,'2013-10-02',50),(1,1,'2013-10-03',50),(1,1,'2013-10-04',50),(1,1,'2013-10-05',50),(1,1,'2013-10-06',50),(1,1,'2013-10-07',50),(1,1,'2013-10-08',50),(1,1,'2013-10-09',50),(1,1,'2013-10-10',50),(1,1,'2013-10-11',50),(1,1,'2013-10-12',50),(1,1,'2013-10-13',50),(1,1,'2013-10-14',50),(1,1,'2013-10-15',50),(1,1,'2013-10-16',50),(1,1,'2013-10-17',50),(1,1,'2013-10-18',50),(1,1,'2013-10-19',50),(1,1,'2013-10-20',50),(1,1,'2013-10-21',50),(1,1,'2013-10-22',50),(1,1,'2013-10-23',50),(1,1,'2013-10-24',50),(1,1,'2013-10-25',50),(1,1,'2013-10-26',50),(1,1,'2013-10-27',50),(1,1,'2013-10-28',50),(1,1,'2013-10-29',50),(1,1,'2013-10-30',50),(1,1,'2013-10-31',50),(1,1,'2013-11-01',50),(1,1,'2013-11-02',50),(1,1,'2013-11-03',50),(1,1,'2013-11-04',50),(1,1,'2013-11-05',50),(1,1,'2013-11-06',50),(1,1,'2013-11-07',50),(1,1,'2013-11-08',50),(1,1,'2013-11-09',50),(1,1,'2013-11-10',50),(1,1,'2013-11-11',50),(1,1,'2013-11-12',50),(1,1,'2013-11-13',50),(1,1,'2013-11-14',50),(1,1,'2013-11-15',50),(1,1,'2013-11-16',50),(1,1,'2013-11-17',50),(1,1,'2013-11-18',50),(1,1,'2013-11-19',50),(1,1,'2013-11-20',50),(1,1,'2013-11-21',50),(1,1,'2013-11-22',50),(1,1,'2013-11-23',50),(1,1,'2013-11-24',50),(1,1,'2013-11-25',50),(1,1,'2013-11-26',50),(1,1,'2013-11-27',50),(1,1,'2013-11-28',50),(1,1,'2013-11-29',50),(1,1,'2013-11-30',50),(1,1,'2013-12-01',50),(1,1,'2013-12-02',50),(1,1,'2013-12-03',50),(1,1,'2013-12-04',50),(1,1,'2013-12-05',50),(1,1,'2013-12-06',50),(1,1,'2013-12-07',50),(1,1,'2013-12-08',50),(1,1,'2013-12-09',50),(1,1,'2013-12-10',50),(1,1,'2013-12-11',50),(1,1,'2013-12-12',50),(1,1,'2013-12-13',50),(1,1,'2013-12-14',50),(1,1,'2013-12-15',50),(1,1,'2013-12-16',50),(1,1,'2013-12-17',50),(1,1,'2013-12-18',50),(1,1,'2013-12-19',50),(1,1,'2013-12-20',50),(1,1,'2013-12-21',50),(1,1,'2013-12-22',50),(1,1,'2013-12-23',50),(1,1,'2013-12-24',50),(1,1,'2013-12-25',50),(1,1,'2013-12-26',50),(1,1,'2013-12-27',50),(1,1,'2013-12-28',50),(1,1,'2013-12-29',50),(1,1,'2013-12-30',50),(1,1,'2013-12-31',50),(1,1,'2014-01-01',50),(1,1,'2014-01-02',50),(1,1,'2014-01-03',50),(1,1,'2014-01-04',50),(1,1,'2014-01-05',50),(1,1,'2014-01-06',50),(1,1,'2014-01-07',50),(1,1,'2014-01-08',50),(1,1,'2014-01-09',50),(1,1,'2014-01-10',50),(1,1,'2014-01-11',50),(1,1,'2014-01-12',50),(1,1,'2014-01-13',50),(1,1,'2014-01-14',50),(1,1,'2014-01-15',50),(1,1,'2014-01-16',50),(1,1,'2014-01-17',50),(1,1,'2014-01-18',50),(1,1,'2014-01-19',50),(1,1,'2014-01-20',50),(1,1,'2014-01-21',50),(1,1,'2014-01-22',50),(1,1,'2014-01-23',50),(1,1,'2014-01-24',50),(1,1,'2014-01-25',50),(1,1,'2014-01-26',50),(1,1,'2014-01-27',50),(1,1,'2014-01-28',50),(1,1,'2014-01-29',50),(1,1,'2014-01-30',50),(1,1,'2014-01-31',50),(1,1,'2014-02-01',50),(1,1,'2014-02-02',50),(1,1,'2014-02-03',50),(1,1,'2014-02-04',50),(1,1,'2014-02-05',50),(1,1,'2014-02-06',50),(1,1,'2014-02-07',50),(1,1,'2014-02-08',50),(1,1,'2014-02-09',50),(1,1,'2014-02-10',50),(1,1,'2014-02-11',50),(1,1,'2014-02-12',50),(1,1,'2014-02-13',50),(1,1,'2014-02-14',50),(1,1,'2014-02-15',50),(1,1,'2014-02-16',50),(1,1,'2014-02-17',50),(1,1,'2014-02-18',50),(1,1,'2014-02-19',50),(1,1,'2014-02-20',50),(1,1,'2014-02-21',50),(1,1,'2014-02-22',50),(1,1,'2014-02-23',50),(1,1,'2014-02-24',50),(1,1,'2014-02-25',50),(1,1,'2014-02-26',50),(1,1,'2014-02-27',50),(1,1,'2014-02-28',50),(1,1,'2014-03-01',50),(1,1,'2014-03-02',50),(1,1,'2014-03-03',50),(1,1,'2014-03-04',50),(1,1,'2014-03-05',50),(1,1,'2014-03-06',50),(1,1,'2014-03-07',50),(1,1,'2014-03-08',50),(1,1,'2014-03-09',50),(1,1,'2014-03-10',50),(1,1,'2014-03-11',50),(1,1,'2014-03-12',50),(1,1,'2014-03-13',50),(1,1,'2014-03-14',50),(1,1,'2014-03-15',50),(1,1,'2014-03-16',50),(1,1,'2014-03-17',50),(1,1,'2014-03-18',50),(1,1,'2014-03-19',50),(1,1,'2014-03-20',50),(1,1,'2014-03-21',50),(1,1,'2014-03-22',50),(1,1,'2014-03-23',50),(1,1,'2014-03-24',50),(1,1,'2014-03-25',50),(1,1,'2014-03-26',50),(1,1,'2014-03-27',50),(1,1,'2014-03-28',50),(1,1,'2014-03-29',50),(1,1,'2014-03-30',50),(1,1,'2014-03-31',50),(1,1,'2014-04-01',50),(1,1,'2014-04-02',50),(1,1,'2014-04-03',50),(1,1,'2014-04-04',50),(1,1,'2014-04-05',50),(1,1,'2014-04-06',50),(1,1,'2014-04-07',50),(1,1,'2014-04-08',50),(1,1,'2014-04-09',50),(1,1,'2014-04-10',50),(1,1,'2014-04-11',50),(1,1,'2014-04-12',50),(1,1,'2014-04-13',50),(1,1,'2014-04-14',50),(1,1,'2014-04-15',50),(1,1,'2014-04-16',50),(1,1,'2014-04-17',50),(1,1,'2014-04-18',50),(1,1,'2014-04-19',50),(1,1,'2014-04-20',50),(1,1,'2014-04-21',50),(1,1,'2014-04-22',50),(1,1,'2014-04-23',50),(1,1,'2014-04-24',50),(1,1,'2014-04-25',50),(1,1,'2014-04-26',50),(1,1,'2014-04-27',50),(1,1,'2014-04-28',50),(1,1,'2014-04-29',50),(1,1,'2014-04-30',50),(1,1,'2014-05-01',50),(1,1,'2014-05-02',50),(1,1,'2014-05-03',50),(1,1,'2014-05-04',50),(1,1,'2014-05-05',50),(1,1,'2014-05-06',50),(1,1,'2014-05-07',50),(1,1,'2014-05-08',50),(1,1,'2014-05-09',50),(1,1,'2014-05-10',50),(1,1,'2014-05-11',50),(1,1,'2014-05-12',50),(1,1,'2014-05-13',50),(1,1,'2014-05-14',50),(1,1,'2014-05-15',50),(1,1,'2014-05-16',50),(1,1,'2014-05-17',50),(1,1,'2014-05-18',50),(1,1,'2014-05-19',50),(1,1,'2014-05-20',50),(1,1,'2014-05-21',50),(1,1,'2014-05-22',50),(1,1,'2014-05-23',50),(1,1,'2014-05-24',50),(1,1,'2014-05-25',50),(1,1,'2014-05-26',50),(1,1,'2014-05-27',50),(1,1,'2014-05-28',50),(1,1,'2014-05-29',50),(1,1,'2014-05-30',50),(1,1,'2014-05-31',50),(1,1,'2014-06-01',50),(1,1,'2014-06-02',50),(1,1,'2014-06-03',50),(1,1,'2014-06-04',50),(1,1,'2014-06-05',50),(1,1,'2014-06-06',50),(1,1,'2014-06-07',50),(1,1,'2014-06-08',50),(1,1,'2014-06-09',50),(1,1,'2014-06-10',50),(1,1,'2014-06-11',50),(1,1,'2014-06-12',50),(1,1,'2014-06-13',50),(1,1,'2014-06-14',50),(1,1,'2014-06-15',50),(1,1,'2014-06-16',50),(1,1,'2014-06-17',50),(1,1,'2014-06-18',50),(1,1,'2014-06-19',50),(1,1,'2014-06-20',50),(1,1,'2014-06-21',50),(1,1,'2014-06-22',50),(1,1,'2014-06-23',50),(1,1,'2014-06-24',50),(1,1,'2014-06-25',50),(1,1,'2014-06-26',50),(1,1,'2014-06-27',50),(1,1,'2014-06-28',50),(1,1,'2014-06-29',50),(1,1,'2014-06-30',50),(1,1,'2014-07-01',50),(1,1,'2014-07-02',50),(1,1,'2014-07-03',50),(1,1,'2014-07-04',50),(1,1,'2014-07-05',50),(1,1,'2014-07-06',50),(1,1,'2014-07-07',50),(1,1,'2014-07-08',50),(1,1,'2014-07-09',50),(1,1,'2014-07-10',50),(1,1,'2014-07-11',50),(1,1,'2014-07-12',50),(1,1,'2014-07-13',50),(1,1,'2014-07-14',50),(1,1,'2014-07-15',50),(1,1,'2014-07-16',50),(1,1,'2014-07-17',50),(1,1,'2014-07-18',50),(1,1,'2014-07-19',50),(1,1,'2014-07-20',50),(1,1,'2014-07-21',50),(1,1,'2014-07-22',50),(1,1,'2014-07-23',50),(1,1,'2014-07-24',50),(1,1,'2014-07-25',50),(1,1,'2014-07-26',50),(1,1,'2014-07-27',50),(1,1,'2014-07-28',50),(1,1,'2014-07-29',50),(1,1,'2014-07-30',50),(1,1,'2014-07-31',50),(1,1,'2014-08-01',50),(1,1,'2014-08-02',50),(1,1,'2014-08-03',50),(1,1,'2014-08-04',50),(1,1,'2014-08-05',50),(1,1,'2014-08-06',50),(1,1,'2014-08-07',50),(1,1,'2014-08-08',50),(1,1,'2014-08-09',50),(1,1,'2014-08-10',50),(1,1,'2014-08-11',50),(1,1,'2014-08-12',50),(1,1,'2014-08-13',50),(1,1,'2014-08-14',50),(1,1,'2014-08-15',50),(1,1,'2014-08-16',50),(1,1,'2014-08-17',50),(1,1,'2014-08-18',50),(1,1,'2014-08-19',50),(1,1,'2014-08-20',50),(1,1,'2014-08-21',50),(1,1,'2014-08-22',50),(1,1,'2014-08-23',50),(1,1,'2014-08-24',50),(1,1,'2014-08-25',50),(1,1,'2014-08-26',50),(1,1,'2014-08-27',50),(1,1,'2014-08-28',50),(1,1,'2014-08-29',50);
/*!40000 ALTER TABLE `#__cp_hotels_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_supplement`
--

DROP TABLE IF EXISTS `#__cp_hotels_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_supplement` (
  `product_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `apply_once` tinyint(4) NOT NULL,
  PRIMARY KEY  (`product_id`,`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_supplement`
--

LOCK TABLES `#__cp_hotels_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_hotels_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_supplement_tax`
--

DROP TABLE IF EXISTS `#__cp_hotels_supplement_tax`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_supplement_tax` (
  `product_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`supplement_id`,`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_supplement_tax`
--

LOCK TABLES `#__cp_hotels_supplement_tax` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_supplement_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_hotels_supplement_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_taxes`
--

DROP TABLE IF EXISTS `#__cp_hotels_taxes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_taxes` (
  `product_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_taxes`
--

LOCK TABLES `#__cp_hotels_taxes` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_taxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_hotels_taxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_hotels_tourismtype`
--

DROP TABLE IF EXISTS `#__cp_hotels_tourismtype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_hotels_tourismtype` (
  `product_id` int(11) NOT NULL,
  `tourismtype_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`tourismtype_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_hotels_tourismtype`
--

LOCK TABLES `#__cp_hotels_tourismtype` WRITE;
/*!40000 ALTER TABLE `#__cp_hotels_tourismtype` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_hotels_tourismtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_pending_comments`
--

DROP TABLE IF EXISTS `#__cp_pending_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_pending_comments` (
  `comment_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `language` varchar(5) NOT NULL,
  `end_date` date NOT NULL,
  `product_type_id` int(11) NOT NULL,
  `total_attempts` int(11) NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  UNIQUE KEY `pending_comments_unique_order` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_pending_comments`
--

LOCK TABLES `#__cp_pending_comments` WRITE;
/*!40000 ALTER TABLE `#__cp_pending_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_pending_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_comments`
--

DROP TABLE IF EXISTS `#__cp_plans_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_comments` (
  `comment_id` int(11) NOT NULL auto_increment,
  `order_id` tinyint(4) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `comment_rate` int(11) NOT NULL,
  `comment_text` varchar(400) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  `end_date` date NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_comments`
--

LOCK TABLES `#__cp_plans_comments` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_plans_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_files`
--

DROP TABLE IF EXISTS `#__cp_plans_files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_files` (
  `product_id` int(11) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  UNIQUE KEY `plans_files_unique` (`product_id`,`file_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_files`
--

LOCK TABLES `#__cp_plans_files` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_plans_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_info`
--

DROP TABLE IF EXISTS `#__cp_plans_info`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_info` (
  `product_id` int(11) NOT NULL auto_increment,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(10) default NULL,
  `product_desc` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `region_id` int(11) default NULL,
  `city_id` int(11) NOT NULL,
  `featured` tinyint(4) NOT NULL,
  `latitude` varchar(255) default NULL,
  `longitude` varchar(255) default NULL,
  `ordering` int(11) NOT NULL default '0',
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `access` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `tag_name1` varchar(255) default NULL,
  `tag_content1` mediumtext,
  `tag_name2` varchar(255) default NULL,
  `tag_content2` mediumtext,
  `tag_name3` varchar(255) default NULL,
  `tag_content3` mediumtext,
  `tag_name4` varchar(255) default NULL,
  `tag_content4` mediumtext,
  `tag_name5` varchar(255) default NULL,
  `tag_content5` mediumtext,
  `tag_name6` varchar(255) default NULL,
  `tag_content6` mediumtext,
  `product_url` mediumtext,
  `supplier_id` int(11) default NULL,
  `duration` varchar(20) NOT NULL,
  `days_total` int(11) NOT NULL,
  `average_rating` tinyint(4) default '0',
  `additional_description` varchar(255) default NULL,
  `disclaimer` varchar(500) default NULL,
  PRIMARY KEY  (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Guarda los productos del Catálogo.';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_info`
--

LOCK TABLES `#__cp_plans_info` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_info` DISABLE KEYS */;
INSERT INTO `#__cp_plans_info` (`product_id`, `product_name`, `product_code`, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, `created`, `created_by`, `modified`, `modified_by`, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `duration`, `days_total`, `average_rating`, `additional_description`, `disclaimer`) VALUES (1,'Primer Paquete Ejemplo','0000000003','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis,',1,38,0,1622,1,'-8.841651120809145','-58.095703125',0,'2013-08-29 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'2013-09-02 15:30:38',62,0,1,'Descripción General','','Incluye','','No incluye','','Itinerario','','Pago y condiciones del servicio','','','','',1,'15 días',15,0,NULL,'Tarifas incluyen impuestos');
/*!40000 ALTER TABLE `#__cp_plans_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_param1`
--

DROP TABLE IF EXISTS `#__cp_plans_param1`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_param1` (
  `product_id` int(11) NOT NULL,
  `param1_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param1_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_param1`
--

LOCK TABLES `#__cp_plans_param1` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_param1` DISABLE KEYS */;
INSERT INTO `#__cp_plans_param1` (`product_id`, `param1_id`) VALUES (1,1);
/*!40000 ALTER TABLE `#__cp_plans_param1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_param2`
--

DROP TABLE IF EXISTS `#__cp_plans_param2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_param2` (
  `product_id` int(11) NOT NULL,
  `param2_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_param2`
--

LOCK TABLES `#__cp_plans_param2` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_param2` DISABLE KEYS */;
INSERT INTO `#__cp_plans_param2` (`product_id`, `param2_id`) VALUES (1,1);
/*!40000 ALTER TABLE `#__cp_plans_param2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_param3`
--

DROP TABLE IF EXISTS `#__cp_plans_param3`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_param3` (
  `product_id` int(11) NOT NULL,
  `param3_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param3_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_param3`
--

LOCK TABLES `#__cp_plans_param3` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_param3` DISABLE KEYS */;
INSERT INTO `#__cp_plans_param3` (`product_id`, `param3_id`) VALUES (1,1);
/*!40000 ALTER TABLE `#__cp_plans_param3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_rate`
--

DROP TABLE IF EXISTS `#__cp_plans_rate`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_rate` (
  `rate_id` int(11) NOT NULL auto_increment,
  `season_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `basic_price` double default NULL,
  `previous_value` double default NULL,
  `currency_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY  (`rate_id`),
  KEY `plans_rate_product_id` (`product_id`),
  KEY `plans_rate_season_id` (`season_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_rate`
--

LOCK TABLES `#__cp_plans_rate` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_rate` DISABLE KEYS */;
INSERT INTO `#__cp_plans_rate` (`rate_id`, `season_id`, `product_id`, `basic_price`, `previous_value`, `currency_id`, `created`, `created_by`, `modified`, `modified_by`) VALUES (1,1,1,100,100,0,'2013-08-29 18:51:41',62,'2013-08-29 18:51:41',62);
/*!40000 ALTER TABLE `#__cp_plans_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_rate_price`
--

DROP TABLE IF EXISTS `#__cp_plans_rate_price`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_rate_price` (
  `rate_id` int(11) NOT NULL,
  `param1` int(11) NOT NULL default '0',
  `param2` int(11) NOT NULL default '0',
  `param3` int(11) NOT NULL default '0',
  `price` double NOT NULL,
  `is_child` tinyint(4) NOT NULL default '0',
  UNIQUE KEY `plans_rate_price_unique` (`rate_id`,`param1`,`param3`,`param2`),
  KEY `plans_rate_id` (`rate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_rate_price`
--

LOCK TABLES `#__cp_plans_rate_price` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_rate_price` DISABLE KEYS */;
INSERT INTO `#__cp_plans_rate_price` (`rate_id`, `param1`, `param2`, `param3`, `price`, `is_child`) VALUES (1,1,1,1,10,0),(1,1,0,1,10,1);
/*!40000 ALTER TABLE `#__cp_plans_rate_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_rate_supplement`
--

DROP TABLE IF EXISTS `#__cp_plans_rate_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_rate_supplement` (
  `rate_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY  (`rate_id`,`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_rate_supplement`
--

LOCK TABLES `#__cp_plans_rate_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_rate_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_plans_rate_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_resume`
--

DROP TABLE IF EXISTS `#__cp_plans_resume`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_resume` (
  `product_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `stock` int(11) NOT NULL,
  `adult_price` double NOT NULL,
  `currency_id` int(11) NOT NULL,
  `child_price` double NOT NULL,
  `previous_price` double NOT NULL,
  UNIQUE KEY `plans_resume_unique_index` (`product_id`,`date`),
  KEY `plans_resume_date` (`date`),
  KEY `plans_resume_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_resume`
--

LOCK TABLES `#__cp_plans_resume` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_resume` DISABLE KEYS */;
INSERT INTO `#__cp_plans_resume` (`product_id`, `date`, `stock`, `adult_price`, `currency_id`, `child_price`, `previous_price`) VALUES (1,'2013-08-29',0,100,2,10,100),(1,'2013-08-30',0,100,2,10,100),(1,'2013-08-31',0,100,2,10,100),(1,'2013-09-01',0,100,2,10,100),(1,'2013-09-02',0,100,2,10,100),(1,'2013-09-03',0,100,2,10,100),(1,'2013-09-04',0,100,2,10,100),(1,'2013-09-05',0,100,2,10,100),(1,'2013-09-06',0,100,2,10,100),(1,'2013-09-07',0,100,2,10,100),(1,'2013-09-08',0,100,2,10,100),(1,'2013-09-09',0,100,2,10,100),(1,'2013-09-10',0,100,2,10,100),(1,'2013-09-11',0,100,2,10,100),(1,'2013-09-12',0,100,2,10,100),(1,'2013-09-13',0,100,2,10,100),(1,'2013-09-14',0,100,2,10,100),(1,'2013-09-15',0,100,2,10,100),(1,'2013-09-16',0,100,2,10,100),(1,'2013-09-17',0,100,2,10,100),(1,'2013-09-18',0,100,2,10,100),(1,'2013-09-19',0,100,2,10,100),(1,'2013-09-20',0,100,2,10,100),(1,'2013-09-21',0,100,2,10,100),(1,'2013-09-22',0,100,2,10,100),(1,'2013-09-23',0,100,2,10,100),(1,'2013-09-24',0,100,2,10,100),(1,'2013-09-25',0,100,2,10,100),(1,'2013-09-26',0,100,2,10,100),(1,'2013-09-27',0,100,2,10,100),(1,'2013-09-28',0,100,2,10,100),(1,'2013-09-29',0,100,2,10,100),(1,'2013-09-30',0,100,2,10,100);
/*!40000 ALTER TABLE `#__cp_plans_resume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_stock`
--

DROP TABLE IF EXISTS `#__cp_plans_stock`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_stock` (
  `product_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `quantity` int(11) NOT NULL,
  UNIQUE KEY `plans_stock_unique_index` (`product_id`,`param_id`,`day`),
  KEY `plans_stock_product_id_index` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_stock`
--

LOCK TABLES `#__cp_plans_stock` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_plans_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_supplement`
--

DROP TABLE IF EXISTS `#__cp_plans_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_supplement` (
  `product_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_supplement`
--

LOCK TABLES `#__cp_plans_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_plans_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_supplement_tax`
--

DROP TABLE IF EXISTS `#__cp_plans_supplement_tax`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_supplement_tax` (
  `product_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`supplement_id`,`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_supplement_tax`
--

LOCK TABLES `#__cp_plans_supplement_tax` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_supplement_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_plans_supplement_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_taxes`
--

DROP TABLE IF EXISTS `#__cp_plans_taxes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_taxes` (
  `product_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_taxes`
--

LOCK TABLES `#__cp_plans_taxes` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_taxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_plans_taxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_plans_tourismtype`
--

DROP TABLE IF EXISTS `#__cp_plans_tourismtype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_plans_tourismtype` (
  `product_id` int(11) NOT NULL,
  `tourismtype_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`tourismtype_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_plans_tourismtype`
--

LOCK TABLES `#__cp_plans_tourismtype` WRITE;
/*!40000 ALTER TABLE `#__cp_plans_tourismtype` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_plans_tourismtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_cars_category`
--

DROP TABLE IF EXISTS `#__cp_prm_cars_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_cars_category` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(100) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_cars_category`
--

LOCK TABLES `#__cp_prm_cars_category` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_cars_category` DISABLE KEYS */;
INSERT INTO `#__cp_prm_cars_category` (`category_id`, `category_name`, `published`) VALUES (1,'Económico',1),(2,'Sedán',1),(3,'4X4',1),(4,'Camioneta',1);
/*!40000 ALTER TABLE `#__cp_prm_cars_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_cars_delivery_city`
--

DROP TABLE IF EXISTS `#__cp_prm_cars_delivery_city`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_cars_delivery_city` (
  `city_id` int(11) NOT NULL auto_increment,
  `city_name` varchar(100) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`city_id`)
) ENGINE=MyISAM AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_cars_delivery_city`
--

LOCK TABLES `#__cp_prm_cars_delivery_city` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_cars_delivery_city` DISABLE KEYS */;
INSERT INTO `#__cp_prm_cars_delivery_city` (`city_id`, `city_name`, `published`) VALUES (1,'Acandi',1),(2,'Acaricuara',1),(3,'Aguaclara',1),(4,'Amalfi',1),(5,'Andes',1),(6,'Apartado',1),(7,'Apiay',1),(8,'Araracuara',1),(9,'Arauca',1),(10,'Arauquita',1),(11,'Arboletas',1),(12,'Arica',1),(13,'Armenia',1),(14,'Ayacucho',1),(15,'Ayapel',1),(16,'Bahia Cupica',1),(17,'Bahia Solano',1),(18,'Barranca De Upia',1),(19,'Barrancabermeja',1),(20,'Barrancominas',1),(21,'Barranquilla',1),(22,'Bogota',1),(23,'Bucaramanga',1),(24,'Buenaventura',1),(25,'Cali',1),(26,'Candilejas',1),(27,'Capurgana',1),(28,'Caquetania',1),(29,'Carimagua',1),(30,'Cartagena',1),(31,'Cartago',1),(32,'Caruru',1),(33,'Casuarito',1),(34,'Caucasia',1),(35,'Chaparral',1),(36,'Chigorodo',1),(37,'Chivolo',1),(38,'Cimitarra',1),(39,'Codazzi',1),(40,'Condoto',1),(41,'Corozal',1),(42,'Covenas',1),(43,'Cravo Norte',1),(44,'Cucuta',1),(45,'Currillo',1),(46,'El Bagre',1),(47,'El Banco',1),(48,'El Charco',1),(49,'El Encanto',1),(50,'El Recreo',1),(51,'El Yopal',1),(52,'Florencia',1),(53,'Gamarra',1),(54,'Gilgal',1),(55,'Girardot',1),(56,'Guacamaya',1),(57,'Guamal',1),(58,'Guapi',1),(59,'Guerima',1),(60,'Hato Corozal',1),(61,'Herrera',1),(62,'Ibague',1),(63,'Ipiales',1),(64,'Iscuande',1),(65,'Jurado',1),(66,'La Chorrera',1),(67,'La Pedrera',1),(68,'La Primavera',1),(69,'La Uribe',1),(70,'Lamacarena',1),(71,'Las Gaviotas',1),(72,'Leguizamo',1),(73,'Leticia',1),(74,'Lopez De Micay',1),(75,'Lorica',1),(76,'Macanal',1),(77,'Magangue',1),(78,'Maicao',1),(79,'Manizales',1),(80,'Mariquita',1),(81,'Medellin',1),(82,'Medellin',1),(83,'Medina',1),(84,'Miraflores',1),(85,'Miriti',1),(86,'Mitu',1),(87,'Mompos',1),(88,'Monfort',1),(89,'Monte Libano',1),(90,'Monteria',1),(91,'Monterrey',1),(92,'Morichal',1),(93,'Mosquera',1),(94,'Mulatos',1),(95,'Nare',1),(96,'Necocli',1),(97,'Neiva',1),(98,'Nunchia',1),(99,'Nuqui',1),(100,'Ocana',1),(101,'Orocue',1),(102,'Otu',1),(103,'Palanquero',1),(104,'Palmira',1),(105,'Paratebueno',1),(106,'Pasto',1),(107,'Payan',1),(108,'Paz De Ariporo',1),(109,'Pereira',1),(110,'Pitalito',1),(111,'Planadas',1),(112,'Planeta Rica',1),(113,'Plato',1),(114,'Popayan',1),(115,'Pore',1),(116,'Providencia',1),(117,'Puerto Asis',1),(118,'Puerto Berrio',1),(119,'Puerto Boyaca',1),(120,'Puerto Carreno',1),(121,'Puerto Inirida',1),(122,'Puerto Leguizamo',1),(123,'Puerto Rico',1),(124,'Quibdo',1),(125,'Riohacha',1),(126,'Rondon',1),(127,'Sabana De Torres',1),(128,'San Andres Isla',1),(129,'San Felipe',1),(130,'San Jose Del Gua',1),(131,'San Juan D Ur',1),(132,'San Juan Del Cesa',1),(133,'San Luis De Pale',1),(134,'San Marcos',1),(135,'San Pedro Jagua',1),(136,'San Pedro Uraba',1),(137,'San Vicente',1),(138,'Santa Ana',1),(139,'Santa Catalina',1),(140,'Santa Maria',1),(141,'Santa Marta',1),(142,'Santa Rosalia',1),(143,'Santana Ramos',1),(144,'Saravena',1),(145,'Sogamoso',1),(146,'Solano',1),(147,'Solita',1),(148,'Tablon De Tamara',1),(149,'Tame',1),(150,'Tarapaca',1),(151,'Tauramena',1),(152,'Tibu',1),(153,'Timbiqui',1),(154,'Tolu',1),(155,'Tres Esquinas',1),(156,'Trinidad',1),(157,'Tulua',1),(158,'Tumaco',1),(159,'Turbo',1),(160,'Unguia',1),(161,'Uribe',1),(162,'Urrao',1),(163,'Valledupar',1),(164,'Villagarzon',1),(165,'Villavicencio',1),(166,'Yaguara',1),(167,'Yari',1),(168,'Yavarate',1),(169,'Zapatoca',1),(170,'Quindío',1),(171,'Puerto Triunfo',1),(172,'Guatape',1),(173,'test',1);
/*!40000 ALTER TABLE `#__cp_prm_cars_delivery_city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_cars_param1`
--

DROP TABLE IF EXISTS `#__cp_prm_cars_param1`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_cars_param1` (
  `param1_id` int(11) NOT NULL auto_increment,
  `param1_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param1_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_cars_param1`
--

LOCK TABLES `#__cp_prm_cars_param1` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_cars_param1` DISABLE KEYS */;
INSERT INTO `#__cp_prm_cars_param1` (`param1_id`, `param1_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Auto',NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `#__cp_prm_cars_param1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_cars_param2`
--

DROP TABLE IF EXISTS `#__cp_prm_cars_param2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_cars_param2` (
  `param2_id` int(11) NOT NULL auto_increment,
  `param2_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param2_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_cars_param2`
--

LOCK TABLES `#__cp_prm_cars_param2` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_cars_param2` DISABLE KEYS */;
INSERT INTO `#__cp_prm_cars_param2` (`param2_id`, `param2_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Hora',NULL,NULL,NULL,0),(2,'Día',NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `#__cp_prm_cars_param2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_city`
--

DROP TABLE IF EXISTS `#__cp_prm_city`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_city` (
  `city_id` int(11) NOT NULL auto_increment,
  `city_name` varchar(100) NOT NULL,
  `city_code` char(3) NOT NULL,
  `country_id` int(11) NOT NULL,
  `region_id` int(11) default NULL,
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`city_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10245 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_city`
--

LOCK TABLES `#__cp_prm_city` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_city` DISABLE KEYS */;
INSERT INTO `#__cp_prm_city` (`city_id`, `city_name`, `city_code`, `country_id`, `region_id`, `ordering`, `published`) VALUES (1,'Bagram','OAI',1,1,1,1),(2,'Bamiyan','BIN',1,1,1,1),(3,'Bost','BST',3,1,1,1),(4,'Camp Bastion','OAZ',1,1,1,1),(5,'Chakcharan','CCN',1,1,1,1),(6,'Darwaz','DAZ',1,1,3,1),(7,'Dwyer','DWR',1,1,1,1),(8,'Faizabad','FBD',1,1,1,1),(9,'Farah','FAH',1,1,2,1),(10,'Gardez','GRG',1,1,1,1),(11,'Ghazni','GZI',1,1,1,1),(12,'Herat','HEA',1,1,2,1),(13,'Jalalabad','JAA',1,1,1,1),(14,'Kabul','KBL',1,1,1,1),(15,'Kandahar','KDH',1,1,1,1),(16,'Khost','KHT',1,1,1,1),(17,'Khwahan','KWH',1,1,1,1),(18,'Kunduz','UND',1,1,2,1),(19,'Kuran O Munjan','KUR',1,1,1,1),(20,'Maimana','MMZ',1,1,1,1),(21,'Mazar I Sharif','MZR',1,1,5,1),(22,'Nimroz','IMZ',1,1,1,1),(23,'Qala Nau','LQN',1,1,1,1),(24,'Salerno','OLR',1,1,1,1),(25,'Sardeh Band','SBF',1,1,1,1),(26,'Shank','OAA',1,1,1,1),(27,'Sharana','OAS',1,1,1,1),(28,'Sheghnan','SGA',1,1,1,1),(29,'Taluqan','TQN',1,1,1,1),(30,'Tirinkot','TII',1,1,1,1),(31,'Urgoon','URN',1,1,1,1),(32,'Uruzgan','URZ',1,1,4,1),(33,'Zaranj','ZAJ',1,1,1,1),(34,'Tirana','TIA',2,2,1,1),(35,'Adrar','AZR',3,3,1,1),(36,'Ain Beida','QBP',3,3,1,1),(37,'Ain Mlila','QIM',3,3,1,1),(38,'Ain Temouchent','QIO',3,3,1,1),(39,'Annaba','AAE',3,3,1,1),(40,'Argel','ALG',3,3,1,1),(41,'Arzew','QAE',3,3,1,1),(42,'Azzazga','ZZA',3,3,1,1),(43,'Barika','ZZB',3,3,1,1),(44,'Batna','BLJ',3,3,1,1),(45,'Bechar','CBH',3,3,1,1),(46,'Bejaia','BJA',3,3,1,1),(47,'Bettioua','QBT',3,3,1,1),(48,'Biskra','BSK',3,3,1,1),(49,'Blida','QLD',3,3,1,1),(50,'Bordj Bou Arreri','QBJ',3,3,1,1),(51,'Bordj Menaiel','ZZM',3,3,1,1),(52,'Bordj Mokhtar','BMW',3,3,1,1),(53,'Bou Saada','BUJ',3,3,1,1),(54,'Boufarik','QFD',3,3,1,1),(55,'Bouira','QBZ',3,3,1,1),(56,'Boukadir','ZAB',3,3,1,1),(57,'Chelghoum Laid','QGM',3,3,1,1),(58,'Cherchell','ZZC',3,3,1,1),(59,'Chlef','CFK',3,3,1,1),(60,'Collo','QOL',3,3,1,1),(61,'Constantine','CZL',3,3,1,1),(62,'Djanet','DJG',3,3,1,1),(63,'Djelfa','QDJ',3,3,1,1),(64,'Dra El Mizan','ZZD',3,3,1,1),(65,'El Bayadh','EBH',3,3,1,1),(66,'El Eulma','ZAE',3,3,1,1),(67,'El Ghazaouet','QAG',3,3,1,1),(68,'El Golea','ELG',3,3,1,1),(69,'El Hadjar','QEH',3,3,1,1),(70,'El Harrouche','QHH',3,3,1,1),(71,'El Kala','QLK',3,3,1,1),(72,'El Oued','ELU',3,3,1,1),(73,'Ghardaia','GHA',3,3,1,1),(74,'Ghazaouet','QVX',3,3,1,1),(75,'Guelma','QGE',3,3,1,1),(76,'Hassi Messaoud','HME',3,3,1,1),(77,'Hassi R\'mel','HRM',3,3,1,1),(78,'Illizi','VVZ',3,3,1,1),(79,'In Amenas','IAM',3,3,1,1),(80,'In Guezzam','INF',3,3,1,1),(81,'In Salah','INZ',3,3,1,1),(82,'Jijel','GJL',3,3,1,1),(83,'Khemis Miliana','ZZK',3,3,1,1),(84,'Khenchela','QKJ',3,3,1,1),(85,'Kherrata','ZZR',3,3,1,1),(86,'Kouba','KDF',3,3,1,1),(87,'Laghouat','LOO',3,3,1,1),(88,'Larba Nath Iraten','ZZL',3,3,1,1),(89,'M Sila','ZZS',3,3,1,1),(90,'Maghnia','QMG',3,3,1,1),(91,'Mascara','MUW',3,3,1,1),(92,'Mecheria','MZW',3,3,1,1),(93,'Medea','QED',3,3,1,1),(94,'Mohammadia','QMW',3,3,1,1),(95,'Nedroma','QAN',3,3,1,1),(96,'Oran','TAF',3,3,1,1),(98,'Ouargla','OGX',3,3,1,1),(99,'Oued Rhiou','QOU',3,3,1,1),(100,'Oued Zenati','QOZ',3,3,1,1),(101,'Oum El Bouaghi','QMH',3,3,1,1),(102,'Relizane','QZN',3,3,1,1),(103,'Saida','QDZ',3,3,1,1),(104,'Sedrata','QDT',3,3,1,1),(105,'Setif','QSF',3,3,1,1),(106,'Sig','QIL',3,3,1,1),(107,'Skikda','SKI',3,3,1,1),(108,'Souk Ahras','QSK',3,3,1,1),(109,'Taher','ZZT',3,3,1,1),(110,'Tamanrasset','TMR',3,3,1,1),(111,'Tebessa','TEE',3,3,1,1),(112,'Tiaret','TID',3,3,1,1),(113,'Timimoun','TMX',3,3,1,1),(114,'Tindouf','TIN',3,3,1,1),(115,'Tizi Ouzou','QZI',3,3,1,1),(116,'Tlemcen','TLM',3,3,1,1),(117,'Touggourt','TGR',3,3,1,1),(118,'Fitiuta','FTI',4,4,1,1),(119,'Ofu','OFU',4,4,1,1),(120,'Pago Pago','PPG',4,4,1,1),(121,'Tau','TAV',4,4,1,1),(122,'Andorra La Vella','ALV',5,5,1,1),(123,'Ambriz','AZZ',6,6,1,1),(124,'Andulo','ANL',6,6,1,1),(125,'Benguela','BUG',6,6,1,1),(126,'Cabinda','CAB',6,6,1,1),(127,'Canfunfo','CFF',6,6,1,1),(128,'Cangamba','CNZ',6,6,1,1),(129,'Capanda','KNP',6,6,1,1),(130,'Catumbela','CBT',6,6,1,1),(131,'Cazombo','CAV',6,6,1,1),(132,'Chitato','PGI',6,6,1,1),(133,'Cuito Cuanavale','CTI',6,6,1,1),(134,'Dirico','DRC',6,6,1,1),(135,'Dundo','DUE',6,6,1,1),(136,'Huambo','NOV',6,6,1,1),(137,'Jamba','JMB',6,6,1,1),(138,'Kuito','SVP',6,6,1,1),(139,'Luanda','LAD',6,6,1,1),(140,'Luau','UAL',6,6,1,1),(141,'Lubango','SDD',6,6,1,1),(142,'Lucapa','LBZ',6,6,1,1),(143,'Luena','LUO',6,6,1,1),(144,'Lumbala N\'guimbo','GGC',6,6,1,1),(145,'Luzamba','LZM',6,6,1,1),(146,'M\'Banza Congo','SSY',6,6,1,1),(147,'Malanje','MEG',6,6,1,1),(148,'Menongue','SPP',6,6,1,1),(149,'N\'dalatando Location','NDF',6,6,1,1),(150,'N\'Zeto','ARZ',6,6,1,1),(151,'Namibe','MSZ',6,6,1,1),(152,'Negage','GXG',6,6,1,1),(153,'Nzagi','NZA',6,6,1,1),(154,'Ondjiva','VPE',6,6,1,1),(155,'Porto Amboim','PBN',6,6,1,1),(156,'Saurimo','VHC',6,6,1,1),(157,'Soyo','SZA',6,6,1,1),(158,'Sumbe','NDD',6,6,1,1),(159,'Uige','UGO',6,6,1,1),(160,'Waku Kungo','CEO',6,6,1,1),(161,'Xangongo','XGN',6,6,1,1),(162,'Anguilla','AXA',7,7,1,1),(163,'Teniente R  Marsh','TNM',8,8,1,1),(164,'Antigua','ANU',9,9,1,1),(165,'Barbuda','BBQ',9,9,1,1),(166,'Alto Rio Senguerr','ARR',10,10,1,1),(167,'Bahia Blanca','BHI',10,10,1,1),(168,'Bragado','QRF',10,10,1,1),(169,'Buenos Aires','BUE',10,10,1,1),(172,'Caleta Olivia','CVI',10,10,1,1),(173,'Carmen De Patagones','CPG',10,10,1,1),(174,'Catamarca','CTC',10,10,1,1),(175,'Caviahue','CVH',10,10,1,1),(176,'Ceres','CRR',10,10,1,1),(177,'Charata','CNT',10,10,1,1),(178,'Chos Malal','HOS',10,10,1,1),(179,'Clorinda','CLX',10,10,1,1),(180,'Colonia Catriel','CCT',10,10,1,1),(181,'Colonia Sarmiento','OLN',10,10,1,1),(182,'Comodoro Rivadavi','CRD',10,10,1,1),(183,'Concordia','COC',10,10,1,1),(184,'Cordoba','COR',10,10,1,1),(185,'Corrientes','CNQ',10,10,1,1),(186,'Curuzu Cuatia','UZU',10,10,1,1),(187,'Cutral','CUT',10,10,1,1),(188,'El Bolson','EHL',10,10,1,1),(189,'El Calafate','FTE',10,10,1,1),(190,'El Maiten','EMX',10,10,1,1),(191,'El Palomar','EPA',10,10,1,1),(192,'Eldorado','ELO',10,10,1,1),(193,'Embarcacion','ZAI',10,10,1,1),(194,'Esquel','EQS',10,10,1,1),(195,'Formosa','FMA',10,10,1,1),(196,'Gdor Gregores','GGS',10,10,1,1),(197,'General Pico','GPO',10,10,1,1),(198,'General Roca','GNR',10,10,1,1),(199,'General Villegas','VGS',10,10,1,1),(200,'Goya','OYA',10,10,1,1),(201,'Gualeguaychu','GHU',10,10,1,1),(202,'Iguazu','IGR',10,10,1,1),(203,'Ingeniero Jacobacci','IGB',10,10,1,1),(204,'Jose D San Martin','JSM',10,10,1,1),(205,'Jujuy','JUJ',10,10,1,1),(206,'Junin','JNI',10,10,1,1),(207,'La Cumbre','LCM',10,10,1,1),(208,'La Plata','LPG',10,10,1,1),(209,'La Rioja','IRJ',10,10,1,1),(210,'Lago Argentino','ING',10,10,1,1),(211,'Las Heras','LHS',10,10,1,1),(212,'Las Lomitas','LLS',10,10,1,1),(213,'Ledesma','XMX',10,10,1,1),(214,'Loncopue','LCP',10,10,1,1),(215,'Los Menucos','LMD',10,10,1,1),(216,'Malargue','LGS',10,10,1,1),(217,'Maquinchao','MQD',10,10,1,1),(218,'Mar del Plata','MDQ',10,10,1,1),(219,'Mendoza','MDZ',10,10,1,1),(220,'Mercedes','MDX',10,10,1,1),(221,'Merlo','RLO',10,10,1,1),(222,'Miramar','MJR',10,10,1,1),(223,'Monte Caseros','MCS',10,10,1,1),(224,'Mosconi','XOS',10,10,1,1),(225,'Necochea','NEC',10,10,1,1),(226,'Neuquen','NQN',10,10,1,1),(227,'Olivos','QLV',10,10,1,1),(228,'Oran','ORA',10,10,1,1),(229,'Parana','PRA',10,10,1,1),(230,'Paso De Los Libres','AOL',10,10,1,1),(231,'Pehuajo','PEH',10,10,1,1),(232,'Perito Moreno','PMQ',10,10,1,1),(233,'Pichanal','XMV',10,10,1,1),(234,'Pinamar','QPQ',10,10,1,1),(235,'Posadas','PSS',10,10,1,1),(236,'Pres  Roque Saenz','PRQ',10,10,1,1),(237,'Puerto Deseado','PUD',10,10,1,1),(238,'Puerto Madryn','PMY',10,10,1,1),(239,'Reconquista','RCQ',10,10,1,1),(240,'Resistencia','RES',10,10,1,1),(241,'Rincon De Los Sauces','RDS',10,10,1,1),(242,'Rio Cuarto','RCU',10,10,1,1),(243,'Rio Gallegos','RGL',10,10,1,1),(244,'Rio Grande','RGA',10,10,1,1),(245,'Rio Hondo','RHD',10,10,1,1),(246,'Rio Mayo','ROY',10,10,1,1),(247,'Rio Turbio','RYO',10,10,1,1),(248,'Rosario','ROS',10,10,1,1),(249,'Saenz Pena','SZQ',10,10,1,1),(250,'Salta','SLA',10,10,1,1),(251,'San Antonio Oeste','OES',10,10,1,1),(252,'San Carlos De Bariloche','BRC',10,10,1,1),(253,'San Juan','UAQ',10,10,1,1),(254,'San Julian','ULA',10,10,1,1),(255,'San Luis','LUQ',10,10,1,1),(256,'San Martin de Los Andes','CPC',10,10,1,1),(257,'San Pedro','XPD',10,10,1,1),(258,'San Rafael','AFA',10,10,1,1),(259,'Santa Cruz','RZA',10,10,1,1),(260,'Santa Fe','SFN',10,10,1,1),(261,'Santa Rosa','RSA',10,10,1,1),(262,'Santa Teresita','SST',10,10,1,1),(263,'Santiago','SDE',10,10,1,1),(264,'Sierra Grande','SGV',10,10,1,1),(265,'Tandil','TDL',10,10,1,1),(266,'Tartagal','TTG',10,10,1,1),(267,'Trelew','REL',10,10,1,1),(268,'Tres Arroyos','OYO',10,10,1,1),(269,'Tucuman','TUC',10,10,1,1),(270,'Ushuaia','USH',10,10,1,1),(271,'Valcheta','VCF',10,10,1,1),(272,'Viedma','VDM',10,10,1,1),(273,'Villa Dolores','VDR',10,10,1,1),(274,'Villa Gesell','VLG',10,10,1,1),(275,'Villa Mercedes','VME',10,10,1,1),(276,'Zapala','APZ',10,10,1,1),(277,'Gyumri','LWN',11,11,1,1),(278,'Yerevan','EVN',11,11,1,1),(279,'Aruba','AUA',12,12,1,1),(280,'Abingdon','ABG',13,13,1,1),(281,'Adelaide','ADL',13,13,1,1),(282,'Agnew','AGW',13,13,1,1),(283,'Airlie Beach','WSY',13,13,1,1),(284,'Albany','ALH',13,13,1,1),(285,'Albury','ABX',13,13,1,1),(286,'Alejandria','AXL',13,13,1,1),(287,'Alice Springs','ASP',13,13,1,1),(288,'Alpha','ABH',13,13,1,1),(289,'Alroy Downs','AYD',13,13,1,1),(290,'Alton Downs','AWN',13,13,1,1),(291,'Amata','AMT',13,13,1,1),(292,'American River','RCN',13,13,1,1),(293,'Ammaroo','AMX',13,13,1,1),(294,'Andamooka','ADO',13,13,1,1),(295,'Angus Downs','ANZ',13,13,1,1),(296,'Anthony Lagoon','AYL',13,13,1,1),(297,'Aramac','AXC',13,13,1,1),(298,'Ararat','ARY',13,13,1,1),(299,'Argyle','GYL',13,13,1,1),(300,'Argyle Downs','AGY',13,13,1,1),(301,'Armidale','ARM',13,13,1,1),(302,'Arrabury','AAB',13,13,1,1),(303,'Augustus Downs','AUD',13,13,1,1),(304,'Aurukun Mission','AUU',13,13,1,1),(305,'Austral Downs','AWP',13,13,1,1),(306,'Auvergne','AVG',13,13,1,1),(307,'Ayers Rock','AYQ',13,13,1,1),(308,'Ayr','AYR',13,13,1,1),(309,'Badu Island','BDD',13,13,1,1),(310,'Bairnsdale','BSJ',13,13,1,1),(311,'Balcanoona','LCN',13,13,1,1),(312,'Balgo Hills','BQW',13,13,1,1),(313,'Ballina','BNK',13,13,1,1),(314,'Balranald','BZD',13,13,1,1),(315,'Bamaga','ABM',13,13,1,1),(316,'Baniyala','BYX',13,13,1,1),(317,'Bankstown','BWU',13,13,1,1),(318,'Barcaldine','BCI',13,13,1,1),(319,'Barkly Downs','BKP',13,13,1,1),(320,'Batavia Downs','BVW',13,13,1,1),(321,'Batemans Bay','QBW',13,13,1,1),(322,'Bathurst','BHS',13,13,1,1),(323,'Bathurst Island','BRT',13,13,1,1),(324,'Beagle Bay','BEE',13,13,1,1),(325,'Bedarra Island','QIY',13,13,1,1),(326,'Bedford Downs','BDW',13,13,1,1),(327,'Bedourie','BEU',13,13,1,1),(328,'Bega','QBE',13,13,1,1),(329,'Belburn','BXF',13,13,1,1),(330,'Benalla','BLN',13,13,1,1),(331,'Bendigo','BXG',13,13,1,1),(332,'Betoota','BTX',13,13,1,1),(333,'Beverley Springs','BVZ',13,13,1,1),(334,'Big Bell','BBE',13,13,1,1),(335,'Billiluna','BIW',13,13,1,1),(336,'Biloela','ZBL',13,13,1,1),(337,'Birdsville','BVI',13,13,1,1),(338,'Bizant','BZP',13,13,1,1),(339,'Blackall','BKQ',13,13,1,1),(340,'Blackwater','BLT',13,13,1,1),(341,'Bloomfield','BFC',13,13,1,1),(342,'Bollon','BLS',13,13,1,1),(343,'Bolwarra','BCK',13,13,1,1),(344,'Borroloola','BOX',13,13,1,1),(345,'Boulia','BQL',13,13,1,1),(346,'Bourke','BRK',13,13,1,1),(347,'Bowen','ZBO',13,13,1,1),(348,'Brampton Island','BMP',13,13,1,1),(349,'Brewarrina','BWQ',13,13,1,1),(350,'Bright','BRJ',13,13,1,1),(351,'Brighton Downs','BHT',13,13,1,1),(352,'Brisbane','BNE',13,13,1,1),(353,'Broken Hill','BHQ',13,13,1,1),(354,'Broome','BME',13,13,1,1),(355,'Brunette Downs','BTD',13,13,1,1),(356,'Bulimba','BIP',13,13,1,1),(357,'Bullocks Flat','QZM',13,13,1,1),(358,'Bunbury','BUY',13,13,1,1),(359,'Bundaberg','BDB',13,13,1,1),(360,'Burketown','BUC',13,13,1,1),(361,'Burnie','BWT',13,13,1,1),(362,'Busselton','BQB',13,13,1,1),(363,'Byron Bay','QYN',13,13,1,1),(364,'Caiguna','CGV',13,13,1,1),(365,'Cairns','CNS',13,13,1,1),(366,'Caloundra','CUD',13,13,1,1),(367,'Camden','CDU',13,13,1,1),(368,'Camfield','CFI',13,13,1,1),(369,'Camooweal','CML',13,13,1,1),(370,'Canberra','CBR',13,13,1,1),(371,'Canobie','CBY',13,13,1,1),(372,'Cape Barren','CBI',13,13,1,1),(373,'Cape Flattery','CQP',13,13,1,1),(374,'Carlton Hill','CRY',13,13,1,1),(375,'Carnarvon','CVQ',13,13,1,1),(376,'Carpentaria Downs','CFP',13,13,1,1),(377,'Casino','CSI',13,13,1,1),(378,'Cattle Creek','CTR',13,13,1,1),(379,'Ceduna','CED',13,13,1,1),(380,'Cessnock','CES',13,13,1,1),(381,'Charleville','CTL',13,13,1,1),(382,'Charters Towers','CXT',13,13,1,1),(383,'Cherrabun','CBC',13,13,1,1),(384,'Cherribah','CRH',13,13,1,1),(385,'Chillagoe','LLG',13,13,1,1),(386,'Chinchilla','CCL',13,13,1,1),(387,'Christmas Creek','CXQ',13,13,1,1),(388,'Clermont','CMQ',13,13,1,1),(389,'Cleve','CVC',13,13,1,1),(390,'Clifton Hills','CFH',13,13,1,1),(391,'Cloncurry','CNJ',13,13,1,1),(392,'Cluny','CZY',13,13,1,1),(393,'Cobar','CAZ',13,13,1,1),(394,'Coen','CUQ',13,13,1,1),(395,'Coffs Harbour','CFS',13,13,1,1),(396,'Colac','XCO',13,13,1,1),(397,'Collarenebri','CRB',13,13,1,1),(398,'Collie','CIE',13,13,1,1),(399,'Collinsville','KCE',13,13,1,1),(400,'Condobolin','CBX',13,13,1,1),(401,'Coober Pedy','CPD',13,13,1,1),(402,'Cooinda','CDA',13,13,1,1),(403,'Cooktown','CTN',13,13,1,1),(404,'Coolah','CLH',13,13,1,1),(405,'Coolawanyah','COY',13,13,1,1),(406,'Coolibah','COB',13,13,1,1),(407,'Cooma','OOM',13,13,1,1),(408,'Coonabarabran','COJ',13,13,1,1),(409,'Coonamble','CNB',13,13,1,1),(410,'Coorabie','CRJ',13,13,1,1),(411,'Cootamundra','CMD',13,13,1,1),(412,'Cordillo Downs','ODL',13,13,1,1),(413,'Corowa','CWW',13,13,1,1),(414,'Corryong','CYG',13,13,1,1),(415,'Cowarie','CWR',13,13,1,1),(416,'Cowell','CCW',13,13,1,1),(417,'Cowra','CWT',13,13,1,1),(418,'Crackenback Vill','QWL',13,13,1,1),(419,'Cresswell Downs','CSD',13,13,1,1),(420,'Croker Island','CKI',13,13,1,1),(421,'Croydon','CDQ',13,13,1,1),(422,'Cue','CUY',13,13,1,1),(423,'Cunnamulla','CMA',13,13,1,1),(424,'Dalby','DBY',13,13,1,1),(425,'Dalgaranga','DGD',13,13,1,1),(426,'Daly River','DVR',13,13,1,1),(427,'Daly Waters','DYW',13,13,1,1),(428,'Darnley Island','NLF',13,13,1,1),(429,'Darwin','DRW',13,13,1,1),(430,'Davenport Downs','DVP',13,13,1,1),(431,'Day Trip Mystery','ZZW',13,13,1,1),(432,'Delissaville','DLV',13,13,1,1),(433,'Delta Downs','DDN',13,13,1,1),(434,'Denham','DNM',13,13,1,1),(435,'Deniliquin','DNQ',13,13,1,1),(436,'Derby','DRB',13,13,1,1),(437,'Devonport','DPO',13,13,1,1),(438,'Diamantina Lakes','DYM',13,13,1,1),(439,'Dirranbandi','DRN',13,13,1,1),(440,'Dixie','DXD',13,13,1,1),(441,'Docker River','DKV',13,13,1,1),(442,'Dongara','DOX',13,13,1,1),(443,'Doogan','DNG',13,13,1,1),(444,'Doomadgee','DMD',13,13,1,1),(445,'Dorunda Station','DRD',13,13,1,1),(446,'Drumduff','DFP',13,13,1,1),(447,'Drysdale River','DRY',13,13,1,1),(448,'Dubbo','DBO',13,13,1,1),(449,'Dulkaninna','DLK',13,13,1,1),(450,'Dunbar','DNB',13,13,1,1),(451,'Durham Downs','DHD',13,13,1,1),(452,'Durrie','DRR',13,13,1,1),(453,'Dysart','DYA',13,13,1,1),(454,'Echuca','ECH',13,13,1,1),(455,'Eden','QDN',13,13,1,1),(456,'Edward River','EDR',13,13,1,1),(457,'Einasleigh','EIH',13,13,1,1),(458,'Elite Mystery Night','ZZI',13,13,1,1),(459,'Elkedra','EKD',13,13,1,1),(460,'Emerald','EMD',13,13,1,1),(461,'Eneabba West','ENB',13,13,1,1),(462,'Erldunda','EDD',13,13,1,1),(463,'Ernabella','ERB',13,13,1,1),(464,'Esperance','EPR',13,13,1,1),(465,'Etadunna','ETD',13,13,1,1),(466,'Eucla','EUC',13,13,1,1),(467,'Eva Downs','EVD',13,13,1,1),(468,'Evans Head','EVH',13,13,1,1),(469,'Exmouth Gulf','EXM',13,13,1,1),(470,'Falls Creek','FLC',13,13,1,1),(471,'Finke','FIK',13,13,1,1),(472,'Finley','FLY',13,13,1,1),(473,'Fitzroy Crossing','FIZ',13,13,1,1),(474,'Flora Valley','FVL',13,13,1,1),(475,'Forbes','FRB',13,13,1,1),(476,'Forrest','FOS',13,13,1,1),(477,'Forrest River','FVR',13,13,1,1),(478,'Forster','FOT',13,13,1,1),(479,'Fort Leavenworth','FLV',13,13,1,1),(480,'Fossil Downs','FSL',13,13,1,1),(481,'Fremantle','JFM',13,13,1,1),(482,'Gamboola','GBP',13,13,1,1),(483,'Garden Point','GPN',13,13,1,1),(484,'Gascoyne Junction','GSC',13,13,1,1),(485,'Gayndah','GAH',13,13,1,1),(486,'Geelong','GEX',13,13,1,1),(487,'George Town','GEE',13,13,1,1),(488,'Georgetown','GTT',13,13,1,1),(489,'Geraldton','GET',13,13,1,1),(490,'Gibb River','GBV',13,13,1,1),(491,'Gladstone','GLT',13,13,1,1),(492,'Glen Innes','GLI',13,13,1,1),(493,'Glengyle','GLG',13,13,1,1),(494,'Glenormiston','GLM',13,13,1,1),(495,'Gold Coast','OOL',13,13,1,1),(496,'Goldsworthy','GLY',13,13,1,1),(497,'Goondiwindi','GOO',13,13,1,1),(498,'Gordon Downs','GDD',13,13,1,1),(499,'Gosford','GOS',13,13,1,1),(500,'Goulburn','GUL',13,13,1,1),(501,'Gove','GOV',13,13,1,1),(502,'Grafton','GFN',13,13,1,1),(503,'Granites','GTS',13,13,1,1),(504,'Greenvale','GVP',13,13,1,1),(505,'Gregory Downs','GGD',13,13,1,1),(506,'Grenfell','GFE',13,13,1,1),(507,'Griffith','GFF',13,13,1,1),(508,'Groote Eylandt','GTE',13,13,1,1),(509,'Gunnedah','GUH',13,13,1,1),(510,'Gympie','GYP',13,13,1,1),(511,'Halls Creek','HCQ',13,13,1,1),(512,'Hamilton','HLT',13,13,1,1),(513,'Hamilton Island','HTI',13,13,1,1),(514,'Hawker','HWK',13,13,1,1),(515,'Hay','HXX',13,13,1,1),(516,'Hayman Island','HIS',13,13,1,1),(517,'Headingly','HIP',13,13,1,1),(518,'Heathlands','HAT',13,13,1,1),(519,'Helenvale','HLV',13,13,1,1),(520,'Henbury','HRY',13,13,1,1),(521,'Hermannsburg','HMG',13,13,1,1),(522,'Hervey Bay','HVB',13,13,1,1),(523,'Highbury','HIG',13,13,1,1),(524,'Hillside','HLL',13,13,1,1),(525,'Hinchinbrooke Island','HNK',13,13,1,1),(526,'Hobart','HBA',13,13,1,1),(527,'Hook Island','HIH',13,13,1,1),(528,'Hooker Creek','HOK',13,13,1,1),(529,'Hope Vale','HPE',13,13,1,1),(530,'Hopetoun','HTU',13,13,1,1),(531,'Horn Island','HID',13,13,1,1),(532,'Horsham','HSM',13,13,1,1),(533,'Hughenden','HGD',13,13,1,1),(534,'Humbert River','HUB',13,13,1,1),(535,'Iffley','IFF',13,13,1,1),(536,'Indulkana','IDK',13,13,1,1),(537,'Ingham','IGH',13,13,1,1),(538,'Injune','INJ',13,13,1,1),(539,'Inkerman','IKP',13,13,1,1),(540,'Innamincka','INM',13,13,1,1),(541,'Innisfail','IFL',13,13,1,1),(542,'Inverell','IVR',13,13,1,1),(543,'Inverway','IVW',13,13,1,1),(544,'Isisford','ISI',13,13,1,1),(545,'Isl A Murray','MYI',13,13,1,1),(546,'Isla Barrow','BWB',13,13,1,1),(547,'Isla Bickerton','BCZ',13,13,1,1),(548,'Isla Boigu','GIC',13,13,1,1),(549,'Isla Coconut','CNC',13,13,1,1),(550,'Isla Dauan','DAJ',13,13,1,1),(551,'Isla Daydream','DDI',13,13,1,1),(552,'Isla De Flinders','FLS',13,13,1,1),(553,'Isla Dunk','DKI',13,13,1,1),(554,'Isla Elcho','ELC',13,13,1,1),(555,'Isla Goulburn','GBL',13,13,1,1),(556,'Isla Great Keppel','GKL',13,13,1,1),(557,'Isla King','KNS',13,13,1,1),(558,'Isla Kubin','KUG',13,13,1,1),(559,'Isla Lindeman','LDC',13,13,1,1),(560,'Isla Lord Howe','LDH',13,13,1,1),(561,'Jabiru','JAB',13,13,1,1),(562,'Jandakot','JAD',13,13,1,1),(563,'Jindabyne','QJD',13,13,1,1),(564,'Julia Creek','JCK',13,13,1,1),(565,'Jundah','JUN',13,13,1,1),(566,'Jurien Bay','JUR',13,13,1,1),(567,'Kalbarri','KAX',13,13,1,1),(568,'Kalgoorlie','KGI',13,13,1,1),(569,'Kalkurung','KFG',13,13,1,1),(570,'Kalpowar','KPP',13,13,1,1),(571,'Kalumburu','UBU',13,13,1,1),(572,'Kamaran Downs','KDS',13,13,1,1),(573,'Kambalda','KDB',13,13,1,1),(574,'Kamileroi','KML',13,13,1,1),(575,'Karratha','KTA',13,13,1,1),(576,'Karumba','KRB',13,13,1,1),(577,'Katanning','KNI',13,13,1,1),(578,'Katherine','KTR',13,13,1,1),(579,'Kempsey','KPS',13,13,1,1),(580,'Kerang','KRA',13,13,1,1),(581,'Kimberley Downs','KBD',13,13,1,1),(582,'Kingaroy','KGY',13,13,1,1),(583,'Kings Canyon','KBJ',13,13,1,1),(584,'Kings Creek Stati','KCS',13,13,1,1),(585,'Kingscote','KGC',13,13,1,1),(586,'Kirkimbie','KBB',13,13,1,1),(587,'Koolatah','KOH',13,13,1,1),(588,'Koolburra','KKP',13,13,1,1),(589,'Koonibba','KQB',13,13,1,1),(590,'Kowanyama','KWM',13,13,1,1),(591,'Kulgera','KGR',13,13,1,1),(592,'Kununurra','KNX',13,13,1,1),(593,'Kurundi','KRD',13,13,1,1),(594,'Lady Elliot Island','LYT',13,13,1,1),(595,'Lake Evella','LEL',13,13,1,1),(596,'Lake Gregory','LGE',13,13,1,1),(597,'Lake Nash','LNH',13,13,1,1),(598,'Lakefield','LFP',13,13,1,1),(599,'Lakeland Downs','LKD',13,13,1,1),(600,'Lansdowne','LDW',13,13,1,1),(601,'Latrobe','LTB',13,13,1,1),(602,'Launceston','LST',13,13,1,1),(603,'Laura','LUU',13,13,1,1),(604,'Laura Station','LUT',13,13,1,1),(605,'Laverton','LVO',13,13,1,1),(606,'Lawn Hill','LWH',13,13,1,1),(607,'Learmonth','LEA',13,13,1,1),(608,'Leeton','QLE',13,13,1,1),(609,'Leigh Creek','LGH',13,13,1,1),(610,'Leinster','LER',13,13,1,1),(611,'Leonora','LNO',13,13,1,1),(612,'Lightning Ridge','LHG',13,13,1,1),(613,'Limbunya','LIB',13,13,1,1),(614,'Linda Downs','LLP',13,13,1,1),(615,'Lismore','LSY',13,13,1,1),(616,'Lissadell','LLL',13,13,1,1),(617,'Lizard Island','LZR',13,13,1,1),(618,'Lock','LOC',13,13,1,1),(619,'Lockhart River','IRG',13,13,1,1),(620,'Long Island','HAP',13,13,1,1),(621,'Longreach','LRE',13,13,1,1),(622,'Lorraine','LOA',13,13,1,1),(623,'Lotusvale','LTV',13,13,1,1),(624,'Lyndhurst','LTP',13,13,1,1),(625,'Mabuiag Island','UBB',13,13,1,1),(626,'Macdonald Downs','MNW',13,13,1,1),(627,'Mackay','MKY',13,13,1,1),(628,'Macksville','MVH',13,13,1,1),(629,'Macmahon Camp 4','MHC',13,13,1,1),(630,'Mainoru','MIZ',13,13,1,1),(631,'Maitland','MTL',13,13,1,1),(632,'Mallacoota','XMC',13,13,1,1),(633,'Mandora','MQA',13,13,1,1),(634,'Maningrida','MNG',13,13,1,1),(635,'Manjimup','MJP',13,13,1,1),(636,'Manly','WGZ',13,13,1,1),(637,'Manners Creek','MFP',13,13,1,1),(638,'Marble Bar','MBB',13,13,1,1),(639,'Mareeba','MRG',13,13,1,1),(640,'Margaret River','MQZ',13,13,1,1),(641,'Margaret River Station','MGV',13,13,1,1),(642,'Marion Downs','MXD',13,13,1,1),(643,'Marla','MRP',13,13,1,1),(644,'Marqua','MQE',13,13,1,1),(645,'Marree','RRE',13,13,1,1),(646,'Maryborough','MBH',13,13,1,1),(647,'McArthur River','MCV',13,13,1,1),(648,'Meekatharra','MKR',13,13,1,1),(649,'Melbourne','MEL',13,13,1,1),(652,'Merimbula','MIM',13,13,1,1),(653,'Merluna','MLV',13,13,1,1),(654,'Merty','RTY',13,13,1,1),(655,'Middlemount','MMM',13,13,1,1),(656,'Mildura','MQL',13,13,1,1),(657,'Millicent','MLR',13,13,1,1),(658,'Millingimbi','MGT',13,13,1,1),(659,'Miners Lake','MRL',13,13,1,1),(660,'Minlaton','XML',13,13,1,1),(661,'Minnipa','MIN',13,13,1,1),(662,'Miranda Downs','MWY',13,13,1,1),(663,'Mitchell','MTQ',13,13,1,1),(664,'Mitchell Plateau','MIH',13,13,1,1),(665,'Mitchell River','MXQ',13,13,1,1),(666,'Mittiebah','MIY',13,13,1,1),(667,'Monkey Mia','MJK',13,13,1,1),(668,'Monkira','ONR',13,13,1,1),(669,'Monto','MNQ',13,13,1,1),(670,'Moolawatana','MWT',13,13,1,1),(671,'Moomba','MOO',13,13,1,1),(672,'Moorabbin','MBW',13,13,1,1),(673,'Mooraberree','OOR',13,13,1,1),(674,'Moranbah','MOV',13,13,1,1),(675,'Morawa','MWB',13,13,1,1),(676,'Moree','MRZ',13,13,1,1),(677,'Moreton','MET',13,13,1,1),(678,'Morney','OXY',13,13,1,1),(679,'Mornington','ONG',13,13,1,1),(680,'Moroak','MRT',13,13,1,1),(681,'Moruya','MYA',13,13,1,1),(682,'Mount Barnett','MBN',13,13,1,1),(683,'Mount Buffalo','MBF',13,13,1,1),(684,'Mount Full Stop','MFL',13,13,1,1),(685,'Mount Gambier','MGB',13,13,1,1),(686,'Mount Gunson','GSN',13,13,1,1),(687,'Mount Hotham','MHU',13,13,1,1),(688,'Mount House','MHO',13,13,1,1),(689,'Mount Isa','ISA',13,13,1,1),(691,'Mount Keith','WME',13,13,1,1),(692,'Mount Magnet','MMG',13,13,1,1),(693,'Mount Swan','MSF',13,13,1,1),(694,'Mountain Valley','MNV',13,13,1,1),(695,'Mt Cavenagh','MKV',13,13,1,1),(696,'Mt Sandford','MTD',13,13,1,1),(697,'Muccan','MUQ',13,13,1,1),(698,'Mudgee','DGE',13,13,1,1),(699,'Mulga Park','MUP',13,13,1,1),(700,'Mulka','MVK',13,13,1,1),(701,'Mullewa','MXU',13,13,1,1),(702,'Mungeranie','MNE',13,13,1,1),(703,'Musgrave','MVU',13,13,1,1),(704,'Muttaburra','UTB',13,13,1,1),(705,'Myroodah','MYO',13,13,1,1),(706,'Mystery Flight','ZZF',13,13,1,1),(707,'Mystery Night','ZZJ',13,13,1,1),(708,'Nambour','NBR',13,13,1,1),(709,'Nambucca Heads','NBH',13,13,1,1),(710,'Nappa Merry','NMR',13,13,1,1),(711,'Napperby','NPP',13,13,1,1),(712,'Naracoorte','NAC',13,13,1,1),(713,'Narooma','QRX',13,13,1,1),(714,'Narrabri','NAA',13,13,1,1),(715,'Narrandera','NRA',13,13,1,1),(716,'Narrogin','NRG',13,13,1,1),(717,'Narromine','QRM',13,13,1,1),(718,'New Moon','NMP',13,13,1,1),(719,'Newcastle','NTL',13,13,1,1),(720,'Newman','ZNE',13,13,1,1),(721,'Newry','NRY',13,13,1,1),(722,'Ngukurr','RPM',13,13,1,1),(723,'Nicholson','NLS',13,13,1,1),(724,'Nifty','NIF',13,13,1,1),(725,'Noonkanbah','NKB',13,13,1,1),(726,'Noosa','NSA',13,13,1,1),(727,'Noosaville','NSV',13,13,1,1),(728,'Normanton','NTN',13,13,1,1),(729,'Norseman','NSM',13,13,1,1),(730,'Nowra','NOA',13,13,1,1),(731,'Nullagine','NLL',13,13,1,1),(732,'Nullarbor','NUR',13,13,1,1),(733,'Numbulwar','NUB',13,13,1,1),(734,'Nutwood Downs','UTD',13,13,1,1),(735,'Nyngan','NYN',13,13,1,1),(736,'Oakey','OKY',13,13,1,1),(737,'Oban','OBA',13,13,1,1),(738,'Oenpelli','OPI',13,13,1,1),(739,'Olympic Dam','OLP',13,13,1,1),(740,'Onslow','ONS',13,13,1,1),(741,'Oodnadatta','ODD',13,13,1,1),(742,'Orange Cudal','OAG',13,13,1,1),(743,'Orbost','RBS',13,13,1,1),(744,'Orchid Beach','OKB',13,13,1,1),(745,'Ord River','ODR',13,13,1,1),(746,'Orientos','OXO',13,13,1,1),(747,'Orpheus Island Re','ORS',13,13,1,1),(748,'Ouyen','OYN',13,13,1,1),(749,'Palm Island','PMK',13,13,1,1),(750,'Pandie Pandie','PDE',13,13,1,1),(751,'Paraburdoo','PBO',13,13,1,1),(752,'Pardoo','PRD',13,13,1,1),(753,'Parkes','PKE',13,13,1,1),(754,'Parndana','PDN',13,13,1,1),(755,'Penneshaw','PEA',13,13,1,1),(756,'Penong','PEY',13,13,1,1),(757,'Peppers Palm Bay','PBY',13,13,1,1),(758,'Peppimenarti','PEP',13,13,1,1),(759,'Perisher Valley','QPV',13,13,1,1),(760,'Perth','PER',13,13,1,1),(761,'Port Augusta','PUG',13,13,1,1),(762,'Port Douglas','PTI',13,13,1,1),(763,'Port Hedland','PHE',13,13,1,1),(764,'Port Hunter','PHJ',13,13,1,1),(765,'Port Keats','PKT',13,13,1,1),(766,'Port Lincoln','PLO',13,13,1,1),(767,'Port Macquarie','PQQ',13,13,1,1),(768,'Port Pirie','PPI',13,13,1,1),(769,'Port Stephens','PTE',13,13,1,1),(770,'Portland','PTJ',13,13,1,1),(771,'Prominent Hill','PXH',13,13,1,1),(772,'Proserpine','PPP',13,13,1,1),(773,'Queenstown','UEE',13,13,1,1),(774,'Quilpie','ULP',13,13,1,1),(775,'Quirindi','UIR',13,13,1,1),(776,'Ramingining','RAM',13,13,1,1),(777,'Ravensthorpe','RVT',13,13,1,1),(778,'Renmark','RMK',13,13,1,1),(779,'Richmond','XRH',13,13,1,1),(781,'Robinhood','ROH',13,13,1,1),(782,'Robinson River','RRV',13,13,1,1),(783,'Robinvale','RBC',13,13,1,1),(784,'Rockhampton','ROK',13,13,1,1),(785,'Rockhampton Downs','RDA',13,13,1,1),(786,'Roebourne','RBU',13,13,1,1),(787,'Rokeby','RKY',13,13,1,1),(788,'Roma','RMA',13,13,1,1),(789,'Roper Bar','RPB',13,13,1,1),(790,'Roper Valley','RPV',13,13,1,1),(791,'Roseberth','RSB',13,13,1,1),(792,'Rosella Plains','RLP',13,13,1,1),(793,'Rottnest Island','RTS',13,13,1,1),(794,'Roy Hill','RHL',13,13,1,1),(795,'Rutland Plains','RTP',13,13,1,1),(796,'Saibai Island','SBR',13,13,1,1),(797,'Sale','SXE',13,13,1,1),(798,'Sandringham','SRM',13,13,1,1),(799,'Sandstone','NDS',13,13,1,1),(800,'Scone','NSO',13,13,1,1),(801,'Shaw River','SWB',13,13,1,1),(802,'Shay Gap','SGP',13,13,1,1),(803,'Shepparton','SHT',13,13,1,1),(804,'Shute Harbour','JHQ',13,13,1,1),(805,'Silver Plains','SSP',13,13,1,1),(806,'Singleton','SIX',13,13,1,1),(807,'Skitube','QTO',13,13,1,1),(808,'Smiggin Holes','QZC',13,13,1,1),(809,'Smith Point','SHU',13,13,1,1),(810,'Smithton','SIO',13,13,1,1),(811,'Snake Bay','SNB',13,13,1,1),(812,'South Galway','ZGL',13,13,1,1),(813,'South Molle Islan','SOI',13,13,1,1),(814,'Southern Cross','SQC',13,13,1,1),(815,'Southport','SHQ',13,13,1,1),(816,'Spring Creek','SCG',13,13,1,1),(817,'Springvale','ZVG',13,13,1,1),(819,'St George','SGO',13,13,1,1),(820,'St Helens','HLS',13,13,1,1),(821,'St Paul\'s Mission','SVM',13,13,1,1),(822,'Stanthorpe','SNH',13,13,1,1),(823,'Starcke','SQP',13,13,1,1),(824,'Stawell','SWC',13,13,1,1),(825,'Stephen Island','STF',13,13,1,1),(826,'Stradbroke Isl','SRR',13,13,1,1),(827,'Strahan','SRN',13,13,1,1),(828,'Strathmore','STH',13,13,1,1),(829,'Streaky Bay','KBY',13,13,1,1),(830,'Sturt Creek','SSK',13,13,1,1),(831,'Sue Island','SYU',13,13,1,1),(832,'Sunshine Coast','MCY',13,13,1,1),(833,'Surfers Paradise','SFP',13,13,1,1),(834,'Swan Hill','SWH',13,13,1,1),(835,'Sydney','SYD',13,13,1,1),(836,'Tableland','TBL',13,13,1,1),(837,'Tamworth','TMW',13,13,1,1),(838,'Tanbar','TXR',13,13,1,1),(839,'Tangalooma','TAN',13,13,1,1),(840,'Tara','XTR',13,13,1,1),(841,'Tarcoola','TAQ',13,13,1,1),(842,'Taree','TRO',13,13,1,1),(843,'Taroom','XTO',13,13,1,1),(844,'Telfer','TEF',13,13,1,1),(845,'Temora','TEM',13,13,1,1),(846,'Tennant Creek','TCA',13,13,1,1),(847,'Tewantin','TWN',13,13,1,1),(848,'Thangool','THG',13,13,1,1),(849,'Thargomindah','XTG',13,13,1,1),(850,'Theda Station','TDN',13,13,1,1),(851,'Theodore','TDR',13,13,1,1),(852,'Thredbo','QTH',13,13,1,1),(853,'Thursday Island','TIS',13,13,1,1),(854,'Thylungra','TYG',13,13,1,1),(855,'Tibooburra','TYB',13,13,1,1),(856,'Timber Creek','TBK',13,13,1,1),(857,'Tobermorey','TYP',13,13,1,1),(858,'Tocumwal','TCW',13,13,1,1),(859,'Tom Price','TPR',13,13,1,1),(860,'Toowoomba','TWB',13,13,1,1),(861,'Torwood','TWP',13,13,1,1),(862,'Townsville','TSV',13,13,1,1),(863,'Traralgon','TGN',13,13,1,1),(864,'Tumut','TUM',13,13,1,1),(865,'Turkey Creek','TKY',13,13,1,1),(866,'Undarra','UDA',13,13,1,1),(867,'Useless Loop','USL',13,13,1,1),(868,'Vanrook','VNR',13,13,1,1),(869,'Victoria River Do','VCD',13,13,1,1),(870,'Wagga Wagga','WGA',13,13,1,1),(871,'Walcha','WLC',13,13,1,1),(872,'Walgett','WGE',13,13,1,1),(873,'Wallal','WLA',13,13,1,1),(874,'Wangaratta','WGT',13,13,1,1),(875,'Warracknabeal','WKB',13,13,1,1),(876,'Warrawagine','WRW',13,13,1,1),(877,'Warren','QRR',13,13,1,1),(878,'Warrnambool','WMB',13,13,1,1),(879,'Warwick','WAZ',13,13,1,1),(880,'Waterloo','WLO',13,13,1,1),(881,'Wauchope','WAU',13,13,1,1),(882,'Wave Hill','WAV',13,13,1,1),(883,'Waverney','WAN',13,13,1,1),(884,'Wee Waa','WEW',13,13,1,1),(885,'Weipa','WEI',13,13,1,1),(886,'Wellington','QEL',13,13,1,1),(887,'Welshpool','WHL',13,13,1,1),(888,'West Wyalong','WWY',13,13,1,1),(889,'Whyalla','WYA',13,13,1,1),(890,'Wilcannia','WIO',13,13,1,1),(891,'Wiluna','WUN',13,13,1,1),(892,'Windarra','WND',13,13,1,1),(893,'Windorah','WNR',13,13,1,1),(894,'Winton','WIN',13,13,1,1),(895,'Wittenoom','WIT',13,13,1,1),(896,'Wollogorang','WLL',13,13,1,1),(897,'Wollongong','WOL',13,13,1,1),(898,'Wondai','WDI',13,13,1,1),(899,'Wondoola','WON',13,13,1,1),(900,'Woodgreen','WOG',13,13,1,1),(901,'Woomera','UMR',13,13,1,1),(902,'Wrotham Park','WPK',13,13,1,1),(903,'Wudinna','WUD',13,13,1,1),(904,'Wyndham','WYN',13,13,1,1),(905,'Yalata Mission','KYI',13,13,1,1),(906,'Yalgoo','YLG',13,13,1,1),(907,'Yam Island','XMY',13,13,1,1),(908,'Yandicoogina','YNN',13,13,1,1),(909,'Yangoonabie','KYB',13,13,1,1),(910,'Yeelirrie','KYF',13,13,1,1),(911,'Yorke Island','OKR',13,13,1,1),(912,'Yorketown','ORR',13,13,1,1),(913,'Young','NGA',13,13,1,1),(914,'Yuendumu','YUE',13,13,1,1),(915,'Bregenz','XGZ',14,14,1,1),(916,'Graz','GRZ',14,14,1,1),(917,'Hohenems Dornbirn','HOH',14,14,1,1),(918,'Innsbruck','INN',14,14,1,1),(919,'Klagenfurt','KLU',14,14,1,1),(920,'Lauterach','QLX',14,14,1,1),(921,'Linz','LNZ',14,14,1,1),(922,'Salzburgo','SZG',14,14,1,1),(923,'Sightseeing Flight','FQQ',14,14,1,1),(924,'St Anton','ANT',14,14,1,1),(925,'Viena','VIE',14,14,1,1),(926,'Woergl','QXZ',14,14,1,1),(927,'Zuers Lech','ZRS',14,14,1,1),(928,'Baku','BAK',15,15,1,1),(930,'Gabala','GBB',15,15,1,1),(931,'Ganja','KVD',15,15,1,1),(932,'Lankaran','LLK',15,15,1,1),(933,'Nakchivan','NAJ',15,15,1,1),(934,'Zaqatala','ZTU',15,15,1,1),(935,'Andros Town','ASD',16,16,1,1),(936,'Arthur\'s Town','ATC',16,16,1,1),(937,'Bimini','NSB',16,16,1,1),(939,'Cape Eleuthera','CEL',16,16,1,1),(940,'Cat Cays','CXY',16,16,1,1),(941,'Cat Island','CAT',16,16,1,1),(942,'Chub Cay','CCZ',16,16,1,1),(943,'Congo Town','COX',16,16,1,1),(944,'Crooked Island','CRI',16,16,1,1),(945,'Deadmans Cay','LGI',16,16,1,1),(946,'Duncan Town','DCT',16,16,1,1),(947,'Freeport','FPO',16,16,1,1),(948,'George Town','GGT',16,16,1,1),(949,'Governors Harbour','GHB',16,16,1,1),(950,'Grand Bahama','GBI',16,16,1,1),(951,'Great Harbour','GHC',16,16,1,1),(952,'Green Turtle','GTC',16,16,1,1),(953,'Harbour Island','HBI',16,16,1,1),(954,'Inagua','IGA',16,16,1,1),(955,'Mangrove Cay','MAY',16,16,1,1),(956,'Marsh Harbour','MHH',16,16,1,1),(957,'Mastic Point','MSK',16,16,1,1),(958,'Mayaguana','MYG',16,16,1,1),(959,'Nassau','NAS',16,16,1,1),(961,'Norman\'s Cay','NMC',16,16,1,1),(962,'North Eleuthera','ELH',16,16,1,1),(963,'Pitts Town','PWN',16,16,1,1),(964,'Powell Point','PPO',16,16,1,1),(965,'Rock Sound','RSD',16,16,1,1),(966,'Rum Cay','RCY',16,16,1,1),(967,'San Andros','SAQ',16,16,1,1),(968,'San Salvador','ZSA',16,16,1,1),(969,'South Andros','TZN',16,16,1,1),(970,'Spanish Wells','SWL',16,16,1,1),(971,'Spring Point','AXP',16,16,1,1),(972,'Staniel Cay','TYM',16,16,1,1),(973,'Stella Maris','SML',16,16,1,1),(974,'The Bight','TBI',16,16,1,1),(975,'Treasure Cay','TCB',16,16,1,1),(976,'Walker\'s Cay','WKR',16,16,1,1),(977,'West End','WTD',16,16,1,1),(978,'Bahrain','BAH',17,17,1,1),(979,'Barisal','BZL',18,18,1,1),(980,'Chittagong','CGP',18,18,1,1),(981,'Comilla','CLA',18,18,1,1),(982,'Cox\'s Bazar','CXB',18,18,1,1),(983,'Dhaka','DAC',18,18,1,1),(984,'Ishurdi','IRD',18,18,1,1),(985,'Jessore','JSR',18,18,1,1),(986,'Khulna','KHL',18,18,1,1),(987,'Rajshahi','RJH',18,18,1,1),(988,'Rangpur','RAU',18,18,1,1),(989,'Saidpur','SPD',18,18,1,1),(990,'Sandwip','SDW',18,18,1,1),(991,'Shamshernagar','ZHM',18,18,1,1),(992,'Sirajganj','SAJ',18,18,1,1),(993,'Sylhet','ZYL',18,18,1,1),(994,'Thakurgaon','TKR',18,18,1,1),(995,'Bridgetown','BGI',19,19,1,1),(996,'Brest','BQT',20,20,1,1),(997,'Gomel','GME',20,20,1,1),(998,'Grodno','GNA',20,20,1,1),(999,'Minsk','MSQ',20,20,1,1),(1001,'Mogilev','MVQ',20,20,1,1),(1002,'Vitebsk','VTB',20,20,1,1),(1003,'Amberes','ANR',21,21,1,1),(1004,'Arlon','QON',21,21,1,1),(1005,'Bruselas','BRU',21,21,1,1),(1007,'Gante','GNE',21,21,1,1),(1008,'Hasselt','QHA',21,21,1,1),(1009,'Kortrijk','KJK',21,21,1,1),(1010,'Leuven','ZGK',21,21,1,1),(1011,'Lieja','LGG',21,21,1,1),(1012,'Mechelen','ZGP',21,21,1,1),(1013,'Mons','QMO',21,21,1,1),(1014,'Mouscron','MWW',21,21,1,1),(1015,'Namur','QNM',21,21,1,1),(1016,'Oostende Brugge','OST',21,21,1,1),(1017,'Tournai','ZGQ',21,21,1,1),(1018,'Wavre','ZGV',21,21,1,1),(1019,'Zoersel','OBL',21,21,1,1),(1020,'Belize Ciudad','TZA',22,22,1,1),(1022,'Belmopan','BCV',22,22,1,1),(1023,'Big Creek','BGK',22,22,1,1),(1024,'Caye Caulker','CUK',22,22,1,1),(1025,'Caye Chapel','CYC',22,22,1,1),(1026,'Corozal','CZH',22,22,1,1),(1027,'Dangriga','DGA',22,22,1,1),(1028,'Independence','INB',22,22,1,1),(1029,'Manatee','MZE',22,22,1,1),(1030,'Melinda','MDB',22,22,1,1),(1031,'Orange Walk','ORZ',22,22,1,1),(1032,'Placencia','PLJ',22,22,1,1),(1033,'Punta Gorda','PND',22,22,1,1),(1034,'San Ignacia','SQS',22,22,1,1),(1035,'San Pedro','SPR',22,22,1,1),(1036,'Santa Cruz','STU',22,22,1,1),(1037,'Sartaneja','SJX',22,22,1,1),(1038,'Silver Creek','SVK',22,22,1,1),(1039,'Cotonou','COO',23,23,1,1),(1040,'Djougou','DJA',23,23,1,1),(1041,'Kandi','KDC',23,23,1,1),(1042,'Natitingou','NAE',23,23,1,1),(1043,'Parakou','PKO',23,23,1,1),(1044,'Save','SVF',23,23,1,1),(1045,'Bermuda','BDA',24,24,1,1),(1046,'Paro','PBH',25,25,1,1),(1047,'Thimbu','QJC',25,25,1,1),(1048,'Apolo','APB',26,26,1,1),(1049,'Ascension','ASC',26,26,1,1),(1050,'Baures','BVL',26,26,1,1),(1051,'Bermejo','BJO',26,26,1,1),(1052,'Camiri','CAM',26,26,1,1),(1053,'Cobija','CIJ',26,26,1,1),(1054,'Cochabamba','CBB',26,26,1,1),(1055,'Concepcion','CEP',26,26,1,1),(1056,'Guayaramerin','GYA',26,26,1,1),(1057,'Huacaraje','BVK',26,26,1,1),(1058,'La Paz','LPB',26,26,1,1),(1059,'Magdalena','MGD',26,26,1,1),(1060,'Monteagudo','MHW',26,26,1,1),(1061,'Oruro','ORU',26,26,1,1),(1062,'Potosi','POI',26,26,1,1),(1063,'Puerto Rico','PUR',26,26,1,1),(1064,'Puerto Suarez','PSZ',26,26,1,1),(1065,'Reyes','REY',26,26,1,1),(1066,'Riberalta','RIB',26,26,1,1),(1067,'Robore','RBO',26,26,1,1),(1068,'Rurrenabaque','RBQ',26,26,1,1),(1069,'San Borja','SRJ',26,26,1,1),(1070,'San Ignacio De M','SNM',26,26,1,1),(1071,'San Ignacio De Ve','SNG',26,26,1,1),(1072,'San Javier','SJV',26,26,1,1),(1073,'San Joaquin','SJB',26,26,1,1),(1074,'San Jose','SJS',26,26,1,1),(1075,'San Matias','MQK',26,26,1,1),(1076,'San Ramon','SRD',26,26,1,1),(1077,'Santa Ana','SBL',26,26,1,1),(1078,'Santa Cruz','VVI',26,26,1,1),(1080,'Santa Rosa','SRB',26,26,1,1),(1081,'Sucre','SRE',26,26,1,1),(1082,'Tarija','TJA',26,26,1,1),(1083,'Trinidad','TDD',26,26,1,1),(1084,'Uyuni','UYU',26,26,1,1),(1085,'Vallegrande','VAH',26,26,1,1),(1086,'Villamontes','VLM',26,26,1,1),(1087,'Yacuiba','BYC',26,26,1,1),(1088,'Banja Luka','BNX',27,27,1,1),(1089,'Mostar','OMO',27,27,1,1),(1090,'Sarajevo','SJJ',27,27,1,1),(1091,'Tuzla','TZL',27,27,1,1),(1092,'Francistown','FRW',28,28,1,1),(1093,'Gaborone','GBE',28,28,1,1),(1094,'Ghanzi','GNZ',28,28,1,1),(1095,'Hukuntsi','HUK',28,28,1,1),(1096,'Jwaneng','JWA',28,28,1,1),(1097,'Kasane','BBK',28,28,1,1),(1098,'Khwai River Lodge','KHW',28,28,1,1),(1099,'Lobatse','LOQ',28,28,1,1),(1100,'Maun','MUB',28,28,1,1),(1101,'Orapa','ORP',28,28,1,1),(1102,'Palapye','QPH',28,28,1,1),(1103,'Savuti','SVT',28,28,1,1),(1104,'Selebi Phikwe','PKW',28,28,1,1),(1105,'Shakawe','SWX',28,28,1,1),(1106,'Sua Pan','SXN',28,28,1,1),(1107,'Tshabong','TBY',28,28,1,1),(1108,'Tuli Lodge','TLD',28,28,1,1),(1109,'Alagoinhas','QGS',30,30,1,1),(1110,'Alcantara','QAH',30,30,1,1),(1111,'Alegrete','ALQ',30,30,1,1),(1112,'Alenquer','ALT',30,30,1,1),(1113,'Alfenas','QXW',30,30,1,1),(1114,'Almenara','AMJ',30,30,1,1),(1115,'Alta Floresta','AFL',30,30,1,1),(1116,'Altamira','ATM',30,30,1,1),(1117,'Alto Parnaiba','APY',30,30,1,1),(1118,'Americana','QWJ',30,30,1,1),(1119,'Anapolis','APS',30,30,1,1),(1120,'Andradas','QRD',30,30,1,1),(1121,'Anuradhapura','ACJ',30,30,1,1),(1122,'Apucarana','APU',30,30,1,1),(1123,'Aracaju','AJU',30,30,1,1),(1124,'Aracatuba','ARU',30,30,1,1),(1125,'Aragarcas','ARS',30,30,1,1),(1126,'Araguaina','AUX',30,30,1,1),(1127,'Arapiraca','APQ',30,30,1,1),(1128,'Arapongas','APX',30,30,1,1),(1129,'Arapoti','AAG',30,30,1,1),(1130,'Ararangua','XRB',30,30,1,1),(1131,'Araraquara','AQA',30,30,1,1),(1132,'Araxa','AAX',30,30,1,1),(1133,'Arcos','QRK',30,30,1,1),(1134,'Aripuana','AIR',30,30,1,1),(1135,'Ariquemes','AQM',30,30,1,1),(1136,'Arraias','AAI',30,30,1,1),(1137,'Assis','AIF',30,30,1,1),(1138,'Astorga','QWN',30,30,1,1),(1139,'Atibaia','ZBW',30,30,1,1),(1141,'Avare','QVP',30,30,1,1),(1142,'Bage','BGX',30,30,1,1),(1143,'Balsas','BSS',30,30,1,1),(1144,'Barbacena','QAK',30,30,1,1),(1145,'Barbelos','BAZ',30,30,1,1),(1146,'Barra','BQQ',30,30,1,1),(1147,'Barra Do Corda','BDC',30,30,1,1),(1148,'Barra Do Garcas','BPG',30,30,1,1),(1149,'Barra Do Pirai','QBD',30,30,1,1),(1150,'Barra Mansa','QBN',30,30,1,1),(1151,'Barreiras','BRA',30,30,1,1),(1152,'Barreirinhas','BRB',30,30,1,1),(1153,'Barretos','BAT',30,30,1,1),(1154,'Bauru','BAU',30,30,1,1),(1156,'Bebedouro','QAU',30,30,1,1),(1157,'Belem','BEL',30,30,1,1),(1158,'Belmonte','BVM',30,30,1,1),(1159,'Belo Horizonte','PLU',30,30,1,1),(1162,'Benjamin Constant','QAV',30,30,1,1),(1163,'Bento Goncalves','BGV',30,30,1,1),(1164,'Betim','QBK',30,30,1,1),(1165,'Birigui','QCF',30,30,1,1),(1166,'Blumenau','BNU',30,30,1,1),(1167,'Boa Vista','BVB',30,30,1,1),(1168,'Boca Do Acre','BCR',30,30,1,1),(1169,'Bom Jesus Da Lapa','LAZ',30,30,1,1),(1170,'Bonito','BYO',30,30,1,1),(1171,'Borba','RBB',30,30,1,1),(1172,'Botucatu','QCJ',30,30,1,1),(1173,'Braganca Paulista','BJP',30,30,1,1),(1174,'Brasilia','BSB',30,30,1,1),(1175,'Breves','BVS',30,30,1,1),(1176,'Brumado','BMS',30,30,1,1),(1177,'Brusque','QJM',30,30,1,1),(1178,'Buzios','BZC',30,30,1,1),(1179,'Cabo Frio','CFB',30,30,1,1),(1181,'Cacador','CFC',30,30,1,1),(1182,'Caceres','CCX',30,30,1,1),(1183,'Cachoeira','CCQ',30,30,1,1),(1184,'Cachoeira Do Sul','QDB',30,30,1,1),(1185,'Cachoeiro Itapemi','QXD',30,30,1,1),(1186,'Cacoal','OAL',30,30,1,1),(1187,'Caldas Novas','CLV',30,30,1,1),(1188,'Camacari','QCC',30,30,1,1),(1189,'Cameta','CMT',30,30,1,1),(1190,'Camocim','CMC',30,30,1,1),(1191,'Campina Grande','CPV',30,30,1,1),(1192,'Campinas','CPQ',30,30,1,1),(1193,'Campo Bom','QCD',30,30,1,1),(1194,'Campo Grande','CGR',30,30,1,1),(1195,'Campo Mourao','CBW',30,30,1,1),(1196,'Campos','CAW',30,30,1,1),(1197,'Campos Do Jordao','QJO',30,30,1,1),(1198,'Cana Brava','NBV',30,30,1,1),(1199,'Canarana','CQA',30,30,1,1),(1200,'Canavieiras','CNV',30,30,1,1),(1201,'Canela','QCN',30,30,1,1),(1202,'Canoas','QNS',30,30,1,1),(1203,'Canoinhas','QNH',30,30,1,1),(1204,'Caraguatatuba','QCQ',30,30,1,1),(1205,'Carajas','CKS',30,30,1,1),(1206,'Caratinga','QTL',30,30,1,1),(1207,'Carauari','CAF',30,30,1,1),(1208,'Caravelas','CRQ',30,30,1,1),(1209,'Carazinho','QRE',30,30,1,1),(1210,'Cariacica','QRJ',30,30,1,1),(1211,'Carolina','CLN',30,30,1,1),(1212,'Caruaru','CAU',30,30,1,1),(1213,'Carutapera','CTP',30,30,1,1),(1214,'Cascavel','CAC',30,30,1,1),(1215,'Cassilandia','CSS',30,30,1,1),(1216,'Castanhal','QHL',30,30,1,1),(1217,'Castro','QAC',30,30,1,1),(1218,'Cataguases','QCG',30,30,1,1),(1219,'Catalao','TLZ',30,30,1,1),(1220,'Catanduva','QDE',30,30,1,1),(1221,'Caxias','QXC',30,30,1,1),(1222,'Caxias Do Sul','CXJ',30,30,1,1),(1223,'Chapeco','XAP',30,30,1,1),(1224,'Charqueada','QDA',30,30,1,1),(1225,'Coari','CIZ',30,30,1,1),(1226,'Colatina','QCH',30,30,1,1),(1227,'Colorado Do Oeste','CSW',30,30,1,1),(1228,'Conceicao Do Arag','CDJ',30,30,1,1),(1229,'Concordia','CCI',30,30,1,1),(1230,'Confreza','CFO',30,30,1,1),(1231,'Conselheiro Lafai','QDF',30,30,1,1),(1232,'Coritiba','BFH',30,30,1,1),(1233,'Cornelio Procopio','CKO',30,30,1,1),(1234,'Corumba','CMG',30,30,1,1),(1235,'Costa Marques','CQS',30,30,1,1),(1236,'Cotia','QOI',30,30,1,1),(1237,'Crisciuma','CCM',30,30,1,1),(1238,'Cruz Alta','CZB',30,30,1,1),(1239,'Cruzeiro Do Sul','CZS',30,30,1,1),(1240,'Cuiaba','CGB',30,30,1,1),(1241,'Curitiba','CWB',30,30,1,1),(1242,'Curitibanos','QCR',30,30,1,1),(1243,'Currais Novos','QCP',30,30,1,1),(1244,'Cururupu','CPU',30,30,1,1),(1245,'Diadema','QDW',30,30,1,1),(1246,'Diamantina','DTI',30,30,1,1),(1247,'Diamantino','DMT',30,30,1,1),(1248,'Dianopolis','DNO',30,30,1,1),(1249,'Dionisio Cerqueira','XIS',30,30,1,1),(1250,'Divinopolis','DIQ',30,30,1,1),(1251,'Dom Pedrito','QDP',30,30,1,1),(1252,'Dourados','DOU',30,30,1,1),(1253,'Dracena','QDC',30,30,1,1),(1254,'Duque De Caxias','QDQ',30,30,1,1),(1255,'Eirunepe','ERN',30,30,1,1),(1256,'Erechim','ERM',30,30,1,1),(1257,'Espinosa','ESI',30,30,1,1),(1258,'Feijo','FEJ',30,30,1,1),(1259,'Feira De Santana','FEC',30,30,1,1),(1260,'Fernando De Noron','FEN',30,30,1,1),(1261,'Floriano','FLB',30,30,1,1),(1262,'Florianopolis','FLN',30,30,1,1),(1263,'Fonte Boa','FBA',30,30,1,1),(1264,'Fortaleza','FOR',30,30,1,1),(1265,'Franca','FRC',30,30,1,1),(1266,'Francisco Beltrao','FBE',30,30,1,1),(1267,'Garanhuns','QGP',30,30,1,1),(1268,'Goiania','GYN',30,30,1,1),(1269,'Governador Valada','GVR',30,30,1,1),(1270,'Gramado','QRP',30,30,1,1),(1271,'Guadalupe','GDP',30,30,1,1),(1272,'Guaira','QGA',30,30,1,1),(1273,'Guajara Mirim','GJM',30,30,1,1),(1274,'Guanambi','GNM',30,30,1,1),(1275,'Guarapari','GUZ',30,30,1,1),(1276,'Guarapuava','GPB',30,30,1,1),(1277,'Guaratingueta','GUJ',30,30,1,1),(1278,'Guarulhos','QCV',30,30,1,1),(1279,'Guimaraes','GMS',30,30,1,1),(1280,'Gurupi','GRP',30,30,1,1),(1281,'Horizontina','HRZ',30,30,1,1),(1282,'Humaita','HUW',30,30,1,1),(1283,'Ibiruba','QIB',30,30,1,1),(1284,'Icoaraci','QDO',30,30,1,1),(1285,'Iguassu Falls','IGU',30,30,1,1),(1286,'Iguatu','QIG',30,30,1,1),(1287,'Ijui','IJU',30,30,1,1),(1288,'Ilha Comandatuba/Una','UNA',30,30,1,1),(1289,'Ilha Do Governador','QGI',30,30,1,1),(1290,'Ilha Solteira','ILB',30,30,1,1),(1291,'Ilheus','IOS',30,30,1,1),(1292,'Imperatriz','IMP',30,30,1,1),(1293,'Ipatinga','IPN',30,30,1,1),(1294,'Ipiau','IPU',30,30,1,1),(1295,'Ipiranga','IPG',30,30,1,1),(1296,'Irece','IRE',30,30,1,1),(1297,'Itabuna','ITN',30,30,1,1),(1298,'Itacoatiara','ITA',30,30,1,1),(1299,'Itaituba','ITB',30,30,1,1),(1300,'Itajai','ITJ',30,30,1,1),(1301,'Itajuba','QDS',30,30,1,1),(1302,'Itambacuri','ITI',30,30,1,1),(1303,'Itapetinga','QIT',30,30,1,1),(1304,'Itapetininga','ZTP',30,30,1,1),(1305,'Itaqui','ITQ',30,30,1,1),(1306,'Itauba','AUB',30,30,1,1),(1307,'Itauna','QIA',30,30,1,1),(1308,'Itu','QTU',30,30,1,1),(1309,'Itubera','ITE',30,30,1,1),(1310,'Itumbiara','ITR',30,30,1,1),(1311,'Jacareacanga','JCR',30,30,1,1),(1312,'Jacobina','JCM',30,30,1,1),(1313,'Jales','JLS',30,30,1,1),(1314,'Januaria','JNA',30,30,1,1),(1315,'Jaragua Do Sul','QJA',30,30,1,1),(1316,'Jatai','JTI',30,30,1,1),(1317,'Jequie','JEQ',30,30,1,1),(1318,'Ji-Parana','JPR',30,30,1,1),(1319,'Joacaba','JCB',30,30,1,1),(1320,'Joao Pessoa','JPA',30,30,1,1),(1321,'Joinville','JOI',30,30,1,1),(1322,'Juara','JUA',30,30,1,1),(1323,'Juazeiro Do Norte','JDO',30,30,1,1),(1324,'Juina','JIA',30,30,1,1),(1325,'Juiz De Fora','JDF',30,30,1,1),(1326,'Jundiai','QDV',30,30,1,1),(1327,'Juruena','JRN',30,30,1,1),(1328,'Labrea','LBR',30,30,1,1),(1329,'Lages','LAJ',30,30,1,1),(1330,'Lajeado','QLB',30,30,1,1),(1331,'Lavras','QLW',30,30,1,1),(1332,'Lencois','LEC',30,30,1,1),(1333,'Lencois Paulista','QGC',30,30,1,1),(1334,'Leopoldina','LEP',30,30,1,1),(1335,'Limeira','QGB',30,30,1,1),(1336,'Lins','LIP',30,30,1,1),(1337,'Livramento','LVB',30,30,1,1),(1338,'Londrina','LDB',30,30,1,1),(1339,'Macae','MEA',30,30,1,1),(1340,'Macapa','MCP',30,30,1,1),(1341,'Maceio','MCZ',30,30,1,1),(1342,'Mafra','QMF',30,30,1,1),(1343,'Mairipora','QMC',30,30,1,1),(1344,'Manaus','MAO',30,30,1,1),(1345,'Manicore','MNX',30,30,1,1),(1346,'Maraba','MAB',30,30,1,1),(1347,'Marilia','MII',30,30,1,1),(1348,'Maringa','MGF',30,30,1,1),(1349,'Mato Grosso','MTG',30,30,1,1),(1350,'Matupa','MBK',30,30,1,1),(1351,'Maues','MBZ',30,30,1,1),(1352,'Minacu','MQH',30,30,1,1),(1353,'Miracema Do Norte','NTM',30,30,1,1),(1354,'Mococa','QOA',30,30,1,1),(1355,'Mogi Das Cruzes','QMI',30,30,1,1),(1356,'Monte Alegre','QGD',30,30,1,1),(1358,'Monte Dourado','MEU',30,30,1,1),(1359,'Monte Negro','QGF',30,30,1,1),(1360,'Montes Claros','MOC',30,30,1,1),(1361,'Mossoro','MVF',30,30,1,1),(1362,'Mucuri','MVS',30,30,1,1),(1363,'Muriae','QUR',30,30,1,1),(1364,'Nanuque','NNU',30,30,1,1),(1365,'Natal','NAT',30,30,1,1),(1366,'Navegantes','NVT',30,30,1,1),(1367,'Niquelandia','NQL',30,30,1,1),(1368,'Niteroi','QNT',30,30,1,1),(1369,'Nova Friburgo','QGJ',30,30,1,1),(1370,'Nova Iguacu','QNV',30,30,1,1),(1371,'Nova Xavantina','NOK',30,30,1,1),(1372,'Novo Aripuana','NVP',30,30,1,1),(1373,'Novo Hamburgo','QHV',30,30,1,1),(1374,'Novo Progresso','NPR',30,30,1,1),(1375,'Obidos','OBI',30,30,1,1),(1376,'Oiapoque','OYK',30,30,1,1),(1377,'Oriximina','ORX',30,30,1,1),(1378,'Osasco','QOC',30,30,1,1),(1379,'Osvaldo Cruz','QOD',30,30,1,1),(1380,'Ourilandia','OIA',30,30,1,1),(1381,'Ourinhos','OUS',30,30,1,1),(1382,'Palmares','QGK',30,30,1,1),(1383,'Palmas','PMW',30,30,1,1),(1384,'Panambi','QMB',30,30,1,1),(1385,'Paranagua','PNG',30,30,1,1),(1386,'Paranaiba','PBB',30,30,1,1),(1387,'Paranavai','PVI',30,30,1,1),(1388,'Parintins','PIN',30,30,1,1),(1389,'Parnaiba','PHB',30,30,1,1),(1390,'Parnamirim','QEU',30,30,1,1),(1391,'Passo Fundo','PFB',30,30,1,1),(1392,'Passos','PSW',30,30,1,1),(1393,'Pato Branco','PTO',30,30,1,1),(1394,'Patos De Minas','POJ',30,30,1,1),(1395,'Paulo Afonso','PAV',30,30,1,1),(1396,'Pelotas','PET',30,30,1,1),(1397,'Petrolina','PNZ',30,30,1,1),(1398,'Petropolis','QPE',30,30,1,1),(1399,'Picos','PCS',30,30,1,1),(1400,'Pimenta Bueno','PBQ',30,30,1,1),(1401,'Pinheiro','PHI',30,30,1,1),(1402,'Piracicaba','QHB',30,30,1,1),(1403,'Pirapora','PIV',30,30,1,1),(1404,'Pirassununga','QPS',30,30,1,1),(1405,'Pitinga','PIG',30,30,1,1),(1406,'Pocos De Caldas','POO',30,30,1,1),(1407,'Pompeia','QPF',30,30,1,1),(1408,'Ponta Grossa','PGZ',30,30,1,1),(1409,'Ponta Pelada','PLL',30,30,1,1),(1410,'Ponta Pora','PMG',30,30,1,1),(1411,'Pontes E Lacerda','LCB',30,30,1,1),(1412,'Porto Alegre','POA',30,30,1,1),(1413,'Porto Alegre Do Norte','PBX',30,30,1,1),(1414,'Porto De Moz','PTQ',30,30,1,1),(1415,'Porto Nacional','PNB',30,30,1,1),(1416,'Porto Seguro','BPS',30,30,1,1),(1417,'Porto Uniao','QPU',30,30,1,1),(1418,'Porto Velho','PVH',30,30,1,1),(1419,'Portos Dos Gaucho','PBV',30,30,1,1),(1420,'Prado','PDF',30,30,1,1),(1421,'Presidente Dutra','PDR',30,30,1,1),(1422,'Presidente Prudente','PPB',30,30,1,1),(1423,'Progresso','PGG',30,30,1,1),(1424,'Quixada','QIX',30,30,1,1),(1425,'Recife','REC',30,30,1,1),(1426,'Redencao','RDC',30,30,1,1),(1427,'Resende','QRZ',30,30,1,1),(1429,'Ribeirao Preto','RAO',30,30,1,1),(1430,'Rio Branco','RBR',30,30,1,1),(1431,'Rio Claro','QIQ',30,30,1,1),(1432,'Rio de Janeiro','GIG',30,30,1,1),(1435,'Rio Do Sul','QRU',30,30,1,1),(1436,'Rio Grande','RIG',30,30,1,1),(1437,'Rio Negrinho','QNE',30,30,1,1),(1438,'Rio Verde','RVD',30,30,1,1),(1439,'Rolandia','QHC',30,30,1,1),(1440,'Rondonopolis','ROO',30,30,1,1),(1441,'Salvador','SSA',30,30,1,1),(1442,'Santa Cruz','SNZ',30,30,1,1),(1443,'Santa Cruz Do Sul','CSU',30,30,1,1),(1444,'Santa Cruz Rio Pardo','QNR',30,30,1,1),(1445,'Santa Fe Do Sul','SFV',30,30,1,1),(1446,'Santa Isabel Do M','IDO',30,30,1,1),(1447,'Santa Isabel Rio Negro','IRZ',30,30,1,1),(1448,'Santa Maria','RIA',30,30,1,1),(1449,'Santa Rosa','SRA',30,30,1,1),(1450,'Santa Terezinha','STZ',30,30,1,1),(1451,'Santa Vitoria','CTQ',30,30,1,1),(1452,'Santana Do Aragua','CMP',30,30,1,1),(1453,'Santarem','STM',30,30,1,1),(1454,'Santiago','XGO',30,30,1,1),(1455,'Santo Andre','QSE',30,30,1,1),(1456,'Santo Angelo','GEL',30,30,1,1),(1457,'Santos','SSZ',30,30,1,1),(1458,'Sao Bento Do Sul','QHE',30,30,1,1),(1459,'Sao Bernardo Do C','QSB',30,30,1,1),(1460,'Sao Borja','QOJ',30,30,1,1),(1461,'Sao Caetano Do Sul','QCX',30,30,1,1),(1462,'Sao Carlos','QSC',30,30,1,1),(1463,'Sao Felix Do Arag','SXO',30,30,1,1),(1464,'Sao Felix Do Xing','SXX',30,30,1,1),(1465,'Sao Francisco','QFS',30,30,1,1),(1466,'Sao Gabriel','SJL',30,30,1,1),(1467,'Sao Goncalo','QSD',30,30,1,1),(1468,'Sao Goncalo Amara','QTE',30,30,1,1),(1469,'Sao Joao del Rei','JDR',30,30,1,1),(1471,'Sao Jose','XOE',30,30,1,1),(1472,'Sao Jose Do Rio Preto','SJP',30,30,1,1),(1473,'Sao Jose Dos Campos','SJK',30,30,1,1),(1474,'Sao Leopoldo','QLL',30,30,1,1),(1475,'Sao Lourenco','SSO',30,30,1,1),(1476,'Sao Lourenco Do Sul','SQY',30,30,1,1),(1477,'Sao Luis','SLZ',30,30,1,1),(1478,'Sao Mateus','SBJ',30,30,1,1),(1479,'Sao Miguel Araguaia','SQM',30,30,1,1),(1480,'Sao Paulo','GRU',30,30,1,1),(1484,'Sao Paulo de Olivenca','OLC',30,30,1,1),(1485,'Sao Sebastiao Do','QHF',30,30,1,1),(1486,'Sena Madureira','ZMD',30,30,1,1),(1487,'Senhor Do Bonfim','SEI',30,30,1,1),(1488,'Serra Norte','RRN',30,30,1,1),(1489,'Serra Pelada','RSG',30,30,1,1),(1490,'Sete Lagoas','QHG',30,30,1,1),(1491,'Sinop','OPS',30,30,1,1),(1492,'Sobral','QBX',30,30,1,1),(1493,'Sorocaba','SOD',30,30,1,1),(1494,'Soure','SFK',30,30,1,1),(1495,'Suia Missu','SWM',30,30,1,1),(1496,'Tabatinga','TBT',30,30,1,1),(1497,'Taguatinga','QHN',30,30,1,1),(1498,'Tangara de Serra','TGQ',30,30,1,1),(1499,'Tarauaca','TRQ',30,30,1,1),(1500,'Taubate','QHP',30,30,1,1),(1501,'Tefe','TFF',30,30,1,1),(1502,'Teixeira Freitas','TXF',30,30,1,1),(1503,'Telemaco Borba','TEC',30,30,1,1),(1504,'Teofilo Otoni','TFL',30,30,1,1),(1505,'Teresina','THE',30,30,1,1),(1506,'Terezopolis','QHT',30,30,1,1),(1507,'Timbauba','QTD',30,30,1,1),(1508,'Timbo','XHR',30,30,1,1),(1509,'Toledo','TOW',30,30,1,1),(1510,'Torres','TSQ',30,30,1,1),(1511,'Tres Coracoes','QID',30,30,1,1),(1512,'Tres Rios','QIH',30,30,1,1),(1513,'Trombetas','TMT',30,30,1,1),(1514,'Tubarao','ZHX',30,30,1,1),(1515,'Tucuma','TUZ',30,30,1,1),(1516,'Tucurui','TUR',30,30,1,1),(1517,'Tupi Paulista','QTG',30,30,1,1),(1518,'Ubatuba','UBT',30,30,1,1),(1519,'Uberaba','UBA',30,30,1,1),(1520,'Uberlandia','UDI',30,30,1,1),(1521,'Umuarama','UMU',30,30,1,1),(1522,'Uniao Da Vitoria','QVB',30,30,1,1),(1523,'Urubupunga','URB',30,30,1,1),(1524,'Uruguaiana','URG',30,30,1,1),(1525,'Valenca','VAL',30,30,1,1),(1526,'Varginha','VAG',30,30,1,1),(1527,'Vicosa','QVC',30,30,1,1),(1528,'Videira','VIA',30,30,1,1),(1529,'Vila Rica','VLP',30,30,1,1),(1530,'Vila Velha','QVH',30,30,1,1),(1531,'Vilhena','BVH',30,30,1,1),(1532,'Vitoria','VIX',30,30,1,1),(1533,'Vitoria Da Cnquis','VDC',30,30,1,1),(1534,'Volta Redonda','QVR',30,30,1,1),(1535,'Votuporanga','VOT',30,30,1,1),(1536,'Xanxere','AXE',30,30,1,1),(1537,'Xinguera','XIG',30,30,1,1),(1538,'Bandar Seri Begawan','BWN',32,32,1,1),(1539,'Kuala Belait','KUB',32,32,1,1),(1540,'Burgas','BOJ',33,33,1,1),(1541,'Gorna Oryahovitsa','GOZ',33,33,1,1),(1542,'Haskovo','HKV',33,33,1,1),(1543,'Jambol','JAM',33,33,1,1),(1544,'Plovdiv','PDV',33,33,1,1),(1545,'Ruse','ROU',33,33,1,1),(1546,'Silistra','SLS',33,33,1,1),(1547,'Sofia','SOF',33,33,1,1),(1548,'Stara Zagora','SZR',33,33,1,1),(1549,'Targovishte','TGV',33,33,1,1),(1550,'Varna','VAR',33,33,1,1),(1551,'Vrajdebna','QVJ',33,33,1,1),(1552,'Aribinda','XAR',34,34,1,1),(1553,'Arly','ARL',34,34,1,1),(1554,'Banfora','BNR',34,34,1,1),(1555,'Bobo Dioulasso','BOY',34,34,1,1),(1556,'Bogande','XBG',34,34,1,1),(1557,'Boulsa','XBO',34,34,1,1),(1558,'Dedougou','DGU',34,34,1,1),(1559,'Diapaga','DIP',34,34,1,1),(1560,'Diebougou','XDE',34,34,1,1),(1561,'Djibo','XDJ',34,34,1,1),(1562,'Dori','DOR',34,34,1,1),(1563,'Fada N\'gourma','FNG',34,34,1,1),(1564,'Gaoua','XGA',34,34,1,1),(1565,'Gorom Gorom','XGG',34,34,1,1),(1566,'Kantchari','XKA',34,34,1,1),(1567,'Kaya','XKY',34,34,1,1),(1568,'Leo','XLU',34,34,1,1),(1569,'Nouna','XNU',34,34,1,1),(1570,'Ouagadougou','OUA',34,34,1,1),(1571,'Ouahigouya','OUG',34,34,1,1),(1572,'Pama','XPA',34,34,1,1),(1573,'Sebba','XSE',34,34,1,1),(1574,'Tambao','TMQ',34,34,1,1),(1575,'Tenkodogo','TEG',34,34,1,1),(1576,'Tougan','TUQ',34,34,1,1),(1577,'Zabre','XZA',34,34,1,1),(1578,'Bujumbura','BJM',35,35,1,1),(1579,'Gitega','GID',35,35,1,1),(1580,'Kirundo','KRE',35,35,1,1),(1581,'Battambang','BBM',36,36,1,1),(1582,'Kampot','KMT',36,36,1,1),(1583,'Koh Kong','KKZ',36,36,1,1),(1584,'Kompong Chhnang','KZC',36,36,1,1),(1585,'Kompong Thom','KZK',36,36,1,1),(1586,'Krakor','KZD',36,36,1,1),(1587,'Kratie','KTI',36,36,1,1),(1588,'Mundulkiri','MWV',36,36,1,1),(1589,'Oddor Meanche','OMY',36,36,1,1),(1590,'Pailin','PAI',36,36,1,1),(1591,'Phnom Penh','PNH',36,36,1,1),(1592,'Ratanankiri','RBE',36,36,1,1),(1593,'Siem Reap','REP',36,36,1,1),(1594,'Sihanoukville','KOS',36,36,1,1),(1595,'Stung Treng','TNX',36,36,1,1),(1596,'Svay Rieng','SVR',36,36,1,1),(1597,'Bafoussam','BFX',37,37,1,1),(1598,'Bali','BLC',37,37,1,1),(1599,'Bamenda','BPC',37,37,1,1),(1600,'Batouri','OUR',37,37,1,1),(1601,'Bertoua','BTA',37,37,1,1),(1602,'Douala','DLA',37,37,1,1),(1603,'Dschang','DSC',37,37,1,1),(1604,'Ebolowa','EBW',37,37,1,1),(1605,'Foumban','FOM',37,37,1,1),(1606,'Garoua','GOU',37,37,1,1),(1607,'Kaele','KLE',37,37,1,1),(1608,'Kribi','KBI',37,37,1,1),(1609,'Limbe','VCC',37,37,1,1),(1610,'Mamfe','MMF',37,37,1,1),(1611,'Maroua','MVR',37,37,1,1),(1612,'Ngaoundere','NGE',37,37,1,1),(1613,'Nkongsamba','NKS',37,37,1,1),(1614,'Tiko','TKC',37,37,1,1),(1615,'Yagoua','GXX',37,37,1,1),(1616,'Yaounde','YAO',37,37,1,1),(1618,'Abbotsford','YXX',38,38,1,1),(1619,'Aklavik','LAK',38,38,1,1),(1620,'Akulivik','AKV',38,38,1,1),(1621,'Aldershot','XLY',38,38,1,1),(1622,'Alert','YLT',38,38,1,1),(1623,'Alert Bay','YAL',38,38,1,1),(1624,'Alexandria','XFS',38,38,1,1),(1625,'Alice Arm','ZAA',38,38,1,1),(1626,'Alma','YTF',38,38,1,1),(1627,'Alta Lake','YAE',38,38,1,1),(1628,'Amherst','XZK',38,38,1,1),(1629,'Amos','YEY',38,38,1,1),(1630,'Anahim Lake','YAA',38,38,1,1),(1631,'Angling Lake','YAX',38,38,1,1),(1632,'Apapamiska Lake','YBS',38,38,1,1),(1633,'Arctic Bay','YAB',38,38,1,1),(1634,'Arctic Red River','ZRR',38,38,1,1),(1635,'Argentia','NWP',38,38,1,1),(1636,'Armstrong','YYW',38,38,1,1),(1637,'Arnes','YNR',38,38,1,1),(1638,'Asbestos Hill','YAF',38,38,1,1),(1639,'Ashcroft','YZA',38,38,1,1),(1640,'Atikokan','YIB',38,38,1,1),(1641,'Attawapiskat','YAT',38,38,1,1),(1642,'Aupauluk','YPJ',38,38,1,1),(1643,'Bagotville','YBG',38,38,1,1),(1644,'Baie Comeau','YBC',38,38,1,1),(1645,'Baie Johan Beetz','YBJ',38,38,1,1),(1646,'Baker Lake','YBK',38,38,1,1),(1647,'Bamfield','YBF',38,38,1,1),(1648,'Banff','YBA',38,38,1,1),(1649,'Bathurst','ZBF',38,38,1,1),(1650,'Bearskin Lake','XBE',38,38,1,1),(1651,'Beatton River','YZC',38,38,1,1),(1652,'Beaver Creek','YXQ',38,38,1,1),(1653,'Bedwell Harbor','YBW',38,38,1,1),(1654,'Bella Coola','QBC',38,38,1,1),(1655,'Belleville','XVV',38,38,1,1),(1656,'Berens River','YBV',38,38,1,1),(1657,'Big Bay Marina','YIG',38,38,1,1),(1658,'Big Bay Yacht Club','YYA',38,38,1,1),(1659,'Big Trout','YTL',38,38,1,1),(1660,'Black Tickle','YBI',38,38,1,1),(1661,'Blanc Sablon','YBX',38,38,1,1),(1662,'Bloodvein','YDV',38,38,1,1),(1663,'Blubber Bay','XBB',38,38,1,1),(1664,'Bobquinn Lake','YBO',38,38,1,1),(1665,'Bonaventure','YVB',38,38,1,1),(1666,'Bonnyville','YBY',38,38,1,1),(1667,'Borden','YBN',38,38,1,1),(1668,'Brampton','XPN',38,38,1,1),(1669,'Brandon','YBR',38,38,1,1),(1670,'Brantford','XFV',38,38,1,1),(1671,'Broadview','YDR',38,38,1,1),(1672,'Brochet','YBT',38,38,1,1),(1673,'Brockville','XBR',38,38,1,1),(1674,'Bromont','ZBM',38,38,1,1),(1675,'Bronson Creek','YBM',38,38,1,1),(1676,'Broughton Island','YVM',38,38,1,1),(1677,'Buchans','YZM',38,38,1,1),(1678,'Buffalo Narrows','YVT',38,38,1,1),(1679,'Bull Harbour','YBH',38,38,1,1),(1680,'Burns Lake','YPZ',38,38,1,1),(1681,'Burwash Landings','YDB',38,38,1,1),(1682,'Calgary','YYC',38,38,1,1),(1683,'Cambridge Bay','YCB',38,38,1,1),(1684,'Campbell River','YBL',38,38,1,1),(1685,'Campbellton','XAZ',38,38,1,1),(1687,'Cape Dorset','YTE',38,38,1,1),(1688,'Cape Dyer','YVN',38,38,1,1),(1689,'Cape St James','YCJ',38,38,1,1),(1690,'Capreol','XAW',38,38,1,1),(1691,'Caribou Island','YCI',38,38,1,1),(1692,'Carleton','XON',38,38,1,1),(1693,'Cartierville','YCV',38,38,1,1),(1694,'Cartwright','YRF',38,38,1,1),(1695,'Casselman','XZB',38,38,1,1),(1696,'Castlegar','YCG',38,38,1,1),(1697,'Cat Lake','YAC',38,38,1,1),(1698,'Centralia','YCE',38,38,1,1),(1699,'Chambord','XCI',38,38,1,1),(1700,'Chandler','XDL',38,38,1,1),(1701,'Chapleau','YLD',38,38,1,1),(1702,'Charlo','YCL',38,38,1,1),(1703,'Charlottetown','YYG',38,38,1,1),(1705,'Chatham','XCM',38,38,1,1),(1706,'Chemainus','XHS',38,38,1,1),(1707,'Chesterfield Inle','YCS',38,38,1,1),(1708,'Chetwynd','YCQ',38,38,1,1),(1709,'Chevery','YHR',38,38,1,1),(1710,'Chibougamau','YMT',38,38,1,1),(1711,'Chilko Lake','CJH',38,38,1,1),(1712,'Chilliwack','YCW',38,38,1,1),(1713,'Chisasibi','YKU',38,38,1,1),(1714,'Churchill','YYQ',38,38,1,1),(1715,'Churchill Falls','ZUM',38,38,1,1),(1716,'Chute-des-Passes','YWQ',38,38,1,1),(1717,'Clinton Creek','YLM',38,38,1,1),(1718,'Cluff Lake','XCL',38,38,1,1),(1719,'Clyde River','YCY',38,38,1,1),(1720,'Co Op Point','YCP',38,38,1,1),(1721,'Cobourg','XGJ',38,38,1,1),(1722,'Cochrane','YCN',38,38,1,1),(1723,'Cold Lake','YOD',38,38,1,1),(1724,'Collins Bay','YKC',38,38,1,1),(1725,'Colville Lake','YCK',38,38,1,1),(1726,'Comox','YQQ',38,38,1,1),(1727,'Coppermine','YCO',38,38,1,1),(1728,'Coral Harbour','YZS',38,38,1,1),(1729,'Corner Brook','YNF',38,38,1,1),(1730,'Cornwall','YCC',38,38,1,1),(1731,'Coronation','YCT',38,38,1,1),(1732,'Cortes Bay','YCF',38,38,1,1),(1733,'Coteau','XGK',38,38,1,1),(1734,'Courtenay','YCA',38,38,1,1),(1735,'Cowley','YYM',38,38,1,1),(1736,'Cranbrook','YXC',38,38,1,1),(1737,'Creston','CFQ',38,38,1,1),(1738,'Cross Lake','YCR',38,38,1,1),(1739,'Daniels Harbour','YDH',38,38,1,1),(1740,'Dauphin','YDN',38,38,1,1),(1741,'Davis Inlet','YDI',38,38,1,1),(1742,'Dawson City','YDA',38,38,1,1),(1743,'Dawson Creek','YDQ',38,38,1,1),(1744,'Dean River','YRD',38,38,1,1),(1745,'Dease Lake','YDL',38,38,1,1),(1746,'Deception','YGY',38,38,1,1),(1747,'Deer Lake','YVZ',38,38,1,1),(1749,'Desolation Sound','YDS',38,38,1,1),(1750,'Digby','YDG',38,38,1,1),(1751,'Doc Creek','YDX',38,38,1,1),(1752,'Dolbeau','YDO',38,38,1,1),(1753,'Douglas Lake','DGF',38,38,1,1),(1754,'Drayton Valley','YDC',38,38,1,1),(1755,'Drummondville','XDM',38,38,1,1),(1756,'Dryden','YHD',38,38,1,1),(1757,'Duncan Quam','DUQ',38,38,1,1),(1758,'Earlton','YXR',38,38,1,1),(1759,'Edmonton','YEA',38,38,1,1),(1762,'Edson','YET',38,38,1,1),(1763,'Ekati','YOA',38,38,1,1),(1764,'Elliot Lake','YEL',38,38,1,1),(1765,'Eskimo Point','YEK',38,38,1,1),(1766,'Esquimalt','YPF',38,38,1,1),(1767,'Estevan','YEN',38,38,1,1),(1768,'Estevan Point','YEP',38,38,1,1),(1769,'Eureka','YEU',38,38,1,1),(1770,'Fairmount Springs','YCZ',38,38,1,1),(1771,'Fairview','ZFW',38,38,1,1),(1772,'Falher','YOE',38,38,1,1),(1773,'Flin Flon','YFO',38,38,1,1),(1774,'Fond Du Lac','ZFD',38,38,1,1),(1775,'Fontanges','YFG',38,38,1,1),(1776,'Forestville','YFE',38,38,1,1),(1777,'Fort Albany','YFA',38,38,1,1),(1778,'Fort Chipewyan','YPY',38,38,1,1),(1779,'Fort Frances','YAG',38,38,1,1),(1780,'Fort Franklin','YWJ',38,38,1,1),(1781,'Fort Good Hope','YGH',38,38,1,1),(1782,'Fort Hope','YFH',38,38,1,1),(1783,'Fort Liard','YJF',38,38,1,1),(1784,'Fort Mackay','JHL',38,38,1,1),(1786,'Fort Mcmurray','YMM',38,38,1,1),(1787,'Fort Mcpherson','ZFM',38,38,1,1),(1788,'Fort Nelson','YYE',38,38,1,1),(1789,'Fort Norman','ZFN',38,38,1,1),(1790,'Fort Reliance','YFL',38,38,1,1),(1791,'Fort Resolution','YFR',38,38,1,1),(1792,'Fort Severn','YER',38,38,1,1),(1793,'Fort Simpson','YFS',38,38,1,1),(1794,'Fort Smith','YSM',38,38,1,1),(1795,'Fort St John','YXJ',38,38,1,1),(1796,'Fox Harbour','YFX',38,38,1,1),(1797,'Fredericton','YFC',38,38,1,1),(1798,'Fredericton Junc','XFC',38,38,1,1),(1799,'Gagetown','YCX',38,38,1,1),(1800,'Gagnon','YGA',38,38,1,1),(1801,'Gananoque','XGW',38,38,1,1),(1802,'Gander','YQX',38,38,1,1),(1803,'Ganges Harbor','YGG',38,38,1,1),(1804,'Garrow Lake','GOW',38,38,1,1),(1805,'Gaspe','YGP',38,38,1,1),(1806,'Gatineau','YND',38,38,1,1),(1807,'Gc Apollo','QZP',38,38,1,1),(1808,'Georgetown','XHM',38,38,1,1),(1809,'Geraldton','YGQ',38,38,1,1),(1810,'Germansen','YGS',38,38,1,1),(1811,'Gethsemani','ZGS',38,38,1,1),(1812,'Gillam','YGX',38,38,1,1),(1813,'Gillies Bay','YGB',38,38,1,1),(1814,'Gimli','YGM',38,38,1,1),(1815,'Gjoa Haven','YHK',38,38,1,1),(1816,'Glencoe','XZC',38,38,1,1),(1817,'Gods Narrows','YGO',38,38,1,1),(1818,'Gods River','ZGI',38,38,1,1),(1819,'Goose Bay','YYR',38,38,1,1),(1820,'Gore Bay','YZE',38,38,1,1),(1821,'Gorge Harbor','YGE',38,38,1,1),(1822,'Grand Forks','ZGF',38,38,1,1),(1823,'Grande Cache','YGC',38,38,1,1),(1824,'Grande Prairie','YQU',38,38,1,1),(1825,'Grande Riviere','XDO',38,38,1,1),(1826,'Granville Lake','XGL',38,38,1,1),(1827,'Great Bear Lake','DAS',38,38,1,1),(1828,'Greenway Sound','YGN',38,38,1,1),(1829,'Greenwood','YZX',38,38,1,1),(1830,'Grimsby','XGY',38,38,1,1),(1831,'Grise Fiord','YGZ',38,38,1,1),(1832,'Guelph','XIA',38,38,1,1),(1833,'Guildwood','XLQ',38,38,1,1),(1834,'Haines Junction','YHT',38,38,1,1),(1835,'Hakai Pass','YHC',38,38,1,1),(1836,'Halifax','YHZ',38,38,1,1),(1838,'Hall Beach','YUX',38,38,1,1),(1839,'Hartley Bay','YTB',38,38,1,1),(1840,'Hatchet Lake','YDJ',38,38,1,1),(1841,'Havre St Pierre','YGV',38,38,1,1),(1842,'Hay River','YHY',38,38,1,1),(1843,'Hearst','YHF',38,38,1,1),(1844,'Hervey','XDU',38,38,1,1),(1845,'High Level','YOJ',38,38,1,1),(1846,'High Prairie','ZHP',38,38,1,1),(1847,'Holman','YHI',38,38,1,1),(1848,'Hope','YHE',38,38,1,1),(1849,'Hope Bay','UZM',38,38,1,1),(1850,'Hopedale','YHO',38,38,1,1),(1851,'Hornepayne','YHN',38,38,1,1),(1852,'Houston','ZHO',38,38,1,1),(1853,'Hudson Bay','YHB',38,38,1,1),(1854,'Hudson Hope','YNH',38,38,1,1),(1855,'Igloolik','YGT',38,38,1,1),(1856,'Ignace','ZUC',38,38,1,1),(1857,'Iles De La Madele','YGR',38,38,1,1),(1858,'Ilford','ILF',38,38,1,1),(1859,'Ingersoll','XIB',38,38,1,1),(1860,'Inukjuak','YPH',38,38,1,1),(1861,'Inuvik','YEV',38,38,1,1),(1862,'Inverlake','TIL',38,38,1,1),(1863,'Iqaluit','YFB',38,38,1,1),(1864,'Isachsen','YIC',38,38,1,1),(1865,'Island Lk Garden','YIV',38,38,1,1),(1866,'Ivujivik','YIK',38,38,1,1),(1867,'Jasper','YJA',38,38,1,1),(1868,'Jasper Hinton','YJP',38,38,1,1),(1869,'Jenpeg','ZJG',38,38,1,1),(1870,'Johnny Mountain','YJO',38,38,1,1),(1871,'Johnson Point','YUN',38,38,1,1),(1872,'Joliette','XJL',38,38,1,1),(1873,'Jonquiere','XJQ',38,38,1,1),(1874,'Kamloops','YKA',38,38,1,1),(1875,'Kangiqsualujjuaq','XGR',38,38,1,1),(1876,'Kangiqsujuaq','YWB',38,38,1,1),(1877,'Kangirsuk','YKG',38,38,1,1),(1878,'Kapuskasing','YYU',38,38,1,1),(1879,'Kasabonika','XKS',38,38,1,1),(1880,'Kasba Lake','YDU',38,38,1,1),(1881,'Kaschechewan','ZKE',38,38,1,1),(1882,'Kattiniq','YAU',38,38,1,1),(1883,'Keewaywin','KEW',38,38,1,1),(1884,'Kegaska','ZKG',38,38,1,1),(1885,'Kelowna','YLW',38,38,1,1),(1886,'Kelsey','KES',38,38,1,1),(1887,'Kemano','XKO',38,38,1,1),(1888,'Kennosao Lake','YKI',38,38,1,1),(1889,'Kenora','YQK',38,38,1,1),(1890,'Key Lake','YKJ',38,38,1,1),(1891,'Killaloe','YXI',38,38,1,1),(1892,'Killineq','XBW',38,38,1,1),(1893,'Kimberley','YQE',38,38,1,1),(1894,'Kincardine','YKD',38,38,1,1),(1895,'Kindersley','YKY',38,38,1,1),(1896,'Kingfisher Lake','KIF',38,38,1,1),(1897,'Kingston','YGK',38,38,1,1),(1898,'Kinoosao','KNY',38,38,1,1),(1899,'Kirkland Lake','YKX',38,38,1,1),(1900,'Kitchener','YKF',38,38,1,1),(1901,'Kitkatia','YKK',38,38,1,1),(1902,'Klemtu','YKT',38,38,1,1),(1903,'Knee Lake','YKE',38,38,1,1),(1904,'Knights Inlet','KNV',38,38,1,1),(1905,'Kuujjuaq','YVP',38,38,1,1),(1906,'Kuujjuarapik','YGW',38,38,1,1),(1907,'La Grande','YGL',38,38,1,1),(1908,'La Ronge','YVC',38,38,1,1),(1909,'La Sarre','SSQ',38,38,1,1),(1910,'La Tabatiere','ZLT',38,38,1,1),(1911,'La Tuque','YLQ',38,38,1,1),(1912,'Lac Biche','YLB',38,38,1,1),(1913,'Lac Brochet','XLB',38,38,1,1),(1914,'Lac Edouard','XEE',38,38,1,1),(1915,'Lac La Martre','YLE',38,38,1,1),(1916,'Lady Franklin','YUJ',38,38,1,1),(1917,'Ladysmith','XEH',38,38,1,1),(1918,'Laforges','YLF',38,38,1,1),(1919,'Lagrande 3','YAR',38,38,1,1),(1920,'Lagrande 4','YAH',38,38,1,1),(1921,'Lake Harbour','YLC',38,38,1,1),(1922,'Langara','YLA',38,38,1,1),(1923,'Langford','XEJ',38,38,1,1),(1924,'Langley','YLY',38,38,1,1),(1925,'Lansdowne House','YLH',38,38,1,1),(1926,'Laurie River','LRQ',38,38,1,1),(1927,'Leaf Bay','XLF',38,38,1,1),(1928,'Leaf Rapids','YLR',38,38,1,1),(1929,'Lebel Sur Quevillon','YLS',38,38,1,1),(1930,'Lethbridge','YQL',38,38,1,1),(1931,'Liard River','YZL',38,38,1,1),(1932,'Little Grand Rapids','ZGR',38,38,1,1),(1933,'Lloydminster','YLL',38,38,1,1),(1934,'Londres','YXU',38,38,1,1),(1935,'Long Point','YLX',38,38,1,1),(1936,'Lupin','YWO',38,38,1,1),(1937,'Lyall Harbour','YAJ',38,38,1,1),(1938,'Lynn Lake','YYL',38,38,1,1),(1939,'Macmillan Pass','XMP',38,38,1,1),(1940,'Main Duck Island','YDK',38,38,1,1),(1941,'Makkovik','YMN',38,38,1,1),(1942,'Manitouwadge','YMG',38,38,1,1),(1943,'Manitowaning','YEM',38,38,1,1),(1944,'Maniwaki','YMW',38,38,1,1),(1945,'Mansons Landing','YMU',38,38,1,1),(1946,'Maple Bay','YAQ',38,38,1,1),(1947,'Marathon','YSP',38,38,1,1),(1948,'Maricourt Airstrip','YMC',38,38,1,1),(1949,'Mary River','YMV',38,38,1,1),(1950,'Mary\'s Harbour','YMH',38,38,1,1),(1951,'Masset','ZMT',38,38,1,1),(1952,'Matagami','YNM',38,38,1,1),(1953,'Matane','YME',38,38,1,1),(1954,'Matapedia','XLP',38,38,1,1),(1955,'Maxville','XID',38,38,1,1),(1956,'Mayo','YMA',38,38,1,1),(1957,'Meadow Lake','YLJ',38,38,1,1),(1958,'Medicine Hat','YXH',38,38,1,1),(1959,'Melville','XEK',38,38,1,1),(1960,'Merritt','YMB',38,38,1,1),(1961,'Merry Island','YMR',38,38,1,1),(1962,'Mildred Lake','NML',38,38,1,1),(1963,'Mile Ranch 108','ZMH',38,38,1,1),(1964,'Minaki','YMI',38,38,1,1),(1965,'Miners Bay','YAV',38,38,1,1),(1966,'Mingan','YLP',38,38,1,1),(1967,'Miramichi','YCH',38,38,1,1),(1969,'Moncton','YQM',38,38,1,1),(1970,'Mont Joli','YYY',38,38,1,1),(1971,'Mont Tremblant','YTM',38,38,1,1),(1972,'Montagne Harbor','YMF',38,38,1,1),(1973,'Montreal','YHU',38,38,1,1),(1977,'Moose Jaw','YMJ',38,38,1,1),(1978,'Moose Lake','YAD',38,38,1,1),(1979,'Moosonee','YMO',38,38,1,1),(1980,'Mould Bay','YMD',38,38,1,1),(1981,'Murray Bay','YML',38,38,1,1),(1982,'Muskoka','YQA',38,38,1,1),(1983,'Muskrat Dam','MSA',38,38,1,1),(1984,'Nain','YDP',38,38,1,1),(1985,'Nakina','YQN',38,38,1,1),(1986,'Namu','ZNU',38,38,1,1),(1987,'Nanaimo','YCD',38,38,1,1),(1988,'Nanisivik','YSR',38,38,1,1),(1989,'Napanee','XIF',38,38,1,1),(1990,'Natashquan','YNA',38,38,1,1),(1991,'Natuashish','YNP',38,38,1,1),(1992,'Negginan','ZNG',38,38,1,1),(1993,'Nemiscau','YNS',38,38,1,1),(1994,'New Carlisle','XEL',38,38,1,1),(1995,'New Richmond','XEM',38,38,1,1),(1996,'New Westminister','YBD',38,38,1,1),(1997,'Niagara Falls','XLV',38,38,1,1),(1998,'Nitchequon','YNI',38,38,1,1),(1999,'Nootka Sound','YNK',38,38,1,1),(2000,'Norman Wells','YVQ',38,38,1,1),(2001,'North Battleford','YQW',38,38,1,1),(2002,'North Bay','YYB',38,38,1,1),(2003,'North Spirit Lake','YNO',38,38,1,1),(2004,'Norway House','YNE',38,38,1,1),(2005,'Oakville','XOK',38,38,1,1),(2006,'Obre Lake','YBU',38,38,1,1),(2007,'Ocean Falls','ZOF',38,38,1,1),(2008,'Ogoki','YOG',38,38,1,1),(2009,'Old Crow','YOC',38,38,1,1),(2010,'Old Fort Bay','ZFB',38,38,1,1),(2011,'Opinaca','YOI',38,38,1,1),(2012,'Oshawa','QWA',38,38,1,1),(2014,'Ottawa','YOW',38,38,1,1),(2015,'Owen Sound','YOS',38,38,1,1),(2016,'Oxford House','YOH',38,38,1,1),(2017,'Pakuashipi','YIF',38,38,1,1),(2018,'Pangnirtung','YXP',38,38,1,1),(2019,'Paradise River','YDE',38,38,1,1),(2020,'Parent','XFE',38,38,1,1),(2021,'Parksville','XPB',38,38,1,1),(2022,'Parry Sound','YPD',38,38,1,1),(2023,'Pass','XZP',38,38,1,1),(2024,'Paulatuk','YPC',38,38,1,1),(2025,'Peace River','YPE',38,38,1,1),(2026,'Peawanuck','YPO',38,38,1,1),(2027,'Pelly Bay','YBB',38,38,1,1),(2028,'Pembroke','YTA',38,38,1,1),(2029,'Pender Harbor','YPT',38,38,1,1),(2030,'Penticton','YYF',38,38,1,1),(2031,'Perce','XFG',38,38,1,1),(2032,'Petawawa','YWA',38,38,1,1),(2033,'Peterborough','YPQ',38,38,1,1),(2034,'Pickle Lake','YPL',38,38,1,1),(2035,'Pikangikum','YPM',38,38,1,1),(2036,'Pikwitonei','PIW',38,38,1,1),(2037,'Pincher Creek','WPC',38,38,1,1),(2038,'Pine House','ZPO',38,38,1,1),(2039,'Pine Point','YPP',38,38,1,1),(2040,'Point Aux Tremble','XPX',38,38,1,1),(2041,'Points North Land','YNL',38,38,1,1),(2042,'Pond Inlet','YIO',38,38,1,1),(2043,'Poplar Hill','YHP',38,38,1,1),(2044,'Poplar River','XPP',38,38,1,1),(2045,'Port Alberni','YPB',38,38,1,1),(2046,'Port Daniel','XFI',38,38,1,1),(2047,'Port Hardy','YZT',38,38,1,1),(2048,'Port Hawkesbury','YPS',38,38,1,1),(2049,'Port Hope','XPH',38,38,1,1),(2050,'Port Hope Simpson','YHA',38,38,1,1),(2051,'Port Mcneil','YMP',38,38,1,1),(2052,'Port Menier','YPN',38,38,1,1),(2053,'Port Radium','YIX',38,38,1,1),(2054,'Port Simpson','YPI',38,38,1,1),(2055,'Portage La Prairie','YPG',38,38,1,1),(2056,'Postville','YSO',38,38,1,1),(2057,'Povungnituk','YPX',38,38,1,1),(2058,'Powell Lake','WPL',38,38,1,1),(2059,'Powell River','YPW',38,38,1,1),(2060,'Prescott','XII',38,38,1,1),(2061,'Prince Albert','YPA',38,38,1,1),(2062,'Prince George','YXS',38,38,1,1),(2063,'Prince Rupert','YPR',38,38,1,1),(2064,'Pukatawagan','XPK',38,38,1,1),(2065,'Quadra Island','YQJ',38,38,1,1),(2066,'Qualicum','XQU',38,38,1,1),(2067,'Quaqtaq','YQC',38,38,1,1),(2068,'Quebec','YQB',38,38,1,1),(2069,'Queen Charlotte Is','ZQS',38,38,1,1),(2070,'Quesnel','YQZ',38,38,1,1),(2071,'Rae Lakes','YRA',38,38,1,1),(2072,'Rail','XZR',38,38,1,1),(2073,'Rainbow Lake','YOP',38,38,1,1),(2074,'Rankin Inlet','YRT',38,38,1,1),(2075,'Red Deer','YQF',38,38,1,1),(2076,'Red Lake','YRL',38,38,1,1),(2077,'Red Sucker Lake','YRS',38,38,1,1),(2078,'Refuge Cove','YRC',38,38,1,1),(2079,'Regina','YQR',38,38,1,1),(2080,'Repulse Bay','YUT',38,38,1,1),(2081,'Resolute','YRB',38,38,1,1),(2082,'Resolution Island','YRE',38,38,1,1),(2083,'Revelstoke','YRV',38,38,1,1),(2084,'Rigolet','YRG',38,38,1,1),(2085,'Rimouski','YXK',38,38,1,1),(2086,'Rivers','YYI',38,38,1,1),(2087,'Rivers Inlet','YRN',38,38,1,1),(2088,'Riviere A Pierre','XRP',38,38,1,1),(2089,'Riviere Au Tonner','YTN',38,38,1,1),(2090,'Riviere Du Loup','YRI',38,38,1,1),(2091,'Roberval','YRJ',38,38,1,1),(2092,'Rocky Mountain Ho','YRM',38,38,1,1),(2093,'Ross River','XRR',38,38,1,1),(2094,'Round Lake','ZRJ',38,38,1,1),(2095,'Rouyn','YUY',38,38,1,1),(2096,'Sable Island','YSA',38,38,1,1),(2097,'Sachigo Lake','ZPB',38,38,1,1),(2098,'Sachs Harbour','YSY',38,38,1,1),(2099,'Sackville','XKV',38,38,1,1),(2100,'Saglek','YSV',38,38,1,1),(2101,'Saint John','YSJ',38,38,1,1),(2102,'Saint Lambert','XLM',38,38,1,1),(2103,'Saint Leonard','YSL',38,38,1,1),(2104,'Salluit','YSW',38,38,1,1),(2106,'Salmon Arm','YSN',38,38,1,1),(2107,'Sandspit','YZP',38,38,1,1),(2108,'Sandy Lake','ZSJ',38,38,1,1),(2109,'Sanikiluaq','YSK',38,38,1,1),(2110,'Sans Souci','YSI',38,38,1,1),(2111,'Sarnia','YZR',38,38,1,1),(2112,'Saskatoon','YXE',38,38,1,1),(2113,'Sault Ste Marie','YAM',38,38,1,1),(2114,'Schefferville','YKL',38,38,1,1),(2115,'Sechelt','YHS',38,38,1,1),(2116,'Senneterre','XFK',38,38,1,1),(2117,'Sept Iles','YZV',38,38,1,1),(2118,'Shamattawa','ZTM',38,38,1,1),(2119,'Shawinigan','XFL',38,38,1,1),(2120,'Shawnigan','XFM',38,38,1,1),(2121,'Shearwater','YSX',38,38,1,1),(2122,'Sherbrooke','YSC',38,38,1,1),(2123,'Shilo','YLO',38,38,1,1),(2124,'Silva Bay','SYF',38,38,1,1),(2125,'Sioux Lookout','YXL',38,38,1,1),(2126,'Slate Island','YSS',38,38,1,1),(2127,'Slave Lake','YZH',38,38,1,1),(2128,'Smith Falls','YSH',38,38,1,1),(2129,'Smithers','YYD',38,38,1,1),(2130,'Snake River','YXF',38,38,1,1),(2131,'Snap Lake','YNX',38,38,1,1),(2132,'Snowdrift','YSG',38,38,1,1),(2133,'South Indian Lake','XSI',38,38,1,1),(2134,'South Trout Lake','ZFL',38,38,1,1),(2135,'Spring Island','YSQ',38,38,1,1),(2136,'Squamish','YSE',38,38,1,1),(2137,'Squirrel Cove','YSZ',38,38,1,1),(2138,'St Anthony','YAY',38,38,1,1),(2139,'St Catharines','YCM',38,38,1,1),(2140,'St Hyacinthe','XIM',38,38,1,1),(2141,'St Jean','YJN',38,38,1,1),(2142,'St Johns','YYT',38,38,1,1),(2143,'St Marys','XIO',38,38,1,1),(2144,'St Paul','ZSP',38,38,1,1),(2145,'St Thomas','YQS',38,38,1,1),(2146,'Ste Therese Point','YST',38,38,1,1),(2147,'Stephenville','YJT',38,38,1,1),(2148,'Stewart','ZST',38,38,1,1),(2149,'Stony Rapids','YSF',38,38,1,1),(2150,'Stratford','XFD',38,38,1,1),(2151,'Strathroy','XTY',38,38,1,1),(2152,'Stuart Island','YRR',38,38,1,1),(2153,'Sturdee','YTC',38,38,1,1),(2154,'Sudbury','YSB',38,38,1,1),(2155,'Suffield','YSD',38,38,1,1),(2156,'Sullivan Bay','YTG',38,38,1,1),(2157,'Summer Beaver','SUR',38,38,1,1),(2158,'Summerside','YSU',38,38,1,1),(2159,'Summit Lake','IUM',38,38,1,1),(2160,'Swan River','ZJN',38,38,1,1),(2161,'Swift Current','YYN',38,38,1,1),(2162,'Sydney','YQY',38,38,1,1),(2163,'Tadoule Lake','XTL',38,38,1,1),(2164,'Tahsis','ZTS',38,38,1,1),(2165,'Taloyoak','YYH',38,38,1,1),(2166,'Taltheilei','GSL',38,38,1,1),(2167,'Taschereau','XFO',38,38,1,1),(2168,'Tasiujuaq','YTQ',38,38,1,1),(2169,'Tasu','YTU',38,38,1,1),(2170,'Telegraph Creek','YTX',38,38,1,1),(2171,'Telegraph Harbour','YBQ',38,38,1,1),(2172,'Terrace','YXT',38,38,1,1),(2173,'Terrace Bay','YTJ',38,38,1,1),(2174,'Teslin','YZW',38,38,1,1),(2175,'Tete A La Baleine','ZTB',38,38,1,1),(2176,'The Pas','YQD',38,38,1,1),(2177,'Thicket Portage','YTD',38,38,1,1),(2178,'Thompson','YTH',38,38,1,1),(2179,'Thunder Bay','YQT',38,38,1,1),(2180,'Timmins','YTS',38,38,1,1),(2181,'Tisdale','YTT',38,38,1,1),(2182,'Tofino','YAZ',38,38,1,1),(2183,'Toronto','YKZ',38,38,1,1),(2188,'Trail','YZZ',38,38,1,1),(2189,'Trenton','YTR',38,38,1,1),(2190,'Triple Island','YTI',38,38,1,1),(2191,'Trois Rivieres','YRQ',38,38,1,1),(2192,'Truro','XLZ',38,38,1,1),(2193,'Tuktoyaktuk','YUB',38,38,1,1),(2194,'Tulugak','YTK',38,38,1,1),(2195,'Tumbler Ridge','TUX',38,38,1,1),(2196,'Tungsten','TNS',38,38,1,1),(2197,'Umiujaq','YUD',38,38,1,1),(2198,'Uranium City','YBE',38,38,1,1),(2199,'Val D\'Or','YVO',38,38,1,1),(2200,'Valcartier','YOY',38,38,1,1),(2201,'Vancouver','YVR',38,38,1,1),(2203,'Vermilion','YVG',38,38,1,1),(2204,'Vernon','YVE',38,38,1,1),(2205,'Victoria','YWH',38,38,1,1),(2207,'Wabush','YWK',38,38,1,1),(2208,'Waskaganish','YKQ',38,38,1,1),(2209,'Watford','XWA',38,38,1,1),(2210,'Watson Lake','YQH',38,38,1,1),(2211,'Wawa','YXZ',38,38,1,1),(2212,'Webequie','YWP',38,38,1,1),(2213,'Wemindji','YNC',38,38,1,1),(2214,'Weymont','XFQ',38,38,1,1),(2215,'Whale Cove','YXN',38,38,1,1),(2216,'Whistler','YWS',38,38,1,1),(2217,'White River','YWR',38,38,1,1),(2218,'Whitecourt','YZU',38,38,1,1),(2219,'Whitehorse','YXY',38,38,1,1),(2220,'Wiarton','YVV',38,38,1,1),(2221,'Williams Harbour','YWM',38,38,1,1),(2222,'Williams Lake','YWL',38,38,1,1),(2223,'Windsor','YQG',38,38,1,1),(2224,'Winisk','YWN',38,38,1,1),(2225,'Winnipeg','YWG',38,38,1,1),(2226,'Wollaston Lake','ZWL',38,38,1,1),(2227,'Woodstock','XIP',38,38,1,1),(2228,'Wrigley','YWY',38,38,1,1),(2229,'Wunnummin Lake','WNN',38,38,1,1),(2230,'Wyoming','XWY',38,38,1,1),(2231,'Yarmouth','YQI',38,38,1,1),(2232,'Yellowknife','YZF',38,38,1,1),(2233,'York Landing','ZAC',38,38,1,1),(2234,'Yorkton','YQV',38,38,1,1),(2235,'Boa Vista Island','BVC',39,39,1,1),(2236,'Brava Island','BVR',39,39,1,1),(2237,'Maio Island','MMO',39,39,1,1),(2238,'Mosteiros','MTI',39,39,1,1),(2239,'Praia','RAI',39,39,1,1),(2240,'Sal Island','SID',39,39,1,1),(2241,'Santo Antao Island','NTO',39,39,1,1),(2242,'Sao Filipe','SFL',39,39,1,1),(2243,'Sao Nicolau Island','SNE',39,39,1,1),(2244,'Sao Vicente Island','VXE',39,39,1,1),(2245,'Cayman Brac Is','CYB',40,40,1,1),(2246,'Grand Cayman Island','GCM',40,40,1,1),(2247,'Little Cayman','LYB',40,40,1,1),(2248,'Bakouma','BMF',41,41,1,1),(2249,'Bambari','BBY',41,41,1,1),(2250,'Bangassou','BGU',41,41,1,1),(2251,'Bangui','BGF',41,41,1,1),(2252,'Batangafo','BTG',41,41,1,1),(2253,'Berberati','BBT',41,41,1,1),(2254,'Birao','IRO',41,41,1,1),(2255,'Bossangoa','BSN',41,41,1,1),(2256,'Bossembele','BEM',41,41,1,1),(2257,'Bouar','BOP',41,41,1,1),(2258,'Bouca','BCF',41,41,1,1),(2259,'Bozoum','BOZ',41,41,1,1),(2260,'Bria','BIV',41,41,1,1),(2261,'Carnot','CRF',41,41,1,1),(2262,'Gounda','GDA',41,41,1,1),(2263,'Kawadjia','KWD',41,41,1,1),(2264,'Koumala','KOL',41,41,1,1),(2265,'Melle','GDI',41,41,1,1),(2266,'Ndele','NDL',41,41,1,1),(2267,'Obo','MKI',41,41,1,1),(2268,'Ouadda','ODA',41,41,1,1),(2269,'Ouanda Djalle','ODJ',41,41,1,1),(2270,'Rafai','RFA',41,41,1,1),(2271,'Yalinga','AIG',41,41,1,1),(2272,'Zemio','IMO',41,41,1,1),(2273,'Abeche','AEH',42,42,1,1),(2274,'Abou Deia','AOD',42,42,1,1),(2275,'Am Timan','AMC',42,42,1,1),(2276,'Ati','ATV',42,42,1,1),(2277,'Bokoro','BKR',42,42,1,1),(2278,'Bol','OTC',42,42,1,1),(2279,'Bongor','OGR',42,42,1,1),(2280,'Bousso','OUT',42,42,1,1),(2281,'Faya-Largeau','FYT',42,42,1,1),(2282,'Lai','LTC',42,42,1,1),(2283,'Mao','AMO',42,42,1,1),(2284,'Melfi','MEF',42,42,1,1),(2285,'Mongo','MVO',42,42,1,1),(2286,'Moundou','MQQ',42,42,1,1),(2287,'N\'Djamena','NDJ',42,42,1,1),(2288,'Oum Hadjer','OUM',42,42,1,1),(2289,'Pala','PLF',42,42,1,1),(2290,'Sarh','SRH',42,42,1,1),(2291,'Zakouma','AKM',42,42,1,1),(2292,'Alto Palena','WAP',43,43,1,1),(2293,'Ancud','ZUD',43,43,1,1),(2294,'Antofagasta','ANF',43,43,1,1),(2295,'Arica','ARI',43,43,1,1),(2296,'Balmaceda','BBA',43,43,1,1),(2297,'Calama','CJC',43,43,1,1),(2298,'Castro','WCA',43,43,1,1),(2299,'Cerro Sombrero','SMB',43,43,1,1),(2300,'Chaiten','WCH',43,43,1,1),(2301,'Chanaral','CNR',43,43,1,1),(2302,'Chile Chico','CCH',43,43,1,1),(2303,'Chillan','YAI',43,43,1,1),(2304,'Chuquicamata','QUI',43,43,1,1),(2305,'Cochrane','LGR',43,43,1,1),(2306,'Comuna Providenci','QOV',43,43,1,1),(2307,'Concepcion','CCP',43,43,1,1),(2308,'Copiapo','CPO',43,43,1,1),(2309,'Coquimbo','COW',43,43,1,1),(2310,'Coyhaique','GXQ',43,43,1,1),(2311,'Curico','ZCQ',43,43,1,1),(2312,'Easter Island','IPC',43,43,1,1),(2313,'El Salvador','ESR',43,43,1,1),(2314,'Frutillar','FRT',43,43,1,1),(2315,'Futaleufu','FFU',43,43,1,1),(2316,'Iquique','IQQ',43,43,1,1),(2317,'La Serena','LSC',43,43,1,1),(2318,'Linares','ZLR',43,43,1,1),(2319,'Los Andes','LOB',43,43,1,1),(2320,'Los angeles','LSQ',43,43,1,1),(2321,'Osorno','ZOS',43,43,1,1),(2322,'Ovalle','OVL',43,43,1,1),(2323,'Porvenir','WPR',43,43,1,1),(2324,'Pucon','ZPC',43,43,1,1),(2325,'Puerto Aisen','WPA',43,43,1,1),(2326,'Puerto Montt','PMC',43,43,1,1),(2327,'Puerto Natales','PNT',43,43,1,1),(2328,'Puerto Varas','PUX',43,43,1,1),(2329,'Puerto Williams','WPU',43,43,1,1),(2330,'Punta Arenas','PUQ',43,43,1,1),(2331,'Rancagua','QRC',43,43,1,1),(2332,'San Antonio','QTN',43,43,1,1),(2333,'Santiago de Chile','SCL',43,43,1,1),(2334,'Talca','TLX',43,43,1,1),(2335,'Taltal','TTC',43,43,1,1),(2336,'Temuco','ZCO',43,43,1,1),(2337,'Tocopilla','TOQ',43,43,1,1),(2338,'Valdivia','ZAL',43,43,1,1),(2339,'Vallenar','VLR',43,43,1,1),(2340,'Valparaiso','VAP',43,43,1,1),(2341,'Victoria','ZIC',43,43,1,1),(2342,'Vina Del Mar','KNA',43,43,1,1),(2343,'Aershan','YIE',44,44,1,1),(2344,'Aksu','AKU',44,44,1,1),(2345,'Altay','AAT',44,44,1,1),(2346,'An Shun','AVA',44,44,1,1),(2347,'Ankang','AKA',44,44,1,1),(2348,'Anqing','AQG',44,44,1,1),(2349,'Anshan','AOG',44,44,1,1),(2350,'Anyang','AYN',44,44,1,1),(2351,'Baishan','NBS',44,44,1,1),(2352,'Bangda','BPX',44,44,1,1),(2353,'Baoshan','BSD',44,44,1,1),(2354,'Baotou','BAV',44,44,1,1),(2355,'Beihai','BHY',44,44,1,1),(2356,'Bengbu','BFU',44,44,1,1),(2357,'BOLE','BPL',44,44,1,1),(2358,'Buerjin City','KJI',44,44,1,1),(2359,'Changchun','CGQ',44,44,1,1),(2360,'Changde','CGD',44,44,1,1),(2361,'Changhai','CNI',44,44,1,1),(2362,'Changsha','CSX',44,44,1,1),(2363,'Changzhi','CIH',44,44,1,1),(2364,'Changzhou','CZX',44,44,1,1),(2365,'Chaoyang','CHG',44,44,1,1),(2366,'Chengdu','CTU',44,44,1,1),(2367,'Chifeng','CIF',44,44,1,1),(2368,'Chongqing','CKG',44,44,1,1),(2369,'Dali','DLU',44,44,1,1),(2370,'Dalian','DLC',44,44,1,1),(2371,'Dandong','DDG',44,44,1,1),(2372,'Daqing Shi','DQA',44,44,1,1),(2373,'Datong','DAT',44,44,1,1),(2374,'Daxian','DAX',44,44,1,1),(2375,'Dayong','DYG',44,44,1,1),(2376,'Dazu','DZU',44,44,1,1),(2377,'Diqing','DIG',44,44,1,1),(2378,'Dongguan','DGM',44,44,1,1),(2379,'Dongsheng','DSN',44,44,1,1),(2380,'Dongying','DOY',44,44,1,1),(2381,'Dunhuang','DNH',44,44,1,1),(2382,'Enshi','ENH',44,44,1,1),(2383,'Erenhot Shi','ERL',44,44,1,1),(2384,'Foshan','FUO',44,44,1,1),(2385,'Fuyang','FUG',44,44,1,1),(2386,'Fuyun','FYN',44,44,1,1),(2387,'Fuzhou','FOC',44,44,1,1),(2388,'Ganzhou','KOW',44,44,1,1),(2389,'Golmud','GOQ',44,44,1,1),(2390,'Guanghan','GHN',44,44,1,1),(2391,'Guanghua','LHK',44,44,1,1),(2392,'Guangzhou','CAN',44,44,1,1),(2393,'Guilin','KWL',44,44,1,1),(2394,'Guiyang','KWE',44,44,1,1),(2395,'Guyuan Shi','GYU',44,44,1,1),(2396,'Haikou','HAK',44,44,1,1),(2397,'Hailar','HLD',44,44,1,1),(2398,'Hami','HMI',44,44,1,1),(2399,'Handan','HDG',44,44,1,1),(2400,'Hangzhou','HGH',44,44,1,1),(2401,'Hanzhong','HZG',44,44,1,1),(2402,'Harbin','HRB',44,44,1,1),(2403,'Hefei','HFE',44,44,1,1),(2404,'Heihe','HEK',44,44,1,1),(2405,'Hengyang','HNY',44,44,1,1),(2406,'Hohhot','HET',44,44,1,1),(2407,'Hotan','HTN',44,44,1,1),(2408,'Huai Hua','HJJ',44,44,1,1),(2409,'Huai\'an','HIA',44,44,1,1),(2410,'Huangyan','HYN',44,44,1,1),(2411,'Huizhou','HUZ',44,44,1,1),(2412,'Ji An','JGS',44,44,1,1),(2413,'Ji\'an','KNC',44,44,1,1),(2414,'Jiamusi','JMU',44,44,1,1),(2415,'Jiang Men','ZBD',44,44,1,1),(2416,'Jiayuguan','JGN',44,44,1,1),(2417,'Jilin','JIL',44,44,1,1),(2418,'Jinan','TNA',44,44,1,1),(2419,'Jingdezhen','JDZ',44,44,1,1),(2420,'Jinghong','JHG',44,44,1,1),(2421,'Jining','JNG',44,44,1,1),(2422,'Jinjiang','JJN',44,44,1,1),(2423,'Jinzhou','JNZ',44,44,1,1),(2424,'Jiujiang','JIU',44,44,1,1),(2425,'Jiuquan','CHW',44,44,1,1),(2426,'Jixi Shi','JXA',44,44,1,1),(2427,'Juzhou','JUZ',44,44,1,1),(2428,'Kangding','KGT',44,44,1,1),(2429,'Karamay','KRY',44,44,1,1),(2430,'Kashi','KHG',44,44,1,1),(2431,'Korla','KRL',44,44,1,1),(2432,'Kunming','KMG',44,44,1,1),(2433,'Kuqa','KCA',44,44,1,1),(2434,'Lanzhou','ZGC',44,44,1,1),(2436,'Lhasa','LXA',44,44,1,1),(2437,'Liangping','LIA',44,44,1,1),(2438,'Lianyungang','LYG',44,44,1,1),(2439,'Lijiang City','LJG',44,44,1,1),(2440,'Lincang','LNJ',44,44,1,1),(2441,'Linxi','LXI',44,44,1,1),(2442,'Linyi','LYI',44,44,1,1),(2443,'Liuzhou','LZH',44,44,1,1),(2444,'Luoyang','LYA',44,44,1,1),(2445,'Lushan','LUZ',44,44,1,1),(2446,'Luxi','LUM',44,44,1,1),(2447,'Luzhou','LZO',44,44,1,1),(2448,'Manzhouli','NZH',44,44,1,1),(2449,'Meixian','MXZ',44,44,1,1),(2450,'Mian Yang','MIG',44,44,1,1),(2451,'Mohe County','OHE',44,44,1,1),(2452,'Mudanjiang','MDG',44,44,1,1),(2453,'Nanchang','KHN',44,44,1,1),(2454,'Nanchong','NAO',44,44,1,1),(2455,'Nanjing','NKG',44,44,1,1),(2456,'Nanning','NNG',44,44,1,1),(2457,'Nansha','NSZ',44,44,1,1),(2458,'Nantong','NTG',44,44,1,1),(2459,'Nanyang','NNY',44,44,1,1),(2460,'Ningbo','NGB',44,44,1,1),(2461,'Pan Yu','ZAX',44,44,1,1),(2462,'Pan Zhi Hua','PZI',44,44,1,1),(2463,'Pekin','PEK',44,44,1,1),(2466,'Qianjiang Shi','JIQ',44,44,1,1),(2467,'Qiemo','IQM',44,44,1,1),(2468,'Qingdao','TAO',44,44,1,1),(2469,'Qingyang','IQN',44,44,1,1),(2470,'Qinhuangdao','SHP',44,44,1,1),(2471,'Qiqihar','NDG',44,44,1,1),(2472,'Rikaze','RKZ',44,44,1,1),(2473,'Rugao','RUG',44,44,1,1),(2474,'Sanya','SYX',44,44,1,1),(2475,'Shanghai','SHA',44,44,1,1),(2477,'Shanhaiguan','SHF',44,44,1,1),(2478,'Shanshan','SXJ',44,44,1,1),(2479,'Shantou','SWA',44,44,1,1),(2480,'Shanzhou','SZO',44,44,1,1),(2481,'Shaoguan','HSC',44,44,1,1),(2482,'Shashi','SHS',44,44,1,1),(2483,'Shekou','ZYK',44,44,1,1),(2484,'Shenyang','SHE',44,44,1,1),(2485,'Shenzhen','SZX',44,44,1,1),(2486,'Shi Quan He','NGQ',44,44,1,1),(2487,'Shijiazhuang','SJW',44,44,1,1),(2488,'Simao','SYM',44,44,1,1),(2489,'Song Pan','JZH',44,44,1,1),(2490,'Suzhou','SZV',44,44,1,1),(2491,'Tacheng','TCG',44,44,1,1),(2492,'Taiyuan','TYN',44,44,1,1),(2493,'Tangshan','TVS',44,44,1,1),(2494,'Tengchong','TCZ',44,44,1,1),(2495,'Tianjin','TSN',44,44,1,1),(2496,'Tianshui','THQ',44,44,1,1),(2497,'Tonghua','TNH',44,44,1,1),(2498,'Tongliao','TGO',44,44,1,1),(2499,'Tongren','TEN',44,44,1,1),(2500,'Tunxi','TXN',44,44,1,1),(2501,'Turpan','TLQ',44,44,1,1),(2502,'Ulanhot','HLH',44,44,1,1),(2503,'Urumqi','URC',44,44,1,1),(2504,'Wanxian','WXN',44,44,1,1),(2505,'Weifang','WEF',44,44,1,1),(2506,'Weihai','WEH',44,44,1,1),(2507,'Wenshan','WNH',44,44,1,1),(2508,'Wenzhou','WNZ',44,44,1,1),(2509,'Wuhan','WUH',44,44,1,1),(2510,'Wuhu','WHU',44,44,1,1),(2511,'Wuxi','WUX',44,44,1,1),(2512,'Wuyishan','WUS',44,44,1,1),(2513,'Wuzhou','WUZ',44,44,1,1),(2514,'Xi An','SIA',44,44,1,1),(2516,'Xiamen','XMN',44,44,1,1),(2517,'Xiangfan','XFN',44,44,1,1),(2518,'Xichang','XIC',44,44,1,1),(2519,'Xilinhot','XIL',44,44,1,1),(2520,'Xin Hui','ZBZ',44,44,1,1),(2521,'Xingcheng','XEN',44,44,1,1),(2522,'Xingning','XIN',44,44,1,1),(2523,'Xingtai','XNT',44,44,1,1),(2524,'Xining','XNN',44,44,1,1),(2525,'Xuzhou','XUZ',44,44,1,1),(2526,'Yan\'an','ENY',44,44,1,1),(2527,'Yancheng','YNZ',44,44,1,1),(2528,'Yanji','YNJ',44,44,1,1),(2529,'Yantai','YNT',44,44,1,1),(2530,'Yibin','YBP',44,44,1,1),(2531,'Yichang','YIH',44,44,1,1),(2532,'Yichun Shi','LDS',44,44,1,1),(2533,'Yilan','YLN',44,44,1,1),(2534,'Yinchuan','INC',44,44,1,1),(2535,'Yining','YIN',44,44,1,1),(2536,'Yiwu','YIW',44,44,1,1),(2537,'Yuanmou','YUA',44,44,1,1),(2538,'Yulin','UYN',44,44,1,1),(2539,'Yun Cheng','YCU',44,44,1,1),(2540,'Yushu Xian','YUS',44,44,1,1),(2541,'Zhanjiang','ZHA',44,44,1,1),(2542,'Zhaotong','ZAT',44,44,1,1),(2543,'Zhengzhou','CGO',44,44,1,1),(2544,'Zhongshan','ZIS',44,44,1,1),(2546,'Zhoushan','HSN',44,44,1,1),(2547,'Zhuhai','ZUH',44,44,1,1),(2549,'Zunyi','ZYI',44,44,1,1),(2550,'Christmas Island','XCH',45,45,1,1),(2551,'Islas Cocos','CCK',46,46,1,1),(2552,'Acandi','ACD',47,47,1,1),(2553,'Acaricuara','ARF',47,47,1,1),(2554,'Aguaclara','ACL',47,47,1,1),(2555,'Amalfi','AFI',47,47,1,1),(2556,'Andes','ADN',47,47,1,1),(2557,'Apartado','APO',47,47,1,1),(2558,'Apiay','API',47,47,1,1),(2559,'Araracuara','ACR',47,47,1,1),(2560,'Arauca','AUC',47,47,1,1),(2561,'Arauquita','ARQ',47,47,1,1),(2562,'Arboletas','ARO',47,47,1,1),(2563,'Arica','ACM',47,47,1,1),(2564,'Armenia','AXM',47,245,1,1),(2565,'Ayacucho','AYC',47,47,1,1),(2566,'Ayapel','AYA',47,47,1,1),(2567,'Bahia Cupica','BHF',47,47,1,1),(2568,'Bahia Solano','BSC',47,47,1,1),(2569,'Barranca De Upia','BAC',47,47,1,1),(2570,'Barrancabermeja','EJA',47,47,1,1),(2571,'Barrancominas','NBB',47,47,1,1),(2572,'Barranquilla','BAQ',47,47,1,1),(2573,'Bogota','BOG',47,47,1,1),(2574,'Bucaramanga','BGA',47,47,1,1),(2575,'Buenaventura','BUN',47,47,1,1),(2576,'Cali','CLO',47,47,1,1),(2577,'Candilejas','CJD',47,47,1,1),(2578,'Capurgana','CPB',47,47,1,1),(2579,'Caquetania','CQT',47,47,1,1),(2580,'Carimagua','CCO',47,47,1,1),(2581,'Cartagena','CTG',47,47,1,1),(2582,'Cartago','CRC',47,47,1,1),(2583,'Caruru','CUO',47,47,1,1),(2584,'Casuarito','CSR',47,47,1,1),(2585,'Caucasia','CAQ',47,47,1,1),(2586,'Chaparral','CPL',47,47,1,1),(2587,'Chigorodo','IGO',47,47,1,1),(2588,'Chivolo','IVO',47,47,1,1),(2589,'Cimitarra','CIM',47,47,1,1),(2590,'Codazzi','DZI',47,47,1,1),(2591,'Condoto','COG',47,47,1,1),(2592,'Corozal','CZU',47,47,1,1),(2593,'Covenas','CVE',47,47,1,1),(2594,'Cravo Norte','RAV',47,47,1,1),(2595,'Cucuta','CUC',47,47,1,1),(2596,'Currillo','CUI',47,47,1,1),(2597,'El Bagre','EBG',47,47,1,1),(2598,'El Banco','ELB',47,47,1,1),(2599,'El Charco','ECR',47,47,1,1),(2600,'El Encanto','ECO',47,47,1,1),(2601,'El Recreo','ELJ',47,47,1,1),(2602,'El Yopal','EYP',47,47,1,1),(2603,'Florencia','FLA',47,47,1,1),(2604,'Gamarra','GRA',47,47,1,1),(2605,'Gilgal','GGL',47,47,1,1),(2606,'Girardot','GIR',47,47,1,1),(2607,'Guacamaya','GCA',47,47,1,1),(2608,'Guamal','GAA',47,47,1,1),(2609,'Guapi','GPI',47,47,1,1),(2610,'Guerima','GMC',47,47,1,1),(2611,'Hato Corozal','HTZ',47,47,1,1),(2612,'Herrera','HRR',47,47,1,1),(2613,'Ibague','IBE',47,47,1,1),(2614,'Ipiales','IPI',47,47,1,1),(2615,'Iscuande','ISD',47,47,1,1),(2616,'Jurado','JUO',47,47,1,1),(2617,'La Chorrera','LCR',47,47,1,1),(2618,'La Pedrera','LPD',47,47,1,1),(2619,'La Primavera','LPE',47,47,1,1),(2620,'La Uribe','LAT',47,47,1,1),(2621,'Lamacarena','LMC',47,47,1,1),(2622,'Las Gaviotas','LGT',47,47,1,1),(2623,'Leguizamo','LGZ',47,47,1,1),(2624,'Leticia','LET',47,47,1,1),(2625,'Lopez De Micay','LMX',47,47,1,1),(2626,'Lorica','LRI',47,47,1,1),(2627,'Macanal','NAD',47,47,1,1),(2628,'Magangue','MGN',47,47,1,1),(2629,'Maicao','MCJ',47,47,1,1),(2630,'Manizales','MZL',47,245,1,1),(2631,'Mariquita','MQU',47,47,1,1),(2632,'Medellin','EOH',47,47,1,1),(2634,'Medina','MND',47,47,1,1),(2635,'Miraflores','MFS',47,47,1,1),(2636,'Miriti','MIX',47,47,1,1),(2637,'Mitu','MVP',47,47,1,1),(2638,'Mompos','MMP',47,47,1,1),(2639,'Monfort','MFB',47,47,1,1),(2640,'Monte Libano','MTB',47,47,1,1),(2641,'Monteria','MTR',47,47,1,1),(2642,'Monterrey','MOY',47,47,1,1),(2643,'Morichal','MHF',47,47,1,1),(2644,'Mosquera','MQR',47,47,1,1),(2645,'Mulatos','ULS',47,47,1,1),(2646,'Nare','NAR',47,47,1,1),(2647,'Necocli','NCI',47,47,1,1),(2648,'Neiva','NVA',47,47,1,1),(2649,'Nunchia','NUH',47,47,1,1),(2650,'Nuqui','NQU',47,47,1,1),(2651,'Ocana','OCV',47,47,1,1),(2652,'Orocue','ORC',47,47,1,1),(2653,'Otu','OTU',47,47,1,1),(2654,'Palanquero','PAL',47,47,1,1),(2655,'Palmira','QPI',47,47,1,1),(2656,'Paratebueno','EUO',47,47,1,1),(2657,'Pasto','PSO',47,47,1,1),(2658,'Payan','PYN',47,47,1,1),(2659,'Paz De Ariporo','PZA',47,47,1,1),(2660,'Pereira','PEI',47,47,1,1),(2661,'Pitalito','PTX',47,47,1,1),(2662,'Planadas','PLA',47,47,1,1),(2663,'Planeta Rica','PLC',47,47,1,1),(2664,'Plato','PLT',47,47,1,1),(2665,'Popayan','PPN',47,47,1,1),(2666,'Pore','PRE',47,47,1,1),(2667,'Providencia','PVA',47,47,1,1),(2668,'Puerto Asis','PUU',47,47,1,1),(2669,'Puerto Berrio','PBE',47,47,1,1),(2670,'Puerto Boyaca','PYA',47,47,1,1),(2671,'Puerto Carreno','PCR',47,47,1,1),(2672,'Puerto Inirida','PDA',47,47,1,1),(2673,'Puerto Leguizamo','LQM',47,47,1,1),(2674,'Puerto Rico','PCC',47,47,1,1),(2675,'Quibdo','UIB',47,47,1,1),(2676,'Riohacha','RCH',47,47,1,1),(2677,'Rondon','RON',47,47,1,1),(2678,'Sabana De Torres','SNT',47,47,1,1),(2679,'San Andres Isla','ADZ',47,47,1,1),(2680,'San Felipe','SSD',47,47,1,1),(2681,'San Jose Del Gua','SJE',47,47,1,1),(2682,'San Juan D Ur','SJR',47,47,1,1),(2683,'San Juan Del Cesa','SJH',47,47,1,1),(2684,'San Luis De Pale','SQE',47,47,1,1),(2685,'San Marcos','SRS',47,47,1,1),(2686,'San Pedro Jagua','SJG',47,47,1,1),(2687,'San Pedro Uraba','NPU',47,47,1,1),(2688,'San Vicente','SVI',47,47,1,1),(2689,'Santa Ana','SQB',47,47,1,1),(2690,'Santa Catalina','SCA',47,47,1,1),(2691,'Santa Maria','SMC',47,47,1,1),(2692,'Santa Marta','SMR',47,248,1,1),(2693,'Santa Rosalia','SSL',47,47,1,1),(2694,'Santana Ramos','SRO',47,47,1,1),(2695,'Saravena','RVE',47,47,1,1),(2696,'Sogamoso','SOX',47,47,1,1),(2697,'Solano','SQF',47,47,1,1),(2698,'Solita','SOH',47,47,1,1),(2699,'Tablon De Tamara','TTM',47,47,1,1),(2700,'Tame','TME',47,47,1,1),(2701,'Tarapaca','TCD',47,47,1,1),(2702,'Tauramena','TAU',47,47,1,1),(2703,'Tibu','TIB',47,47,1,1),(2704,'Timbiqui','TBD',47,47,1,1),(2705,'Tolu','TLU',47,47,1,1),(2706,'Tres Esquinas','TQS',47,47,1,1),(2707,'Trinidad','TDA',47,47,1,1),(2708,'Tulua','ULQ',47,47,1,1),(2709,'Tumaco','TCO',47,47,1,1),(2710,'Turbo','TRB',47,47,1,1),(2711,'Unguia','UNC',47,47,1,1),(2712,'Uribe','URI',47,47,1,1),(2713,'Urrao','URR',47,47,1,1),(2714,'Valledupar','VUP',47,47,1,1),(2715,'Villagarzon','VGZ',47,47,1,1),(2716,'Villavicencio','VVC',47,47,1,1),(2717,'Yaguara','AYG',47,47,1,1),(2718,'Yari','AYI',47,47,1,1),(2719,'Yavarate','VAB',47,47,1,1),(2720,'Zapatoca','AZT',47,47,1,1),(2721,'Anjouan','AJN',48,48,1,1),(2722,'Moheli','NWA',48,48,1,1),(2723,'Moroni','HAH',48,48,1,1),(2725,'Betou','BTB',49,49,1,1),(2726,'Boundji','BOE',49,49,1,1),(2727,'Brazzaville','BZV',49,49,1,1),(2728,'Djambala','DJM',49,49,1,1),(2729,'Dolisie','DIS',49,49,1,1),(2730,'Epena','EPN',49,49,1,1),(2731,'Ewo','EWO',49,49,1,1),(2732,'Gamboma','GMM',49,49,1,1),(2733,'Impfondo','ION',49,49,1,1),(2734,'Kelle','KEE',49,49,1,1),(2735,'Kindamba','KNJ',49,49,1,1),(2736,'Lage','LCO',49,49,1,1),(2737,'Lekana','LKC',49,49,1,1),(2738,'Makabana','KMK',49,49,1,1),(2739,'Makoua','MKJ',49,49,1,1),(2740,'Mossendjo','MSX',49,49,1,1),(2741,'Mouyondzi','MUY',49,49,1,1),(2742,'N\'Kayi','NKY',49,49,1,1),(2743,'Okoyo','OKG',49,49,1,1),(2744,'Ouesso','OUE',49,49,1,1),(2745,'Owando','FTX',49,49,1,1),(2746,'Pointe-Noire','PNR',49,49,1,1),(2747,'Sibiti','SIB',49,49,1,1),(2748,'Souanke','SOE',49,49,1,1),(2749,'Zanaga','ANJ',49,49,1,1),(2750,'Bandundu','FDU',50,50,1,1),(2751,'Basankusu','BSU',50,50,1,1),(2752,'Basongo','BAN',50,50,1,1),(2753,'Beni','BNC',50,50,1,1),(2754,'Boende','BNB',50,50,1,1),(2755,'Boma','BOA',50,50,1,1),(2756,'Bukavu','BKY',50,50,1,1),(2757,'Bumba','BMB',50,50,1,1),(2758,'Bunia','BUX',50,50,1,1),(2759,'Buta','BZU',50,50,1,1),(2760,'Butembo','RUE',50,50,1,1),(2761,'Gandajika','GDJ',50,50,1,1),(2762,'Gbadolite','BDT',50,50,1,1),(2763,'Gemena','GMA',50,50,1,1),(2764,'Goma','GOM',50,50,1,1),(2765,'Idiofa','IDF',50,50,1,1),(2766,'Ikela','IKL',50,50,1,1),(2767,'Ilebo','PFR',50,50,1,1),(2768,'Inongo','INO',50,50,1,1),(2769,'Isiro','IRP',50,50,1,1),(2770,'Kabalo','KBO',50,50,1,1),(2771,'Kabinda','KBN',50,50,1,1),(2772,'Kalemie','FMI',50,50,1,1),(2773,'Kalima','KLY',50,50,1,1),(2774,'Kamina','KMN',50,50,1,1),(2775,'Kananga','KGA',50,50,1,1),(2776,'Kaniama','KNM',50,50,1,1),(2777,'Kapanga','KAP',50,50,1,1),(2778,'Kasenga','KEC',50,50,1,1),(2779,'Kasongo-Lunda','KGN',50,50,1,1),(2780,'Kikwit','KKW',50,50,1,1),(2781,'Kilwa','KIL',50,50,1,1),(2782,'Kindu','KND',50,50,1,1),(2783,'Kinshasa','FIH',50,50,1,1),(2785,'Kiri','KRZ',50,50,1,1),(2786,'Kisangani','FKI',50,50,1,1),(2787,'Kolo','NKL',50,50,1,1),(2788,'Kolwezi','KWZ',50,50,1,1),(2789,'Kongolo','KOO',50,50,1,1),(2790,'Kotakoli','KLI',50,50,1,1),(2791,'Libenge','LIE',50,50,1,1),(2792,'Lisala','LIQ',50,50,1,1),(2793,'Lodja','LJA',50,50,1,1),(2794,'Lubumbashi','FBM',50,50,1,1),(2795,'Luiza','LZA',50,50,1,1),(2796,'Luozi','LZI',50,50,1,1),(2797,'Lusambo','LBO',50,50,1,1),(2798,'Lusanga','LUS',50,50,1,1),(2799,'Manono','MNO',50,50,1,1),(2800,'Masi-Manimba','MSM',50,50,1,1),(2801,'Matadi','MAT',50,50,1,1),(2802,'Mbandaka','MDK',50,50,1,1),(2803,'Mbuji-Mayi','MJM',50,50,1,1),(2804,'Moba','BDV',50,50,1,1),(2805,'Muanda','MNB',50,50,1,1),(2806,'Mweka','MEW',50,50,1,1),(2807,'Nioki','NIO',50,50,1,1),(2808,'Punia','PUN',50,50,1,1),(2809,'Pweto','PWO',50,50,1,1),(2810,'Tshikapa','TSH',50,50,1,1),(2811,'Yangambi','YAN',50,50,1,1),(2812,'Aitutaki','AIT',51,51,1,1),(2813,'Atiu Island','AIU',51,51,1,1),(2814,'Mangaia Island','MGS',51,51,1,1),(2815,'Manihiki Island','MHX',51,51,1,1),(2816,'Mauke Island','MUK',51,51,1,1),(2817,'Mitiaro Island','MOI',51,51,1,1),(2818,'Penrhyn Island','PYE',51,51,1,1),(2819,'Puka Puka Island','PZK',51,51,1,1),(2820,'Rarotonga','RAR',51,51,1,1),(2821,'Barra Colorado','BCL',52,52,1,1),(2822,'Buenos Aires','BAI',52,52,1,1),(2823,'Canas','CSC',52,52,1,1),(2824,'Carillo','RIK',52,52,1,1),(2825,'Coto 47','OTR',52,52,1,1),(2826,'Drake Bay','DRK',52,52,1,1),(2827,'Flamingo','FMG',52,52,1,1),(2828,'Fortuna','FON',52,52,1,1),(2829,'Golfito','GLF',52,52,1,1),(2830,'Guapiles','GPL',52,52,1,1),(2831,'Las Canas','LCS',52,52,1,1),(2832,'Liberia','LIR',52,52,1,1),(2833,'Limon','LIO',52,52,1,1),(2834,'Los Chiles','LSL',52,52,1,1),(2835,'Nicoya','NCT',52,52,1,1),(2836,'Nosara Beach','NOB',52,52,1,1),(2837,'Palmar','PMZ',52,52,1,1),(2838,'Playa Samara','PLD',52,52,1,1),(2839,'Puerto Jimenez','PJM',52,52,1,1),(2840,'Punta Islita','PBP',52,52,1,1),(2841,'Punta Renes','JAP',52,52,1,1),(2842,'Quepos','XQP',52,52,1,1),(2843,'Rio Frio','RFR',52,52,1,1),(2844,'San Jose','SJO',52,52,1,1),(2846,'San Vito','TOO',52,52,1,1),(2847,'Santa Cruz','SZC',52,52,1,1),(2848,'Tamarindo','TNO',52,52,1,1),(2849,'Tambour','TMU',52,52,1,1),(2850,'Tortuquero','TTQ',52,52,1,1),(2851,'Upala','UPL',52,52,1,1),(2852,'Abengourou','OGO',53,53,1,1),(2853,'Abidjan','ABJ',53,53,1,1),(2854,'Aboisso','ABO',53,53,1,1),(2855,'Bondoukou','BDK',53,53,1,1),(2856,'Bouake','BYK',53,53,1,1),(2857,'Bouna','BQO',53,53,1,1),(2858,'Boundiali','BXI',53,53,1,1),(2859,'Daloa','DJO',53,53,1,1),(2860,'Dimbokro','DIM',53,53,1,1),(2861,'Divo','DIV',53,53,1,1),(2862,'Ferkessedougou','FEK',53,53,1,1),(2863,'Gagnoa','GGN',53,53,1,1),(2864,'Grand Bereby','BBV',53,53,1,1),(2865,'Guiglo','GGO',53,53,1,1),(2866,'Katiola','KTC',53,53,1,1),(2867,'Korhogo','HGO',53,53,1,1),(2868,'Man','MJC',53,53,1,1),(2869,'Odienne','KEO',53,53,1,1),(2870,'Ouango Fitini','OFI',53,53,1,1),(2871,'San Pedro','SPY',53,53,1,1),(2872,'Sassandra','ZSS',53,53,1,1),(2873,'Seguela','SEO',53,53,1,1),(2874,'Tabou','TXU',53,53,1,1),(2875,'Touba','TOZ',53,53,1,1),(2876,'Yamoussoukro','ASK',53,53,1,1),(2877,'Brac','BWK',54,54,1,1),(2878,'Dubrovnik','DBV',54,54,1,1),(2879,'Mali Losinj','LSZ',54,54,1,1),(2880,'Osijek','OSI',54,54,1,1),(2881,'Pula','PUY',54,54,1,1),(2882,'Rijeka','RJK',54,54,1,1),(2883,'Split','SPU',54,54,1,1),(2884,'Zadar','ZAD',54,54,1,1),(2885,'Zagreb','ZAG',54,54,1,1),(2886,'Baracoa','BCA',55,55,1,1),(2887,'Bayamo','BYM',55,55,1,1),(2888,'Camagüey','CMW',55,55,1,1),(2889,'Cayo Coco','CCC',55,55,1,1),(2890,'Cayo Largo Del Sur','CYO',55,55,1,1),(2891,'Ciego De Avila','AVI',55,55,1,1),(2892,'Cienfuegos','CFG',55,55,1,1),(2893,'Colon','QCO',55,55,1,1),(2894,'Guantanamo','GAO',55,55,1,1),(2895,'Holguin','HOG',55,55,1,1),(2896,'La Coloma','LCL',55,55,1,1),(2897,'La Habana','HAV',55,55,1,1),(2898,'Las Tunas','VTU',55,55,1,1),(2899,'Manzanillo','MZO',55,55,1,1),(2900,'Matanzas','QMA',55,55,1,1),(2901,'Mayajigua','MJG',55,55,1,1),(2902,'Moa','MOA',55,55,1,1),(2903,'Nicaro','ICR',55,55,1,1),(2904,'Nueva Gerona','GER',55,55,1,1),(2905,'Pinar Del Rio','QPD',55,55,1,1),(2906,'Preston','PST',55,55,1,1),(2907,'Punta Alegre','UPA',55,55,1,1),(2908,'Punta De Maisi','UMA',55,55,1,1),(2909,'San Julian','SNJ',55,55,1,1),(2910,'San Nicolas Bari','QSN',55,55,1,1),(2911,'Sancti Spiritus','USS',55,55,1,1),(2912,'Santa Clara','SNU',55,55,1,1),(2913,'Santiago','SCU',55,55,1,1),(2914,'Siguanea','SZJ',55,55,1,1),(2915,'Trinidad','TND',55,55,1,1),(2916,'Varadero','VRA',55,55,1,1),(2917,'Ayia Napa','QNP',56,56,1,1),(2918,'Episkopi','EPK',56,56,1,1),(2919,'Ercan','ECN',56,56,1,1),(2920,'Gecitkale','GEC',56,56,1,1),(2921,'Larnaca','LCA',56,56,1,1),(2922,'Limassol','QLI',56,56,1,1),(2923,'Nicosia','NIC',56,56,1,1),(2924,'Paphos','PFO',56,56,1,1),(2925,'Brno','BRQ',57,57,1,1),(2926,'Karlovy Vary','KLV',57,57,1,1),(2927,'Marianske Lazne','MKA',57,57,1,1),(2928,'Olomouc','OLO',57,57,1,1),(2929,'Ostrava','OSR',57,57,1,1),(2930,'Pardubice','PED',57,57,1,1),(2931,'Praga','PRG',57,57,1,1),(2932,'Prerov','PRV',57,57,1,1),(2933,'Uherske Hradiste','UHE',57,57,1,1),(2934,'Zabreh','ZBE',57,57,1,1),(2935,'Zlin','GTW',57,57,1,1),(2936,'Aabenraa','XNR',58,58,1,1),(2937,'Aalborg','AAL',58,58,1,1),(2938,'Aarhus','AAR',58,58,1,1),(2939,'Aarhus Limo','ZBU',58,58,1,1),(2940,'Billund','BLL',58,58,1,1),(2941,'Bornholm','RNN',58,58,1,1),(2942,'Brande','ZMW',58,58,1,1),(2943,'Broenderslev','XAQ',58,58,1,1),(2944,'Copenhague','RKE',58,58,1,1),(2946,'Esbjerg','EBJ',58,58,1,1),(2947,'Fredericia','ZBJ',58,58,1,1),(2948,'Frederikshavn','QFH',58,58,1,1),(2949,'Graasten','XRA',58,58,1,1),(2950,'Guderup','ZFK',58,58,1,1),(2951,'Herning','XAK',58,58,1,1),(2953,'Hirtshals','XAJ',58,58,1,1),(2954,'Hjorring','QHJ',58,58,1,1),(2955,'Holstebro','QWO',58,58,1,1),(2956,'Horsens','ZIL',58,58,1,1),(2957,'Karup','KRP',58,58,1,1),(2958,'Kjellerup','QJW',58,58,1,1),(2959,'Kolding','ZBT',58,58,1,1),(2960,'Laeso Airport','BYR',58,58,1,1),(2961,'Maribo','MRW',58,58,1,1),(2962,'Nyborg','ZIB',58,58,1,1),(2963,'Nykobing Mors','ZAW',58,58,1,1),(2964,'Nykoing Sjaelland','ZIJ',58,58,1,1),(2965,'Odense','ODE',58,58,1,1),(2966,'Randers','ZIR',58,58,1,1),(2967,'Saeby','QJS',58,58,1,1),(2968,'Silkeborg','XAH',58,58,1,1),(2969,'Sindal','CNL',58,58,1,1),(2970,'Skagen','QJV',58,58,1,1),(2971,'Skive','SQW',58,58,1,1),(2972,'Sonderborg','SGD',58,58,1,1),(2973,'Stauning','STA',58,58,1,1),(2974,'Struer','QWQ',58,58,1,1),(2975,'Svendborg','QXV',58,58,1,1),(2976,'Thisted','TED',58,58,1,1),(2977,'Vejle','XVX',58,58,1,1),(2978,'Viborg','ZGX',58,58,1,1),(2979,'Vojens','SKS',58,58,1,1),(2980,'Ali Sabieh','AII',59,59,1,1),(2981,'Djibouti','JIB',59,59,1,1),(2982,'Moucha Island','MHI',59,59,1,1),(2983,'Obock','OBC',59,59,1,1),(2984,'Tadjoura','TDJ',59,59,1,1),(2985,'Dominica','DOM',60,60,1,1),(2987,'Barahona','BRX',61,61,1,1),(2988,'Cabo Rojo','CBJ',61,61,1,1),(2989,'Constanza','COZ',61,61,1,1),(2990,'El Portillo/Samana','EPS',61,61,1,1),(2991,'La Romana','LRM',61,61,1,1),(2992,'Puerto Plata','POP',61,61,1,1),(2993,'Punta Cana','PUJ',61,61,1,1),(2994,'Sabana De Mar','SNX',61,61,1,1),(2995,'Samana','AZS',61,61,1,1),(2996,'San Juan D Ma','SJM',61,61,1,1),(2997,'Santiago','STI',61,61,1,1),(2998,'Santo Domingo','SDQ',61,61,1,1),(2999,'Ambato','ATF',63,63,1,1),(3000,'Bahia De Caraquez','BHA',63,63,1,1),(3001,'Coca','OCC',63,63,1,1),(3002,'Cuenca','CUE',63,63,1,1),(3003,'Esmeraldas','ESM',63,63,1,1),(3004,'Galapagos','GPS',63,63,1,1),(3005,'Guayaquil','GYE',63,63,1,1),(3006,'Isle Baltra','WGL',63,63,1,1),(3007,'Jipijapa','JIP',63,63,1,1),(3008,'Lago Agrio','LGQ',63,63,1,1),(3009,'Loja','LOH',63,63,1,1),(3010,'Macara','MRR',63,63,1,1),(3011,'Macas','XMS',63,63,1,1),(3012,'Machala','MCH',63,63,1,1),(3013,'Manta','MEC',63,63,1,1),(3014,'Mendez','MZD',63,63,1,1),(3015,'Pastaza','PTZ',63,63,1,1),(3016,'Portoviejo','PVO',63,63,1,1),(3017,'Putumayo','PYO',63,63,1,1),(3018,'Quito','UIO',63,63,1,1),(3019,'Salinas','SNC',63,63,1,1),(3020,'San Cristobal','SCY',63,63,1,1),(3021,'Santa Cecilia','WSE',63,63,1,1),(3022,'Santa Rosa','ETR',63,63,1,1),(3023,'Sucua','SUQ',63,63,1,1),(3024,'Taisha','TSC',63,63,1,1),(3025,'Tarapoa','TPC',63,63,1,1),(3026,'Tiputini','TPN',63,63,1,1),(3027,'Tulcan','TUA',63,63,1,1),(3028,'Abu Rudeis','AUE',64,64,1,1),(3029,'Abu Simbel','ABS',64,64,1,1),(3030,'Al Arish','AAC',64,64,1,1),(3031,'Alejandria','ALY',64,64,1,1),(3033,'Assiut','ATZ',64,64,1,1),(3034,'Aswan','ASW',64,64,1,1),(3035,'Dakhla Oasis','DAK',64,64,1,1),(3036,'Damanhour','QUD',64,64,1,1),(3037,'Damietta','QDX',64,64,1,1),(3038,'El Cairo','CAI',64,64,1,1),(3039,'El Mahalla El Kob','QEK',64,64,1,1),(3040,'El Minya','EMY',64,64,1,1),(3041,'Elmanzala','QEM',64,64,1,1),(3042,'Gerga','QGX',64,64,1,1),(3043,'Hurghada','HRG',64,64,1,1),(3044,'Ismailia','QIV',64,64,1,1),(3045,'Kharga','UVL',64,64,1,1),(3046,'Luxor','LXR',64,64,1,1),(3047,'Mansoura','QSU',64,64,1,1),(3048,'Marsa Alam','RMF',64,64,1,1),(3049,'Meet Ghamr','QFM',64,64,1,1),(3050,'Mersa Matruh','MUH',64,64,1,1),(3051,'Port Said','PSD',64,64,1,1),(3052,'Ramadan','TFR',64,64,1,1),(3053,'Ras An Naqb','RAF',64,64,1,1),(3054,'Santa Katarina','SKV',64,64,1,1),(3055,'Sharm El Sheikh','SSH',64,64,1,1),(3056,'Shebeen El Kom','QUH',64,64,1,1),(3057,'Sidi Barani','SQK',64,64,1,1),(3058,'Siwa','SEW',64,64,1,1),(3059,'Sohag','HMB',64,64,1,1),(3060,'Taba','TCP',64,64,1,1),(3061,'Tanta','QTT',64,64,1,1),(3062,'Tour Sinai City','ELT',64,64,1,1),(3063,'Zagazeeg','QZZ',64,64,1,1),(3064,'San Salvador','SAL',65,65,1,1),(3065,'Bata','BSG',66,66,1,1),(3066,'Malabo','SSG',66,66,1,1),(3067,'Asmara','ASM',67,67,1,1),(3068,'Assab','ASA',67,67,1,1),(3069,'Massawa','MSW',67,67,1,1),(3070,'Teseney','TES',67,67,1,1),(3071,'Kardla','KDL',68,68,1,1),(3072,'Kuressaare','URE',68,68,1,1),(3073,'Parnu','EPU',68,68,1,1),(3074,'Tallinn','TLL',68,68,1,1),(3075,'Tartu','TAY',68,68,1,1),(3076,'Addis Ababa','ADD',69,69,1,1),(3077,'Arba Minch','AMH',69,69,1,1),(3078,'Asela','ALK',69,69,1,1),(3079,'Asosa','ASO',69,69,1,1),(3080,'Awareh','AWH',69,69,1,1),(3081,'Awasa','AWA',69,69,1,1),(3082,'Axum','AXU',69,69,1,1),(3083,'Bahar Dar','BJR',69,69,1,1),(3084,'Bedele','XBL',69,69,1,1),(3085,'Beica','BEI',69,69,1,1),(3086,'Bulchi','BCY',69,69,1,1),(3087,'Chagni','MKD',69,69,1,1),(3088,'Debre Marcos','DBM',69,69,1,1),(3089,'Debre Tabor','DBT',69,69,1,1),(3090,'Degeh Bur','DGC',69,69,1,1),(3091,'Dembi Dolo','DEM',69,69,1,1),(3092,'Dessie','DSE',69,69,1,1),(3093,'Dire Dawa','DIR',69,69,1,1),(3094,'Fincha\'A','FNH',69,69,1,1),(3095,'Gambela','GMB',69,69,1,1),(3096,'Geladi','GLC',69,69,1,1),(3097,'Ghimbi','GHD',69,69,1,1),(3098,'Ginir','GNN',69,69,1,1),(3099,'Goba','GOB',69,69,1,1),(3100,'Gode','GDE',69,69,1,1),(3101,'Gonder','GDQ',69,69,1,1),(3102,'Gore','GOR',69,69,1,1),(3103,'Harar','QHR',69,69,1,1),(3104,'Humera','HUE',69,69,1,1),(3105,'Inda Selassie','SHC',69,69,1,1),(3106,'Jijiga','JIJ',69,69,1,1),(3107,'Jimma','JIM',69,69,1,1),(3108,'Jinka','BCO',69,69,1,1),(3109,'Kabri Dehar','ABK',69,69,1,1),(3110,'Kelafo','LFO',69,69,1,1),(3111,'Lalibela','LLI',69,69,1,1),(3112,'Mekane Selam','MKS',69,69,1,1),(3113,'Mekele','MQX',69,69,1,1),(3114,'Mena','MZX',69,69,1,1),(3115,'Mendi','NDM',69,69,1,1),(3116,'Metema','ETE',69,69,1,1),(3117,'Misrak Gashamo','MHJ',69,69,1,1),(3118,'Mizan Teferi','MTF',69,69,1,1),(3119,'Mota','OTA',69,69,1,1),(3120,'Moyale','MYS',69,69,1,1),(3121,'Mui','MUJ',69,69,1,1),(3122,'Negele','EGL',69,69,1,1),(3123,'Nejo','NEJ',69,69,1,1),(3124,'Nekemte','NEK',69,69,1,1),(3125,'Pawe','PWI',69,69,1,1),(3126,'Semera','SZE',69,69,1,1),(3127,'Shakiso','SKR',69,69,1,1),(3128,'Shehdi','SQJ',69,69,1,1),(3129,'Shilabo','HIL',69,69,1,1),(3130,'Sodo','SXU',69,69,1,1),(3131,'Tippi','TIE',69,69,1,1),(3132,'Tum','TUJ',69,69,1,1),(3133,'Waca','WAC',69,69,1,1),(3134,'Warder','WRA',69,69,1,1),(3135,'Mount Pleasant','MPN',70,70,1,1),(3136,'Port Stanley','PSY',70,70,1,1),(3137,'Faroe Islands','FAE',71,71,1,1),(3138,'Ba','BFJ',72,72,1,1),(3139,'Blue Lagoon','BXL',72,72,1,1),(3140,'Bua','BVF',72,72,1,1),(3141,'Bureta','LEV',72,72,1,1),(3142,'Castaway','CST',72,72,1,1),(3143,'Cicia','ICI',72,72,1,1),(3144,'Isla Laucala','LUC',72,72,1,1),(3145,'Kadavu','KDV',72,72,1,1),(3146,'Koro Island','KXF',72,72,1,1),(3147,'Korolevu','KVU',72,72,1,1),(3148,'Labasa','LBS',72,72,1,1),(3149,'Lakeba','LKB',72,72,1,1),(3150,'Malololailai','PTF',72,72,1,1),(3151,'Mana Islands','MNF',72,72,1,1),(3152,'Moala','MFJ',72,72,1,1),(3153,'Nadi','NAN',72,72,1,1),(3154,'Natadola','NTA',72,72,1,1),(3155,'Ngau Island','NGI',72,72,1,1),(3156,'Ono I Lau','ONU',72,72,1,1),(3157,'Pacific Harbor','PHR',72,72,1,1),(3158,'Rabi','RBI',72,72,1,1),(3159,'Rotuma Island','RTA',72,72,1,1),(3160,'Saqani','AQS',72,72,1,1),(3161,'Savusavu','SVU',72,72,1,1),(3162,'Suva','SUV',72,72,1,1),(3163,'Taveuni','TVU',72,72,1,1),(3164,'Turtle Island','TTL',72,72,1,1),(3165,'Vanuabalavu','VBV',72,72,1,1),(3166,'Vatukoula','VAU',72,72,1,1),(3167,'Vatulele','VTF',72,72,1,1),(3168,'Wakaya Island','KAY',72,72,1,1),(3169,'Yasawa','YAS',72,72,1,1),(3170,'Enontekio','ENF',73,73,1,1),(3171,'Forssa','QVE',73,73,1,1),(3172,'Halli','KEV',73,73,1,1),(3173,'Hameenlinna','QVM',73,73,1,1),(3174,'Hamina','QVZ',73,73,1,1),(3175,'Heinola','QVV',73,73,1,1),(3176,'Helsinki','HEM',73,73,1,1),(3178,'Hyvinkaa','HYV',73,73,1,1),(3179,'Ivalo','IVL',73,73,1,1),(3180,'Joensuu','JOE',73,73,1,1),(3181,'Jyvaskyla','JYV',73,73,1,1),(3182,'Kajaani','KAJ',73,73,1,1),(3183,'Karkkila','QVF',73,73,1,1),(3184,'Kauhajoki','KHJ',73,73,1,1),(3185,'Kauhava','KAU',73,73,1,1),(3186,'Kemi Tornio','KEM',73,73,1,1),(3187,'Kitee','KTQ',73,73,1,1),(3188,'Kittila','KTT',73,73,1,1),(3189,'Kokkola Pietarsaari','KOK',73,73,1,1),(3190,'Kotka','QVW',73,73,1,1),(3191,'Kouvola','QVY',73,73,1,1),(3192,'Kuopio','KUO',73,73,1,1),(3193,'Kuusamo','KAO',73,73,1,1),(3194,'Lahti','QLF',73,73,1,1),(3195,'Lappeenranta','LPP',73,73,1,1),(3196,'Loimaa','QZJ',73,73,1,1),(3197,'Loviisa','QXI',73,73,1,1),(3198,'Mantsala','QZK',73,73,1,1),(3199,'Mariehamn','MHQ',73,73,1,1),(3200,'Mikkeli','MIK',73,73,1,1),(3201,'Oulu','OUL',73,73,1,1),(3202,'Pori','POR',73,73,1,1),(3203,'Porvoo','QXJ',73,73,1,1),(3204,'Rauma','QZU',73,73,1,1),(3205,'Riihimaki','QVT',73,73,1,1),(3206,'Rovaniemi','RVN',73,73,1,1),(3207,'Ruka','XHD',73,73,1,1),(3208,'Salo','QVD',73,73,1,1),(3209,'Savonlinna','SVL',73,73,1,1),(3210,'Seinajoki','SJY',73,73,1,1),(3211,'Sodankyla','SOT',73,73,1,1),(3212,'Sotkamo','XQS',73,73,1,1),(3213,'Tampere','TMP',73,73,1,1),(3214,'Tervakoski','QVS',73,73,1,1),(3215,'Turku','TKU',73,73,1,1),(3216,'Utti','UTI',73,73,1,1),(3217,'Vaasa','VAA',73,73,1,1),(3218,'Varkaus','VRK',73,73,1,1),(3219,'Vuokatti','XVM',73,73,1,1),(3220,'Ylivieska','YLI',73,73,1,1),(3221,'Abbeville','XAB',74,74,1,1),(3222,'Agde','XAG',74,74,1,1),(3223,'Agen','AGF',74,74,1,1),(3224,'Aime','QAI',74,74,1,1),(3225,'Aix En Provence','QXB',74,74,1,1),(3226,'Aix Les Bains','XAI',74,74,1,1),(3227,'Ajaccio','AJA',74,74,1,1),(3228,'Albert','BYF',74,74,1,1),(3229,'Albertville','XAV',74,74,1,1),(3230,'Albi','LBI',74,74,1,1),(3231,'Alencon','XAN',74,74,1,1),(3232,'Ales','XAS',74,74,1,1),(3233,'Alpe D\'Huez','AHZ',74,74,1,1),(3234,'Amboise','XAM',74,74,1,1),(3235,'Amiens','QAM',74,74,1,1),(3236,'Angers','ANE',74,74,1,1),(3237,'Angouleme','ANG',74,74,1,1),(3238,'Annecy','NCY',74,74,1,1),(3239,'Annemasse','QNJ',74,74,1,1),(3240,'Antibes','XAT',74,74,1,1),(3241,'Arcachon','XAC',74,74,1,1),(3242,'Arles','ZAF',74,74,1,1),(3243,'Armentieres','XRM',74,74,1,1),(3244,'Arras','QRV',74,74,1,1),(3245,'Aubagne','JAH',74,74,1,1),(3246,'Aubenas','OBS',74,74,1,1),(3247,'Aulnoye Aymeries','XOY',74,74,1,1),(3248,'Auray','XUY',74,74,1,1),(3249,'Aurillac','AUR',74,74,1,1),(3250,'Auxerre','AUF',74,74,1,1),(3251,'Avignon','AVN',74,74,1,1),(3252,'Avoriaz','AVF',74,74,1,1),(3253,'Ax Les Thermes','XLT',74,74,1,1),(3254,'Bandol','XBZ',74,74,1,1),(3255,'Banyuls Sur Mer','XBU',74,74,1,1),(3256,'Bar Le Duc','XBD',74,74,1,1),(3257,'Barcelonnette','BAE',74,74,1,1),(3258,'Bareges','XBA',74,74,1,1),(3259,'Basilea Mulhouse','BSL',74,74,1,1),(3262,'Bastia','BIA',74,74,1,1),(3263,'Bayonne','XBY',74,74,1,1),(3264,'Beaulieu Sur Mer','XBM',74,74,1,1),(3265,'Beaune','XBV',74,74,1,1),(3266,'Belfort','BOR',74,74,1,1),(3267,'Bellegarde','XBF',74,74,1,1),(3268,'Bergerac','EGC',74,74,1,1),(3269,'Bernay','XBX',74,74,1,1),(3270,'Besancon','QBQ',74,74,1,1),(3271,'Bethune','XBH',74,74,1,1),(3272,'Beziers','BZR',74,74,1,1),(3273,'Biarritz','BIQ',74,74,1,1),(3274,'Blois','XBQ',74,74,1,1),(3275,'Boulogne Sur Mer','XBS',74,74,1,1),(3276,'Boulogne-Billancourt','XBT',74,74,1,1),(3277,'Bourg En Bresse','XBK',74,74,1,1),(3278,'Bourg St Maurice','QBM',74,74,1,1),(3279,'Bourges','BOU',74,74,1,1),(3280,'Brest','BES',74,74,1,1),(3281,'Briancon','XBC',74,74,1,1),(3282,'Brive La Gaillarde','BVE',74,74,1,1),(3283,'Burdeos','BOD',74,74,1,1),(3284,'Caen','CFR',74,74,1,1),(3285,'Cagnes Sur Mer','XCG',74,74,1,1),(3286,'Cahors','ZAO',74,74,1,1),(3287,'Calais Dunkerque','CQF',74,74,1,1),(3288,'Calvi','CLY',74,74,1,1),(3289,'Cambrai','XCB',74,74,1,1),(3290,'Cannes','CEQ',74,74,1,1),(3292,'Carcassonne','CCF',74,74,1,1),(3293,'Castres','DCM',74,74,1,1),(3294,'Caudebec En Caux','QUX',74,74,1,1),(3295,'Caussade','XCS',74,74,1,1),(3296,'Cerbere','XCE',74,74,1,1),(3297,'Chalon Sur Saone','XCD',74,74,1,1),(3298,'Chambery Aix les Bains','CMF',74,74,1,1),(3299,'Chamonix Mont Blanc','XCF',74,74,1,1),(3300,'Chamrousse','XCQ',74,74,1,1),(3301,'Chantilly','XCV',74,74,1,1),(3302,'Charleville Mezie','XCZ',74,74,1,1),(3303,'Chartres','QTJ',74,74,1,1),(3304,'Chateau Thierry','XCY',74,74,1,1),(3305,'Chatellerault','XCX',74,74,1,1),(3306,'Chaumont','XCW',74,74,1,1),(3307,'Cherbourg','CER',74,74,1,1),(3308,'Cholet','CET',74,74,1,1),(3309,'Clermont Ferrand','CFE',74,74,1,1),(3310,'Cognac','CNG',74,74,1,1),(3311,'Collioure','XCU',74,74,1,1),(3312,'Colmar','CMR',74,74,1,1),(3313,'Compiegne','XCP',74,74,1,1),(3314,'Courbevoie','QEV',74,74,1,1),(3315,'Courchevel','CVF',74,74,1,1),(3316,'Creil','CSF',74,74,1,1),(3317,'Creteil','QFC',74,74,1,1),(3318,'Dax','XDA',74,74,1,1),(3319,'Deauville','DOL',74,74,1,1),(3320,'Deols','CHR',74,74,1,1),(3321,'Dieppe','DPE',74,74,1,1),(3322,'Digne','XDI',74,74,1,1),(3323,'Dijon','DIJ',74,74,1,1),(3324,'Dinard St Malo','DNR',74,74,1,1),(3325,'Disneyland Paris','XED',74,74,1,1),(3326,'Dives Cabourg','XDC',74,74,1,1),(3327,'Dole','DLE',74,74,1,1),(3328,'Douai','XDN',74,74,1,1),(3329,'Dreux','XDR',74,74,1,1),(3330,'El Havre','LEH',74,74,1,1),(3331,'Epernay','XEP',74,74,1,1),(3332,'Epinal','EPL',74,74,1,1),(3333,'Estrasburgo','SXB',74,74,1,1),(3334,'Evian Les Bains','XEB',74,74,1,1),(3335,'Evreux','EVX',74,74,1,1),(3336,'Evry','JEV',74,74,1,1),(3337,'Figari','FSC',74,74,1,1),(3338,'Foix','XFX',74,74,1,1),(3339,'Font Romeu','QZF',74,74,1,1),(3340,'Fontainebleau','XFB',74,74,1,1),(3341,'Frejus','FRJ',74,74,1,1),(3342,'Gap','GAT',74,74,1,1),(3343,'Granville','GFR',74,74,1,1),(3344,'Gueret','XGT',74,74,1,1),(3345,'Hazebrouck','HZB',74,74,1,1),(3346,'Hendaye','XHY',74,74,1,1),(3347,'Hyeres','XHE',74,74,1,1),(3348,'Ile D\'Yeu','IDY',74,74,1,1),(3349,'Isola France','VHF',74,74,1,1),(3350,'Istres','QIE',74,74,1,1),(3351,'Juan Les Pins','JLP',74,74,1,1),(3352,'La Bastide-Puylaurent','XLA',74,74,1,1),(3353,'La Baule Les Pins','XBP',74,74,1,1),(3354,'La Baule-Escoublac','LBY',74,74,1,1),(3355,'La Ciotat','XCT',74,74,1,1),(3356,'La Llagone','QZG',74,74,1,1),(3357,'La Plagne','PLG',74,74,1,1),(3358,'La Roche Sur Yon','XRO',74,74,1,1),(3359,'La Roche-sur-Yon','EDM',74,74,1,1),(3360,'La Rochelle','LRH',74,74,1,1),(3361,'Landerneau','XLD',74,74,1,1),(3362,'Landvisiau','LDV',74,74,1,1),(3363,'Lannion','LAI',74,74,1,1),(3364,'Laon','XLN',74,74,1,1),(3365,'Latour-de-Carol','XTE',74,74,1,1),(3366,'Laval','LVA',74,74,1,1),(3367,'Le Bourg d\'Oisans','XBI',74,74,1,1),(3368,'Le Castellet','CTT',74,74,1,1),(3369,'Le Creusot Montceau','XCC',74,74,1,1),(3370,'Le Mans','LME',74,74,1,1),(3371,'Le Puy','LPY',74,74,1,1),(3372,'Le Touquet Paris Plage','LTQ',74,74,1,1),(3373,'Lens','XLE',74,74,1,1),(3374,'Les Angles','QZH',74,74,1,1),(3375,'Les Arcs','XRS',74,74,1,1),(3376,'Les Deux Alpes','DXA',74,74,1,1),(3377,'Les Sables','LSO',74,74,1,1),(3378,'Levallois','QBH',74,74,1,1),(3379,'Libourne','XLR',74,74,1,1),(3380,'Lille','LIL',74,74,1,1),(3381,'Limoges','LIG',74,74,1,1),(3382,'Lisieux','XLX',74,74,1,1),(3383,'Lognes','XLG',74,74,1,1),(3384,'Lons Le Saunier','XLL',74,74,1,1),(3385,'Lorient','LRT',74,74,1,1),(3386,'Lourdes Tarbes','LDE',74,74,1,1),(3387,'Luchon','XLH',74,74,1,1),(3388,'Lyon','XYD',74,74,1,1),(3392,'Macon','QNX',74,74,1,1),(3393,'Marmande','XMR',74,74,1,1),(3394,'Marsella','MRS',74,74,1,1),(3395,'Maubeuge','XME',74,74,1,1),(3396,'Megeve','MVV',74,74,1,1),(3397,'Mende','MEN',74,74,1,1),(3398,'Menton','XMT',74,74,1,1),(3399,'Meribel','MFX',74,74,1,1),(3400,'Metz Nancy','ENC',74,74,1,1),(3403,'Modane','XMO',74,74,1,1),(3404,'Mont Dauphin','SCP',74,74,1,1),(3405,'Mont De Marsan','XMJ',74,74,1,1),(3406,'Mont Louis','QZE',74,74,1,1),(3407,'Montauban','XMW',74,74,1,1),(3408,'Montbeliard','XMF',74,74,1,1),(3409,'Montelimar','XMK',74,74,1,1),(3410,'Montlucon','MCU',74,74,1,1),(3411,'Montpellier','MPL',74,74,1,1),(3412,'Morlaix','MXN',74,74,1,1),(3413,'Morzine','XMQ',74,74,1,1),(3414,'Moulins','XMU',74,74,1,1),(3415,'Moutiers','QMU',74,74,1,1),(3416,'Nantes','NTE',74,74,1,1),(3417,'Neuilly Sur Seine','QNL',74,74,1,1),(3418,'Nevers','NVS',74,74,1,1),(3419,'Nimes','FNI',74,74,1,1),(3420,'Niort','NIT',74,74,1,1),(3421,'Niza','NCE',74,74,1,1),(3422,'Offline Point','QAF',74,74,1,1),(3423,'Orange','XOG',74,74,1,1),(3424,'Orleans','ORE',74,74,1,1),(3425,'Paris','PAR',74,74,1,1),(3434,'Pau','PUF',74,74,1,1),(3435,'Perigueux','PGX',74,74,1,1),(3436,'Perpignan','PGF',74,74,1,1),(3437,'Poitiers','PIS',74,74,1,1),(3438,'Pontorson Mt St Michel','XPM',74,74,1,1),(3439,'Port Vendres','XPV',74,74,1,1),(3440,'Propriano','PRP',74,74,1,1),(3441,'Provins','XPS',74,74,1,1),(3442,'Quimper','UIP',74,74,1,1),(3443,'Rambouillet','XRT',74,74,1,1),(3444,'Redon','XRN',74,74,1,1),(3445,'Reims','RHE',74,74,1,1),(3446,'Rennes','RNS',74,74,1,1),(3447,'Riom','XRI',74,74,1,1),(3448,'Roanne','RNE',74,74,1,1),(3449,'Rochefort','RCO',74,74,1,1),(3450,'Rodez','RDZ',74,74,1,1),(3451,'Roissy En France','QZV',74,74,1,1),(3452,'Roubaix','XRX',74,74,1,1),(3453,'Rouen','URO',74,74,1,1),(3454,'Royan','RYN',74,74,1,1),(3455,'Saint Die','XCK',74,74,1,1),(3456,'Saint Tropez','LTT',74,74,1,1),(3457,'Saint Yan','SYT',74,74,1,1),(3458,'Saintes','XST',74,74,1,1),(3459,'Sallanches','XSN',74,74,1,1),(3460,'Sarlat','XSL',74,74,1,1),(3461,'Saumur','XSU',74,74,1,1),(3462,'Seclin','XSX',74,74,1,1),(3463,'Sedan','XSW',74,74,1,1),(3464,'Selestat','XSQ',74,74,1,1),(3465,'Senlis','XSV',74,74,1,1),(3466,'Sens','XSF',74,74,1,1),(3467,'Serre Chevalier','SEC',74,74,1,1),(3468,'Sete','XSY',74,74,1,1),(3469,'Severac Lechateau','ZBH',74,74,1,1),(3470,'Soissons','XSS',74,74,1,1),(3471,'Solenzara','SOZ',74,74,1,1),(3472,'Sophia Antipolis','SXD',74,74,1,1),(3473,'St Brieuc','SBK',74,74,1,1),(3474,'St Claude','XTC',74,74,1,1),(3475,'St Die','XTD',74,74,1,1),(3476,'St Etienne','EBU',74,74,1,1),(3477,'St Gervais/Le Fayet','XGF',74,74,1,1),(3478,'St Gilles Croix D','XGV',74,74,1,1),(3479,'St Jean De Luz','XJZ',74,74,1,1),(3480,'St Louis','XLI',74,74,1,1),(3481,'St Malo','XSB',74,74,1,1),(3482,'St Nazaire','SNR',74,74,1,1),(3483,'St Omer','XSG',74,74,1,1),(3484,'St Quentin','XSJ',74,74,1,1),(3485,'St Quentin En Yve','XQY',74,74,1,1),(3486,'St Raphael','XSK',74,74,1,1),(3487,'Tarbes','XTB',74,74,1,1),(3488,'Thionville','XTH',74,74,1,1),(3489,'Thonon Les Bains','XTS',74,74,1,1),(3490,'Tignes','TGF',74,74,1,1),(3491,'Tolon','TLN',74,74,1,1),(3492,'Toulouse','TLS',74,74,1,1),(3493,'Tourcoing','XTN',74,74,1,1),(3494,'Tours','TUF',74,74,1,1),(3495,'Troyes','QYR',74,74,1,1),(3496,'Tulle','XTU',74,74,1,1),(3497,'Val D\'Isere','VAZ',74,74,1,1),(3498,'Valbonne','QVI',74,74,1,1),(3499,'Valence','VAF',74,74,1,1),(3500,'Valenciennes','XVS',74,74,1,1),(3501,'Valloire','XVR',74,74,1,1),(3502,'Vannes','VNE',74,74,1,1),(3503,'Vendome','XVD',74,74,1,1),(3504,'Verdun','XVN',74,74,1,1),(3505,'Versailles','XVE',74,74,1,1),(3506,'Vesoul','XVO',74,74,1,1),(3507,'Vichy','VHY',74,74,1,1),(3508,'Vienne','XVI',74,74,1,1),(3509,'Vierzon','XVZ',74,74,1,1),(3510,'Vilgenis','QVG',74,74,1,1),(3511,'Villefranche Sur Saone','XVF',74,74,1,1),(3512,'Villepinte','XVP',74,74,1,1),(3513,'Vitre','XVT',74,74,1,1),(3514,'Vittel','VTL',74,74,1,1),(3515,'Cayenne','CAY',75,75,1,1),(3516,'Kourou','QKR',75,75,1,1),(3517,'Maripasoula','MPY',75,75,1,1),(3518,'Regina','REI',75,75,1,1),(3519,'Saul','XAU',75,75,1,1),(3520,'St Georges De Loy','OYP',75,75,1,1),(3521,'St Laurent du Maroni','LDX',75,75,1,1),(3522,'Ahe','AHE',76,76,1,1),(3523,'Anaa','AAA',76,76,1,1),(3524,'Apataki','APK',76,76,1,1),(3525,'Arutua','AXR',76,76,1,1),(3526,'Atuona','AUQ',76,76,1,1),(3527,'Bora Bora','BOB',76,76,1,1),(3528,'Faaite','FAC',76,76,1,1),(3529,'Fakahina','FHZ',76,76,1,1),(3530,'Fakarava','FAV',76,76,1,1),(3531,'Fangatau','FGU',76,76,1,1),(3532,'Hao Island','HOI',76,76,1,1),(3533,'Hikueru','HHZ',76,76,1,1),(3534,'Hiva Oa','HIX',76,76,1,1),(3535,'Huahine','HUH',76,76,1,1),(3536,'Isla Gambier','GMR',76,76,1,1),(3537,'Kaukura Atoll','KKR',76,76,1,1),(3538,'Makemo','MKP',76,76,1,1),(3539,'Manihi','XMH',76,76,1,1),(3540,'Mataiva','MVT',76,76,1,1),(3541,'Maupiti','MAU',76,76,1,1),(3542,'Moorea','MOZ',76,76,1,1),(3543,'Napuka Island','NAU',76,76,1,1),(3544,'Nuku Hiva','NHV',76,76,1,1),(3545,'Nukutavake','NUK',76,76,1,1),(3546,'Papeete','PPT',76,76,1,1),(3547,'Puka Puka','PKP',76,76,1,1),(3548,'Pukarua','PUK',76,76,1,1),(3549,'Raiatea','RFP',76,76,1,1),(3550,'Rangiroa','RGI',76,76,1,1),(3551,'Reao','REA',76,76,1,1),(3552,'Rurutu','RUR',76,76,1,1),(3553,'Takapoto','TKP',76,76,1,1),(3554,'Takaroa','TKX',76,76,1,1),(3555,'Takume','TJN',76,76,1,1),(3556,'Tatakoto','TKV',76,76,1,1),(3557,'Tetiaroa Is','TTI',76,76,1,1),(3558,'Tikehau Atoll','TIH',76,76,1,1),(3559,'Tubuai','TUB',76,76,1,1),(3560,'Tureira','ZTA',76,76,1,1),(3561,'Ua Huka','UAH',76,76,1,1),(3562,'Ua Pou','UAP',76,76,1,1),(3563,'Vahitahi','VHZ',76,76,1,1),(3564,'Akieni','AKE',78,78,1,1),(3565,'Alowe','AWE',78,78,1,1),(3566,'Biawonque','BAW',78,78,1,1),(3567,'Bitam','BMM',78,78,1,1),(3568,'Bongo','BGP',78,78,1,1),(3569,'Booue','BGB',78,78,1,1),(3570,'Fougamou','FOU',78,78,1,1),(3571,'Franceville','MVB',78,78,1,1),(3572,'Gamba','GAX',78,78,1,1),(3573,'Iguela','IGE',78,78,1,1),(3574,'Kongoboumba','GKO',78,78,1,1),(3575,'Koulamoutou','KOU',78,78,1,1),(3576,'Lambarene','LBQ',78,78,1,1),(3577,'Lastoursville','LTL',78,78,1,1),(3578,'Lekoni','LEO',78,78,1,1),(3579,'Libreville','LBV',78,78,1,1),(3580,'Makokou','MKU',78,78,1,1),(3581,'Mandji','KMD',78,78,1,1),(3582,'Manega','MGO',78,78,1,1),(3583,'Mayumba','MYB',78,78,1,1),(3584,'Mbigou','MBC',78,78,1,1),(3585,'Medouneu','MDV',78,78,1,1),(3586,'Mekambo','MKB',78,78,1,1),(3587,'Mevang','MVG',78,78,1,1),(3588,'Miele Mimbale','GIM',78,78,1,1),(3589,'Minvoul','MVX',78,78,1,1),(3590,'Mitzic','MZC',78,78,1,1),(3591,'Moabi','MGX',78,78,1,1),(3592,'Moanda','MFF',78,78,1,1),(3593,'Mouila','MJL',78,78,1,1),(3594,'Ndende','KDN',78,78,1,1),(3595,'Ndjole','KDJ',78,78,1,1),(3596,'Nkan','NKA',78,78,1,1),(3597,'Okondja','OKN',78,78,1,1),(3598,'Omboue','OMB',78,78,1,1),(3599,'Ouanga','OUU',78,78,1,1),(3600,'Owendo','OWE',78,78,1,1),(3601,'Oyem','OYE',78,78,1,1),(3602,'Port Gentil','POG',78,78,1,1),(3603,'Sette Cama','ZKM',78,78,1,1),(3604,'Tchibanga','TCH',78,78,1,1),(3605,'Wagny','WGY',78,78,1,1),(3606,'Wora Na Yeno','WNE',78,78,1,1),(3607,'Banjul','BJL',79,79,1,1),(3608,'Batumi','BUS',80,80,1,1),(3610,'Kutaisi','KUT',80,80,1,1),(3611,'Sukhumi','SUI',80,80,1,1),(3612,'Tbilisi','TBS',80,80,1,1),(3613,'Altenburg','AOC',81,81,1,1),(3614,'Alzey','XZY',81,81,1,1),(3615,'Anklam','QKQ',81,81,1,1),(3616,'Ansbach','QOB',81,81,1,1),(3617,'Aquisgran','AAH',81,81,1,1),(3618,'Arenshausen','ZAS',81,81,1,1),(3619,'Arnsberg','ZCA',81,81,1,1),(3620,'Aschaffenburg','ZCB',81,81,1,1),(3621,'Aue','ZAU',81,81,1,1),(3622,'Baltrum','BMR',81,81,1,1),(3623,'Bamberg','ZCD',81,81,1,1),(3624,'Bayreuth','BYU',81,81,1,1),(3625,'Berchtesgaden','ZCE',81,81,1,1),(3626,'Bergheim','ZCF',81,81,1,1),(3627,'Bergisch Gladbach','ZCG',81,81,1,1),(3628,'Bergkamen','ZCH',81,81,1,1),(3629,'Berlin','TXL',81,81,1,1),(3633,'Bielefeld','BFE',81,81,1,1),(3634,'Bitburg','BBJ',81,81,1,1),(3635,'Bocholt','ZCI',81,81,1,1),(3636,'Bochum','QBO',81,81,1,1),(3637,'Boeblingen','PHM',81,81,1,1),(3638,'Bonn','BNJ',81,81,1,1),(3639,'Borkum','BMK',81,81,1,1),(3640,'Bottrop','ZCJ',81,81,1,1),(3641,'Brema','BRE',81,81,1,1),(3642,'Bremerhaven','BRV',81,81,1,1),(3643,'Bruehl','ZCK',81,81,1,1),(3644,'Brunswick/Wolfsburg','ZQU',81,81,1,1),(3646,'Burg Feuerstein','URD',81,81,1,1),(3647,'Cachoeirinha','QKA',81,81,1,1),(3648,'Castrop Rauxel','ZCM',81,81,1,1),(3649,'Celle','ZCN',81,81,1,1),(3650,'Cham','QHQ',81,81,1,1),(3651,'Chemnitz','ZTZ',81,81,1,1),(3652,'Cochstedt','CSO',81,81,1,1),(3653,'Colonia/Bonn','QKL',81,81,1,1),(3655,'Cottbus','ZTT',81,81,1,1),(3657,'Crailsheim','QEI',81,81,1,1),(3658,'Cuxhaven','FCN',81,81,1,1),(3659,'Dachau','ZCR',81,81,1,1),(3660,'Darmstadt','ZCS',81,81,1,1),(3661,'Delmenhorst','ZCT',81,81,1,1),(3662,'Dessau','ZSU',81,81,1,1),(3663,'Detmold','ZCU',81,81,1,1),(3664,'Dinslaken','ZCV',81,81,1,1),(3665,'Donauwoerth','QWR',81,81,1,1),(3666,'Dormagen','ZCW',81,81,1,1),(3667,'Dorsten','ZCX',81,81,1,1),(3668,'Dortmund','DTM',81,81,1,1),(3669,'Dresden','DRS',81,81,1,1),(3670,'Dueren','ZCY',81,81,1,1),(3671,'Duesseldorf','DUS',81,81,1,1),(3675,'Duisburg','DUI',81,81,1,1),(3676,'Egelsbach','QEF',81,81,1,1),(3677,'Eisenach','EIB',81,81,1,1),(3678,'Emden','EME',81,81,1,1),(3679,'Emmerich','QEX',81,81,1,1),(3680,'Erfurt','ERF',81,81,1,1),(3681,'Erlangen','ZCZ',81,81,1,1),(3682,'Eschweiler','ZEA',81,81,1,1),(3683,'Essen','ESS',81,81,1,1),(3684,'Esslingen','ZEB',81,81,1,1),(3685,'Euskirchen','ZED',81,81,1,1),(3686,'Fictitious Point','ZGZ',81,81,1,1),(3688,'Finkenwerder','XFW',81,81,1,1),(3689,'Flensburg','FLF',81,81,1,1),(3690,'Frankfurt','HHN',81,81,1,1),(3692,'Frankfurt Oder','ZFR',81,81,1,1),(3693,'Freiburg','QFB',81,81,1,1),(3694,'Freilassing','QFL',81,81,1,1),(3695,'Friedrichshafen','FDH',81,81,1,1),(3696,'Fritzlar','FRZ',81,81,1,1),(3697,'Fuerstenfeldbruck','FEL',81,81,1,1),(3698,'Fulda','ZEE',81,81,1,1),(3699,'Garbsen','ZEH',81,81,1,1),(3700,'Garmisch Partenkirchen','ZEI',81,81,1,1),(3701,'Geilenkirchen','GKE',81,81,1,1),(3702,'Gelsenkirchen','ZEJ',81,81,1,1),(3703,'Gera','ZGA',81,81,1,1),(3704,'Giebelstadt','GHF',81,81,1,1),(3705,'Giessen','ZQY',81,81,1,1),(3706,'Gladbeck','ZEK',81,81,1,1),(3707,'Goeppingen','ZES',81,81,1,1),(3708,'Goerlitz','ZGE',81,81,1,1),(3709,'Goettingen','ZEU',81,81,1,1),(3710,'Goslar','ZET',81,81,1,1),(3711,'Gotha','ZGO',81,81,1,1),(3712,'Greifswald','ZGW',81,81,1,1),(3713,'Grevenbroich','ZEV',81,81,1,1),(3714,'Guetersloh','GUT',81,81,1,1),(3715,'Guettin','GTI',81,81,1,1),(3716,'Gummersbach','ZEW',81,81,1,1),(3717,'Hagen','ZEY',81,81,1,1),(3718,'Hamburgo','LBC',81,81,1,1),(3720,'Hameln','ZEZ',81,81,1,1),(3721,'Hamm','ZNB',81,81,1,1),(3722,'Hanau','ZNF',81,81,1,1),(3723,'Hannover','HAJ',81,81,1,1),(3724,'Heide Buesum','HEI',81,81,1,1),(3725,'Heidelberg','HDB',81,81,1,1),(3726,'Heidenheim','ZNI',81,81,1,1),(3727,'Heilbronn','ZNJ',81,81,1,1),(3728,'Helgoland','HGL',81,81,1,1),(3729,'Herford','ZNK',81,81,1,1),(3730,'Heringsdorf','HDF',81,81,1,1),(3731,'Herne','ZNL',81,81,1,1),(3732,'Herten','ZNM',81,81,1,1),(3733,'Hettlingen','ZNH',81,81,1,1),(3734,'Hilden','ZNN',81,81,1,1),(3735,'Hildesheim','ZNO',81,81,1,1),(3736,'Hof','HOQ',81,81,1,1),(3737,'Homburg','QOG',81,81,1,1),(3738,'Huerth','ZNP',81,81,1,1),(3739,'Husum','QHU',81,81,1,1),(3740,'Illesheim','ILH',81,81,1,1),(3741,'Ingolstadt','IGS',81,81,1,1),(3742,'Iserlohn','ZNR',81,81,1,1),(3743,'Jena','ZJS',81,81,1,1),(3744,'Juist','JUI',81,81,1,1),(3745,'Kaiserslautern','KLT',81,81,1,1),(3746,'Karlsruhe/Baden-Baden','FKB',81,81,1,1),(3748,'Kassel','KSF',81,81,1,1),(3749,'Kehl','ZIW',81,81,1,1),(3750,'Kelsterbach','QLH',81,81,1,1),(3751,'Kempten','ZNS',81,81,1,1),(3752,'Kerpen','ZNT',81,81,1,1),(3753,'Kiel','KEL',81,81,1,1),(3754,'Kirchheim','QHD',81,81,1,1),(3755,'Kitzingen','KZG',81,81,1,1),(3756,'Koblenz','ZNV',81,81,1,1),(3757,'Koethen','KOQ',81,81,1,1),(3758,'Konstanz','QKZ',81,81,1,1),(3759,'Krefeld','QKF',81,81,1,1),(3760,'Lagenfeld','ZNX',81,81,1,1),(3761,'Lahr','LHA',81,81,1,1),(3762,'Landshut','QLG',81,81,1,1),(3763,'Langenhagen','ZNY',81,81,1,1),(3764,'Langeoog','LGO',81,81,1,1),(3765,'Leipzig/Halle','LEJ',81,81,1,1),(3767,'Lemwerder','XLW',81,81,1,1),(3768,'Leverkusen','ZOA',81,81,1,1),(3769,'Limburg','ZNW',81,81,1,1),(3770,'Lindau','QII',81,81,1,1),(3771,'Loerrach','QLO',81,81,1,1),(3772,'Ludwigsburg','ZOD',81,81,1,1),(3773,'Ludwigshafen','ZOE',81,81,1,1),(3774,'Ludwigslust','ZLU',81,81,1,1),(3775,'Luedenscheid','ZOC',81,81,1,1),(3776,'Lueneburg','ZOG',81,81,1,1),(3777,'Luenen','ZOH',81,81,1,1),(3778,'Lutherstadt Wittenberg','ZWT',81,81,1,1),(3779,'Magdeburg','ZMG',81,81,1,1),(3780,'Mainz','QMZ',81,81,1,1),(3781,'Mannheim','MHG',81,81,1,1),(3782,'Marburg An Der Lahn','ZOI',81,81,1,1),(3783,'Mariensiel','WVN',81,81,1,1),(3784,'Marl','ZOJ',81,81,1,1),(3785,'Meerbusch','ZOK',81,81,1,1),(3786,'Memmingen','FMM',81,81,1,1),(3788,'Menden','ZOL',81,81,1,1),(3789,'Minden','ZOM',81,81,1,1),(3790,'Mittenwald','QWD',81,81,1,1),(3791,'Moers','ZON',81,81,1,1),(3792,'Muelheim an der Ruhr','ZOO',81,81,1,1),(3793,'Muenster/Osnabrueck','FMO',81,81,1,1),(3794,'Munich','MUC',81,81,1,1),(3796,'Nanhai','ZEF',81,81,1,1),(3797,'Neu Ulm','ZOT',81,81,1,1),(3798,'Neubrandenburg','FNB',81,81,1,1),(3799,'Neumuenster','EUM',81,81,1,1),(3800,'Neunkirchen','ZOP',81,81,1,1),(3801,'Neuss','ZOQ',81,81,1,1),(3802,'Neustadt Weinstrasse','ZOR',81,81,1,1),(3803,'Neuwied','ZOU',81,81,1,1),(3804,'Norden','NOD',81,81,1,1),(3805,'Norderney','NRD',81,81,1,1),(3806,'Norderstedt','ZOV',81,81,1,1),(3807,'Nordhorn','ZOW',81,81,1,1),(3808,'Nuremberg','NUE',81,81,1,1),(3809,'Oberammergau','ZOX',81,81,1,1),(3810,'Oberhausen','ZOY',81,81,1,1),(3811,'Oberpfaffenhofen','OBF',81,81,1,1),(3812,'Offenbach','ZOZ',81,81,1,1),(3813,'Offenburg','ZPA',81,81,1,1),(3814,'Oldenburg','ZPD',81,81,1,1),(3815,'Paderborn/Lippstadt','PAD',81,81,1,1),(3817,'Pasewalk','ZSK',81,81,1,1),(3818,'Passau','ZPF',81,81,1,1),(3819,'Peenemuende','PEF',81,81,1,1),(3820,'Peine','ZPG',81,81,1,1),(3821,'Pforzheim','UPF',81,81,1,1),(3822,'Pirmasens','ZPI',81,81,1,1),(3823,'Potsdam','XXP',81,81,1,1),(3824,'Puttgarden','QUA',81,81,1,1),(3825,'Railway Germany','QYG',81,81,1,1),(3826,'Railways Zone F','QYF',81,81,1,1),(3827,'Railways Zone J','QYJ',81,81,1,1),(3828,'Ramstein','RMS',81,81,1,1),(3829,'Rastatt','ZRW',81,81,1,1),(3830,'Ratingen','ZPJ',81,81,1,1),(3831,'Ravensburg','QRB',81,81,1,1),(3833,'Rechlin','REB',81,81,1,1),(3834,'Recklinghausen','ZPL',81,81,1,1),(3835,'Regensburg','ZPM',81,81,1,1),(3836,'Remscheid','ZPN',81,81,1,1),(3837,'Reutlingen','ZPP',81,81,1,1),(3838,'Rheine','ZPQ',81,81,1,1),(3839,'Riesa','IES',81,81,1,1),(3841,'Rosenheim','ZPR',81,81,1,1),(3842,'Rostock','RLG',81,81,1,1),(3843,'Rothenburg','QTK',81,81,1,1),(3844,'Ruedesheim','QSY',81,81,1,1),(3845,'Ruesselsheim','ZPS',81,81,1,1),(3846,'Saarbrucken','SCN',81,81,1,1),(3848,'Saarlouis','ZPT',81,81,1,1),(3849,'Salzgitter','ZPU',81,81,1,1),(3850,'Salzwedel','ZSQ',81,81,1,1),(3851,'Sassnitz','ZSI',81,81,1,1),(3852,'Schleswig','QWI',81,81,1,1),(3854,'Schoena','ZSC',81,81,1,1),(3855,'Schwaebisch Gmuen','ZPV',81,81,1,1),(3856,'Schwanheide','ZSD',81,81,1,1),(3857,'Schweinfurt','ZPW',81,81,1,1),(3858,'Schwerin','SZW',81,81,1,1),(3859,'Schwerte','ZPX',81,81,1,1),(3860,'Seeheim','QSH',81,81,1,1),(3861,'Siegburg','ZPY',81,81,1,1),(3862,'Siegen','SGE',81,81,1,1),(3863,'Simbach','QIP',81,81,1,1),(3864,'Sindelfingen','ZPZ',81,81,1,1),(3865,'Singen','ZQA',81,81,1,1),(3866,'Solingen','ZIO',81,81,1,1),(3867,'Sonneberg','ZSG',81,81,1,1),(3868,'Spangdahlem','SPM',81,81,1,1),(3869,'Speyer','ZQC',81,81,1,1),(3870,'St Peter-Ording','PSH',81,81,1,1),(3871,'Stade','ZQD',81,81,1,1),(3872,'Stendal','ZSN',81,81,1,1),(3873,'Stolberg','ZQE',81,81,1,1),(3874,'Stralsund','BBH',81,81,1,1),(3875,'Straubing','RBM',81,81,1,1),(3876,'Strausberg','QPK',81,81,1,1),(3877,'Stuttgart','STR',81,81,1,1),(3879,'Suhl','ZSO',81,81,1,1),(3880,'Trier','ZQF',81,81,1,1),(3881,'Troisdorf','ZQG',81,81,1,1),(3882,'Tuebingen','ZQH',81,81,1,1),(3883,'Uetersen','QSM',81,81,1,1),(3884,'Ulm','QUL',81,81,1,1),(3885,'Unna','ZQI',81,81,1,1),(3886,'Varrelbusch','VAC',81,81,1,1),(3887,'Velbert','ZQJ',81,81,1,1),(3888,'Verden','QVQ',81,81,1,1),(3889,'Viersen','ZQK',81,81,1,1),(3890,'Villingen Schwenningen','ZQL',81,81,1,1),(3891,'Voelklingen','ZQM',81,81,1,1),(3892,'Waiblingen','ZQO',81,81,1,1),(3893,'Wangerooge','AGE',81,81,1,1),(3894,'Warnemuende','ZWD',81,81,1,1),(3895,'Weingarten','ZWG',81,81,1,1),(3896,'Wesel','ZQP',81,81,1,1),(3897,'Westerland','GWT',81,81,1,1),(3898,'Wetzlar','ZQQ',81,81,1,1),(3899,'Wiesbaden','UWE',81,81,1,1),(3900,'Wismar','ZWM',81,81,1,1),(3901,'Witten','ZQR',81,81,1,1),(3902,'Wittenberge','ZWN',81,81,1,1),(3903,'Wolfenbuettel','ZQT',81,81,1,1),(3904,'Worms','ZQV',81,81,1,1),(3905,'Wuerzburg','QWU',81,81,1,1),(3906,'Wuppertal','UWP',81,81,1,1),(3907,'Wyk Auf Foehr','OHR',81,81,1,1),(3908,'Zittau','ZIT',81,81,1,1),(3909,'Accra','ACC',82,82,1,1),(3910,'Kumasi','KMS',82,82,1,1),(3911,'Sunyani','NYI',82,82,1,1),(3912,'Takoradi','TKD',82,82,1,1),(3913,'Tamale','TML',82,82,1,1),(3914,'Gibraltar','GIB',83,83,1,1),(3915,'Aghios Nicolaos','ZAN',84,84,1,1),(3916,'Agrinion','AGQ',84,84,1,1),(3917,'Alexandroupolis','AXD',84,84,1,1),(3918,'Astypalea Island','JTY',84,84,1,1),(3919,'Atenas','ATH',84,84,1,1),(3920,'Chalkida','QKG',84,84,1,1),(3921,'Chania','CHQ',84,84,1,1),(3922,'Chios','JKH',84,84,1,1),(3923,'Heraklion','HER',84,84,1,1),(3924,'Ikaria Island','JIK',84,84,1,1),(3925,'Ioannina','IOA',84,84,1,1),(3926,'Kalamata','KLX',84,84,1,1),(3927,'Karlovasi','ZKR',84,84,1,1),(3928,'Karpathos','AOK',84,84,1,1),(3929,'Kasos Island','KSJ',84,84,1,1),(3930,'Kastelorizo','KZS',84,84,1,1),(3931,'Kastoria','KSO',84,84,1,1),(3932,'Kavala','KVA',84,84,1,1),(3933,'Kefallinia','EFL',84,84,1,1),(3934,'Kerkyra','CFU',84,84,1,1),(3935,'Kithira','KIT',84,84,1,1),(3936,'Komotini','ZKT',84,84,1,1),(3937,'Kos','KGS',84,84,1,1),(3938,'Kozani','KZI',84,84,1,1),(3939,'Larisa','LRA',84,84,1,1),(3940,'Lemnos','LXS',84,84,1,1),(3941,'Leros','LRS',84,84,1,1),(3942,'Milos','MLO',84,84,1,1),(3943,'Mykonos','JMK',84,84,1,1),(3944,'Mytilini','MJT',84,84,1,1),(3945,'Naxos','JNX',84,84,1,1),(3946,'Paros','PAS',84,84,1,1),(3947,'Patras','GPA',84,84,1,1),(3948,'Porto Kheli','PKH',84,84,1,1),(3949,'Preveza Lefkas','PVK',84,84,1,1),(3950,'Pyrgos','PYR',84,84,1,1),(3951,'Rethymno','ZRE',84,84,1,1),(3952,'Rhodes','RHO',84,84,1,1),(3953,'Samos','SMI',84,84,1,1),(3954,'Sitia','JSH',84,84,1,1),(3955,'Skiathos','JSI',84,84,1,1),(3956,'Skiros','SKU',84,84,1,1),(3957,'Sparta','SPJ',84,84,1,1),(3958,'Syros Island','JSY',84,84,1,1),(3959,'Thessaloniki','SKG',84,84,1,1),(3960,'Thira','JTR',84,84,1,1),(3961,'Volos','VOL',84,84,1,1),(3962,'Zakinthos Is','ZTH',84,84,1,1),(3963,'Aappilattoq','QUV',85,85,1,1),(3964,'Aasiaat','JEG',85,85,1,1),(3965,'Akunnaaq','QCU',85,85,1,1),(3966,'Alluitsup','QFJ',85,85,1,1),(3967,'Alluitsup Paa','LLU',85,85,1,1),(3968,'Ammassivik','QUW',85,85,1,1),(3969,'Arsuk','JRK',85,85,1,1),(3970,'Atammik','QJF',85,85,1,1),(3971,'Attu','QGQ',85,85,1,1),(3972,'Dundas','DUN',85,85,1,1),(3973,'Eqalugaarsuit','QFG',85,85,1,1),(3974,'Groennedal','JGR',85,85,1,1),(3975,'Igaliku','QFX',85,85,1,1),(3976,'Iginniarfik','QFI',85,85,1,1),(3977,'Ikamiut','QJI',85,85,1,1),(3978,'Ikerasaarsuk','QRY',85,85,1,1),(3979,'Ilimanaq Harbour','XIQ',85,85,1,1),(3980,'Ilulissat','JAV',85,85,1,1),(3981,'Itilleq','QJG',85,85,1,1),(3982,'Kangaamiut','QKT',85,85,1,1),(3983,'Kangaatsiaq','QPW',85,85,1,1),(3984,'Kangerluk','QGR',85,85,1,1),(3985,'Kangerlussuaq','SFJ',85,85,1,1),(3986,'Kitsissuarsuit','QJE',85,85,1,1),(3987,'Kulusuk','KUS',85,85,1,1),(3988,'Maniitsoq','JSU',85,85,1,1),(3989,'Nanortalik','JNN',85,85,1,1),(3990,'Napasoq','QJT',85,85,1,1),(3991,'Narsaq','JNS',85,85,1,1),(3992,'Narsaq Kujalleq','QFN',85,85,1,1),(3993,'Narsarsuaq','UAK',85,85,1,1),(3994,'Neerlerit Inaat','CNP',85,85,1,1),(3995,'Niaqornaarsuk','QMK',85,85,1,1),(3996,'Nuuk','GOH',85,85,1,1),(3997,'Oqatsut Harbour','XEO',85,85,1,1),(3998,'Paamiut','JFR',85,85,1,1),(3999,'Qaanaaq','NAQ',85,85,1,1),(4000,'Qaarsut','JQA',85,85,1,1),(4001,'Qaqortoq','JJU',85,85,1,1),(4002,'Qasigiannguit','JCH',85,85,1,1),(4003,'Qassiarsuk','QFT',85,85,1,1),(4004,'Qeqertarsuaq','JGO',85,85,1,1),(4005,'Qeqertarsuatsiaat','QEY',85,85,1,1),(4006,'Qullissat','QUE',85,85,1,1),(4007,'Saarloq','QOQ',85,85,1,1),(4008,'Saqqaq','QUP',85,85,1,1),(4009,'Scoresbysund','OBY',85,85,1,1),(4010,'Sisimiut','JHS',85,85,1,1),(4011,'Tasiilaq','AGM',85,85,1,1),(4012,'Tasiuasaq Harbour','XEQ',85,85,1,1),(4013,'Thule','THU',85,85,1,1),(4014,'Upernavik','JUV',85,85,1,1),(4015,'Uummannaq','UMD',85,85,1,1),(4016,'Carriacou Island','CRU',86,86,1,1),(4017,'Grenada','GND',86,86,1,1),(4018,'Basse Terre','BBR',87,87,1,1),(4019,'La Desirade','DSD',87,87,1,1),(4020,'Marie Galante','GBJ',87,87,1,1),(4021,'Marigot','XOT',87,87,1,1),(4022,'Pointe A Pitre','PTP',87,87,1,1),(4023,'St Barthelemy','SBH',87,87,1,1),(4024,'St Francois','SFC',87,87,1,1),(4025,'St Martin','SFG',87,87,1,1),(4026,'Terre De Bas','HTB',87,87,1,1),(4027,'Terre De Haut','LSS',87,87,1,1),(4028,'Guam','GUM',88,88,1,1),(4030,'Carmelita','CMM',89,89,1,1),(4031,'Chiquimula','CIQ',89,89,1,1),(4032,'Coatepeque','CTF',89,89,1,1),(4033,'Coban','CBV',89,89,1,1),(4034,'Dos Lagunas','DON',89,89,1,1),(4035,'El Naranjo','ENJ',89,89,1,1),(4036,'Flores','FRS',89,89,1,1),(4037,'Guatemala Ciudad','GUA',89,89,1,1),(4038,'Huehuetenango','HUG',89,89,1,1),(4039,'Los Tablones','LOX',89,89,1,1),(4040,'Melchor De Menco','MCR',89,89,1,1),(4041,'Paso Caballos','PCG',89,89,1,1),(4042,'Playa Grande','PKJ',89,89,1,1),(4043,'Poptun','PON',89,89,1,1),(4044,'Puerto Barrios','PBR',89,89,1,1),(4045,'Puerto San Jose','GSJ',89,89,1,1),(4046,'Quetzaltenango','AAZ',89,89,1,1),(4047,'Quiche','AQB',89,89,1,1),(4048,'Rio Dulce','LCF',89,89,1,1),(4049,'Rubelsanto','RUV',89,89,1,1),(4050,'Tikal','TKM',89,89,1,1),(4051,'Uaxactun','UAX',89,89,1,1),(4052,'Boke','BKJ',90,90,1,1),(4053,'Conakry','CKY',90,90,1,1),(4054,'Faranah','FAA',90,90,1,1),(4055,'Fria','FIG',90,90,1,1),(4056,'Kankan','KNN',90,90,1,1),(4057,'Kissidougou','KSI',90,90,1,1),(4058,'Koundara','SBI',90,90,1,1),(4059,'Labe','LEK',90,90,1,1),(4060,'Macenta','MCA',90,90,1,1),(4061,'Nzerekore','NZE',90,90,1,1),(4062,'Siguiri','GII',90,90,1,1),(4063,'Bissau','OXB',91,91,1,1),(4064,'Bubaque','BQE',91,91,1,1),(4065,'Aishalton','AHL',92,92,1,1),(4066,'Annai','NAI',92,92,1,1),(4067,'Baramita','BMJ',92,92,1,1),(4068,'Bartica','GFO',92,92,1,1),(4069,'Bemichi','BCG',92,92,1,1),(4070,'Ekereku','EKE',92,92,1,1),(4071,'Georgetown','GEO',92,92,1,1),(4072,'Imbaimadai','IMB',92,92,1,1),(4073,'Kaieteur','KAI',92,92,1,1),(4074,'Kamarang','KAR',92,92,1,1),(4075,'Karanambo','KRM',92,92,1,1),(4076,'Karasabai','KRG',92,92,1,1),(4077,'Kato','KTO',92,92,1,1),(4078,'Konawaruk','KKG',92,92,1,1),(4079,'Kurupung','KPG',92,92,1,1),(4080,'Lethem','LTM',92,92,1,1),(4081,'Lumid Pau','LUB',92,92,1,1),(4082,'Mabaruma','USI',92,92,1,1),(4083,'Mahdia','MHA',92,92,1,1),(4084,'Maikwak','VEG',92,92,1,1),(4085,'Matthews Ridge','MWJ',92,92,1,1),(4086,'Monkey Mountain','MYM',92,92,1,1),(4087,'New Amsterdam','QSX',92,92,1,1),(4088,'Ogle','OGL',92,92,1,1),(4089,'Orinduik','ORJ',92,92,1,1),(4090,'Paramakotoi','PMT',92,92,1,1),(4091,'Paruima','PRR',92,92,1,1),(4092,'Pipillipai','PIQ',92,92,1,1),(4093,'Port Kaituma','PKM',92,92,1,1),(4094,'Sandcreek','SDC',92,92,1,1),(4095,'Skeldon','SKM',92,92,1,1),(4096,'Cap Haitien','CAP',93,93,1,1),(4097,'Jacmel','JAK',93,93,1,1),(4098,'Jeremie','JEE',93,93,1,1),(4099,'Les Cayes','CYA',93,93,1,1),(4100,'Port Au Prince','PAP',93,93,1,1),(4101,'Port De Paix','PAX',93,93,1,1),(4102,'Ahuas','AHS',96,96,1,1),(4103,'Brus Laguna','BHG',96,96,1,1),(4104,'Catacamas','CAA',96,96,1,1),(4105,'Cauquira','CDD',96,96,1,1),(4106,'Comayagua','XPL',96,96,1,1),(4107,'Copan','RUY',96,96,1,1),(4108,'Coronel E Soto A','ENQ',96,96,1,1),(4109,'Coyoles','CYL',96,96,1,1),(4110,'Erandique','EDQ',96,96,1,1),(4111,'Gracias','GAC',96,96,1,1),(4112,'Gualaco','GUO',96,96,1,1),(4113,'Guanaja','GJA',96,96,1,1),(4114,'Iriona','IRN',96,96,1,1),(4115,'Juticalpa','JUT',96,96,1,1),(4116,'La Ceiba','LCE',96,96,1,1),(4117,'La Esperanza','LEZ',96,96,1,1),(4118,'La Union','LUI',96,96,1,1),(4119,'Las Limas','LLH',96,96,1,1),(4120,'Limon','LMH',96,96,1,1),(4121,'Marcala','MRJ',96,96,1,1),(4122,'Olanchito','OAN',96,96,1,1),(4123,'Palacios','PCH',96,96,1,1),(4124,'Puerto Lempira','PEU',96,96,1,1),(4125,'Roatan','RTB',96,96,1,1),(4126,'San Esteban','SET',96,96,1,1),(4127,'San Pedro Sula','SAP',96,96,1,1),(4128,'Santa Rosa Copan','SDH',96,96,1,1),(4129,'Sulaco','SCD',96,96,1,1),(4130,'Tegucigalpa','TGU',96,96,1,1),(4131,'Tela','TEA',96,96,1,1),(4132,'Tocoa','TCF',96,96,1,1),(4133,'Trujillo','TJI',96,96,1,1),(4134,'Utila','UII',96,96,1,1),(4135,'Victoria','VTA',96,96,1,1),(4136,'Yoro','ORO',96,96,1,1),(4137,'Cheung Sha Wan','ZZQ',97,97,1,1),(4138,'Hong Kong','HKG',97,97,1,1),(4139,'Kennedy Town','XKT',97,97,1,1),(4140,'Kwun Tong','KTZ',97,97,1,1),(4141,'Shek Mum','QDM',97,97,1,1),(4142,'Tsuen Wan','ZTW',97,97,1,1),(4143,'Balaton','SOB',98,98,1,1),(4144,'Budapest','BUD',98,98,1,1),(4145,'Debrecen','DEB',98,98,1,1),(4146,'Gyor','QGY',98,98,1,1),(4147,'Miskolc','MCQ',98,98,1,1),(4148,'Pecs','PEV',98,98,1,1),(4150,'Szeged','QZD',98,98,1,1),(4151,'Szombathely','ZBX',98,98,1,1),(4152,'Veszprem','ZFP',98,98,1,1),(4153,'Akureyri','AEY',99,99,1,1),(4154,'Bakkafjordur','BJD',99,99,1,1),(4155,'Bildudalur','BIU',99,99,1,1),(4156,'Blonduos','BLO',99,99,1,1),(4157,'Borgarfjordur Eystri','BGJ',99,99,1,1),(4158,'Breiddalsvik','BXV',99,99,1,1),(4159,'Djupivogur','DJU',99,99,1,1),(4160,'Egilsstadir','EGS',99,99,1,1),(4161,'Fagurholsmyri','FAG',99,99,1,1),(4162,'Faskrudsfjordur','FAS',99,99,1,1),(4163,'Flateyri','FLI',99,99,1,1),(4164,'Gjogur','GJR',99,99,1,1),(4165,'Grimsey','GRY',99,99,1,1),(4166,'Grundarfjordur','GUU',99,99,1,1),(4167,'Holmavik','HVK',99,99,1,1),(4168,'Hornafjordur','HFN',99,99,1,1),(4169,'Husavik','HZK',99,99,1,1),(4170,'Isafjordur','IFJ',99,99,1,1),(4171,'Kopasker','OPA',99,99,1,1),(4172,'Myvatn','MVA',99,99,1,1),(4173,'Nordfjordur','NOR',99,99,1,1),(4174,'Olafsfjordur','OFJ',99,99,1,1),(4175,'Olafsvik','OLI',99,99,1,1),(4176,'Patreksfjordur','PFJ',99,99,1,1),(4177,'Raufarhofn','RFN',99,99,1,1),(4178,'Reykholar','RHA',99,99,1,1),(4179,'Reykjavik','REK',99,99,1,1),(4182,'Saudarkrokur','SAK',99,99,1,1),(4183,'Siglufjordur','SIJ',99,99,1,1),(4184,'Stykkisholmur','SYK',99,99,1,1),(4185,'Thingeyri','TEY',99,99,1,1),(4186,'Thorshofn','THO',99,99,1,1),(4187,'Vestmannaeyjar','VEY',99,99,1,1),(4188,'Vopnafjordur','VPN',99,99,1,1),(4189,'Agartala','IXA',100,100,1,1),(4190,'Agatti Island','AGX',100,100,1,1),(4191,'Agra','AGR',100,100,1,1),(4192,'Ahmedabad','AMD',100,100,1,1),(4193,'Aizawl','AJL',100,100,1,1),(4194,'Akola','AKD',100,100,1,1),(4195,'Allahabad','IXD',100,100,1,1),(4196,'Along','IXV',100,100,1,1),(4197,'Amritsar','ATQ',100,100,1,1),(4198,'Anand','QNB',100,100,1,1),(4199,'Aurangabad','IXU',100,100,1,1),(4200,'Bagdogra','IXB',100,100,1,1),(4201,'Balurghat','RGH',100,100,1,1),(4202,'Bareli','BEK',100,100,1,1),(4203,'Belgaum','IXG',100,100,1,1),(4204,'Bellary','BEP',100,100,1,1),(4205,'Bengaluru','BLR',100,100,1,1),(4206,'Bhadohi','XXB',100,100,1,1),(4207,'Bhatinda','BUP',100,100,1,1),(4208,'Bhavnagar','BHU',100,100,1,1),(4209,'Bhopal','BHO',100,100,1,1),(4210,'Bhubaneswar','BBI',100,100,1,1),(4211,'Bhuj','BHJ',100,100,1,1),(4212,'Bikaner','BKB',100,100,1,1),(4213,'Bilaspur','PAB',100,100,1,1),(4214,'Calicut','CCJ',100,100,1,1),(4215,'Car Nicobar','CBD',100,100,1,1),(4216,'Chandigarh','IXC',100,100,1,1),(4217,'Chennai','MAA',100,100,1,1),(4218,'Coimbatore','CJB',100,100,1,1),(4219,'Cooch Behar','COH',100,100,1,1),(4220,'Cuddapah','CDP',100,100,1,1),(4221,'Daman','NMB',100,100,1,1),(4222,'Daparizo','DAE',100,100,1,1),(4223,'Darjeeling','DAI',100,100,1,1),(4224,'Dehra Dun','DED',100,100,1,1),(4225,'Delhi','DEL',100,100,1,1),(4226,'Deparizo','DEP',100,100,1,1),(4227,'Dhanbad','DBD',100,100,1,1),(4228,'Dharamsala','DHM',100,100,1,1),(4229,'Dibrugarh','DIB',100,100,1,1),(4230,'Dimapur','DMU',100,100,1,1),(4231,'Diu','DIU',100,100,1,1),(4232,'Faridabad','QNF',100,100,1,1),(4233,'Gauhati','GAU',100,100,1,1),(4234,'Gaya','GAY',100,100,1,1),(4235,'Goa','GOI',100,100,1,1),(4236,'Gorakhpur','GOP',100,100,1,1),(4237,'Guna','GUX',100,100,1,1),(4238,'Gwalior','GWL',100,100,1,1),(4239,'Hissar','HSS',100,100,1,1),(4240,'Hubli','HBX',100,100,1,1),(4241,'Hyderabad','HYD',100,100,1,1),(4242,'Imphal','IMF',100,100,1,1),(4243,'Indore','IDR',100,100,1,1),(4244,'Jabalpur','JLR',100,100,1,1),(4245,'Jagdalpur','JGB',100,100,1,1),(4246,'Jaipur','JAI',100,100,1,1),(4247,'Jaisalmer','JSA',100,100,1,1),(4248,'Jammu','IXJ',100,100,1,1),(4249,'Jamnagar','JGA',100,100,1,1),(4250,'Jamshedpur','IXW',100,100,1,1),(4251,'Jeypore','PYB',100,100,1,1),(4252,'Jodhpur','JDH',100,100,1,1),(4253,'Jorhat','JRH',100,100,1,1),(4254,'Jullundur','QJU',100,100,1,1),(4255,'Kailashahar','IXH',100,100,1,1),(4256,'Kamalpur','IXQ',100,100,1,1),(4257,'Kandla','IXY',100,100,1,1),(4258,'Kanpur','KNU',100,100,1,1),(4259,'Keshod','IXK',100,100,1,1),(4260,'Khajuraho','HJR',100,100,1,1),(4261,'Khowai','IXN',100,100,1,1),(4262,'Kochi','COK',100,100,1,1),(4263,'Kolhapur','KLH',100,100,1,1),(4264,'Kolkata','CCU',100,100,1,1),(4265,'Kota','KTU',100,100,1,1),(4266,'Kulu','KUU',100,100,1,1),(4267,'Latur','LTU',100,100,1,1),(4268,'Leh','IXL',100,100,1,1),(4269,'Lilabari','IXI',100,100,1,1),(4270,'Lucknow','LKO',100,100,1,1),(4271,'Ludhiana','LUH',100,100,1,1),(4272,'Madurai','IXM',100,100,1,1),(4273,'Malda','LDA',100,100,1,1),(4274,'Mangalore','IXE',100,100,1,1),(4275,'Mohanbari','MOH',100,100,1,1),(4276,'Mumbai (Bombay)','BOM',100,100,1,1),(4277,'Muzaffarnagar','MZA',100,100,1,1),(4278,'Muzaffarpur','MZU',100,100,1,1),(4279,'Mysore','MYQ',100,100,1,1),(4280,'Nagpur','NAG',100,100,1,1),(4281,'Nanded','NDC',100,100,1,1),(4282,'Nasik','ISK',100,100,1,1),(4283,'Nawanshahar','QNW',100,100,1,1),(4284,'Neyveli','NVY',100,100,1,1),(4285,'Osmanabad','OMN',100,100,1,1),(4286,'Pantnagar','PGH',100,100,1,1),(4287,'Pasighat','IXT',100,100,1,1),(4288,'Pathankot','IXP',100,100,1,1),(4289,'Patna','PAT',100,100,1,1),(4290,'Pondicherry','PNY',100,100,1,1),(4291,'Poona','PNQ',100,100,1,1),(4292,'Porbandar','PBD',100,100,1,1),(4293,'Port Blair','IXZ',100,100,1,1),(4294,'Puttaparthi','PUT',100,100,1,1),(4295,'Raipur','RPR',100,100,1,1),(4296,'Rajahmundry','RJA',100,100,1,1),(4297,'Rajkot','RAJ',100,100,1,1),(4298,'Rajouri','RJI',100,100,1,1),(4299,'Ramagundam','RMD',100,100,1,1),(4300,'Ranchi','IXR',100,100,1,1),(4301,'Ratnagiri','RTC',100,100,1,1),(4302,'Rewa','REW',100,100,1,1),(4303,'Rourkela','RRK',100,100,1,1),(4304,'Rupsi','RUP',100,100,1,1),(4305,'Salem','SXV',100,100,1,1),(4306,'Satna','TNI',100,100,1,1),(4307,'Shillong','SHL',100,100,1,1),(4308,'Sholapur','SSE',100,100,1,1),(4309,'Silchar','IXS',100,100,1,1),(4310,'Simla','SLV',100,100,1,1),(4311,'Srinagar','SXR',100,100,1,1),(4312,'Surat','STV',100,100,1,1),(4313,'Tezpur','TEZ',100,100,1,1),(4314,'Tezu','TEI',100,100,1,1),(4315,'Thanjavur','TJV',100,100,1,1),(4316,'Thiruvananthapuram','TRV',100,100,1,1),(4317,'Tiruchirapally','TRZ',100,100,1,1),(4318,'Tirupati','TIR',100,100,1,1),(4319,'Tuticorin','TCR',100,100,1,1),(4320,'Udaipur','UDR',100,100,1,1),(4321,'Vadodara','BDQ',100,100,1,1),(4322,'Varanasi','VNS',100,100,1,1),(4323,'Vidyanagar','VDY',100,100,1,1),(4324,'Vijayawada','VGA',100,100,1,1),(4325,'Vishakhapatnam','VTZ',100,100,1,1),(4326,'Warrangal','WGC',100,100,1,1),(4327,'Zero','ZER',100,100,1,1),(4328,'Aek Godang','AEG',101,101,1,1),(4329,'Alor Island','ARD',101,101,1,1),(4330,'Amahai','AHI',101,101,1,1),(4331,'Ambon','AMQ',101,101,1,1),(4332,'Anggi','AGD',101,101,1,1),(4333,'Apalapsili','AAS',101,101,1,1),(4334,'Arso','ARJ',101,101,1,1),(4335,'Astraksetra','AKQ',101,101,1,1),(4336,'Atambua','ABU',101,101,1,1),(4337,'Atauro','AUT',101,101,1,1),(4338,'Ayawasi','AYW',101,101,1,1),(4339,'Babo','BXB',101,101,1,1),(4340,'Bade','BXD',101,101,1,1),(4341,'Bajawa','BJW',101,101,1,1),(4342,'Balikpapan','BPN',101,101,1,1),(4343,'Banaina','NAF',101,101,1,1),(4344,'Banda Aceh','BTJ',101,101,1,1),(4345,'Bandanaira','NDA',101,101,1,1),(4346,'Bandar Lampung','TKG',101,101,1,1),(4347,'Bandung','BDO',101,101,1,1),(4348,'Banjarmasin','BDJ',101,101,1,1),(4349,'Batom','BXM',101,101,1,1),(4350,'Batu Besar','BTH',101,101,1,1),(4351,'Batu Licin','BTW',101,101,1,1),(4352,'Baubau','BUW',101,101,1,1),(4353,'Baucau','BCH',101,101,1,1),(4354,'Bengkulu','BKS',101,101,1,1),(4355,'Benjina','BJK',101,101,1,1),(4356,'Berau','BEJ',101,101,1,1),(4357,'Biak','BIK',101,101,1,1),(4358,'Bima','BMU',101,101,1,1),(4359,'Bintuni','NTI',101,101,1,1),(4360,'Bokondini','BUI',101,101,1,1),(4361,'Bolaang','BJG',101,101,1,1),(4362,'Bontang','BXT',101,101,1,1),(4363,'Bunyu','BYQ',101,101,1,1),(4364,'Buol','UOL',101,101,1,1),(4365,'Cepu','CPF',101,101,1,1),(4366,'Cilacap','CXP',101,101,1,1),(4367,'Cirebon','CBN',101,101,1,1),(4368,'Dabra','DRH',101,101,1,1),(4369,'Datadawai','DTD',101,101,1,1),(4370,'Denpasar Bali','DPS',101,101,1,1),(4371,'Dobo','DOB',101,101,1,1),(4372,'Dumai','DUM',101,101,1,1),(4373,'Elelim','ELR',101,101,1,1),(4374,'Enarotali','EWI',101,101,1,1),(4375,'Ende','ENE',101,101,1,1),(4376,'Ewer','EWE',101,101,1,1),(4377,'Fak Fak','FKQ',101,101,1,1),(4378,'Gag Island','GAV',101,101,1,1),(4379,'Galela','GLX',101,101,1,1),(4380,'Gebe','GEB',101,101,1,1),(4381,'Gorontalo','GTO',101,101,1,1),(4382,'Gunungsitoli','GNS',101,101,1,1),(4383,'Illaga','ILA',101,101,1,1),(4384,'Ilu','IUL',101,101,1,1),(4385,'Inanwatan','INX',101,101,1,1),(4386,'Jakarta','CGK',101,101,1,1),(4389,'Jambi','DJB',101,101,1,1),(4390,'Jayapura','DJJ',101,101,1,1),(4391,'Kaimana','KNG',101,101,1,1),(4392,'Kambuaya','KBX',101,101,1,1),(4393,'Kamur','KCD',101,101,1,1),(4394,'Karimunjawa','KWB',101,101,1,1),(4395,'Karubaga','KBF',101,101,1,1),(4396,'Kau','KAZ',101,101,1,1),(4397,'Kebar','KEQ',101,101,1,1),(4398,'Keisah','KEA',101,101,1,1),(4399,'Kelila','LLN',101,101,1,1),(4400,'Keluang','KLQ',101,101,1,1),(4401,'Kendari','KDI',101,101,1,1),(4402,'Kepi','KEI',101,101,1,1),(4403,'Kerinci','KRC',101,101,1,1),(4404,'Ketapang','KTG',101,101,1,1),(4405,'Kimam','KMM',101,101,1,1),(4406,'Kokonao','KOX',101,101,1,1),(4407,'Kon','KCI',101,101,1,1),(4408,'Kotabangun','KOD',101,101,1,1),(4409,'Kotabaru','KBU',101,101,1,1),(4410,'Kupang','KOE',101,101,1,1),(4411,'Labuan Bajo','LBJ',101,101,1,1),(4412,'Labuha','LAH',101,101,1,1),(4413,'Langgur','LUV',101,101,1,1),(4414,'Larantuka','LKA',101,101,1,1),(4415,'Lereh','LHI',101,101,1,1),(4416,'Lewoleba','LWE',101,101,1,1),(4417,'Lhok Sukon','LSX',101,101,1,1),(4418,'Lhoksumawe','LSW',101,101,1,1),(4419,'Long Apung','LPU',101,101,1,1),(4420,'Long Bawan','LBW',101,101,1,1),(4421,'Lunyuk','LYK',101,101,1,1),(4422,'Luwuk','LUW',101,101,1,1),(4423,'Malang','MLG',101,101,1,1),(4424,'Maliana','MPT',101,101,1,1),(4425,'Mamuju','MJU',101,101,1,1),(4426,'Manado','MDC',101,101,1,1),(4427,'Mangole','MAL',101,101,1,1),(4428,'Mangunjaya','MJY',101,101,1,1),(4429,'Manokwari','MKW',101,101,1,1),(4430,'Masalembo','MSI',101,101,1,1),(4431,'Masamba','MXB',101,101,1,1),(4432,'Matak','MWK',101,101,1,1),(4433,'Mataram','AMI',101,101,1,1),(4434,'Maumere','MOF',101,101,1,1),(4435,'Medan','MES',101,101,1,1),(4436,'Melangguane','MNA',101,101,1,1),(4437,'Merauke','MKQ',101,101,1,1),(4438,'Merdey','RDE',101,101,1,1),(4439,'Meulaboh','MEQ',101,101,1,1),(4440,'Mindiptana','MDP',101,101,1,1),(4441,'Moanamani','ONI',101,101,1,1),(4442,'Morotai Island','OTI',101,101,1,1),(4443,'Muko Muko','MPC',101,101,1,1),(4444,'Mulia','LII',101,101,1,1),(4445,'Muting','MUF',101,101,1,1),(4446,'Nabire','NBX',101,101,1,1),(4447,'Naha','NAH',101,101,1,1),(4448,'Namlea','NAM',101,101,1,1),(4449,'Namrole','NRE',101,101,1,1),(4450,'Nangapinoh','NPO',101,101,1,1),(4451,'Natuna Ranai','NTX',101,101,1,1),(4452,'Nias','ZQX',101,101,1,1),(4453,'Numfoor','FOO',101,101,1,1),(4454,'Nunukan','NNX',101,101,1,1),(4455,'Obano','OBD',101,101,1,1),(4456,'Ocussi','OEC',101,101,1,1),(4457,'Okaba','OKQ',101,101,1,1),(4458,'Oksibil','OKL',101,101,1,1),(4459,'Padang','PDG',101,101,1,1),(4460,'Palangkaraya','PKY',101,101,1,1),(4461,'Palembang','PLM',101,101,1,1),(4462,'Palibelo','PBW',101,101,1,1),(4463,'Palu','PLW',101,101,1,1),(4464,'Pangkalanbuun','PKN',101,101,1,1),(4465,'Pangkalpinang','PGK',101,101,1,1),(4466,'Pasir Pangarayan','PPR',101,101,1,1),(4467,'Pekanbaru','PKU',101,101,1,1),(4468,'Pendopo','PDO',101,101,1,1),(4469,'Pomala','PUM',101,101,1,1),(4470,'Pondok Cabe','PCB',101,101,1,1),(4471,'Pontianak','PNK',101,101,1,1),(4472,'Poso','PSJ',101,101,1,1),(4473,'Pulau Panjang','PPJ',101,101,1,1),(4474,'Purwokerto','PWL',101,101,1,1),(4475,'Putussibau','PSU',101,101,1,1),(4476,'Raha','RAQ',101,101,1,1),(4477,'Ransiki','RSK',101,101,1,1),(4478,'Rengat','RGT',101,101,1,1),(4479,'Rokot','RKI',101,101,1,1),(4480,'Roti','RTI',101,101,1,1),(4481,'Ruteng','RTG',101,101,1,1),(4482,'Sabang','SBG',101,101,1,1),(4483,'Samarinda','SRI',101,101,1,1),(4484,'Sampit','SMQ',101,101,1,1),(4485,'Sanana','SQN',101,101,1,1),(4486,'Sanggata','SGQ',101,101,1,1),(4487,'Sangir','SAE',101,101,1,1),(4488,'Sarmi','ZRM',101,101,1,1),(4489,'Saumlaki','SXK',101,101,1,1),(4490,'Sawu','SAU',101,101,1,1),(4491,'Semarang','SRG',101,101,1,1),(4492,'Senggeh','SEH',101,101,1,1),(4493,'Senggo','ZEG',101,101,1,1),(4494,'Senipah','SZH',101,101,1,1),(4495,'Serui','ZRI',101,101,1,1),(4496,'Sibisa','SIW',101,101,1,1),(4497,'Sinak','NKD',101,101,1,1),(4498,'Singkep','SIQ',101,101,1,1),(4499,'Sintang','SQG',101,101,1,1),(4500,'Sipora','RKO',101,101,1,1),(4501,'Solo City','SOC',101,101,1,1),(4502,'Soroako','SQR',101,101,1,1),(4503,'Sorong','SOQ',101,101,1,1),(4504,'Steenkool','ZKL',101,101,1,1),(4505,'Suai','UAI',101,101,1,1),(4506,'Sumbawa','SWQ',101,101,1,1),(4507,'Sumenep','SUP',101,101,1,1),(4508,'Sungai Pakning','SEQ',101,101,1,1),(4509,'Surabaya','SUB',101,101,1,1),(4510,'Taliabu','TAX',101,101,1,1),(4511,'Tambolaka','TMC',101,101,1,1),(4512,'Tana Toraja','TTR',101,101,1,1),(4513,'Tanah Grogot','TNB',101,101,1,1),(4514,'Tanahmerah','TMH',101,101,1,1),(4515,'Tandjung Pandan','TJQ',101,101,1,1),(4516,'Tanjung Balai','TJB',101,101,1,1),(4517,'Tanjung Pinang','TNJ',101,101,1,1),(4518,'Tanjung Santan','TSX',101,101,1,1),(4519,'Tanjung Selor','TJS',101,101,1,1),(4520,'Tanjung Warukin','TJG',101,101,1,1),(4521,'Tapaktuan','TPK',101,101,1,1),(4522,'Tarakan','TRK',101,101,1,1),(4523,'Tasikmalaya','TSY',101,101,1,1),(4524,'Tembagapura','TIM',101,101,1,1),(4525,'Teminabuan','TXM',101,101,1,1),(4526,'Ternate','TTE',101,101,1,1),(4527,'Tiom','TMY',101,101,1,1),(4528,'Tolitoli','TLI',101,101,1,1),(4529,'Tumbang Samba','TBM',101,101,1,1),(4530,'Ubrub','UBR',101,101,1,1),(4531,'Ujung Pandang','UPG',101,101,1,1),(4532,'Viqueque','VIQ',101,101,1,1),(4533,'Wagethe','WET',101,101,1,1),(4534,'Wahai Maluku','WBA',101,101,1,1),(4535,'Waingapu','WGP',101,101,1,1),(4536,'Wamena','WMX',101,101,1,1),(4537,'Waris','WAR',101,101,1,1),(4538,'Wasior','WSR',101,101,1,1),(4539,'Yogyakarta','JOG',101,101,1,1),(4540,'Yuruf','RUF',101,101,1,1),(4541,'Zugapa Indonesia','UGU',101,101,1,1),(4542,'Abadan','ABD',102,102,1,1),(4543,'Abu Musa Island','AEU',102,102,1,1),(4544,'Ahwaz','AWZ',102,102,1,1),(4545,'Ardabil','ADU',102,102,1,1),(4546,'Asaloyeh','YEH',102,102,1,1),(4548,'Babolsar','BBL',102,102,1,1),(4549,'Bahregan','IAQ',102,102,1,1),(4550,'Bam','BXR',102,102,1,1),(4551,'Bandar Abbas','BND',102,102,1,1),(4552,'Bandar Khomeini','QBR',102,102,1,1),(4553,'Bandar Lengeh','BDH',102,102,1,1),(4554,'Bandar Mahshahr','MRX',102,102,1,1),(4555,'Birjand','XBJ',102,102,1,1),(4556,'Bishen Kola','BSM',102,102,1,1),(4557,'Bojnurd','BJB',102,102,1,1),(4558,'Bushehr','BUZ',102,102,1,1),(4559,'Chah Bahar','ZBR',102,102,1,1),(4560,'Esfahan','IFN',102,102,1,1),(4561,'Gheshm Island','GSM',102,102,1,1),(4562,'Hamadan','HDM',102,102,1,1),(4563,'Ilam','IIL',102,102,1,1),(4564,'Iranshahr','IHR',102,102,1,1),(4565,'Kangan','KNR',102,102,1,1),(4566,'Karaj','QKC',102,102,1,1),(4568,'Kerman','KER',102,102,1,1),(4569,'Kermanshah','KSH',102,102,1,1),(4570,'Khaneh','KHA',102,102,1,1),(4571,'Khark Island','KHK',102,102,1,1),(4572,'Khorramabad','KHD',102,102,1,1),(4573,'Kish Island','KIH',102,102,1,1),(4574,'LAR','LRR',102,102,1,1),(4575,'Lavan Island','LVP',102,102,1,1),(4576,'Mashhad','MHD',102,102,1,1),(4577,'Masjed Soleiman','QMJ',102,102,1,1),(4578,'Nogeh','NUJ',102,102,1,1),(4579,'Now Shahr','NSH',102,102,1,1),(4580,'Omidiyeh','OMI',102,102,1,1),(4581,'Parsabad','PFQ',102,102,1,1),(4582,'Persepolis','WPS',102,102,1,1),(4583,'Qom','QUM',102,102,1,1),(4585,'Rafsanjan','RJN',102,102,1,1),(4586,'Ramsar','RZR',102,102,1,1),(4587,'Rasht','RAS',102,102,1,1),(4588,'Sanandaj','SDG',102,102,1,1),(4589,'Sarakhs','CKT',102,102,1,1),(4590,'Sari','SRY',102,102,1,1),(4591,'Shahre Kord','CQD',102,102,1,1),(4592,'Shahrkord','QHK',102,102,1,1),(4593,'Shahrud','RUD',102,102,1,1),(4594,'Shiraz','SYZ',102,102,1,1),(4595,'Sirjan','SYJ',102,102,1,1),(4596,'Sirri Island','SXI',102,102,1,1),(4597,'Tabas','TCX',102,102,1,1),(4598,'Tabriz','TBZ',102,102,1,1),(4599,'Tehran','IKA',102,102,1,1),(4601,'Urumiyeh','OMH',102,102,1,1),(4602,'Yasoudj','QYS',102,102,1,1),(4603,'Yazd','AZD',102,102,1,1),(4604,'Zahedan','ZAH',102,102,1,1),(4605,'Al Asad','IQA',103,103,1,1),(4606,'Al Fallujah','TQD',103,103,1,1),(4607,'Baghdad','BGW',103,103,1,1),(4608,'Bamerny','BMN',103,103,1,1),(4609,'Basra','BSR',103,103,1,1),(4610,'Erbil','EBL',103,103,1,1),(4611,'Kirkuk','KIK',103,103,1,1),(4612,'Mosul','OSM',103,103,1,1),(4613,'Najaf','NJF',103,103,1,1),(4614,'Qayyarah','RQW',103,103,1,1),(4615,'Sulaymaniyah','ISU',103,103,1,1),(4616,'Bantry','BYT',104,104,1,1),(4617,'Belmullet','BLY',104,104,1,1),(4618,'Cork','ORK',104,104,1,1),(4619,'Donegal','CFN',104,104,1,1),(4620,'Dublin','DUB',104,104,1,1),(4621,'Galway','GWY',104,104,1,1),(4622,'Inisheer','INQ',104,104,1,1),(4623,'Inishmaan','IIA',104,104,1,1),(4624,'Inishmore','IOR',104,104,1,1),(4625,'Kerry County','KIR',104,104,1,1),(4626,'Kilkenny','KKY',104,104,1,1),(4627,'Knock','NOC',104,104,1,1),(4628,'Letterkenny','LTR',104,104,1,1),(4629,'Limerick','LMK',104,104,1,1),(4630,'Shannon','SNN',104,104,1,1),(4631,'Sligo','SXL',104,104,1,1),(4633,'Spiddal','NNR',104,104,1,1),(4634,'Waterford','WAT',104,104,1,1),(4635,'Beer Sheba','BEV',105,105,1,1),(4636,'Ein Yahav','EIY',105,105,1,1),(4637,'Elat','ETH',105,105,1,1),(4639,'Haifa','HFA',105,105,1,1),(4640,'Jerusalen','JRS',105,105,1,1),(4641,'Kiryat Shmona','KSW',105,105,1,1),(4642,'Masada','MTZ',105,105,1,1),(4643,'Mitspeh Ramon','MIP',105,105,1,1),(4644,'Rosh Pina','RPN',105,105,1,1),(4645,'Sedom','SED',105,105,1,1),(4646,'Tel Aviv Yafo','SDV',105,105,1,1),(4648,'Yotvata','YOT',105,105,1,1),(4649,'Agrigento','QAO',106,106,1,1),(4650,'Albenga','ALL',106,106,1,1),(4651,'Alessandria','QAL',106,106,1,1),(4652,'Alghero','AHO',106,106,1,1),(4653,'Ancona','AOI',106,106,1,1),(4654,'Aosta','AOT',106,106,1,1),(4655,'Aprilia','QZR',106,106,1,1),(4656,'Arbatax','QTX',106,106,1,1),(4657,'Arezzo','QZO',106,106,1,1),(4658,'Ascoli Piceno','QNO',106,106,1,1),(4659,'Avellino','QVN',106,106,1,1),(4660,'Aviano','AVB',106,106,1,1),(4661,'Bari','BRI',106,106,1,1),(4662,'Belluno','BLX',106,106,1,1),(4663,'Benevento','QBV',106,106,1,1),(4664,'Bologna','BLQ',106,106,1,1),(4665,'Bolzano/Bozen','BZO',106,106,1,1),(4666,'Brescia','QBS',106,106,1,1),(4667,'Brindisi','BDS',106,106,1,1),(4668,'Cagliari','CAG',106,106,1,1),(4669,'Caltanissetta','QCL',106,106,1,1),(4670,'Campobasso','QPB',106,106,1,1),(4671,'Capri','PRJ',106,106,1,1),(4672,'Caserta','QTC',106,106,1,1),(4673,'Catania','CTA',106,106,1,1),(4674,'Catanzaro','QCZ',106,106,1,1),(4675,'Chiusa Klausen','ZAK',106,106,1,1),(4676,'Codroipo','QOP',106,106,1,1),(4677,'Comiso','CIY',106,106,1,1),(4678,'Como','QCM',106,106,1,1),(4679,'Cosenza','QCS',106,106,1,1),(4680,'Crotone','CRV',106,106,1,1),(4681,'Cuneo','CUF',106,106,1,1),(4682,'Decimomannu','DCI',106,106,1,1),(4683,'Elba Island','EBA',106,106,1,1),(4684,'Florencia','FLR',106,106,1,1),(4685,'Foggia','FOG',106,106,1,1),(4686,'Forli','FRL',106,106,1,1),(4687,'Frosinone','QFR',106,106,1,1),(4688,'Genova','GOA',106,106,1,1),(4689,'Gorizia','QGO',106,106,1,1),(4690,'Grosseto','GRS',106,106,1,1),(4691,'Ischia','ISH',106,106,1,1),(4692,'L\'Aquila','QAQ',106,106,1,1),(4693,'La Spezia','QLP',106,106,1,1),(4694,'Lamezia Terme','SUF',106,106,1,1),(4695,'Lampedusa','LMP',106,106,1,1),(4696,'Latina','QLT',106,106,1,1),(4697,'Lecce','LCC',106,106,1,1),(4698,'Lipari Island','ZIP',106,106,1,1),(4699,'Lucca','LCV',106,106,1,1),(4700,'Marina Di Massa','QMM',106,106,1,1),(4701,'Marsala','QMR',106,106,1,1),(4702,'Merano Italy','VKB',106,106,1,1),(4703,'Merano Meran','ZMR',106,106,1,1),(4704,'Messina','QME',106,106,1,1),(4705,'Milan','MIL',106,106,1,1),(4709,'Modena','ZMO',106,106,1,1),(4710,'Napoles','NAP',106,106,1,1),(4711,'Nuoro','QNU',106,106,1,1),(4712,'Olbia','OLB',106,106,1,1),(4713,'Oristano','FNU',106,106,1,1),(4714,'Padova','QPA',106,106,1,1),(4715,'Palermo','PMO',106,106,1,1),(4716,'Panarea Island','ZJE',106,106,1,1),(4717,'Pantelleria','PNL',106,106,1,1),(4718,'Parma','PMF',106,106,1,1),(4719,'Perugia','PEG',106,106,1,1),(4720,'Pescara','PSR',106,106,1,1),(4721,'Piacenza','QPZ',106,106,1,1),(4722,'Pisa','PSA',106,106,1,1),(4723,'Pomezia','QEZ',106,106,1,1),(4724,'Ponza','ZJY',106,106,1,1),(4725,'Pordenone','QAD',106,106,1,1),(4726,'Potenza','QPO',106,106,1,1),(4727,'Prato','QPR',106,106,1,1),(4728,'Ragusa','QRG',106,106,1,1),(4729,'Ravenna','RAN',106,106,1,1),(4730,'Reggio di Calabria','REG',106,106,1,1),(4731,'Reggio Nell Emilia','ZRO',106,106,1,1),(4732,'Rieti','QRT',106,106,1,1),(4733,'Rimini','RMI',106,106,1,1),(4734,'Roma','FCO',106,106,1,1),(4737,'Salerno','QSR',106,106,1,1),(4738,'Salina','ZIQ',106,106,1,1),(4739,'San Domino','TQR',106,106,1,1),(4740,'Sassari','QSS',106,106,1,1),(4741,'Segrate','SWK',106,106,1,1),(4742,'Siena','SAY',106,106,1,1),(4743,'Sigonella','NSY',106,106,1,1),(4744,'Siracusa','QIC',106,106,1,1),(4745,'Sora','QXE',106,106,1,1),(4746,'Sorrento','RRO',106,106,1,1),(4747,'Stromboli Island','ZJX',106,106,1,1),(4748,'Sulmona','QLN',106,106,1,1),(4749,'Taormina','TFC',106,106,1,1),(4750,'Taranto','TAR',106,106,1,1),(4751,'Teramo','QEA',106,106,1,1),(4752,'Termini Imere','QTI',106,106,1,1),(4753,'Tortoli','TTB',106,106,1,1),(4754,'Trapani','TPS',106,106,1,1),(4755,'Trento','QTB',106,106,1,1),(4756,'Trento/Trient','ZIA',106,106,1,1),(4757,'Trieste','TRS',106,106,1,1),(4758,'Turin','TRN',106,106,1,1),(4759,'Udine','UDN',106,106,1,1),(4760,'Varese','QVA',106,106,1,1),(4761,'Venecia','TSF',106,106,1,1),(4763,'Verona','VRN',106,106,1,1),(4765,'Vicenza','VIC',106,106,1,1),(4766,'Vulcano Island','ZIE',106,106,1,1),(4767,'Kingston','KTP',107,107,1,1),(4769,'Mandeville','MVJ',107,107,1,1),(4770,'Montego Bay','MBJ',107,107,1,1),(4771,'Negril','NEG',107,107,1,1),(4772,'Ocho Rios','OCJ',107,107,1,1),(4773,'Port Antonio','POT',107,107,1,1),(4774,'Aguni','AGJ',108,108,1,1),(4775,'Akita','AXT',108,108,1,1),(4776,'Amami O Shima','ASJ',108,108,1,1),(4777,'Aomori','AOJ',108,108,1,1),(4778,'Asahikawa','AKJ',108,108,1,1),(4779,'Atsugi','NJA',108,108,1,1),(4780,'Beppu','BPU',108,108,1,1),(4781,'Chiba City','QCB',108,108,1,1),(4782,'Fukue','FUJ',108,108,1,1),(4783,'Fukui','FKJ',108,108,1,1),(4784,'Fukuoka','FUK',108,108,1,1),(4785,'Fukushima','FKS',108,108,1,1),(4786,'Fukuyama','QFY',108,108,1,1),(4787,'Gifu','QGU',108,108,1,1),(4788,'Hachijo Jima','HAC',108,108,1,1),(4789,'Hachinohe','HHE',108,108,1,1),(4790,'Hachioji City','QHY',108,108,1,1),(4791,'Hakodate','HKD',108,108,1,1),(4792,'Hateruma','HTR',108,108,1,1),(4793,'Hiroshima','HIJ',108,108,1,1),(4794,'Ibaraki','IBR',108,108,1,1),(4795,'Iejima','IEJ',108,108,1,1),(4796,'Iki','IKI',108,108,1,1),(4797,'Ishigaki','ISG',108,108,1,1),(4798,'Iwami','IWJ',108,108,1,1),(4799,'Iwo Jima Vol','IWO',108,108,1,1),(4800,'Izumo','IZO',108,108,1,1),(4801,'Kagoshima','KOJ',108,108,1,1),(4802,'Kanazawa','QKW',108,108,1,1),(4803,'Kerama','KJP',108,108,1,1),(4804,'Kikaiga Shima','KKX',108,108,1,1),(4805,'Kita Kyushu','KKJ',108,108,1,1),(4806,'Kitadaito','KTD',108,108,1,1),(4807,'Kochi','KCZ',108,108,1,1),(4808,'Komatsu','KMQ',108,108,1,1),(4809,'Kumamoto','KMJ',108,108,1,1),(4810,'Kumejima','UEO',108,108,1,1),(4811,'Kushimoto','KUJ',108,108,1,1),(4812,'Kushiro','KUH',108,108,1,1),(4813,'Kyoto','UKY',108,108,1,1),(4814,'Maebashi','QEB',108,108,1,1),(4815,'Marcus Island','MUS',108,108,1,1),(4816,'Matsumoto','MMJ',108,108,1,1),(4817,'Matsuyama','MYJ',108,108,1,1),(4818,'Memanbetsu','MMB',108,108,1,1),(4819,'Minami Daito','MMD',108,108,1,1),(4820,'Misawa','MSJ',108,108,1,1),(4821,'Mito','QIS',108,108,1,1),(4822,'Miyake Jima','MYE',108,108,1,1),(4823,'Miyako Jima','MMY',108,108,1,1),(4824,'Miyazaki','KMI',108,108,1,1),(4825,'Monbetsu','MBE',108,108,1,1),(4826,'Moriguchi','QGT',108,108,1,1),(4827,'Morioka','HNA',108,108,1,1),(4828,'Muroran','QRN',108,108,1,1),(4829,'Nagano','QNG',108,108,1,1),(4830,'Nagasaki','NGS',108,108,1,1),(4831,'Nagoya','NGO',108,108,1,1),(4833,'Nakashibetsu','SHB',108,108,1,1),(4834,'Nara City','QNZ',108,108,1,1),(4835,'Niigata','KIJ',108,108,1,1),(4836,'Niihama','IHA',108,108,1,1),(4837,'Nishinoomote','IIN',108,108,1,1),(4838,'Obihiro','OBO',108,108,1,1),(4839,'Odate Noshiro','ONJ',108,108,1,1),(4840,'Oita','OIT',108,108,1,1),(4841,'Okayama','OKJ',108,108,1,1),(4842,'Oki Island','OKI',108,108,1,1),(4843,'Okinawa','OKA',108,108,1,1),(4844,'Okino Erabu','OKE',108,108,1,1),(4845,'Okushiri','OIR',108,108,1,1),(4846,'Omiya','QOM',108,108,1,1),(4847,'Omura','OMJ',108,108,1,1),(4848,'Osaka','UKB',108,108,1,1),(4852,'Oshima','OIM',108,108,1,1),(4853,'Otaru','QOT',108,108,1,1),(4854,'Otsu City','QOO',108,108,1,1),(4855,'Rebun','RBJ',108,108,1,1),(4856,'Rishiri','RIS',108,108,1,1),(4857,'Ryotsu Sado Is','SDO',108,108,1,1),(4858,'Sado Shima','SDS',108,108,1,1),(4859,'Saga','HSG',108,108,1,1),(4860,'Sakai','QKV',108,108,1,1),(4861,'Sapporo','SPK',108,108,1,1),(4864,'Sendai','SDJ',108,108,1,1),(4865,'Shimojishima','SHI',108,108,1,1),(4866,'Shirahama','SHM',108,108,1,1),(4867,'Shizuoka','FSZ',108,108,1,1),(4868,'Shonai','SYO',108,108,1,1),(4869,'Takamatsu','TAK',108,108,1,1),(4870,'Tanegashima','TNE',108,108,1,1),(4871,'Taramajima','TRA',108,108,1,1),(4872,'Tokio','TYO',108,108,1,1),(4876,'Tokunoshima','TKN',108,108,1,1),(4877,'Tokushima','TKS',108,108,1,1),(4878,'Tokyo','QXO',108,108,1,1),(4879,'Tomakomai','QTM',108,108,1,1),(4880,'Tottori','TTJ',108,108,1,1),(4881,'Toyama','TOY',108,108,1,1),(4882,'Toyooka','TJH',108,108,1,1),(4883,'Tsu','QTY',108,108,1,1),(4884,'Tsukuba','XEI',108,108,1,1),(4885,'Tsushima','TSJ',108,108,1,1),(4886,'Ube','UBJ',108,108,1,1),(4887,'Utsunomiya','QUT',108,108,1,1),(4888,'Wajima','NTQ',108,108,1,1),(4889,'Wakayama','QKY',108,108,1,1),(4890,'Wakkanai','WKJ',108,108,1,1),(4891,'Yakushima','KUM',108,108,1,1),(4892,'Yamagata','GAJ',108,108,1,1),(4893,'Yokohama','YOK',108,108,1,1),(4894,'Yonago','YGJ',108,108,1,1),(4895,'Yonaguni Jima','OGN',108,108,1,1),(4896,'Yoronjima','RNJ',108,108,1,1),(4897,'Amman','ADJ',109,109,1,1),(4899,'Aqaba','AQJ',109,109,1,1),(4900,'Irbid','QIR',109,109,1,1),(4901,'Maan','MPQ',109,109,1,1),(4902,'Madaba','QMD',109,109,1,1),(4903,'Mafraq','OMF',109,109,1,1),(4904,'Zarqa','QZA',109,109,1,1),(4905,'Aktau','SCO',110,110,1,1),(4906,'Aktyubinsk','AKX',110,110,1,1),(4907,'Almaty','ALA',110,110,1,1),(4908,'Arkalyk','AYK',110,110,1,1),(4909,'Astana','TSE',110,110,1,1),(4910,'Atbasar','ATX',110,110,1,1),(4911,'Atyrau','GUW',110,110,1,1),(4912,'Balhash','BXH',110,110,1,1),(4913,'Burundai','BXJ',110,110,1,1),(4914,'Chimkent','CIT',110,110,1,1),(4915,'Ekibastuz','EKB',110,110,1,1),(4916,'Karaganda','KGF',110,110,1,1),(4917,'Kokchetau','KOV',110,110,1,1),(4918,'Kostanay','KSN',110,110,1,1),(4919,'Kzyl Orda','KZO',110,110,1,1),(4920,'Pavlodar','PWQ',110,110,1,1),(4921,'Petropavlovsk','PPK',110,110,1,1),(4922,'Semipalatinsk','PLX',110,110,1,1),(4923,'Taldy Kurgan','TDK',110,110,1,1),(4924,'Uralsk','URA',110,110,1,1),(4925,'Ust Kamenogorsk','UKK',110,110,1,1),(4926,'Zaisan','SZI',110,110,1,1),(4927,'Zhairem','HRC',110,110,1,1),(4928,'Zhambyl','DMB',110,110,1,1),(4929,'Zhezkazgan','DZN',110,110,1,1),(4930,'Amboseli','ASV',111,111,1,1),(4931,'Bamburi','BMQ',111,111,1,1),(4932,'Eldoret','EDL',111,111,1,1),(4933,'Eliye Springs','EYS',111,111,1,1),(4934,'Fergusons Gulf','FER',111,111,1,1),(4935,'Garissa','GAS',111,111,1,1),(4936,'Hola','HOA',111,111,1,1),(4937,'Kakamega','GGM',111,111,1,1),(4938,'Kalokol','KLK',111,111,1,1),(4939,'Kericho','KEY',111,111,1,1),(4940,'Kerio Valley','KRV',111,111,1,1),(4941,'Kilaguni','ILU',111,111,1,1),(4942,'Kisumu','KIS',111,111,1,1),(4943,'Kitale','KTL',111,111,1,1),(4944,'Kiunga','KIU',111,111,1,1),(4945,'Kiwayu','KWY',111,111,1,1),(4946,'Lake Baringo','LBN',111,111,1,1),(4947,'Lake Rudolf','LKU',111,111,1,1),(4948,'Lamu','LAU',111,111,1,1),(4949,'Liboi','LBK',111,111,1,1),(4950,'Lodwar','LOK',111,111,1,1),(4951,'Lokichoggio','LKG',111,111,1,1),(4952,'Loyangalani','LOY',111,111,1,1),(4953,'Malindi','MYD',111,111,1,1),(4954,'Mandera','NDE',111,111,1,1),(4955,'Mara Lodges','MRE',111,111,1,1),(4956,'Marsabit','RBT',111,111,1,1),(4957,'Mombasa','MBA',111,111,1,1),(4958,'Moyale','OYL',111,111,1,1),(4959,'Mumias','MUM',111,111,1,1),(4960,'Nairobi','NBO',111,111,1,1),(4962,'Nakuru','NUU',111,111,1,1),(4963,'Nanyuki','NYK',111,111,1,1),(4964,'Nyeri','NYE',111,111,1,1),(4965,'Nzoia','NZO',111,111,1,1),(4966,'Samburu','UAS',111,111,1,1),(4967,'Ukunda','UKA',111,111,1,1),(4968,'Wajir','WJR',111,111,1,1),(4969,'Abaiang','ABF',112,112,1,1),(4970,'Abemama Atoll','AEA',112,112,1,1),(4971,'Aranuka','AAK',112,112,1,1),(4972,'Arorae Island','AIS',112,112,1,1),(4973,'Beru','BEZ',112,112,1,1),(4974,'Butaritari','BBG',112,112,1,1),(4975,'Canton Island','CIS',112,112,1,1),(4976,'Christmas Island','CXI',112,112,1,1),(4977,'Kuria','KUC',112,112,1,1),(4978,'Maiana','MNK',112,112,1,1),(4979,'Makin Island','MTK',112,112,1,1),(4980,'Marakei','MZK',112,112,1,1),(4981,'Nikunau','NIG',112,112,1,1),(4982,'Nonouti','NON',112,112,1,1),(4983,'Onotoa','OOT',112,112,1,1),(4984,'Tabiteuea North','TBF',112,112,1,1),(4985,'Tabiteuea South','TSU',112,112,1,1),(4986,'Tabuaeran','TNV',112,112,1,1),(4987,'Tamana Island','TMN',112,112,1,1),(4988,'Tarawa','TRW',112,112,1,1),(4989,'Teraina','TNQ',112,112,1,1),(4990,'Ansan','XNS',113,113,1,1),(4991,'Cheonan','XOX',113,113,1,1),(4992,'Kuwait','KWI',113,113,1,1),(4993,'Bishkek','FRU',114,114,1,1),(4994,'Osh','OSS',114,114,1,1),(4995,'Attopeu','AOU',115,115,1,1),(4996,'Ban Houei','OUI',115,115,1,1),(4997,'Houeisay','HOE',115,115,1,1),(4998,'Khong','KOG',115,115,1,1),(4999,'Luang Namtha','LXG',115,115,1,1),(5000,'Luang Prabang','LPQ',115,115,1,1),(5001,'Muong Sai','UON',115,115,1,1),(5002,'Oudomxay','ODY',115,115,1,1),(5003,'Paksane','PKS',115,115,1,1),(5004,'Pakse','PKZ',115,115,1,1),(5005,'Sam Neua','NEU',115,115,1,1),(5006,'Saravane','VNA',115,115,1,1),(5007,'Savannakhet','ZVK',115,115,1,1),(5008,'Sayaboury','ZBY',115,115,1,1),(5009,'Seno','SND',115,115,1,1),(5010,'Thakhek','THK',115,115,1,1),(5011,'Udomxay','UDO',115,115,1,1),(5012,'Vangrieng','VGG',115,115,1,1),(5013,'Viengxay','VNG',115,115,1,1),(5014,'Vientiane','VTE',115,115,1,1),(5015,'Xayabury','XAY',115,115,1,1),(5016,'Xieng Khouang','XKH',115,115,1,1),(5017,'Xienglom','XIE',115,115,1,1),(5018,'Daugavpils','DGP',116,116,1,1),(5019,'Liepaja','LPX',116,116,1,1),(5020,'Riga','RIX',116,116,1,1),(5021,'Ventspils','VNT',116,116,1,1),(5022,'Beirut','BEY',117,117,1,1),(5023,'Jall Ed Dib','QJQ',117,117,1,1),(5024,'Jounie','QJN',117,117,1,1),(5025,'Sidon','QSQ',117,117,1,1),(5026,'Tripoli','KYE',117,117,1,1),(5027,'Zahle','QZQ',117,117,1,1),(5028,'Lebakeng','LEF',118,118,1,1),(5029,'Leribe','LRB',118,118,1,1),(5030,'Lesobeng','LES',118,118,1,1),(5031,'Mafeteng','MFC',118,118,1,1),(5032,'Maseru','MSU',118,118,1,1),(5033,'Matsaile','MSG',118,118,1,1),(5034,'Mokhotlong','MKH',118,118,1,1),(5035,'Nkaus','NKU',118,118,1,1),(5036,'Pelaneng','PEL',118,118,1,1),(5037,'Qachas Nek','UNE',118,118,1,1),(5038,'Quthing','UTG',118,118,1,1),(5039,'Sehonghong','SHK',118,118,1,1),(5040,'Sekakes','SKQ',118,118,1,1),(5041,'Semongkong','SOK',118,118,1,1),(5042,'Seshutes','SHZ',118,118,1,1),(5043,'Thaba Tseka','THB',118,118,1,1),(5044,'Tlokoeng','TKO',118,118,1,1),(5045,'Bella Yella','BYL',119,119,1,1),(5046,'Buchanan','UCN',119,119,1,1),(5047,'Cape Palmas','CPA',119,119,1,1),(5048,'Foya','FOY',119,119,1,1),(5049,'Grand Cess','GRC',119,119,1,1),(5050,'Monrovia','ROB',119,119,1,1),(5052,'Nimba','NIA',119,119,1,1),(5053,'Rivercess','RVC',119,119,1,1),(5054,'Sasstown','SAZ',119,119,1,1),(5055,'Sinoe','SNI',119,119,1,1),(5056,'Tapeta','TPT',119,119,1,1),(5057,'Tchien','THC',119,119,1,1),(5058,'Voinjama','VOI',119,119,1,1),(5059,'Weasua','WES',119,119,1,1),(5060,'Wologissi','WOI',119,119,1,1),(5061,'Agedabia','QGG',120,120,1,1),(5062,'Bani Walid','QBL',120,120,1,1),(5063,'Beida','LAQ',120,120,1,1),(5064,'Benghazi','BEN',120,120,1,1),(5065,'Brack','BCQ',120,120,1,1),(5066,'Derna','DNF',120,120,1,1),(5067,'Elgarhbolli','QEJ',120,120,1,1),(5068,'Elmarj City','QEC',120,120,1,1),(5069,'Ghadames','LTD',120,120,1,1),(5070,'Ghat','GHT',120,120,1,1),(5071,'Gherian','QGH',120,120,1,1),(5072,'Houn','HUQ',120,120,1,1),(5073,'Khoms','QKO',120,120,1,1),(5074,'Kufrah','AKF',120,120,1,1),(5075,'Marsa Brega','LMQ',120,120,1,1),(5076,'Misurata','MRA',120,120,1,1),(5077,'Mitiga','MJI',120,120,1,1),(5078,'Murzuq','QMQ',120,120,1,1),(5079,'Nafoora','NFR',120,120,1,1),(5080,'Sebha','SEB',120,120,1,1),(5081,'Sert','SRX',120,120,1,1),(5082,'Tobruk','TOB',120,120,1,1),(5083,'Tripoli','TIP',120,120,1,1),(5084,'Zawia Town','QZT',120,120,1,1),(5085,'Zliten','QZL',120,120,1,1),(5086,'Vaduz','QVU',121,121,1,1),(5087,'Kaunas','KUN',122,122,1,1),(5088,'Klaipeda/Palanga','PLQ',122,122,1,1),(5089,'Panevezys','PNV',122,122,1,1),(5090,'Shauliaj','HLJ',122,122,1,1),(5091,'Siauliai','SQQ',122,122,1,1),(5092,'Vilnius','VNO',122,122,1,1),(5093,'Luxemburgo','LUX',123,123,1,1),(5094,'Macau','MFM',124,124,1,1),(5095,'Taipa','YFT',124,124,1,1),(5096,'Bitola','QBI',125,125,1,1),(5097,'Ohrid','OHD',125,125,1,1),(5098,'Skopje','SKP',125,125,1,1),(5099,'Struga','QXP',125,125,1,1),(5100,'Ambanja','IVA',126,126,1,1),(5101,'Ambatolahy','AHY',126,126,1,1),(5102,'Ambatomainty','AMY',126,126,1,1),(5103,'Ambatondrazaka','WAM',126,126,1,1),(5104,'Ambilobe','AMB',126,126,1,1),(5105,'Ampanihy','AMP',126,126,1,1),(5106,'Analalava','HVA',126,126,1,1),(5107,'Andapa','ZWA',126,126,1,1),(5108,'Andriamena','WAD',126,126,1,1),(5109,'Ankavandra','JVA',126,126,1,1),(5110,'Ankazoabo','WAK',126,126,1,1),(5111,'Ankisatra','TZO',126,126,1,1),(5112,'Antalaha','ANM',126,126,1,1),(5113,'Antananarivo','TNR',126,126,1,1),(5114,'Antsalova','WAQ',126,126,1,1),(5115,'Antsirabe','ATJ',126,126,1,1),(5116,'Antsiranana','DIE',126,126,1,1),(5117,'Antsohihy','WAI',126,126,1,1),(5118,'Bealanana','WBE',126,126,1,1),(5119,'Befandriana','WBD',126,126,1,1),(5120,'Bekily','OVA',126,126,1,1),(5121,'Belo','BMD',126,126,1,1),(5122,'Beroroha','WBO',126,126,1,1),(5123,'Besakoa','BSV',126,126,1,1),(5124,'Besalampy','BPY',126,126,1,1),(5125,'Betioky','BKU',126,126,1,1),(5126,'Doany','DOA',126,126,1,1),(5127,'Farafangana','RVA',126,126,1,1),(5128,'Fianarantsoa','WFI',126,126,1,1),(5129,'Fort Dauphin','FTU',126,126,1,1),(5130,'Ihosy','IHO',126,126,1,1),(5131,'Ilaka','ILK',126,126,1,1),(5132,'Madirovalo','WMV',126,126,1,1),(5133,'Mahanoro','VVB',126,126,1,1),(5134,'Maintirano','MXT',126,126,1,1),(5135,'Majunga','MJN',126,126,1,1),(5136,'Malaimbandy','WML',126,126,1,1),(5137,'Mampikony','WMP',126,126,1,1),(5138,'Manakara','WVK',126,126,1,1),(5139,'Mananara','WMR',126,126,1,1),(5140,'Mananjary','MNJ',126,126,1,1),(5141,'Mandabe','WMD',126,126,1,1),(5142,'Mandritsara','WMA',126,126,1,1),(5143,'Manja','MJA',126,126,1,1),(5144,'Maroantsetra','WMN',126,126,1,1),(5145,'Miandrivazo','ZVA',126,126,1,1),(5146,'Morafenobe','TVA',126,126,1,1),(5147,'Moramanga','OHB',126,126,1,1),(5148,'Morombe','MXM',126,126,1,1),(5149,'Morondava','MOQ',126,126,1,1),(5150,'Nossi Be','NOS',126,126,1,1),(5151,'Port Berge','WPB',126,126,1,1),(5152,'Sainte Marie','SMS',126,126,1,1),(5153,'Sambava','SVB',126,126,1,1),(5154,'Soalala','DWB',126,126,1,1),(5155,'Tamatave','TMM',126,126,1,1),(5156,'Tambohorano','WTA',126,126,1,1),(5157,'Tanandava','TDV',126,126,1,1),(5158,'Tsaratanana','TTS',126,126,1,1),(5159,'Tsiroanomandidy','WTS',126,126,1,1),(5160,'Tulear','TLE',126,126,1,1),(5161,'Vangaindrano','VND',126,126,1,1),(5162,'Vatomandry','VAT',126,126,1,1),(5163,'Vohemar','VOH',126,126,1,1),(5164,'Blantyre','BLZ',127,127,1,1),(5165,'Chelinda','CEH',127,127,1,1),(5166,'Club Makokola','CMK',127,127,1,1),(5167,'Dwangwa','DWA',127,127,1,1),(5168,'Karonga','KGJ',127,127,1,1),(5169,'Kasungu','KBQ',127,127,1,1),(5170,'Likoma Island','LIX',127,127,1,1),(5171,'Lilongwe','LLW',127,127,1,1),(5172,'Mangochi','MAI',127,127,1,1),(5173,'Monkey Bay','MYZ',127,127,1,1),(5174,'Mzuzu','ZZU',127,127,1,1),(5175,'Salima','LMB',127,127,1,1),(5176,'Alor Setar','AOR',128,128,1,1),(5177,'Bakalalan','BKM',128,128,1,1),(5178,'Bario','BBN',128,128,1,1),(5179,'Belaga','BLG',128,128,1,1),(5180,'Bintulu','BTU',128,128,1,1),(5181,'Genting','GTB',128,128,1,1),(5182,'Ipoh','IPH',128,128,1,1),(5183,'Johor Bahru','JHB',128,128,1,1),(5184,'Kapit','KPI',128,128,1,1),(5185,'Keningau','KGU',128,128,1,1),(5186,'Kerteh','KTE',128,128,1,1),(5187,'Kota Bharu','KBR',128,128,1,1),(5188,'Kota Kinabalu','BKI',128,128,1,1),(5189,'Kuala Lumpur','KUL',128,128,1,1),(5191,'Kuala Terengganu','TGG',128,128,1,1),(5192,'Kuantan','KUA',128,128,1,1),(5193,'Kuching','KCH',128,128,1,1),(5194,'Kudat','KUD',128,128,1,1),(5195,'Labuan','LBU',128,128,1,1),(5196,'Lahad Datu','LDU',128,128,1,1),(5197,'Langkawi','LGK',128,128,1,1),(5198,'Lawas','LWY',128,128,1,1),(5199,'Limbang','LMN',128,128,1,1),(5200,'Long Banga','LBP',128,128,1,1),(5201,'Long Lama','LLM',128,128,1,1),(5202,'Long Lellang','LGL',128,128,1,1),(5203,'Long Pasia','GSA',128,128,1,1),(5204,'Long Semado','LSM',128,128,1,1),(5205,'Long Seridan','ODN',128,128,1,1),(5206,'Long Sukang','LSU',128,128,1,1),(5207,'Malacca','MKZ',128,128,1,1),(5208,'Marudi','MUR',128,128,1,1),(5209,'Mersing','MEP',128,128,1,1),(5210,'Miri','MYY',128,128,1,1),(5211,'Mostyn','MZS',128,128,1,1),(5212,'Mukah','MKM',128,128,1,1),(5213,'Mulu','MZV',128,128,1,1),(5214,'Pamol','PAY',128,128,1,1),(5215,'Pangkor Perak','PKG',128,128,1,1),(5216,'Penang','PEN',128,128,1,1),(5217,'Pulau Layang','LAC',128,128,1,1),(5218,'Ranau','RNU',128,128,1,1),(5219,'Redang','RDN',128,128,1,1),(5220,'Sahabat 16','SXS',128,128,1,1),(5221,'Sandakan','SDK',128,128,1,1),(5222,'Sematan','BSE',128,128,1,1),(5223,'Semporna','SMM',128,128,1,1),(5224,'Sepulot','SPE',128,128,1,1),(5225,'Sibu','SBW',128,128,1,1),(5226,'Simanggang','SGG',128,128,1,1),(5227,'Sipitang','SPT',128,128,1,1),(5228,'Sitiawan','SWY',128,128,1,1),(5229,'Sungei Tekai','GTK',128,128,1,1),(5230,'Taiping','TPG',128,128,1,1),(5231,'Taman Negara','SXT',128,128,1,1),(5232,'Tanjung Manis','TGC',128,128,1,1),(5233,'Tawau','TWU',128,128,1,1),(5234,'Telupid','TEL',128,128,1,1),(5235,'Tioman','TOD',128,128,1,1),(5236,'Tomanggong','TMG',128,128,1,1),(5237,'Gan Island','GAN',129,129,1,1),(5238,'Hanimaadhoo','HAQ',129,129,1,1),(5239,'Kaadedhdhoo','KDM',129,129,1,1),(5240,'Kadhdhoo','KDO',129,129,1,1),(5241,'Maamigili','VAM',129,129,1,1),(5242,'Male','MLE',129,129,1,1),(5243,'Bamako','BKO',130,130,1,1),(5244,'Gao','GAQ',130,130,1,1),(5245,'Goundam','GUD',130,130,1,1),(5246,'Kayes','KYS',130,130,1,1),(5247,'Kenieba','KNZ',130,130,1,1),(5248,'Koutiala','KTX',130,130,1,1),(5249,'Mopti','MZI',130,130,1,1),(5250,'Nara','NRM',130,130,1,1),(5251,'Nioro','NIX',130,130,1,1),(5252,'Segou','SZU',130,130,1,1),(5253,'Sikasso','KSS',130,130,1,1),(5254,'Tombouctou','TOM',130,130,1,1),(5255,'Yelimane','EYL',130,130,1,1),(5256,'Gozo','GZM',131,131,1,1),(5257,'Kemmuna','JCO',131,131,1,1),(5258,'Malta','MLA',131,131,1,1),(5259,'Ailinglapalap Isl','AIP',132,132,1,1),(5260,'Ailuk Island','AIM',132,132,1,1),(5261,'Airok','AIC',132,132,1,1),(5262,'Arno','AMR',132,132,1,1),(5263,'Aur Island','AUL',132,132,1,1),(5264,'Bikini Atoll','BII',132,132,1,1),(5265,'Ebadon','EBN',132,132,1,1),(5266,'Ebeye','QEE',132,132,1,1),(5267,'Ebon','EBO',132,132,1,1),(5268,'Enewetak Island','ENT',132,132,1,1),(5269,'Ine Island','IMI',132,132,1,1),(5270,'Jabot','JAT',132,132,1,1),(5271,'Jaluit Island','UIT',132,132,1,1),(5272,'Jeh','JEJ',132,132,1,1),(5273,'Kaben','KBT',132,132,1,1),(5274,'Kili Island','KIO',132,132,1,1),(5275,'Kwajalein','KWA',132,132,1,1),(5276,'Lae Island','LML',132,132,1,1),(5277,'Likiep Island','LIK',132,132,1,1),(5278,'Loen','LOF',132,132,1,1),(5279,'Majkin','MJE',132,132,1,1),(5280,'Majuro','MAJ',132,132,1,1),(5281,'Maloelap Island','MAV',132,132,1,1),(5282,'Mejit Island','MJB',132,132,1,1),(5283,'Mili Island','MIJ',132,132,1,1),(5284,'Namdrik Island','NDK',132,132,1,1),(5285,'Namu','NMU',132,132,1,1),(5286,'Rongelap Island','RNP',132,132,1,1),(5287,'Tabal','TBV',132,132,1,1),(5288,'Tinak Island','TIC',132,132,1,1),(5289,'Ujae Island','UJE',132,132,1,1),(5290,'Utirik Island','UTK',132,132,1,1),(5291,'Woja','WJA',132,132,1,1),(5292,'Wotho Island','WTO',132,132,1,1),(5293,'Wotje Island','WTE',132,132,1,1),(5294,'Fort De France','FDF',133,133,1,1),(5295,'Aioun El Atrouss','AEO',134,134,1,1),(5296,'Akjoujt','AJJ',134,134,1,1),(5297,'Aleg','LEG',134,134,1,1),(5298,'Atar','ATR',134,134,1,1),(5299,'Bogue','BGH',134,134,1,1),(5300,'Boutilimit','OTL',134,134,1,1),(5301,'Chinguetti','CGT',134,134,1,1),(5302,'El Gouera','ZLG',134,134,1,1),(5303,'Fderik','FGD',134,134,1,1),(5304,'Kaedi','KED',134,134,1,1),(5305,'Kiffa','KFA',134,134,1,1),(5306,'Mbout','MBR',134,134,1,1),(5307,'Moudjeria','MOM',134,134,1,1),(5308,'Nema','EMN',134,134,1,1),(5309,'Nouadhibou','NDB',134,134,1,1),(5310,'Nouakchott','NKC',134,134,1,1),(5311,'Selibabi','SEY',134,134,1,1),(5312,'Tamchakett','THT',134,134,1,1),(5313,'Tichit','THI',134,134,1,1),(5314,'Tidjikja','TIY',134,134,1,1),(5315,'Timbedra','TMD',134,134,1,1),(5316,'Zouerate','OUZ',134,134,1,1),(5317,'Mauritius','MRU',135,135,1,1),(5318,'Rodrigues Island','RRG',135,135,1,1),(5319,'Dzaoudzi','DZA',136,136,1,1),(5320,'Abreojos','AJS',137,137,1,1),(5321,'Acapulco','ACA',137,137,1,1),(5322,'Aguascalientes','AGU',137,137,1,1),(5323,'Alamos','XAL',137,137,1,1),(5324,'Apatzingan','AZG',137,137,1,1),(5325,'Bahia Angeles','BHL',137,137,1,1),(5326,'Campeche','CPE',137,137,1,1),(5327,'Cananea','CNA',137,137,1,1),(5328,'Cancun','CUN',137,137,1,1),(5329,'Chetumal','CTM',137,137,1,1),(5330,'Chichen Itza','CZA',137,137,1,1),(5331,'Chihuahua','CUU',137,137,1,1),(5332,'Ciudad Acuna','ACN',137,137,1,1),(5333,'Ciudad Constitucion','CUA',137,137,1,1),(5334,'Ciudad Del Carmen','CME',137,137,1,1),(5335,'Ciudad Juarez','CJS',137,137,1,1),(5336,'Ciudad Mante','MMC',137,137,1,1),(5337,'Ciudad Obregon','CEN',137,137,1,1),(5338,'Ciudad Victoria','CVM',137,137,1,1),(5339,'Coatzacoalcos','QTZ',137,137,1,1),(5340,'Colima','CLQ',137,137,1,1),(5341,'Cozumel','CZM',137,137,1,1),(5342,'Cuernavaca','CVJ',137,137,1,1),(5343,'Culiacan','CUL',137,137,1,1),(5344,'Durango','DGO',137,137,1,1),(5345,'Ensenada','ESE',137,137,1,1),(5346,'Guadalajara','GDL',137,137,1,1),(5347,'Guaymas','GYM',137,137,1,1),(5348,'Guerrero Negro','GUB',137,137,1,1),(5349,'Hermosillo','HMO',137,137,1,1),(5350,'Huatulco','HUX',137,137,1,1),(5351,'Isla Mujeres','ISJ',137,137,1,1),(5352,'Ixtapa','ZIH',137,137,1,1),(5353,'Ixtepec','IZT',137,137,1,1),(5354,'Jalapa','JAL',137,137,1,1),(5355,'La Paz','LAP',137,137,1,1),(5356,'Lagos De Moreno','LOM',137,137,1,1),(5357,'Lazaro Cardenas','LZC',137,137,1,1),(5358,'Leon','BJX',137,137,1,1),(5359,'Loreto','LTO',137,137,1,1),(5360,'Los Mochis','LMM',137,137,1,1),(5361,'Manzanillo','ZLO',137,137,1,1),(5362,'Matamoros','MAM',137,137,1,1),(5363,'Mazatlan','MZT',137,137,1,1),(5364,'Merida','MID',137,137,1,1),(5365,'Mexicali','MXL',137,137,1,1),(5366,'Mexico D.F.','TLC',137,137,1,1),(5368,'Minatitlan','MTT',137,137,1,1),(5369,'Monclova','LOV',137,137,1,1),(5370,'Monterrey','MTY',137,137,1,1),(5372,'Morelia','MLM',137,137,1,1),(5373,'Mulege','MUG',137,137,1,1),(5374,'Nogales','NOG',137,137,1,1),(5375,'Nueva Casas Grand','NCG',137,137,1,1),(5376,'Nuevo Laredo','NLD',137,137,1,1),(5377,'Oaxaca','OAX',137,137,1,1),(5378,'Palenque','PQM',137,137,1,1),(5379,'Piedras Negras','PDS',137,137,1,1),(5380,'Pinotepa Nacional','PNO',137,137,1,1),(5381,'Playa Del Carmen','PCM',137,137,1,1),(5382,'Pochutla','PUH',137,137,1,1),(5383,'Poza Rica','PAZ',137,137,1,1),(5384,'Puebla','PBC',137,137,1,1),(5385,'Puerto Escondido','PXM',137,137,1,1),(5386,'Puerto Juarez','PJZ',137,137,1,1),(5387,'Puerto Penasco','PPE',137,137,1,1),(5388,'Puerto Vallarta','PVR',137,137,1,1),(5389,'Punta Chivato','PCV',137,137,1,1),(5390,'Punta Colorada','PCO',137,137,1,1),(5391,'Queretaro','QRO',137,137,1,1),(5392,'Reynosa','REX',137,137,1,1),(5393,'Salina Cruz','SCX',137,137,1,1),(5394,'Saltillo','SLW',137,137,1,1),(5395,'San Felipe','SFH',137,137,1,1),(5396,'San Ignacio','SGM',137,137,1,1),(5397,'San Jose Cabo','SJD',137,137,1,1),(5398,'San Luis Potosi','SLP',137,137,1,1),(5399,'San Luis Rio Colorado','UAC',137,137,1,1),(5400,'San Quintin','SNQ',137,137,1,1),(5401,'Santa Rosalia','SRL',137,137,1,1),(5402,'Tampico','TAM',137,137,1,1),(5403,'Tamuin','TSL',137,137,1,1),(5404,'Tapachula Intl','TAP',137,137,1,1),(5405,'Tehuacan','TCN',137,137,1,1),(5406,'Tepic','TPQ',137,137,1,1),(5407,'Tijuana','TIJ',137,137,1,1),(5408,'Tizimin','TZM',137,137,1,1),(5409,'Torreon','TRC',137,137,1,1),(5410,'Tulum','TUY',137,137,1,1),(5411,'Tuxtla Gutierrez','TGZ',137,137,1,1),(5412,'Uruapan','UPN',137,137,1,1),(5413,'Veracruz','VER',137,137,1,1),(5414,'Villa Constitucion','VIB',137,137,1,1),(5415,'Villahermosa','VSA',137,137,1,1),(5416,'Zacatecas','ZCL',137,137,1,1),(5417,'Zamora','ZMM',137,137,1,1),(5418,'Kosrae','KSA',138,138,1,1),(5419,'Pohnpei','PNI',138,138,1,1),(5420,'Truk','TKK',138,138,1,1),(5421,'Ulithi','ULI',138,138,1,1),(5422,'Yap','YAP',138,138,1,1),(5423,'Balti','BZY',139,139,1,1),(5424,'Chisinau','KIV',139,139,1,1),(5425,'Monaco Monte Carlo','XMM',140,140,1,1),(5426,'Monte Carlo','MCM',140,140,1,1),(5427,'Altai','LTI',141,141,1,1),(5428,'Arvaikheer','AVK',141,141,1,1),(5429,'Baruun Urt','UUN',141,141,1,1),(5430,'Bayankhongor','BYN',141,141,1,1),(5431,'Bulgan','UGA',141,141,1,1),(5432,'Choibalsan','COQ',141,141,1,1),(5433,'Dalanzagdad','DLZ',141,141,1,1),(5434,'Erdenet','ERT',141,141,1,1),(5435,'Kharkhorin','KHR',141,141,1,1),(5436,'Khujirt','HJT',141,141,1,1),(5437,'Mandalgobi','MXW',141,141,1,1),(5438,'Moron','MXV',141,141,1,1),(5439,'Tsetserleg','TSZ',141,141,1,1),(5440,'Ulaanbaatar','ULN',141,141,1,1),(5441,'Ulaangom','ULO',141,141,1,1),(5442,'Ulgii','ULG',141,141,1,1),(5443,'Uliastai','ULZ',141,141,1,1),(5444,'Underkhaan','UNR',141,141,1,1),(5445,'Montserrat','MNI',142,142,1,1),(5446,'Agadir','AGA',143,143,1,1),(5447,'Al Hoceima','AHU',143,143,1,1),(5448,'Ben Slimane','GMD',143,143,1,1),(5449,'Casablanca','CAS',143,143,1,1),(5451,'Dakhla','VIL',143,143,1,1),(5452,'Errachidia','ERH',143,143,1,1),(5453,'Essaouira','ESU',143,143,1,1),(5454,'Fes','FEZ',143,143,1,1),(5455,'Guelmime','GLN',143,143,1,1),(5456,'Kenitra','NNA',143,143,1,1),(5457,'Laayoune','EUN',143,143,1,1),(5458,'Marrakech','RAK',143,143,1,1),(5459,'Meknes','MEK',143,143,1,1),(5460,'Nador','NDR',143,143,1,1),(5461,'Ouarzazate','OZZ',143,143,1,1),(5462,'Oujda','OUD',143,143,1,1),(5463,'Rabat','RBA',143,143,1,1),(5464,'Sidi Ifni','SII',143,143,1,1),(5465,'Smara','SMW',143,143,1,1),(5466,'Tan Tan','TTA',143,143,1,1),(5467,'Tanger','TNG',143,143,1,1),(5468,'Tetouan','TTU',143,143,1,1),(5469,'Zagora','OZG',143,143,1,1),(5470,'Alto Molocue','AME',144,144,1,1),(5471,'Angoche','ANO',144,144,1,1),(5472,'Bajone','BJN',144,144,1,1),(5473,'Bazaruto Island','BZB',144,144,1,1),(5474,'Beira','BEW',144,144,1,1),(5475,'Benguerra Island','BCW',144,144,1,1),(5476,'Caia','CMZ',144,144,1,1),(5477,'Chilonzuine Island','IDC',144,144,1,1),(5478,'Chimoio','VPY',144,144,1,1),(5479,'Chinde','INE',144,144,1,1),(5480,'Chokwe','TGS',144,144,1,1),(5481,'Cuamba','FXO',144,144,1,1),(5482,'Dugong Beach Lodge','DGK',144,144,1,1),(5483,'Gurue','VJQ',144,144,1,1),(5484,'Ibo','IBO',144,144,1,1),(5485,'Inhambane','INH',144,144,1,1),(5486,'Inhaminga','IMG',144,144,1,1),(5487,'Lichinga','VXC',144,144,1,1),(5488,'Luabo','LBM',144,144,1,1),(5489,'Maganja Da Costa','MJS',144,144,1,1),(5490,'Magaruque Island','MFW',144,144,1,1),(5491,'Maputo','MPM',144,144,1,1),(5492,'Marromeu','RRM',144,144,1,1),(5493,'Mocimboa da Praia','MZB',144,144,1,1),(5494,'Moma','MMW',144,144,1,1),(5495,'Montepuez','MTU',144,144,1,1),(5496,'Mueda','MUD',144,144,1,1),(5497,'Nacala','MNC',144,144,1,1),(5498,'Nampula','APL',144,144,1,1),(5499,'Nangade','NND',144,144,1,1),(5500,'Palma','LMZ',144,144,1,1),(5501,'Pebane','PEB',144,144,1,1),(5502,'Pemba','POL',144,144,1,1),(5503,'Ponta do Oura','PDD',144,144,1,1),(5504,'Quelimane','UEL',144,144,1,1),(5505,'Santa Carolina','NTC',144,144,1,1),(5506,'Tete','TET',144,144,1,1),(5507,'Vilankulos','VNX',144,144,1,1),(5508,'Xai-Xai','VJB',144,144,1,1),(5509,'Bagan','BPE',145,145,1,1),(5510,'Bassein','BSX',145,145,1,1),(5511,'Bhamo','BMO',145,145,1,1),(5512,'Dawe','TVY',145,145,1,1),(5513,'Gangaw','GAW',145,145,1,1),(5514,'Gwa','GWA',145,145,1,1),(5515,'Heho','HEH',145,145,1,1),(5516,'Henzada','HEB',145,145,1,1),(5517,'Homalin','HOX',145,145,1,1),(5518,'Kalemyo','KMV',145,145,1,1),(5519,'Kawthaung','KAW',145,145,1,1),(5520,'Keng Tung','KET',145,145,1,1),(5521,'Khamti','KHM',145,145,1,1),(5522,'Kyaukpyu','KYP',145,145,1,1),(5523,'Kyauktaw','KYT',145,145,1,1),(5524,'Lashio','LSH',145,145,1,1),(5525,'Loikaw','LIW',145,145,1,1),(5526,'Magwe','MWQ',145,145,1,1),(5527,'Manaung','MGU',145,145,1,1),(5528,'Mandalay','MDL',145,145,1,1),(5529,'Maulmyine','MNU',145,145,1,1),(5530,'Momeik','MOE',145,145,1,1),(5531,'Mong Hsat','MOG',145,145,1,1),(5532,'Mong Ton','MGK',145,145,1,1),(5533,'Myeik','MGZ',145,145,1,1),(5534,'Myitkyina','MYT',145,145,1,1),(5535,'Namsang','NMS',145,145,1,1),(5536,'Namtu','NMT',145,145,1,1),(5537,'Nay Pyi Taw','NYT',145,145,1,1),(5538,'Nyaung U','NYU',145,145,1,1),(5539,'Pa An','PAA',145,145,1,1),(5540,'Pakokku','PKK',145,145,1,1),(5541,'Papun','PPU',145,145,1,1),(5542,'Prome','PRU',145,145,1,1),(5543,'Putao','PBU',145,145,1,1),(5544,'Sittwe','AKY',145,145,1,1),(5545,'Tachilek','THL',145,145,1,1),(5546,'Thandwe','SNW',145,145,1,1),(5547,'Tilin','TIO',145,145,1,1),(5548,'Yangon','RGN',145,145,1,1),(5549,'Ye','XYE',145,145,1,1),(5550,'Ai Ais','AIW',146,146,1,1),(5551,'Arandis','ADI',146,146,1,1),(5552,'Bagani','BQI',146,146,1,1),(5553,'Gobabis','GOG',146,146,1,1),(5554,'Grootfontein','GFY',146,146,1,1),(5555,'Halali','HAL',146,146,1,1),(5556,'Karasburg','KAS',146,146,1,1),(5557,'Katima Mulilo','MPA',146,146,1,1),(5558,'Keetmanshoop','KMP',146,146,1,1),(5559,'Lianshulu','LHU',146,146,1,1),(5560,'Luderitz','LUD',146,146,1,1),(5561,'Midgard','MQG',146,146,1,1),(5562,'Mokuti Lodge','OKU',146,146,1,1),(5563,'Mount Etjo','MJO',146,146,1,1),(5564,'Namutoni','NNI',146,146,1,1),(5565,'Okaukuejo','OKF',146,146,1,1),(5566,'Omega','OMG',146,146,1,1),(5567,'Ondangwa','OND',146,146,1,1),(5568,'Ongawa','OGV',146,146,1,1),(5569,'Opuwo','OPW',146,146,1,1),(5570,'Oranjemund','OMD',146,146,1,1),(5571,'Oshakati','OHI',146,146,1,1),(5572,'Otjiwarongo','OTJ',146,146,1,1),(5573,'Rosh Pinah','RHN',146,146,1,1),(5574,'Rundu','NDU',146,146,1,1),(5575,'Sesriem','SZM',146,146,1,1),(5576,'Swakopmund','ZSZ',146,146,1,1),(5578,'Terrace Bay','TCY',146,146,1,1),(5579,'Tsumeb','TSB',146,146,1,1),(5580,'Walvis Bay','WVB',146,146,1,1),(5581,'Windhoek','WDH',146,146,1,1),(5583,'Nauru Island','INU',147,147,1,1),(5584,'Baglung','BGL',148,148,1,1),(5585,'Baitadi','BIT',148,148,1,1),(5586,'Bajhang','BJH',148,148,1,1),(5587,'Bajura','BJU',148,148,1,1),(5588,'Bhadrapur','BDP',148,148,1,1),(5589,'Bhairawa','BWA',148,148,1,1),(5590,'Bharatpur','BHR',148,148,1,1),(5591,'Bhojpur','BHP',148,148,1,1),(5592,'Biratnagar','BIR',148,148,1,1),(5593,'Chaurjhari','HRJ',148,148,1,1),(5594,'Dang','DNP',148,148,1,1),(5595,'Darchula','DAP',148,148,1,1),(5596,'Dhangarhi','DHI',148,148,1,1),(5597,'Dolpa','DOP',148,148,1,1),(5598,'Gorkha','GKH',148,148,1,1),(5599,'Janakpur','JKR',148,148,1,1),(5600,'Jiri','JIR',148,148,1,1),(5601,'Jomsom','JMO',148,148,1,1),(5602,'Jumla','JUM',148,148,1,1),(5603,'Kathmandu','KTM',148,148,1,1),(5604,'Lamidanda','LDN',148,148,1,1),(5605,'Langtang','LTG',148,148,1,1),(5606,'Lukla','LUA',148,148,1,1),(5607,'Mahendranagar','XMG',148,148,1,1),(5608,'Manang','NGX',148,148,1,1),(5609,'Meghauli','MEY',148,148,1,1),(5610,'Mountain','MWP',148,148,1,1),(5611,'Nepalganj','KEP',148,148,1,1),(5612,'Phaplu','PPL',148,148,1,1),(5613,'Pokhara','PKR',148,148,1,1),(5614,'Rajbiraj','RJB',148,148,1,1),(5615,'Ramechhap','RHP',148,148,1,1),(5616,'Rolpa','RPA',148,148,1,1),(5617,'Rukumkot','RUK',148,148,1,1),(5618,'Rumjatar','RUM',148,148,1,1),(5619,'Sanfebagar','FEB',148,148,1,1),(5620,'Silgadi Doti','SIH',148,148,1,1),(5621,'Simara','SIF',148,148,1,1),(5622,'Simikot','IMK',148,148,1,1),(5623,'Surkhet','SKH',148,148,1,1),(5624,'Syangboche','SYH',148,148,1,1),(5625,'Taplejung','TPJ',148,148,1,1),(5626,'Tikapur','TPU',148,148,1,1),(5627,'Tumling Tar','TMI',148,148,1,1),(5628,'Aalsmeer','QFA',149,149,1,1),(5629,'Almelo','QYL',149,149,1,1),(5630,'Amersfoort','QYM',149,149,1,1),(5631,'Amsterdam','AMS',149,149,1,1),(5633,'Any Station Belgium','ZWY',149,149,1,1),(5634,'Apeldoorn','QYP',149,149,1,1),(5635,'Arnhem','ZYM',149,149,1,1),(5636,'Bergen Op Zoom','WOE',149,149,1,1),(5637,'Breda','GLZ',149,149,1,1),(5638,'Den Helder','DHR',149,149,1,1),(5639,'Deventer','QYV',149,149,1,1),(5640,'Drachten','QYC',149,149,1,1),(5641,'Dutch Rail','ZUS',149,149,1,1),(5642,'Dutch Rail Z 01','ZVL',149,149,1,1),(5643,'Dutch Rail Z 02','ZWO',149,149,1,1),(5644,'Dutch Rail Z 03','ZVN',149,149,1,1),(5645,'Dutch Rail Z 04','ZVO',149,149,1,1),(5646,'Dutch Rail Z 05','ZVP',149,149,1,1),(5647,'Dutch Rail Z 06','ZVQ',149,149,1,1),(5648,'Dutch Rail Z 07','ZWQ',149,149,1,1),(5649,'Dutch Rail Z 08','ZVS',149,149,1,1),(5650,'Dutch Rail Z 09','ZVT',149,149,1,1),(5651,'Dutch Rail Z 10','ZVU',149,149,1,1),(5652,'Dutch Rail Z 11','ZVV',149,149,1,1),(5653,'Dutch Rail Z 12','ZVW',149,149,1,1),(5654,'Dutch Rail Z 13','ZVX',149,149,1,1),(5655,'Dutch Rail Z 14','ZVY',149,149,1,1),(5656,'Dutch Rail Z 15','ZVZ',149,149,1,1),(5657,'Dutch Rail Z 16','ZUO',149,149,1,1),(5658,'Dutch Rail Z 17','ZUP',149,149,1,1),(5659,'Dutch Rail Z 18','ZUQ',149,149,1,1),(5660,'Dutch Rail Z 19','ZUR',149,149,1,1),(5661,'Dutch Rail Z 22','ZUU',149,149,1,1),(5662,'Dutch Rail Z 23','ZUV',149,149,1,1),(5663,'Dutch Rail Z 24','ZUW',149,149,1,1),(5664,'Dutch Rail Z 25','ZUX',149,149,1,1),(5665,'Dutch Rail Z 26','ZUY',149,149,1,1),(5666,'Dutch Rail Z 27','ZUZ',149,149,1,1),(5667,'Dutch Rail Z 28','ZWX',149,149,1,1),(5668,'Eindhoven','EIN',149,149,1,1),(5669,'Enschede','ENS',149,149,1,1),(5670,'Groningen','GRQ',149,149,1,1),(5671,'Heerenveen','QYZ',149,149,1,1),(5672,'Hengelo','QYH',149,149,1,1),(5673,'Hilversum','QYI',149,149,1,1),(5674,'Hoofddorp','QHZ',149,149,1,1),(5675,'La Haya','HAG',149,149,1,1),(5676,'Leeuwarden','LWR',149,149,1,1),(5677,'Lelystad','LEY',149,149,1,1),(5678,'Maastricht','MST',149,149,1,1),(5679,'Off-Line Point','ZWZ',149,149,1,1),(5680,'Paterswolde','QYT',149,149,1,1),(5681,'Roosendaal','ZYO',149,149,1,1),(5682,'Rotterdam','RTM',149,149,1,1),(5683,'Schiphol','SPL',149,149,1,1),(5684,'Uden','UDE',149,149,1,1),(5685,'Utrecht','UTC',149,149,1,1),(5686,'Belep Island','BMY',151,151,1,1),(5687,'Hienghene','HNG',151,151,1,1),(5688,'Houailou','HLU',151,151,1,1),(5689,'Ile Des Pins','ILP',151,151,1,1),(5690,'Ile Ouen','IOU',151,151,1,1),(5691,'Kone','KNQ',151,151,1,1),(5692,'Koumac','KOC',151,151,1,1),(5693,'Lifou','LIF',151,151,1,1),(5694,'Mare','MEE',151,151,1,1),(5695,'Mueo','PDC',151,151,1,1),(5696,'Noumea','GEA',151,151,1,1),(5698,'Ouvea','UVE',151,151,1,1),(5699,'Poum','PUV',151,151,1,1),(5700,'Tiga','TGJ',151,151,1,1),(5701,'Touho','TOU',151,151,1,1),(5702,'Alexandra','ALR',152,152,1,1),(5703,'Ardmore','AMZ',152,152,1,1),(5704,'Ashburton','ASG',152,152,1,1),(5705,'Auckland','AKL',152,152,1,1),(5706,'Blenheim','BHE',152,152,1,1),(5707,'Chatham Island','CHT',152,152,1,1),(5708,'Christchurch','CHC',152,152,1,1),(5709,'Coromandel','CMV',152,152,1,1),(5710,'Dargaville','DGR',152,152,1,1),(5711,'Dunedin','DUD',152,152,1,1),(5712,'Fox Glacier','FGL',152,152,1,1),(5713,'Franz Josef','WHO',152,152,1,1),(5714,'Gisborne','GIS',152,152,1,1),(5715,'Great Barrier Island','GBZ',152,152,1,1),(5716,'Greymouth','GMN',152,152,1,1),(5717,'Hamilton','HLZ',152,152,1,1),(5718,'Hokitika','HKK',152,152,1,1),(5719,'Invercargill','IVC',152,152,1,1),(5720,'Kaikohe','KKO',152,152,1,1),(5721,'Kaikoura','KBZ',152,152,1,1),(5722,'Kaitaia','KAT',152,152,1,1),(5723,'Kawau Island','KUI',152,152,1,1),(5724,'Kerikeri','KKE',152,152,1,1),(5725,'Mansion House','KWU',152,152,1,1),(5726,'Masterton','MRO',152,152,1,1),(5727,'Matamata','MTA',152,152,1,1),(5728,'Milford Sound','MFN',152,152,1,1),(5729,'Motueka','MZP',152,152,1,1),(5730,'Mount Cook','MON',152,152,1,1),(5732,'Mystery Explorer','ZUF',152,152,1,1),(5733,'Mystery Flights','ZUA',152,152,1,1),(5734,'Mystery Indulgence','ZUG',152,152,1,1),(5735,'Napier','NPE',152,152,1,1),(5736,'Nelson','NSN',152,152,1,1),(5737,'New Plymouth','NPL',152,152,1,1),(5738,'Oamaru','OAM',152,152,1,1),(5739,'Ohakea','OHA',152,152,1,1),(5740,'Pakatoa Island','PKL',152,152,1,1),(5741,'Palmerston North','PMR',152,152,1,1),(5742,'Paraparaumu','PPQ',152,152,1,1),(5743,'Picton','PCN',152,152,1,1),(5744,'Port Fitzroy','GBS',152,152,1,1),(5745,'Queenstown','ZQN',152,152,1,1),(5746,'Raglan','RAG',152,152,1,1),(5747,'Rotorua','ROT',152,152,1,1),(5748,'Scenic Flight','XXS',152,152,1,1),(5750,'Stewart Island','SZS',152,152,1,1),(5751,'Surfdale','WIK',152,152,1,1),(5752,'Taharoa','THH',152,152,1,1),(5753,'Takaka','KTF',152,152,1,1),(5754,'Taupo','TUO',152,152,1,1),(5755,'Tauranga','TRG',152,152,1,1),(5756,'Te Anau','TEU',152,152,1,1),(5757,'Thames','TMZ',152,152,1,1),(5758,'Timaru','TIU',152,152,1,1),(5759,'Tokoroa','TKZ',152,152,1,1),(5760,'Wairoa','WIR',152,152,1,1),(5761,'Waitangi','WGN',152,152,1,1),(5762,'Wanaka','WKA',152,152,1,1),(5763,'Wanganui','WAG',152,152,1,1),(5764,'Wellington','WLG',152,152,1,1),(5765,'Westport','WSZ',152,152,1,1),(5766,'Whakatane','WHK',152,152,1,1),(5767,'Whangarei','WRE',152,152,1,1),(5768,'Whitianga','WTZ',152,152,1,1),(5769,'Bluefields','BEF',153,153,1,1),(5770,'Bonanza','BZA',153,153,1,1),(5771,'Corn Island','RNI',153,153,1,1),(5772,'Managua','MGA',153,153,1,1),(5773,'Nueva Guinea','NVG',153,153,1,1),(5774,'Puerto Cabezas','PUZ',153,153,1,1),(5775,'Rosita','RFS',153,153,1,1),(5776,'San Carlos','NCR',153,153,1,1),(5777,'Siuna','SIU',153,153,1,1),(5778,'Waspam','WSP',153,153,1,1),(5779,'Agadez','AJY',154,154,1,1),(5780,'Arlit','RLT',154,154,1,1),(5781,'Birni Nkoni','BKN',154,154,1,1),(5782,'Maradi','MFQ',154,154,1,1),(5783,'Niamey','NIM',154,154,1,1),(5784,'Tahoua','THZ',154,154,1,1),(5785,'Zinder','ZND',154,154,1,1),(5786,'Aba','QAX',155,155,1,1),(5787,'Abeokuta','QAT',155,155,1,1),(5788,'Abuja','ABV',155,155,1,1),(5789,'Ajaokuta','QJK',155,155,1,1),(5790,'Akure','AKR',155,155,1,1),(5791,'Apapa','QAP',155,155,1,1),(5792,'Asaba','ABB',155,155,1,1),(5793,'Bacita','QCT',155,155,1,1),(5794,'Bauchi','QBU',155,155,1,1),(5796,'Benin Ciudad','BNI',155,155,1,1),(5797,'Calabar','CBQ',155,155,1,1),(5798,'Enugu','ENU',155,155,1,1),(5799,'Gombe','GMO',155,155,1,1),(5800,'Gusau','QUS',155,155,1,1),(5801,'Ibadan','IBA',155,155,1,1),(5802,'Ikoyi','QIK',155,155,1,1),(5803,'Ilorin','ILR',155,155,1,1),(5804,'Jos','JOS',155,155,1,1),(5805,'Kaduna','KAD',155,155,1,1),(5806,'Kano','KAN',155,155,1,1),(5807,'Katsina','DKA',155,155,1,1),(5808,'Lagos','LOS',155,155,1,1),(5809,'Maiduguri','MIU',155,155,1,1),(5810,'Makurdi','MDI',155,155,1,1),(5811,'Marina','QNN',155,155,1,1),(5812,'Minna','MXJ',155,155,1,1),(5813,'Nsukka','QNK',155,155,1,1),(5814,'Onitsha','QNI',155,155,1,1),(5815,'Owerri','QOW',155,155,1,1),(5816,'Port Harcourt','PHG',155,155,1,1),(5818,'Sokoto','SKO',155,155,1,1),(5819,'Suru Lere','QSL',155,155,1,1),(5820,'Uyo','QUO',155,155,1,1),(5821,'Victoria Island','QVL',155,155,1,1),(5822,'Warri','QRW',155,155,1,1),(5823,'Yaba','QYB',155,155,1,1),(5824,'Yola','YOL',155,155,1,1),(5825,'Zaria','ZAR',155,155,1,1),(5826,'Niue Island','IUE',156,156,1,1),(5827,'Norfolk Island','NLK',157,157,1,1),(5828,'Orang','RGO',158,158,1,1),(5829,'Pyongyang','FNJ',158,158,1,1),(5830,'Samjiyon','YJS',158,158,1,1),(5831,'Sondok','DSO',158,158,1,1),(5832,'Wonson','WOS',158,158,1,1),(5833,'Rota','ROP',159,159,1,1),(5834,'Saipan','SPN',159,159,1,1),(5835,'Tinian','TIQ',159,159,1,1),(5836,'AL','XXR',160,160,1,1),(5837,'Alesund','AES',160,160,1,1),(5838,'Alta','ALF',160,160,1,1),(5839,'Alvidal','XJA',160,160,1,1),(5840,'ANDALSNES','XGI',160,160,1,1),(5841,'Andenes','ANX',160,160,1,1),(5842,'ARENDAL','XGD',160,160,1,1),(5843,'ARNA RAIL STATION','XHT',160,160,1,1),(5844,'ASKER','XGU',160,160,1,1),(5845,'Bangsund','ZXC',160,160,1,1),(5846,'Bardufoss','BDU',160,160,1,1),(5847,'Basmoen','ZXK',160,160,1,1),(5848,'Batsfjord','BJF',160,160,1,1),(5849,'Bergen','BGO',160,160,1,1),(5850,'Bergen Harbour','QFV',160,160,1,1),(5851,'BERKAK','XUB',160,160,1,1),(5852,'Berlevag','BVG',160,160,1,1),(5853,'BJERKA','ZMZ',160,160,1,1),(5854,'BO','XXC',160,160,1,1),(5855,'Bodo','BOO',160,160,1,1),(5856,'Bronnoysund','BNN',160,160,1,1),(5857,'BRUMUNDDAL','ZVB',160,160,1,1),(5858,'BRYNE','XOB',160,160,1,1),(5859,'DOMBAS','XGP',160,160,1,1),(5860,'DRAMMEN','XND',160,160,1,1),(5861,'Drangedal','ZVD',160,160,1,1),(5862,'EGERSUND','XRD',160,160,1,1),(5863,'ELVERUM','XUC',160,160,1,1),(5864,'Fagernes','VDB',160,160,1,1),(5865,'FAUSKE','ZXO',160,160,1,1),(5866,'FINSE','XKN',160,160,1,1),(5867,'FLAM','XGH',160,160,1,1),(5868,'Floro','FRO',160,160,1,1),(5869,'Forde','FDE',160,160,1,1),(5870,'Fore','ZXJ',160,160,1,1),(5871,'FREDRIKSTAD','XKF',160,160,1,1),(5872,'GJERSTAD','XGS',160,160,1,1),(5873,'GJOVIK RAIL STATION','ZYG',160,160,1,1),(5874,'Gol','GLL',160,160,1,1),(5875,'Gravvika','ZXG',160,160,1,1),(5876,'GRONG','XKG',160,160,1,1),(5877,'HALDEN','XKD',160,160,1,1),(5878,'Hamar','HMR',160,160,1,1),(5879,'Hammerfest','HFT',160,160,1,1),(5880,'Harstad','HRD',160,160,1,1),(5881,'Harstad-Narvik','EVE',160,160,1,1),(5882,'Hasvik','HAA',160,160,1,1),(5883,'HAUGASTOL','ZWJ',160,160,1,1),(5884,'Haugesund','HAU',160,160,1,1),(5885,'Havoysund','QVO',160,160,1,1),(5886,'HEIMDAL','XUE',160,160,1,1),(5887,'Hemnes','ZXR',160,160,1,1),(5888,'HJERKINN','YVH',160,160,1,1),(5889,'HOLMESTRAND','XUG',160,160,1,1),(5890,'HONEFOSS','XHF',160,160,1,1),(5891,'Honningsvag','HVG',160,160,1,1),(5892,'Jan Mayen','ZXB',160,160,1,1),(5893,'Karasjok','QKK',160,160,1,1),(5894,'Kautokeino','QKX',160,160,1,1),(5895,'Kirkenes','KKN',160,160,1,1),(5896,'Kjollefjord','QJL',160,160,1,1),(5897,'KONGSBERG','XKB',160,160,1,1),(5898,'KONGSVINGER','XZD',160,160,1,1),(5899,'KOPPANG RAIL STATION','YVK',160,160,1,1),(5900,'Kristiansand','KRS',160,160,1,1),(5902,'Kristiansund','KSU',160,160,1,1),(5903,'Lakselv','LKL',160,160,1,1),(5904,'LARVIK','XKK',160,160,1,1),(5905,'Leka','ZXN',160,160,1,1),(5906,'Leknes','LKN',160,160,1,1),(5907,'LEVANGER','XUH',160,160,1,1),(5908,'LILLEHAMMER','XXL',160,160,1,1),(5909,'LILLESTROM','XKI',160,160,1,1),(5910,'Longyearbyen','LYR',160,160,1,1),(5911,'LYSAKER','XUI',160,160,1,1),(5912,'Maloy Harbour','QFQ',160,160,1,1),(5913,'MARNARDAL','ZYY',160,160,1,1),(5914,'Mehamn','MEH',160,160,1,1),(5915,'Mo I Rana','MQN',160,160,1,1),(5916,'MOELV','XUJ',160,160,1,1),(5917,'Molde','MOL',160,160,1,1),(5918,'Mosjoen','MJF',160,160,1,1),(5919,'Moss','XKM',160,160,1,1),(5920,'MYRDAL','XOL',160,160,1,1),(5921,'Namsos','OSY',160,160,1,1),(5922,'Narvik','NVK',160,160,1,1),(5923,'NELAUG','XHL',160,160,1,1),(5924,'NESBYEN','XUL',160,160,1,1),(5925,'NESLANDSVATN','XUM',160,160,1,1),(5926,'NORDAGUTU','XUO',160,160,1,1),(5927,'Notodden','NTB',160,160,1,1),(5928,'Oksfjord','QOK',160,160,1,1),(5929,'OPPDAL','XOD',160,160,1,1),(5930,'Orland','OLA',160,160,1,1),(5931,'Orsta Volda','HOV',160,160,1,1),(5932,'Oslo','TRF',160,160,1,1),(5934,'OTTA','XOR',160,160,1,1),(5935,'PORSGRUNN','XKP',160,160,1,1),(5936,'RADE','ZXX',160,160,1,1),(5937,'RAUFOSS','ZMQ',160,160,1,1),(5938,'RENA','XKE',160,160,1,1),(5939,'RINGEBU','XUQ',160,160,1,1),(5940,'Rodoy','ZXI',160,160,1,1),(5941,'ROGNAN','ZXM',160,160,1,1),(5942,'Roros','RRS',160,160,1,1),(5943,'Rorvik','RVK',160,160,1,1),(5944,'Rost','RET',160,160,1,1),(5945,'RYGGE','ZXU',160,160,1,1),(5946,'Sandane','SDN',160,160,1,1),(5947,'SANDEFJORD','ZYS',160,160,1,1),(5948,'SANDNES','XKC',160,160,1,1),(5949,'Sandnessjoen','SSJ',160,160,1,1),(5950,'SANDVIKA','ZYW',160,160,1,1),(5951,'SARPSBORG','XKQ',160,160,1,1),(5952,'Selje Harbour','QFK',160,160,1,1),(5953,'SIRA','XOQ',160,160,1,1),(5954,'SKI','YVS',160,160,1,1),(5955,'Skien','SKE',160,160,1,1),(5956,'Skjerstad','ZXL',160,160,1,1),(5957,'SKOPPUM','XUR',160,160,1,1),(5958,'SNARTEMO','XUS',160,160,1,1),(5959,'Sogndal','SOG',160,160,1,1),(5960,'Solstad','ZXQ',160,160,1,1),(5961,'Sorfjord','ZXH',160,160,1,1),(5962,'Sorkjosen','SOJ',160,160,1,1),(5963,'Soroya','QZS',160,160,1,1),(5964,'Stavanger','SVG',160,160,1,1),(5965,'STEINKJER','XKJ',160,160,1,1),(5966,'STJORDAL','XUU',160,160,1,1),(5967,'Stokmarknes','SKN',160,160,1,1),(5968,'Stord','SRP',160,160,1,1),(5969,'STOREKVINA','XUV',160,160,1,1),(5970,'STOREN','XUW',160,160,1,1),(5971,'Svalbard','SYG',160,160,1,1),(5972,'Svolvaer','SVJ',160,160,1,1),(5973,'Tana','QTP',160,160,1,1),(5974,'TONSBERG','XKW',160,160,1,1),(5975,'Tromso','TOS',160,160,1,1),(5976,'Trondheim','TRD',160,160,1,1),(5977,'TYNSET RAIL STATION','ZMX',160,160,1,1),(5978,'USTAOSET','XUX',160,160,1,1),(5979,'Vadso','VDS',160,160,1,1),(5980,'Vaeroy','VRY',160,160,1,1),(5981,'Vardo','VAW',160,160,1,1),(5982,'Vegarshei','ZYV',160,160,1,1),(5983,'VENNESLA','XXE',160,160,1,1),(5984,'VERDAL','XXG',160,160,1,1),(5985,'Vikna','ZXD',160,160,1,1),(5986,'VINSTRA','XKZ',160,160,1,1),(5987,'VOSS','XVK',160,160,1,1),(5988,'Adam','AOM',161,161,1,1),(5989,'Bahja','BJQ',161,161,1,1),(5990,'Buraimi','RMB',161,161,1,1),(5991,'Diba Al Bayah','BYB',161,161,1,1),(5992,'Fahud','FAU',161,161,1,1),(5993,'Khasab','KHS',161,161,1,1),(5994,'Lekhwair','LKW',161,161,1,1),(5995,'Marmul','OMM',161,161,1,1),(5996,'Masirah','MSH',161,161,1,1),(5997,'Mukhaizna','UKH',161,161,1,1),(5998,'Muscat','MCT',161,161,1,1),(5999,'Saih Rawl','AHW',161,161,1,1),(6000,'Salalah','SLL',161,161,1,1),(6001,'Sur','SUH',161,161,1,1),(6002,'Thumrait','TTH',161,161,1,1),(6003,'Abbottabad','AAW',162,162,1,1),(6004,'Attock','ATG',162,162,1,1),(6005,'Badin','BDN',162,162,1,1),(6006,'Bahawalnagar','WGB',162,162,1,1),(6007,'Bahawalpur','BHV',162,162,1,1),(6008,'Bannu','BNP',162,162,1,1),(6009,'Bhurban','BHC',162,162,1,1),(6010,'Campbellpore','CWP',162,162,1,1),(6011,'Chilas','CHB',162,162,1,1),(6012,'Chitral','CJL',162,162,1,1),(6013,'Dadu','DDU',162,162,1,1),(6014,'Dalbandin','DBA',162,162,1,1),(6015,'Dera Ghazi Khan','DEA',162,162,1,1),(6016,'Dera Ismail Khan','DSK',162,162,1,1),(6017,'Faisalabad','LYP',162,162,1,1),(6018,'Gilgit','GIL',162,162,1,1),(6019,'Gujrat','GRT',162,162,1,1),(6020,'Gwadar','GWD',162,162,1,1),(6021,'Hyderabad','HDD',162,162,1,1),(6022,'Islamabad','ISB',162,162,1,1),(6023,'Jacobabad','JAG',162,162,1,1),(6024,'Jiwani','JIW',162,162,1,1),(6025,'Kadanwari','KCF',162,162,1,1),(6026,'Kalat','KBH',162,162,1,1),(6027,'Karachi','KHI',162,162,1,1),(6028,'Kharian','QKH',162,162,1,1),(6029,'Khuzdar','KDD',162,162,1,1),(6030,'Kohat','OHT',162,162,1,1),(6031,'Lahore','LHE',162,162,1,1),(6032,'Lora Lai','LRG',162,162,1,1),(6033,'Mansehra','HRA',162,162,1,1),(6034,'Mianwali','MWD',162,162,1,1),(6035,'Mirpur','QML',162,162,1,1),(6036,'Mirpur Khas','MPD',162,162,1,1),(6037,'Mohenjodaro','MJD',162,162,1,1),(6038,'Multan','MUX',162,162,1,1),(6039,'Muzaffarabad','MFG',162,162,1,1),(6040,'Nawabshah','WNS',162,162,1,1),(6041,'Nushki','NHS',162,162,1,1),(6042,'Ormara','ORW',162,162,1,1),(6043,'Panjgur','PJG',162,162,1,1),(6044,'Para Chinar','PAJ',162,162,1,1),(6045,'Pasni','PSI',162,162,1,1),(6046,'Peshawar','PEW',162,162,1,1),(6047,'Quetta','UET',162,162,1,1),(6048,'Rahim Yar Khan','RYK',162,162,1,1),(6049,'Rawala Kot','RAZ',162,162,1,1),(6050,'Rawalpindi','RWP',162,162,1,1),(6051,'Sahiwal','SWN',162,162,1,1),(6052,'Saidu Sharif','SDT',162,162,1,1),(6053,'Sargodha','SGI',162,162,1,1),(6054,'Savi Ragha','ZEO',162,162,1,1),(6055,'Sehwen Sharif','SYW',162,162,1,1),(6056,'Shikarpur','SWV',162,162,1,1),(6057,'Sialkot','SKT',162,162,1,1),(6058,'Sibi','SBQ',162,162,1,1),(6059,'Skardu','KDU',162,162,1,1),(6060,'Sui','SUL',162,162,1,1),(6061,'Sukkur','SKZ',162,162,1,1),(6062,'Taftan','TFT',162,162,1,1),(6063,'Tarbela','TLB',162,162,1,1),(6064,'Turbat','TUK',162,162,1,1),(6065,'Wana','WAF',162,162,1,1),(6066,'Zhob','PZH',162,162,1,1),(6067,'Koror','ROR',163,163,1,1),(6068,'Gaza','GZA',164,164,1,1),(6069,'Nablus','ZDF',164,164,1,1),(6070,'Ramallah','ZDM',164,164,1,1),(6071,'Achutupo','ACU',165,165,1,1),(6072,'Ailigandi','AIL',165,165,1,1),(6073,'Balboa','BLB',165,165,1,1),(6074,'Bocas Del Toro','BOC',165,165,1,1),(6075,'Caledonia','CDE',165,165,1,1),(6076,'Carti','CTE',165,165,1,1),(6077,'Changuinola','CHX',165,165,1,1),(6078,'Chitre','CTD',165,165,1,1),(6079,'Colon','ONX',165,165,1,1),(6080,'Contadora','OTD',165,165,1,1),(6081,'Corazon De Jesus','CZJ',165,165,1,1),(6082,'David','DAV',165,165,1,1),(6083,'El Porvenir','PVE',165,165,1,1),(6084,'El Real','ELE',165,165,1,1),(6085,'Garachine','GHE',165,165,1,1),(6086,'Isla Viveros','IVI',165,165,1,1),(6087,'Jaque','JQE',165,165,1,1),(6088,'La Palma','PLP',165,165,1,1),(6089,'Mamitupo','MPI',165,165,1,1),(6090,'Mulatupo','MPP',165,165,1,1),(6091,'Nargana','NGN',165,165,1,1),(6092,'Panama Ciudad','PAC',165,165,1,1),(6094,'Playon Chico','PYC',165,165,1,1),(6095,'Puerto Armuellas','AML',165,165,1,1),(6096,'Puerto Obaldia','PUE',165,165,1,1),(6097,'Rio Alzucar','RIZ',165,165,1,1),(6098,'Rio Sidra','RSI',165,165,1,1),(6099,'Rio Tigre','RIT',165,165,1,1),(6100,'Sambu','SAX',165,165,1,1),(6101,'San Blas','NBL',165,165,1,1),(6102,'San Miguel','NMG',165,165,1,1),(6103,'Santa Fe','SFW',165,165,1,1),(6104,'Santiago','SYP',165,165,1,1),(6105,'Ticantiki','TJC',165,165,1,1),(6106,'Torembi','TCJ',165,165,1,1),(6107,'Tubala','TUW',165,165,1,1),(6108,'Tupile','TUE',165,165,1,1),(6109,'Ustupo','UTU',165,165,1,1),(6110,'Yaviza','PYV',165,165,1,1),(6111,'Abau','ABW',166,166,1,1),(6112,'Afore','AFR',166,166,1,1),(6113,'Agaun','AUP',166,166,1,1),(6114,'Aiome','AIE',166,166,1,1),(6115,'Aitape','ATP',166,166,1,1),(6116,'Aiyura','AYU',166,166,1,1),(6117,'Alotau','GUR',166,166,1,1),(6118,'Ama','AMF',166,166,1,1),(6119,'Amanab','AMU',166,166,1,1),(6120,'Amazon Bay','AZB',166,166,1,1),(6121,'Amboin','AMG',166,166,1,1),(6122,'Ambunti','AUJ',166,166,1,1),(6123,'Angoram','AGG',166,166,1,1),(6124,'Anguganak','AKG',166,166,1,1),(6125,'Annanberg','AOB',166,166,1,1),(6126,'April River','APR',166,166,1,1),(6127,'Aragip','ARP',166,166,1,1),(6128,'Arawa','RAW',166,166,1,1),(6129,'Aroa','AOA',166,166,1,1),(6130,'Arona','AON',166,166,1,1),(6131,'Asapa','APP',166,166,1,1),(6132,'Aseki','AEK',166,166,1,1),(6133,'Asirim','ASZ',166,166,1,1),(6134,'Atkamba','ABP',166,166,1,1),(6135,'Aua Island','AUI',166,166,1,1),(6136,'Aumo','AUV',166,166,1,1),(6137,'Awaba','AWB',166,166,1,1),(6138,'Awar','AWR',166,166,1,1),(6139,'Baibara','BAP',166,166,1,1),(6140,'Baimuru','VMU',166,166,1,1),(6141,'Baindoung','BDZ',166,166,1,1),(6142,'Bali','BAJ',166,166,1,1),(6143,'Balimo','OPU',166,166,1,1),(6144,'Bambu','BCP',166,166,1,1),(6145,'Bamu','BMZ',166,166,1,1),(6146,'Banz','BNZ',166,166,1,1),(6147,'Bapi','BPD',166,166,1,1),(6148,'Bawan','BWJ',166,166,1,1),(6149,'Bensbach','BSP',166,166,1,1),(6150,'Bereina','BEA',166,166,1,1),(6151,'Bewani','BWP',166,166,1,1),(6152,'Bialla','BAA',166,166,1,1),(6153,'Biaru','BRP',166,166,1,1),(6154,'Biliau','BIJ',166,166,1,1),(6155,'Bimin','BIZ',166,166,1,1),(6156,'Biniguni','XBN',166,166,1,1),(6157,'Boana','BNV',166,166,1,1),(6158,'Boang','BOV',166,166,1,1),(6159,'Bodinumu','BNM',166,166,1,1),(6160,'Boku','BOQ',166,166,1,1),(6161,'Bolovip','BVP',166,166,1,1),(6162,'Bomai','BMH',166,166,1,1),(6163,'Boridi','BPB',166,166,1,1),(6164,'Boset','BOT',166,166,1,1),(6165,'Brahman','BRH',166,166,1,1),(6166,'Buin','UBI',166,166,1,1),(6167,'Buka','BUA',166,166,1,1),(6168,'Bulolo','BUL',166,166,1,1),(6169,'Bundi','BNT',166,166,1,1),(6170,'Bunsil','BXZ',166,166,1,1),(6171,'Cape Gloucester','CGC',166,166,1,1),(6172,'Cape Orford','CPI',166,166,1,1),(6173,'Cape Rodney','CPN',166,166,1,1),(6174,'Cape Vogel','CVL',166,166,1,1),(6175,'Chungribu','CVB',166,166,1,1),(6176,'Dalbertis','DLB',166,166,1,1),(6177,'Daru','DAU',166,166,1,1),(6178,'Daugo','DGG',166,166,1,1),(6179,'Daup','DAF',166,166,1,1),(6180,'Debepare','DBP',166,166,1,1),(6181,'Derim','DER',166,166,1,1),(6182,'Dinangat','DNU',166,166,1,1),(6183,'Dios','DOS',166,166,1,1),(6184,'Dodoima','DDM',166,166,1,1),(6185,'Doini','DOI',166,166,1,1),(6186,'Dorobisoro','DOO',166,166,1,1),(6187,'Dumpu','DPU',166,166,1,1),(6188,'Efogi','EFG',166,166,1,1),(6189,'Eia','EIA',166,166,1,1),(6190,'Eliptamin','EPT',166,166,1,1),(6191,'Embessa','EMS',166,166,1,1),(6192,'Emirau','EMI',166,166,1,1),(6193,'Emo','EMO',166,166,1,1),(6194,'Engati','EGA',166,166,1,1),(6195,'Erave','ERE',166,166,1,1),(6196,'Erume','ERU',166,166,1,1),(6197,'Esa\'ala','ESA',166,166,1,1),(6198,'Fane','FNE',166,166,1,1),(6199,'Feramin','FRQ',166,166,1,1),(6200,'Finschhafen','FIN',166,166,1,1),(6201,'Fulleborn','FUB',166,166,1,1),(6202,'Fuma','FUM',166,166,1,1),(6203,'Garaina','GAR',166,166,1,1),(6204,'Garasa','GRL',166,166,1,1),(6205,'Garuahi','GRH',166,166,1,1),(6206,'Gasmata Island','GMI',166,166,1,1),(6207,'Gasuke','GBC',166,166,1,1),(6208,'Gewoya','GEW',166,166,1,1),(6209,'Gnarowein','GWN',166,166,1,1),(6210,'Gonalia','GOE',166,166,1,1),(6211,'Gora','GOC',166,166,1,1),(6212,'Goroka','GKA',166,166,1,1),(6213,'Green Islands','GEI',166,166,1,1),(6214,'Green River','GVI',166,166,1,1),(6215,'Guari','GUG',166,166,1,1),(6216,'Guasopa','GAZ',166,166,1,1),(6217,'Gulgubip','GLP',166,166,1,1),(6218,'Guriaso','GUE',166,166,1,1),(6219,'Gusap','GAP',166,166,1,1),(6220,'Haelogo','HEO',166,166,1,1),(6221,'Hatzfeldthaven','HAZ',166,166,1,1),(6222,'Hawabango','HWA',166,166,1,1),(6223,'Hayfields','HYF',166,166,1,1),(6224,'Heiweni','HNI',166,166,1,1),(6225,'Hivaro','HIT',166,166,1,1),(6226,'Honinabi','HNN',166,166,1,1),(6227,'Hoskins','HKN',166,166,1,1),(6228,'Iamalele','IMA',166,166,1,1),(6229,'Iaura','IAU',166,166,1,1),(6230,'Iboki','IBI',166,166,1,1),(6231,'Ihu','IHU',166,166,1,1),(6232,'Ileg','ILX',166,166,1,1),(6233,'Imane','IMN',166,166,1,1),(6234,'Imonda','IMD',166,166,1,1),(6235,'Indagen','IDN',166,166,1,1),(6236,'Inus','IUS',166,166,1,1),(6237,'Iokea','IOK',166,166,1,1),(6238,'Ioma','IOP',166,166,1,1),(6239,'Itokama','ITK',166,166,1,1),(6240,'Jacquinot Bay','JAQ',166,166,1,1),(6241,'Josephstaal','JOP',166,166,1,1),(6242,'Kabwum','KBM',166,166,1,1),(6243,'Kagi','KGW',166,166,1,1),(6244,'Kagua','AGK',166,166,1,1),(6245,'Kaiapit','KIA',166,166,1,1),(6246,'Kaintiba','KZF',166,166,1,1),(6247,'Kamberatoro','KDQ',166,166,1,1),(6248,'Kamina','KMF',166,166,1,1),(6249,'Kamiraba','KJU',166,166,1,1),(6250,'Kamulai','KAQ',166,166,1,1),(6251,'Kamusi','KUY',166,166,1,1),(6252,'Kanabea','KEX',166,166,1,1),(6253,'Kanainj','KNE',166,166,1,1),(6254,'Kandep','KDP',166,166,1,1),(6255,'Kandrian','KDR',166,166,1,1),(6256,'Kanua','KTK',166,166,1,1),(6257,'Kar','KAK',166,166,1,1),(6258,'Kar Kar','KRX',166,166,1,1),(6259,'Karato','KAF',166,166,1,1),(6260,'Karawari','KRJ',166,166,1,1),(6261,'Karimui','KMR',166,166,1,1),(6262,'Karoola','KXR',166,166,1,1),(6263,'Kasanombe','KSB',166,166,1,1),(6264,'Kavieng','KVG',166,166,1,1),(6265,'Kawito','KWO',166,166,1,1),(6266,'Keglsugl','KEG',166,166,1,1),(6267,'Kelanoa','KNL',166,166,1,1),(6268,'Kerau','KRU',166,166,1,1),(6269,'Kerema','KMA',166,166,1,1),(6270,'Kieta','KIE',166,166,1,1),(6271,'Kikinonda','KIZ',166,166,1,1),(6272,'Kikori','KRI',166,166,1,1),(6273,'Kira','KIQ',166,166,1,1),(6274,'Kisengan','KSG',166,166,1,1),(6275,'Kitava','KVE',166,166,1,1),(6276,'Kiunga','UNG',166,166,1,1),(6277,'Kiwai Island','KWX',166,166,1,1),(6278,'Koinambe','KMB',166,166,1,1),(6279,'Kokoda','KKD',166,166,1,1),(6280,'Kokoro','KOR',166,166,1,1),(6281,'Kol','KQL',166,166,1,1),(6282,'Komaio','KCJ',166,166,1,1),(6283,'Komako','HOC',166,166,1,1),(6284,'Komo Manda','KOM',166,166,1,1),(6285,'Kompiam','KPM',166,166,1,1),(6286,'Konge','KGB',166,166,1,1),(6287,'Kopiago','KPA',166,166,1,1),(6288,'Koroba','KDE',166,166,1,1),(6289,'Kosipe','KSP',166,166,1,1),(6290,'Kundiawa','CMU',166,166,1,1),(6291,'Kungum','KGM',166,166,1,1),(6292,'Kupiano','KUP',166,166,1,1),(6293,'Kuri','KUQ',166,166,1,1),(6294,'Kurwina','KWV',166,166,1,1),(6295,'Lablab','LAB',166,166,1,1),(6296,'Lae','LAE',166,166,1,1),(6297,'Laiagam','LGM',166,166,1,1),(6298,'Lake Murray','LMY',166,166,1,1),(6299,'Lamassa','LMG',166,166,1,1),(6300,'Langimar','LNM',166,166,1,1),(6301,'Lehu','LHP',166,166,1,1),(6302,'Leitre','LTF',166,166,1,1),(6303,'Lengbati','LNC',166,166,1,1),(6304,'Leron Plains','LPN',166,166,1,1),(6305,'Lese','LNG',166,166,1,1),(6306,'Lihir Island','LNV',166,166,1,1),(6307,'Linga Linga','LGN',166,166,1,1),(6308,'Loani','LNQ',166,166,1,1),(6309,'Long Island','LSJ',166,166,1,1),(6310,'Losuia','LSA',166,166,1,1),(6311,'Lowai','LWI',166,166,1,1),(6312,'Lumi','LMI',166,166,1,1),(6313,'Madang','MAG',166,166,1,1),(6314,'Makini','MPG',166,166,1,1),(6315,'Mal','MMV',166,166,1,1),(6316,'Malalaua','MLQ',166,166,1,1),(6317,'Malekolon','MKN',166,166,1,1),(6318,'Mamai','MAP',166,166,1,1),(6319,'Manare','MRM',166,166,1,1),(6320,'Manetai','MVI',166,166,1,1),(6321,'Manga','MGP',166,166,1,1),(6322,'Manguna','MFO',166,166,1,1),(6323,'Manumu','UUU',166,166,1,1),(6324,'Manus Island','MAS',166,166,1,1),(6325,'Mapoda','MPF',166,166,1,1),(6326,'Mapua','MPU',166,166,1,1),(6327,'Maramuni','MWI',166,166,1,1),(6328,'Marawaka','MWG',166,166,1,1),(6329,'Margarima','MGG',166,166,1,1),(6330,'Maron','MNP',166,166,1,1),(6331,'Masa','MBV',166,166,1,1),(6332,'May River','MRH',166,166,1,1),(6333,'Mendi','MDU',166,166,1,1),(6334,'Menyamya','MYX',166,166,1,1),(6335,'Mesalia','MFZ',166,166,1,1),(6336,'Mindik','MXK',166,166,1,1),(6337,'Minj','MZN',166,166,1,1),(6338,'Misima Island','MIS',166,166,1,1),(6339,'Miyanmin','MPX',166,166,1,1),(6340,'Moki','MJJ',166,166,1,1),(6341,'Morehead','MHY',166,166,1,1),(6342,'Morobe','OBM',166,166,1,1),(6343,'Mougulu','GUV',166,166,1,1),(6344,'Mount Aue','UAE',166,166,1,1),(6345,'Mount Hagen','HGU',166,166,1,1),(6346,'Munbil','LNF',166,166,1,1),(6347,'Munduku','MDM',166,166,1,1),(6348,'Mussau','MWU',166,166,1,1),(6349,'Nadunumu','NDN',166,166,1,1),(6350,'Namatanai','ATN',166,166,1,1),(6351,'Nambaiyufa','NBA',166,166,1,1),(6352,'Namudi','NDI',166,166,1,1),(6353,'Nankina','NKN',166,166,1,1),(6354,'Naoro','NOO',166,166,1,1),(6355,'Negarbo','GBF',166,166,1,1),(6356,'Ningerum','NGR',166,166,1,1),(6357,'Nipa','NPG',166,166,1,1),(6358,'Nissan Island','IIS',166,166,1,1),(6359,'Nomad River','NOM',166,166,1,1),(6360,'Nomane','NMN',166,166,1,1),(6361,'Nowata','NWT',166,166,1,1),(6362,'Nuguria','NUG',166,166,1,1),(6363,'Nuku','UKU',166,166,1,1),(6364,'Nutuve','NUT',166,166,1,1),(6365,'Obo','OBX',166,166,1,1),(6366,'Ogeranang','OGE',166,166,1,1),(6367,'Oksapmin','OKP',166,166,1,1),(6368,'Olsobip','OLQ',166,166,1,1),(6369,'Omkalai','OML',166,166,1,1),(6370,'Omora','OSE',166,166,1,1),(6371,'Ononge','ONB',166,166,1,1),(6372,'Open Bay','OPB',166,166,1,1),(6373,'Oram','RAX',166,166,1,1),(6374,'Oria','OTY',166,166,1,1),(6375,'Ossima','OSG',166,166,1,1),(6376,'Paiela','PLE',166,166,1,1),(6377,'Pambwa','PAW',166,166,1,1),(6378,'Pangia','PGN',166,166,1,1),(6379,'Pangoa','PGB',166,166,1,1),(6380,'Param','PPX',166,166,1,1),(6381,'Pimaga','PMP',166,166,1,1),(6382,'Pindiu','PDI',166,166,1,1),(6383,'Popondetta','PNP',166,166,1,1),(6384,'Porgera','RGE',166,166,1,1),(6385,'Port Moresby','POM',166,166,1,1),(6386,'Puas','PUA',166,166,1,1),(6387,'Pumani','PMN',166,166,1,1),(6388,'Pureni','PUI',166,166,1,1),(6389,'Rabaraba','RBP',166,166,1,1),(6390,'Rabaul','RAB',166,166,1,1),(6391,'Rakanda','RAA',166,166,1,1),(6392,'Robinson River','RNR',166,166,1,1),(6393,'Rumginae','RMN',166,166,1,1),(6394,'Ruti','RUU',166,166,1,1),(6395,'Sabah','SBV',166,166,1,1),(6396,'Safia','SFU',166,166,1,1),(6397,'Sagarai','SGJ',166,166,1,1),(6398,'Saidor','SDI',166,166,1,1),(6399,'Salamo','SAM',166,166,1,1),(6400,'Samarai Isl','SQT',166,166,1,1),(6401,'Sangapi','SGK',166,166,1,1),(6402,'Sapmanga','SMH',166,166,1,1),(6403,'Satwag','SWG',166,166,1,1),(6404,'Sauren','SXW',166,166,1,1),(6405,'Sehulea','SXH',166,166,1,1),(6406,'Selbang','SBC',166,166,1,1),(6407,'Sepik Plains','SPV',166,166,1,1),(6408,'Sialum','SXA',166,166,1,1),(6409,'Siassi','SSS',166,166,1,1),(6410,'Sila','SIL',166,166,1,1),(6411,'Silur','SWR',166,166,1,1),(6412,'Sim','SMJ',166,166,1,1),(6413,'Simbai','SIM',166,166,1,1),(6414,'Simberi Island','NIS',166,166,1,1),(6415,'Singaua','SGB',166,166,1,1),(6416,'Sissano','SIZ',166,166,1,1),(6417,'Siwea','SWE',166,166,1,1),(6418,'Sopu','SPH',166,166,1,1),(6419,'Stockholm','SMP',166,166,1,1),(6420,'Suabi','SBE',166,166,1,1),(6421,'Suki','SKC',166,166,1,1),(6422,'Sule','ULE',166,166,1,1),(6423,'Suria','SUZ',166,166,1,1),(6424,'Tabibuga','TBA',166,166,1,1),(6425,'Tabubil','TBG',166,166,1,1),(6426,'Tadji','TAJ',166,166,1,1),(6427,'Tagula','TGL',166,166,1,1),(6428,'Talasea','TLW',166,166,1,1),(6429,'Tapini','TPI',166,166,1,1),(6430,'Tarabo','TBQ',166,166,1,1),(6431,'Tarakbits','TRJ',166,166,1,1),(6432,'Tari','TIZ',166,166,1,1),(6433,'Taskul','TSK',166,166,1,1),(6434,'Tauta','TUT',166,166,1,1),(6435,'Tawa','TWY',166,166,1,1),(6436,'Tekadu','TKB',166,166,1,1),(6437,'Tekin','TKW',166,166,1,1),(6438,'Telefomin','TFM',166,166,1,1),(6439,'Teptep','TEP',166,166,1,1),(6440,'Terapo','TEO',166,166,1,1),(6441,'Tetabedi','TDB',166,166,1,1),(6442,'Tilfalmin','TFA',166,166,1,1),(6443,'Timbunke','TBE',166,166,1,1),(6444,'Tinboli','TCK',166,166,1,1),(6445,'Tingwon','TIG',166,166,1,1),(6446,'Tol','TLO',166,166,1,1),(6447,'Tonu','TON',166,166,1,1),(6448,'Torokina','TOK',166,166,1,1),(6449,'Tsewi','TSW',166,166,1,1),(6450,'Tsili Tsili','TSI',166,166,1,1),(6451,'Tufi','TFI',166,166,1,1),(6452,'Tumolbil','TLP',166,166,1,1),(6453,'Umba','UMC',166,166,1,1),(6454,'Upiara','UPR',166,166,1,1),(6455,'Uroubi','URU',166,166,1,1),(6456,'Usino','USO',166,166,1,1),(6457,'Uvol','UVO',166,166,1,1),(6458,'Vanimo','VAI',166,166,1,1),(6459,'Vivigani','VIV',166,166,1,1),(6460,'Wabag','WAB',166,166,1,1),(6461,'Wabo','WAO',166,166,1,1),(6462,'Wagau','WGU',166,166,1,1),(6463,'Wakunai','WKN',166,166,1,1),(6464,'Wanigela','AGL',166,166,1,1),(6465,'Wantoat','WTT',166,166,1,1),(6466,'Wanuma','WNU',166,166,1,1),(6467,'Wapenamanda','WBM',166,166,1,1),(6468,'Wapolu','WBC',166,166,1,1),(6469,'Wasu','WSU',166,166,1,1),(6470,'Wasua','WSA',166,166,1,1),(6471,'Wasum','WUM',166,166,1,1),(6472,'Wau','WUG',166,166,1,1),(6473,'Wawoi Falls','WAJ',166,166,1,1),(6474,'Weam','WEP',166,166,1,1),(6475,'Wedau','WED',166,166,1,1),(6476,'Wewak','WWK',166,166,1,1),(6477,'Wipim','WPM',166,166,1,1),(6478,'Witu','WIU',166,166,1,1),(6479,'Woitape','WTP',166,166,1,1),(6480,'Wonenara','WOA',166,166,1,1),(6481,'Wuvulu Is','WUV',166,166,1,1),(6482,'Yalumet','KYX',166,166,1,1),(6483,'Yapsiei','KPE',166,166,1,1),(6484,'Yasuru','KSX',166,166,1,1),(6485,'Yegepa','PGE',166,166,1,1),(6486,'Yellow River','XYR',166,166,1,1),(6487,'Yenkis','YEQ',166,166,1,1),(6488,'Yeva','YVD',166,166,1,1),(6489,'Yongai','KGH',166,166,1,1),(6490,'Yule Island','RKU',166,166,1,1),(6491,'Zenag','ZEN',166,166,1,1),(6492,'\"Concepci&oacute','CIO',167,167,1,1),(6493,'Asuncion','ASU',167,167,1,1),(6494,'Ayolas','AYO',167,167,1,1),(6495,'Ciudad Del Este','AGT',167,167,1,1),(6496,'Encarnacion','ENO',167,167,1,1),(6497,'Filadelfia','FLM',167,167,1,1),(6498,'Mariscal Estigarr','ESG',167,167,1,1),(6499,'Pedro Juan Caball','PJC',167,167,1,1),(6500,'Pilar','PIL',167,167,1,1),(6501,'Vallemi','VMI',167,167,1,1),(6502,'Alerta','ALD',168,168,1,1),(6503,'Andahuaylas','ANS',168,168,1,1),(6504,'Anta','ATA',168,168,1,1),(6505,'Arequipa','AQP',168,168,1,1),(6506,'Ayacucho','AYP',168,168,1,1),(6507,'Bellavista','BLP',168,168,1,1),(6508,'Cajamarca','CJA',168,168,1,1),(6509,'Chachapoyas','CHH',168,168,1,1),(6510,'Chiclayo','CIX',168,168,1,1),(6511,'Chimbote','CHM',168,168,1,1),(6512,'Cuzco','CUZ',168,168,1,1),(6513,'Huanuco','HUU',168,168,1,1),(6514,'Iberia','IBP',168,168,1,1),(6515,'Ilo','ILQ',168,168,1,1),(6516,'Iquitos','IQT',168,168,1,1),(6517,'Jauja','JAU',168,168,1,1),(6518,'Juanjui','JJI',168,168,1,1),(6519,'Juliaca','JUL',168,168,1,1),(6520,'Lima','LIM',168,168,1,1),(6521,'Machu Picchu','MFT',168,168,1,1),(6522,'Moyobamba','MBP',168,168,1,1),(6523,'Pisco','PIO',168,168,1,1),(6524,'Piura','PIU',168,168,1,1),(6525,'Pucallpa','PCL',168,168,1,1),(6526,'Puerto Maldonado','PEM',168,168,1,1),(6527,'Quincemil','UMI',168,168,1,1),(6528,'Rioja','RIJ',168,168,1,1),(6529,'Rodriguez De Men','RIM',168,168,1,1),(6530,'San Juan','SJA',168,168,1,1),(6531,'San Juan Aposento','APE',168,168,1,1),(6532,'Santa Maria','SMG',168,168,1,1),(6533,'Saposoa','SQU',168,168,1,1),(6534,'Shiringayoc','SYC',168,168,1,1),(6535,'Tacna','TCQ',168,168,1,1),(6536,'Talara','TYL',168,168,1,1),(6537,'Tarapoto','TPP',168,168,1,1),(6538,'Tingo Maria','TGI',168,168,1,1),(6539,'Trujillo','TRU',168,168,1,1),(6540,'Tumbes','TBP',168,168,1,1),(6541,'Yurimaguas','YMS',168,168,1,1),(6542,'Alah','AAV',169,169,1,1),(6543,'Bacolod','BCD',169,169,1,1),(6544,'Baganga','BNQ',169,169,1,1),(6545,'Baguio','BAG',169,169,1,1),(6546,'Baler','BQA',169,169,1,1),(6547,'Basco','BSO',169,169,1,1),(6548,'Bislig','BPH',169,169,1,1),(6549,'Busuanga','USU',169,169,1,1),(6550,'Butuan','BXU',169,169,1,1),(6551,'Cagayan De Oro','CGY',169,169,1,1),(6552,'Cagayan De Sulu','CDY',169,169,1,1),(6553,'Calbayog','CYP',169,169,1,1),(6554,'Camiguin','CGM',169,169,1,1),(6555,'Casiguran','CGG',169,169,1,1),(6556,'Catarman','CRM',169,169,1,1),(6557,'Caticlan','MPH',169,169,1,1),(6558,'Cauayan','CYZ',169,169,1,1),(6559,'Cebu','CEB',169,169,1,1),(6560,'Coron','XCN',169,169,1,1),(6561,'Cotabato','CBO',169,169,1,1),(6562,'Culion','CUJ',169,169,1,1),(6563,'Cuyo','CYU',169,169,1,1),(6564,'Daet','DTE',169,169,1,1),(6565,'Davao','DVO',169,169,1,1),(6566,'Del Carmen','IAO',169,169,1,1),(6567,'Dilasag','DSG',169,169,1,1),(6568,'Dipolog','DPL',169,169,1,1),(6569,'Dumaguete','DGT',169,169,1,1),(6570,'El Nido','ENI',169,169,1,1),(6571,'General Santos','GES',169,169,1,1),(6572,'Iligan','IGN',169,169,1,1),(6573,'Iloilo','ILO',169,169,1,1),(6574,'Ipil','IPE',169,169,1,1),(6575,'Jolo','JOL',169,169,1,1),(6576,'Kalibo','KLO',169,169,1,1),(6577,'Laoag','LAO',169,169,1,1),(6578,'Legaspi','LGP',169,169,1,1),(6579,'Lubang','LBX',169,169,1,1),(6580,'Luzon Is Ncp','CRK',169,169,1,1),(6582,'Lwbak','LWA',169,169,1,1),(6583,'Mactan Island','NOP',169,169,1,1),(6584,'Malabang','MLP',169,169,1,1),(6585,'Mamburao','MBO',169,169,1,1),(6586,'Manila','MNL',169,169,1,1),(6587,'Maramag','XMA',169,169,1,1),(6588,'Marinduque','MRQ',169,169,1,1),(6589,'Masbate','MBT',169,169,1,1),(6590,'Mati','MXI',169,169,1,1),(6591,'Naga','WNP',169,169,1,1),(6592,'Ormoc','OMC',169,169,1,1),(6593,'Ozamis City','OZC',169,169,1,1),(6594,'Pagadian','PAG',169,169,1,1),(6595,'Puerto Princesa','PPS',169,169,1,1),(6596,'Roxas City','RXS',169,169,1,1),(6597,'San Fernando','SFE',169,169,1,1),(6598,'San Jose','SJI',169,169,1,1),(6599,'Sanga Sanga','SGS',169,169,1,1),(6600,'Sangley Point','NSP',169,169,1,1),(6601,'Siasi','SSV',169,169,1,1),(6602,'Sicogon Island','ICO',169,169,1,1),(6603,'Siocon','XSO',169,169,1,1),(6604,'Subic Bay','SFS',169,169,1,1),(6605,'Surigao','SUG',169,169,1,1),(6606,'Tablas','TBH',169,169,1,1),(6607,'Tacloban','TAC',169,169,1,1),(6608,'Tagbilaran','TAG',169,169,1,1),(6609,'Tagbita','TGB',169,169,1,1),(6610,'Tandag','TDG',169,169,1,1),(6611,'Tawitawi','TWT',169,169,1,1),(6612,'Tuguegarao','TUG',169,169,1,1),(6613,'Virac','VRC',169,169,1,1),(6614,'Zamboanga','ZAM',169,169,1,1),(6615,'Bialystok','QYY',171,171,1,1),(6616,'Bielsko Biala','QEO',171,171,1,1),(6617,'Bydgoszcz','BZG',171,171,1,1),(6618,'Czestochowa','CZW',171,171,1,1),(6619,'Elblag','ZBG',171,171,1,1),(6620,'Elk','QKD',171,171,1,1),(6621,'Gdansk','GDN',171,171,1,1),(6622,'Gdynia','QYD',171,171,1,1),(6623,'Gizycko','ZYC',171,171,1,1),(6624,'Gliwice','QLC',171,171,1,1),(6625,'Katowice','KTW',171,171,1,1),(6626,'Kielce','QKI',171,171,1,1),(6627,'Kolobrzeg','QJY',171,171,1,1),(6628,'Koszalin','OSZ',171,171,1,1),(6629,'Krakow','KRK',171,171,1,1),(6630,'Lodz','LCJ',171,171,1,1),(6631,'Lomza','QOY',171,171,1,1),(6632,'Lublin','QLU',171,171,1,1),(6633,'Nowy Targ','QWS',171,171,1,1),(6634,'Olsztyn','QYO',171,171,1,1),(6635,'Opole','QPM',171,171,1,1),(6636,'Ostrow Wiekloposk','QDG',171,171,1,1),(6637,'Plock','QPC',171,171,1,1),(6638,'Poznan','POZ',171,171,1,1),(6639,'Radom','QXR',171,171,1,1),(6640,'Rzeszow','RZE',171,171,1,1),(6641,'Stalowa Wola','QXQ',171,171,1,1),(6642,'Suwalki','ZWK',171,171,1,1),(6643,'Szczecin','SZZ',171,171,1,1),(6644,'Szczytno','SZY',171,171,1,1),(6645,'Tarnobrzeg','QEP',171,171,1,1),(6646,'Varsovia','WAW',171,171,1,1),(6647,'Wloclawek','QWK',171,171,1,1),(6648,'Wroclaw','WRO',171,171,1,1),(6649,'Zakopane','QAZ',171,171,1,1),(6650,'Zielona Gora','IEG',171,171,1,1),(6651,'Aveiro','ZAV',172,172,1,1),(6652,'Beja','BYJ',172,172,1,1),(6653,'Braga','BGZ',172,172,1,1),(6654,'Braganca','BGC',172,172,1,1),(6655,'Chaves','CHV',172,172,1,1),(6656,'Coimbra','CBP',172,172,1,1),(6657,'Covilha','COV',172,172,1,1),(6658,'Faro','FAO',172,172,1,1),(6659,'Flores Island','FLW',172,172,1,1),(6660,'Funchal','FNC',172,172,1,1),(6661,'Horta','HOR',172,172,1,1),(6662,'Isla Corvo','CVU',172,172,1,1),(6663,'Isla Graciosa','GRW',172,172,1,1),(6664,'Isla Pico','PIX',172,172,1,1),(6665,'Isla Terceira','TER',172,172,1,1),(6666,'Leiria','QLR',172,172,1,1),(6667,'Lisboa','LIS',172,172,1,1),(6668,'Oporto','OPO',172,172,1,1),(6669,'Ponta Delgada Azores','PDL',172,172,1,1),(6670,'Portimao','PRM',172,172,1,1),(6671,'Porto Santo','PXO',172,172,1,1),(6672,'Ribeira Grande','QEG',172,172,1,1),(6673,'Santa Maria Island','SMA',172,172,1,1),(6674,'Sao Jorge','SJZ',172,172,1,1),(6675,'Setubal','XSZ',172,172,1,1),(6676,'Sines','SIE',172,172,1,1),(6677,'Vila Real','VRL',172,172,1,1),(6678,'Viseu','VSE',172,172,1,1),(6679,'Aguadilla','BQN',173,173,1,1),(6680,'Arecibo','ARE',173,173,1,1),(6681,'Culebra','CPX',173,173,1,1),(6682,'Dorado','DDP',173,173,1,1),(6683,'Fajardo','FAJ',173,173,1,1),(6684,'Humacao','HUC',173,173,1,1),(6685,'Mayaguez','MAZ',173,173,1,1),(6686,'Ponce','PSE',173,173,1,1),(6687,'San Juan','SJU',173,173,1,1),(6689,'Vieques','VQS',173,173,1,1),(6690,'Air Base','XJD',174,174,1,1),(6691,'Doha','XOZ',174,174,1,1),(6693,'Saint Denis Reunion','RUN',175,175,1,1),(6694,'Saint Pierre','ZSE',175,175,1,1),(6695,'Alba Iulia','QAY',176,176,1,1),(6696,'Arad','ARW',176,176,1,1),(6697,'Bacau','BCM',176,176,1,1),(6698,'Baia Mare','BAY',176,176,1,1),(6699,'Bistrita Nasaud','QBY',176,176,1,1),(6700,'Botosani','QDD',176,176,1,1),(6701,'Brasov','XHV',176,176,1,1),(6702,'Bucarest','OTP',176,176,1,1),(6705,'Caransebes','CSB',176,176,1,1),(6706,'Cluj Napoca','CLJ',176,176,1,1),(6707,'Constanta','CND',176,176,1,1),(6708,'Craiova','CRA',176,176,1,1),(6709,'Iasi','IAS',176,176,1,1),(6710,'Lugoj','LGJ',176,176,1,1),(6711,'Oradea','OMR',176,176,1,1),(6712,'Piatra Neamt','QPN',176,176,1,1),(6713,'Ploiesti','QPL',176,176,1,1),(6714,'Resita','QRS',176,176,1,1),(6715,'Satu Mare','SUJ',176,176,1,1),(6716,'Sibiu','SBZ',176,176,1,1),(6717,'Sovata','QSV',176,176,1,1),(6718,'Suceava','SCV',176,176,1,1),(6719,'Timisoara','TSR',176,176,1,1),(6720,'Tirgu Mures','TGM',176,176,1,1),(6721,'Tulcea','TCE',176,176,1,1),(6722,'Abakan','ABA',177,177,1,1),(6723,'Achinsk','ACS',177,177,1,1),(6724,'Adler Sochi','AER',177,177,1,1),(6725,'Aldan','ADH',177,177,1,1),(6726,'Amderma','AMV',177,177,1,1),(6727,'Anadyr','DYR',177,177,1,1),(6728,'Anapa','AAQ',177,177,1,1),(6729,'Arkhangelsk','ARH',177,177,1,1),(6730,'Astrakhan','ASF',177,177,1,1),(6731,'Balakovo','BWO',177,177,1,1),(6732,'Barnaul','BAX',177,177,1,1),(6733,'Belgorod','EGO',177,177,1,1),(6734,'Beloretsk','BCX',177,177,1,1),(6735,'Beloyarsky','EYK',177,177,1,1),(6736,'Berezova','EZV',177,177,1,1),(6737,'Blagoveshchensk','BQS',177,177,1,1),(6738,'Bodaybo','ODO',177,177,1,1),(6739,'Bogorodskoye','BQG',177,177,1,1),(6740,'Bratsk','BTK',177,177,1,1),(6741,'Bryansk','BZK',177,177,1,1),(6742,'Bugulma','UUA',177,177,1,1),(6743,'Chakalovskiy','CKL',177,177,1,1),(6744,'Cheboksary','CSY',177,177,1,1),(6745,'Chelyabinsk','CEK',177,177,1,1),(6746,'Cherepovets','CEE',177,177,1,1),(6747,'Cherskiy','CYX',177,177,1,1),(6748,'Chita','HTA',177,177,1,1),(6749,'Chokurdah','CKH',177,177,1,1),(6750,'Dikson','DKS',177,177,1,1),(6751,'Elista','ESL',177,177,1,1),(6752,'Gelendzhik','GDZ',177,177,1,1),(6753,'Grozny','GRV',177,177,1,1),(6754,'Igarka','IAA',177,177,1,1),(6755,'Igrim','IRM',177,177,1,1),(6756,'Inta','INA',177,177,1,1),(6757,'Irkutsk','IKT',177,177,1,1),(6758,'Iturup Island','BVV',177,177,1,1),(6759,'Ivanovo','IWA',177,177,1,1),(6760,'Izhevsk','IJK',177,177,1,1),(6761,'Joshkar Ola','JOK',177,177,1,1),(6762,'Kaliningrad','KGD',177,177,1,1),(6763,'Kaluga','KLF',177,177,1,1),(6764,'Kazan','KZN',177,177,1,1),(6765,'Kemerovo','KEJ',177,177,1,1),(6766,'Khabarovsk','KHV',177,177,1,1),(6767,'Khanty-Mansiysk','HMA',177,177,1,1),(6768,'Khatanga','HTG',177,177,1,1),(6769,'Kirensk','KCK',177,177,1,1),(6770,'Kirov','KVX',177,177,1,1),(6771,'Kirovsk Apatity','KVK',177,177,1,1),(6772,'Kogalym','KGP',177,177,1,1),(6773,'Komsomolsk Na Amu','KXK',177,177,1,1),(6774,'Kostroma','KMW',177,177,1,1),(6775,'Kotlas','KSZ',177,177,1,1),(6776,'Krasnodar','KRR',177,177,1,1),(6777,'Krasnoyarsk','KJA',177,177,1,1),(6778,'Kurgan','KRO',177,177,1,1),(6779,'Kursk','URS',177,177,1,1),(6780,'Kyzyl','KYZ',177,177,1,1),(6781,'Lensk','ULK',177,177,1,1),(6782,'Lipetsk','LPK',177,177,1,1),(6783,'Magadan','GDX',177,177,1,1),(6784,'Magas','IGT',177,177,1,1),(6785,'Magdagachi','GDG',177,177,1,1),(6786,'Magnitogorsk','MQF',177,177,1,1),(6787,'Makhachkala','MCX',177,177,1,1),(6788,'Mineralnye Vody','MRV',177,177,1,1),(6789,'Mirny','MJZ',177,177,1,1),(6790,'Moscu','SVO',177,177,1,1),(6795,'Murmansk','MMK',177,177,1,1),(6796,'Mys Kamennyy','YMK',177,177,1,1),(6797,'Nadym','NYM',177,177,1,1),(6798,'Nalchik','NAL',177,177,1,1),(6799,'Naryan Mar','NNM',177,177,1,1),(6800,'Neftekamsk','NEF',177,177,1,1),(6801,'Nefteyugansk','NFG',177,177,1,1),(6802,'Neryungri','NER',177,177,1,1),(6803,'Nizhnekamsk','NBC',177,177,1,1),(6804,'Nizhnevartovsk','NJC',177,177,1,1),(6805,'Nizhny Novgorod','GOJ',177,177,1,1),(6806,'Nogliki','NGK',177,177,1,1),(6807,'Norilsk','NSK',177,177,1,1),(6808,'Novgorod','NVR',177,177,1,1),(6809,'Novokuznetsk','NOZ',177,177,1,1),(6810,'Novorossiysk','NOI',177,177,1,1),(6811,'Novosibirsk','OVB',177,177,1,1),(6812,'Novy Urengoy','NUX',177,177,1,1),(6813,'Noyabrsk','NOJ',177,177,1,1),(6814,'Nyagan','NYA',177,177,1,1),(6815,'Okha','OHH',177,177,1,1),(6816,'Okhotsk','OHO',177,177,1,1),(6817,'Oktiabrsky','OKT',177,177,1,1),(6818,'Omsk','OMS',177,177,1,1),(6819,'Orel','OEL',177,177,1,1),(6820,'Orenburg','REN',177,177,1,1),(6821,'Orsk','OSW',177,177,1,1),(6822,'Pechora','PEX',177,177,1,1),(6823,'Penza','PEZ',177,177,1,1),(6824,'Perm','PEE',177,177,1,1),(6825,'Petropavlovsk','PKC',177,177,1,1),(6826,'Petrozavodsk','PES',177,177,1,1),(6827,'Pevek','PWE',177,177,1,1),(6828,'Polyarnyj','PYJ',177,177,1,1),(6829,'Provideniya','PVS',177,177,1,1),(6830,'Pskov','PKV',177,177,1,1),(6831,'Raduzhny','RAT',177,177,1,1),(6832,'Rostov','ROV',177,177,1,1),(6833,'Ryazan','RZN',177,177,1,1),(6834,'Rybinsk','RYB',177,177,1,1),(6835,'Saint Petersburg','LED',177,177,1,1),(6836,'Salehard','SLY',177,177,1,1),(6837,'Samara','KUF',177,177,1,1),(6838,'Saransk','SKX',177,177,1,1),(6839,'Saratov','RTW',177,177,1,1),(6840,'Shakhtersk','EKS',177,177,1,1),(6841,'Smolensk','LNX',177,177,1,1),(6842,'Solovetsky','CSH',177,177,1,1),(6843,'Sovetskaya Gavan','GVN',177,177,1,1),(6844,'Sovetsky','OVS',177,177,1,1),(6845,'Stavropol','STW',177,177,1,1),(6846,'Strezhevoy','SWT',177,177,1,1),(6847,'Surgut','SGC',177,177,1,1),(6848,'Syktyvkar','SCW',177,177,1,1),(6849,'Tambov','TBW',177,177,1,1),(6850,'Tarko-Sale','TQL',177,177,1,1),(6851,'Tiksi','IKS',177,177,1,1),(6852,'Tobolsk','TOX',177,177,1,1),(6853,'Tomsk','TOF',177,177,1,1),(6854,'Tula','TYA',177,177,1,1),(6855,'Turukhansk','THX',177,177,1,1),(6856,'Tver','KLD',177,177,1,1),(6857,'Tynda','TYD',177,177,1,1),(6858,'Tyumen','TJM',177,177,1,1),(6859,'Ufa','UFA',177,177,1,1),(6860,'Ukhta','UCT',177,177,1,1),(6861,'Ulan Ude','UUD',177,177,1,1),(6862,'Ulyanovsk','ULY',177,177,1,1),(6863,'Uray','URJ',177,177,1,1),(6864,'Urengot','UEN',177,177,1,1),(6865,'Usinsk','USK',177,177,1,1),(6866,'Ust Ilimsk','UIK',177,177,1,1),(6867,'Ust Kut','UKX',177,177,1,1),(6868,'Ust-Tsilma','UTS',177,177,1,1),(6869,'Velikiye Luki','VLU',177,177,1,1),(6870,'Veliky Ustyug','VUS',177,177,1,1),(6871,'Vladikavkaz','OGZ',177,177,1,1),(6872,'Vladivostok','VVO',177,177,1,1),(6873,'Volgodonsk','VLK',177,177,1,1),(6874,'Volgograd','VOG',177,177,1,1),(6875,'Vologda','VGD',177,177,1,1),(6876,'Vorkuta','VKT',177,177,1,1),(6877,'Voronezh','VOZ',177,177,1,1),(6878,'Yakutsk','YKS',177,177,1,1),(6879,'Yaroslavl','IAR',177,177,1,1),(6880,'Yekaterinburg','SVX',177,177,1,1),(6881,'Yeniseysk','EIE',177,177,1,1),(6882,'Yerbogachen','ERG',177,177,1,1),(6883,'Yeysk','EIK',177,177,1,1),(6884,'Yuzhno Kurilsk','DEE',177,177,1,1),(6885,'Yuzhno Sakhalinsk','UUS',177,177,1,1),(6886,'Zonalnoe','ZZO',177,177,1,1),(6887,'Butare','BTQ',178,178,1,1),(6888,'Cyangugu','KME',178,178,1,1),(6889,'Gisenyi','GYI',178,178,1,1),(6890,'Kigali','KGL',178,178,1,1),(6891,'Ruhengeri','RHG',178,178,1,1),(6892,'Ascension Island','ASI',179,179,1,1),(6893,'Air Base','XIJ',180,180,1,1),(6894,'Nevis','NEV',180,180,1,1),(6895,'St Kitts','SKB',180,180,1,1),(6896,'Santa Lucia','UVF',181,181,1,1),(6898,'Miquelon','MQC',182,182,1,1),(6899,'St Pierre','FSP',182,182,1,1),(6900,'Canouan Island','CIW',183,183,1,1),(6901,'Mustique Island','MQS',183,183,1,1),(6902,'Palm Island','PLI',183,183,1,1),(6903,'Port Elizabeth','BQU',183,183,1,1),(6904,'St Vincent','SVD',183,183,1,1),(6905,'Union Island','UNI',183,183,1,1),(6906,'Apia','FGI',184,184,1,1),(6908,'Asau','AAU',184,184,1,1),(6909,'Isla Maota Savaii','MXS',184,184,1,1),(6910,'Lalomalava','LAV',184,184,1,1),(6911,'San Marino','SAI',185,185,1,1),(6912,'Porto Alegre','PGP',186,186,1,1),(6913,'Principe Island','PCP',186,186,1,1),(6914,'Sao Tome Is','TMS',186,186,1,1),(6915,'Abha','AHB',187,187,1,1),(6916,'Al Baha','ABT',187,187,1,1),(6917,'Al Kharj','AKH',187,187,1,1),(6918,'Al Ula','ULH',187,187,1,1),(6919,'Arar','RAE',187,187,1,1),(6920,'Bisha','BHH',187,187,1,1),(6921,'Dammam','DMM',187,187,1,1),(6922,'Dawadmi','DWD',187,187,1,1),(6923,'Dhahran','DHA',187,187,1,1),(6924,'Gassim','ELQ',187,187,1,1),(6925,'Gizan','GIZ',187,187,1,1),(6926,'Gurayat','URY',187,187,1,1),(6927,'Hafr Albatin','HBT',187,187,1,1),(6928,'Hail','HAS',187,187,1,1),(6929,'Hofuf','HOF',187,187,1,1),(6930,'Jeddah','JED',187,187,1,1),(6931,'Jouf','AJF',187,187,1,1),(6932,'Jubail','QJB',187,187,1,1),(6933,'Khamis Mushait','KMX',187,187,1,1),(6934,'King Khalid Mil City','KMC',187,187,1,1),(6935,'Madinah','MED',187,187,1,1),(6936,'Majma','MJH',187,187,1,1),(6937,'Makkah','QCA',187,187,1,1),(6938,'Nejran','EAM',187,187,1,1),(6939,'Qaisumah','AQI',187,187,1,1),(6940,'Qatif','QTF',187,187,1,1),(6941,'Rafha','RAH',187,187,1,1),(6942,'Riyadh','RUH',187,187,1,1),(6943,'Sharurah','SHW',187,187,1,1),(6944,'Sulayel','SLF',187,187,1,1),(6945,'Tabuk','TUU',187,187,1,1),(6946,'Taif','TIF',187,187,1,1),(6947,'Turaif','TUI',187,187,1,1),(6948,'Unayzah','UZH',187,187,1,1),(6949,'Wadi Ad Dawaser','WAE',187,187,1,1),(6950,'Wedjh','EJH',187,187,1,1),(6951,'Yanbu Al Bahr','YNB',187,187,1,1),(6952,'Zilfi','ZUL',187,187,1,1),(6953,'Bakel','BXE',188,188,1,1),(6954,'Cap Skiring','CSK',188,188,1,1),(6955,'Dakar','DKR',188,188,1,1),(6956,'Kaolack','KLC',188,188,1,1),(6957,'Kedougou','KGG',188,188,1,1),(6958,'Kolda','KDA',188,188,1,1),(6959,'Matam','MAX',188,188,1,1),(6960,'Niokolo-Koba','NIK',188,188,1,1),(6961,'Podor','POD',188,188,1,1),(6962,'Richard-Toll','RDT',188,188,1,1),(6963,'Simenti','SMY',188,188,1,1),(6964,'St-Louis','XLS',188,188,1,1),(6965,'Tambacounda','TUD',188,188,1,1),(6966,'Ziguinchor','ZIG',188,188,1,1),(6967,'Bird Island','BDI',189,189,1,1),(6968,'Denis Island','DEI',189,189,1,1),(6969,'Desroches','DES',189,189,1,1),(6970,'Fregate Is','FRK',189,189,1,1),(6971,'Mahe Island','SEZ',189,189,1,1),(6972,'Praslin Island','PRI',189,189,1,1),(6973,'Bonthe','BTE',190,190,1,1),(6974,'Daru','DSL',190,190,1,1),(6975,'Freetown','FNA',190,190,1,1),(6976,'Gbangbatok','GBK',190,190,1,1),(6977,'Kabala','KBA',190,190,1,1),(6978,'Kenema','KEN',190,190,1,1),(6979,'Sierra Leone','SRK',190,190,1,1),(6980,'Yengema','WYE',190,190,1,1),(6981,'Singapore','XSP',191,191,1,1),(6984,'Tengah','TGA',191,191,1,1),(6985,'Bratislava','BTS',192,192,1,1),(6986,'Kosice','KSC',192,192,1,1),(6987,'Lucenec','LUE',192,192,1,1),(6988,'Piestany','PZY',192,192,1,1),(6989,'Poprad','TAT',192,192,1,1),(6990,'Presov','POV',192,192,1,1),(6991,'Sliac','SLD',192,192,1,1),(6992,'Zilina','ILZ',192,192,1,1),(6993,'Ljubljana','LJU',193,193,1,1),(6994,'Maribor','MBX',193,193,1,1),(6995,'Portoroz','POW',193,193,1,1),(6996,'Afutara','AFT',194,194,1,1),(6997,'Anuha Island Resort','ANH',194,194,1,1),(6998,'Arona','RNA',194,194,1,1),(6999,'Atoifi','ATD',194,194,1,1),(7000,'Auki','AKS',194,194,1,1),(7001,'Avu Avu','AVU',194,194,1,1),(7002,'Balalae','BAS',194,194,1,1),(7003,'Barakoma','VEV',194,194,1,1),(7004,'Barora','RRI',194,194,1,1),(7005,'Batuna','BPF',194,194,1,1),(7006,'Bellona','BNY',194,194,1,1),(7007,'Choiseul Bay','CHY',194,194,1,1),(7008,'Fera Island','FRE',194,194,1,1),(7009,'Gatokae','GTA',194,194,1,1),(7010,'Geva','GEF',194,194,1,1),(7011,'Gizo','GZO',194,194,1,1),(7012,'Guadalcanal','GSI',194,194,1,1),(7013,'Honiara','HIR',194,194,1,1),(7014,'Jajao','JJA',194,194,1,1),(7015,'Kagau','KGE',194,194,1,1),(7016,'Kirakira','IRA',194,194,1,1),(7017,'Kukundu','KUE',194,194,1,1),(7018,'Kwai Harbour','KWR',194,194,1,1),(7019,'Kwailabesi','KWS',194,194,1,1),(7020,'Marau Sound','RUS',194,194,1,1),(7021,'Mbambanakira','MBU',194,194,1,1),(7022,'Mono','MNY',194,194,1,1),(7023,'Munda','MUA',194,194,1,1),(7024,'Onepusu','ONE',194,194,1,1),(7025,'Parasi','PRS',194,194,1,1),(7026,'Ramata','RBV',194,194,1,1),(7027,'Rennell','RNL',194,194,1,1),(7028,'Ringi Cove','RIN',194,194,1,1),(7029,'Santa Ana','NNB',194,194,1,1),(7030,'Santa Cruz Is','SCZ',194,194,1,1),(7031,'Savo','SVY',194,194,1,1),(7032,'Sege','EGM',194,194,1,1),(7033,'Suavanao','VAO',194,194,1,1),(7034,'Tarapaina','TAA',194,194,1,1),(7035,'Tulagi Island','TLG',194,194,1,1),(7036,'Viru','VIU',194,194,1,1),(7037,'Yandina','XYA',194,194,1,1),(7038,'Alula','ALU',195,195,1,1),(7039,'Baidoa','BIB',195,195,1,1),(7040,'Bardera','BSY',195,195,1,1),(7041,'Berbera','BBO',195,195,1,1),(7042,'Borama','BXX',195,195,1,1),(7043,'Bossaso','BSA',195,195,1,1),(7044,'Burao','BUO',195,195,1,1),(7045,'Candala','CXN',195,195,1,1),(7046,'Eil','HCM',195,195,1,1),(7047,'Erigavo','ERA',195,195,1,1),(7048,'Galcaio','GLK',195,195,1,1),(7049,'Garbaharey','GBM',195,195,1,1),(7050,'Gardo','GSR',195,195,1,1),(7051,'Garoe','GGR',195,195,1,1),(7052,'Hargeisa','HGA',195,195,1,1),(7053,'Kismayu','KMU',195,195,1,1),(7054,'Las Khoreh','LKR',195,195,1,1),(7055,'Lugh Ganane','LGX',195,195,1,1),(7056,'Mogadishu','MGQ',195,195,1,1),(7057,'Obbia','CMO',195,195,1,1),(7058,'Scusciuban','CMS',195,195,1,1),(7059,'Aggeneys','AGZ',196,196,1,1),(7060,'Alexander Bay','ALJ',196,196,1,1),(7061,'Alldays','ADY',196,196,1,1),(7062,'Bisho','BIY',196,196,1,1),(7063,'Bloemfontein','BFN',196,196,1,1),(7064,'Butterworth','UTE',196,196,1,1),(7065,'Cradock','CDO',196,196,1,1),(7066,'Dukuduk','DUK',196,196,1,1),(7067,'Durban','VIR',196,196,1,1),(7069,'El Cabo','CPT',196,196,1,1),(7070,'Ellisras','ELL',196,196,1,1),(7071,'Empangeni','EMG',196,196,1,1),(7072,'Ficksburg','FCB',196,196,1,1),(7073,'George','GRJ',196,196,1,1),(7074,'Giyani','GIY',196,196,1,1),(7075,'Harrismith','HRS',196,196,1,1),(7076,'Hazyview','HZV',196,196,1,1),(7077,'Hluhluwe','HLW',196,196,1,1),(7078,'Hoedspruit Transv','HDS',196,196,1,1),(7079,'Inyati','INY',196,196,1,1),(7080,'Johannesburgo','QRA',196,196,1,1),(7084,'Khoka Moya','KHO',196,196,1,1),(7085,'Kimberley','KIM',196,196,1,1),(7086,'Kleinzee','KLZ',196,196,1,1),(7087,'Klerksdorp','KXE',196,196,1,1),(7088,'Koinghaas','KIG',196,196,1,1),(7089,'Komatipoort','KOF',196,196,1,1),(7090,'Kruger National P','QKP',196,196,1,1),(7091,'Kuruman','KMH',196,196,1,1),(7092,'Ladysmith','LAY',196,196,1,1),(7093,'Lime Acres','LMR',196,196,1,1),(7094,'Londolozi','LDZ',196,196,1,1),(7095,'Londres Del Este','ELS',196,196,1,1),(7096,'Louis Trichardt','LCD',196,196,1,1),(7097,'Lusikisiki','LUJ',196,196,1,1),(7098,'Mala Mala','AAM',196,196,1,1),(7099,'Malelane','LLE',196,196,1,1),(7100,'Margate','MGH',196,196,1,1),(7101,'Messina','MEZ',196,196,1,1),(7102,'Mkambati','MBM',196,196,1,1),(7103,'Mkuze','MZQ',196,196,1,1),(7104,'Mmabatho','MBD',196,196,1,1),(7105,'Mossel Bay','MZY',196,196,1,1),(7106,'Motswari','MWR',196,196,1,1),(7107,'Mzamba (Wild Coas','MZF',196,196,1,1),(7108,'Nelspruit','NLP',196,196,1,1),(7110,'Newcastle','NCS',196,196,1,1),(7111,'Ngala','NGL',196,196,1,1),(7112,'Oudtshoorn','OUH',196,196,1,1),(7113,'Phalaborwa','PHW',196,196,1,1),(7114,'Pietermaritzburg','PZB',196,196,1,1),(7115,'Plettenberg Bay','PBZ',196,196,1,1),(7116,'Polokwane','PTG',196,196,1,1),(7117,'Port Alfred','AFD',196,196,1,1),(7118,'Port Elizabeth','PLZ',196,196,1,1),(7119,'Port Saint Johns','JOH',196,196,1,1),(7120,'Pretoria','PRY',196,196,1,1),(7121,'Prieska','PRK',196,196,1,1),(7122,'Queenstown','UTW',196,196,1,1),(7123,'Reivilo','RVO',196,196,1,1),(7124,'Richards Bay','RCB',196,196,1,1),(7125,'Robertson','ROD',196,196,1,1),(7126,'Sabi Sabi','GSS',196,196,1,1),(7127,'Saldanha Bay','SDB',196,196,1,1),(7128,'Secunda','ZEC',196,196,1,1),(7129,'Sishen','SIS',196,196,1,1),(7130,'Skukuza','SZK',196,196,1,1),(7131,'Springbok','SBU',196,196,1,1),(7132,'Sun City','NTY',196,196,1,1),(7133,'Tanda Tula','TDT',196,196,1,1),(7134,'Thaba Nchu','TCU',196,196,1,1),(7135,'Thohoyandou','THY',196,196,1,1),(7136,'Tshipise','TSD',196,196,1,1),(7137,'Tzaneen','LTA',196,196,1,1),(7138,'Ulundi','ULD',196,196,1,1),(7139,'Ulusaba','ULX',196,196,1,1),(7140,'Umtata','UTT',196,196,1,1),(7141,'Upington','UTN',196,196,1,1),(7142,'Vredendal','VRE',196,196,1,1),(7143,'Vryburg','VRU',196,196,1,1),(7144,'Vryheid','VYD',196,196,1,1),(7145,'Welkom','WEL',196,196,1,1),(7146,'Andong','QDY',198,198,1,1),(7147,'Anyang','QYA',198,198,1,1),(7148,'Busan','PUS',198,198,1,1),(7149,'Cheong Ju','CJJ',198,198,1,1),(7150,'Chinhae','CHF',198,198,1,1),(7151,'Chun Chon City','QUN',198,198,1,1),(7152,'Chung Mu City','QUU',198,198,1,1),(7153,'Daegu','TAE',198,198,1,1),(7154,'Gumi City','QKM',198,198,1,1),(7155,'Gunsan','KUV',198,198,1,1),(7156,'Gwangju','KWJ',198,198,1,1),(7157,'Incheon','JCN',198,198,1,1),(7158,'Jeju','CJU',198,198,1,1),(7159,'Jeonju','CHN',198,198,1,1),(7160,'Jinju','HIN',198,198,1,1),(7161,'Kangnung','KAG',198,198,1,1),(7162,'Koyang','QYK',198,198,1,1),(7163,'Masan','QMS',198,198,1,1),(7164,'Mokpo','MPK',198,198,1,1),(7165,'Muan','MWX',198,198,1,1),(7166,'Osan','OSN',198,198,1,1),(7167,'Pohang','KPO',198,198,1,1),(7168,'Puchon City','QJP',198,198,1,1),(7169,'Samchok','SUK',198,198,1,1),(7170,'Seul','ICN',198,198,1,1),(7174,'Sokcho','SHO',198,198,1,1),(7175,'Su Won Ciudad','SWU',198,198,1,1),(7176,'Sunchon','SYS',198,198,1,1),(7177,'Taedok','QET',198,198,1,1),(7178,'Taejon','QTW',198,198,1,1),(7179,'Uijongbu','QUJ',198,198,1,1),(7180,'Ulsan','USN',198,198,1,1),(7181,'Won Ju','WJU',198,198,1,1),(7183,'Yangyang','YNY',198,198,1,1),(7184,'Yechon','YEC',198,198,1,1),(7185,'Yeosu','RSU',198,198,1,1),(7186,'Albacete','ABC',199,199,1,1),(7187,'Algeciras','AEI',199,199,1,1),(7188,'Alicante','ALC',199,199,1,1),(7189,'Almeria','LEI',199,199,1,1),(7190,'Badajoz','BJZ',199,199,1,1),(7191,'Barcelona','BCN',199,199,1,1),(7192,'Bilbao','BIO',199,199,1,1),(7193,'Bobadilla','OZI',199,199,1,1),(7194,'Burgos','RGS',199,199,1,1),(7195,'Caceres','QUQ',199,199,1,1),(7196,'Cadiz','CDZ',199,199,1,1),(7197,'Cartagena','XUF',199,199,1,1),(7198,'Ceuta','JCU',199,199,1,1),(7199,'Ciudad Real','CQM',199,199,1,1),(7200,'Ciudadela','QIU',199,199,1,1),(7201,'Cordoba','ODB',199,199,1,1),(7202,'Corralejo','QFU',199,199,1,1),(7203,'Fuengirola','FGR',199,199,1,1),(7204,'Fuerteventura','FUE',199,199,1,1),(7205,'Gijon','QIJ',199,199,1,1),(7206,'Girona','GRO',199,199,1,1),(7207,'Granada','GRX',199,199,1,1),(7208,'Huelva','HEV',199,199,1,1),(7209,'Huesca','HSK',199,199,1,1),(7210,'Ibiza','IBZ',199,199,1,1),(7211,'Jerez','XRY',199,199,1,1),(7212,'La Coruña','LCG',199,199,1,1),(7213,'La Junquera','QJR',199,199,1,1),(7214,'La Munoza','QLM',199,199,1,1),(7215,'La Palma del Condado','NDO',199,199,1,1),(7216,'Lanzarote','ACE',199,199,1,1),(7217,'Las Palmas','LPA',199,199,1,1),(7218,'Leon','LEN',199,199,1,1),(7219,'Lleida','ILD',199,199,1,1),(7220,'Logroño','RJL',199,199,1,1),(7221,'Los Cristianos','QCI',199,199,1,1),(7222,'Madrid','TOJ',199,199,1,1),(7224,'Malaga','AGP',199,199,1,1),(7225,'Marbella','QRL',199,199,1,1),(7226,'Melilla','MLN',199,199,1,1),(7227,'Menorca','MAH',199,199,1,1),(7228,'Merida','QWX',199,199,1,1),(7229,'Montilla','OZU',199,199,1,1),(7230,'Moron','OZP',199,199,1,1),(7231,'Murcia','MJV',199,199,1,1),(7232,'Navalmoral De La Mata','QWW',199,199,1,1),(7233,'Oviedo','OVD',199,199,1,1),(7234,'Palma de Mallorca','PMI',199,199,1,1),(7235,'Pamplona','PNA',199,199,1,1),(7236,'Playa Blanca','QLY',199,199,1,1),(7237,'Puente Genil','GEN',199,199,1,1),(7238,'Puerto de la Cruz','UPC',199,199,1,1),(7239,'Puerto De La Luz','QUZ',199,199,1,1),(7240,'Puerto de Santa Maria','PXS',199,199,1,1),(7241,'Puertollano','UER',199,199,1,1),(7242,'Reus','REU',199,199,1,1),(7243,'Ronda','RRA',199,199,1,1),(7244,'Rota','ROZ',199,199,1,1),(7245,'Sabadell','QSA',199,199,1,1),(7246,'Salamanca','SLM',199,199,1,1),(7247,'San Fernando','FES',199,199,1,1),(7248,'San Pablo','SPO',199,199,1,1),(7249,'San Pedro Alcantara','ZRC',199,199,1,1),(7250,'San Sebastian','EAS',199,199,1,1),(7251,'San Sebastian Gomera','GMZ',199,199,1,1),(7252,'San Sebastinan de la Gomera','QGZ',199,199,1,1),(7253,'Santa Cruz de la Palma','SPC',199,199,1,1),(7254,'Santander','SDR',199,199,1,1),(7255,'Santiago de Compostela','SCQ',199,199,1,1),(7256,'Segovia','XOU',199,199,1,1),(7257,'Seo De Urgel','LEU',199,199,1,1),(7258,'Sevilla','SVQ',199,199,1,1),(7259,'Talavera De La Reina','QWT',199,199,1,1),(7260,'Tarragona','QGN',199,199,1,1),(7261,'Tenerife','TFS',199,199,1,1),(7264,'Toledo','XTJ',199,199,1,1),(7265,'Torremolinos','UTL',199,199,1,1),(7266,'Tudela','EEL',199,199,1,1),(7267,'Valencia','VLC',199,199,1,1),(7268,'Valladolid','VLL',199,199,1,1),(7269,'Valverde','VDE',199,199,1,1),(7270,'Vigo','VGO',199,199,1,1),(7271,'Vitoria','VIT',199,199,1,1),(7272,'Zaragoza','ZAZ',199,199,1,1),(7273,'Ampara','ADP',200,200,1,1),(7274,'Batticaloa','BTC',200,200,1,1),(7275,'Colombo','CMB',200,200,1,1),(7277,'Gal Oya','GOY',200,200,1,1),(7278,'Jaffna','JAF',200,200,1,1),(7279,'Minneriya','MNH',200,200,1,1),(7280,'Tissa Tank','TTW',200,200,1,1),(7281,'Trincomalee','TRR',200,200,1,1),(7282,'Ad Dabbah','AAD',201,201,1,1),(7283,'Atbara','ATB',201,201,1,1),(7284,'Damazin','RSS',201,201,1,1),(7285,'Dinder','DNX',201,201,1,1),(7286,'Dongola','DOG',201,201,1,1),(7287,'El Debba','EDB',201,201,1,1),(7288,'El Fasher','ELF',201,201,1,1),(7289,'El Obeid','EBD',201,201,1,1),(7290,'En Nahud','NUD',201,201,1,1),(7291,'Gedaref','GSU',201,201,1,1),(7292,'Geneina','EGN',201,201,1,1),(7293,'Jartun','KRT',201,201,1,1),(7294,'Juba','JUB',201,201,1,1),(7295,'Kadugli','KDX',201,201,1,1),(7296,'Kassala','KSL',201,201,1,1),(7297,'Khashm El Girba','GBU',201,201,1,1),(7298,'Kosti','KST',201,201,1,1),(7299,'Malakal','MAK',201,201,1,1),(7300,'Merowe','MWE',201,201,1,1),(7301,'New Halfa','NHF',201,201,1,1),(7302,'Nyala','UYL',201,201,1,1),(7303,'Port Sudan','PZU',201,201,1,1),(7304,'Wad Madani','DNI',201,201,1,1),(7305,'Wadi Halfa','WHF',201,201,1,1),(7306,'Wau','WUU',201,201,1,1),(7307,'Albina','ABN',202,202,1,1),(7308,'Awaradam','AAJ',202,202,1,1),(7309,'Botopasie','BTO',202,202,1,1),(7310,'Djoemoe','DOE',202,202,1,1),(7311,'Drietabbetje','DRJ',202,202,1,1),(7312,'Kasikasima','KCB',202,202,1,1),(7313,'Ladouanie','LDO',202,202,1,1),(7314,'Moengo','MOJ',202,202,1,1),(7315,'Nieuw Nickerie','ICK',202,202,1,1),(7316,'Paloemeu','OEM',202,202,1,1),(7317,'Paramaribo','PBM',202,202,1,1),(7319,'Stoelmans Eiland','SMZ',202,202,1,1),(7320,'Totness','TOT',202,202,1,1),(7321,'Wageningen','AGI',202,202,1,1),(7322,'Washabo','WSO',202,202,1,1),(7323,'Manzini','MTS',204,204,1,1),(7324,'Mbabane','QMN',204,204,1,1),(7325,'Alvesta','XXA',205,205,1,1),(7326,'Angelholm/Helsingborg','AGH',205,205,1,1),(7327,'Arboga','XXT',205,205,1,1),(7328,'Arvidsjaur','AJR',205,205,1,1),(7329,'Arvika','XYY',205,205,1,1),(7330,'Avesta','XYP',205,205,1,1),(7331,'Borlange/Falun','BLE',205,205,1,1),(7332,'Degerfors','XXD',205,205,1,1),(7333,'Enkoping','XWQ',205,205,1,1),(7334,'Eskilstuna','EKT',205,205,1,1),(7335,'Estocolmo','BMA',205,205,1,1),(7340,'Falkenberg','XYM',205,205,1,1),(7341,'Falkoping','XYF',205,205,1,1),(7342,'Flemingsberg','XEW',205,205,1,1),(7343,'Flen','XYI',205,205,1,1),(7344,'Gallivare','GEV',205,205,1,1),(7345,'Gavle','GVX',205,205,1,1),(7346,'Goteborg','GSE',205,205,1,1),(7348,'Hagfors','HFS',205,205,1,1),(7349,'Hallsberg','XWM',205,205,1,1),(7350,'Halmstad','HAD',205,205,1,1),(7351,'Hassleholm','XWP',205,205,1,1),(7352,'Hedemora','XXU',205,205,1,1),(7353,'Hemavan/Tarnaby','HMV',205,205,1,1),(7354,'Herrljunga','XYC',205,205,1,1),(7355,'Hudiksvall','HUV',205,205,1,1),(7356,'Hultsfred/Vimmerby','HLF',205,205,1,1),(7357,'Idre','IDB',205,205,1,1),(7358,'Jonkoping','JKG',205,205,1,1),(7359,'Kalmar','KLR',205,205,1,1),(7360,'Karlshamn','XYO',205,205,1,1),(7361,'Karlskoga','KSK',205,205,1,1),(7362,'Karlstad','KSD',205,205,1,1),(7363,'Katrineholm','XXK',205,205,1,1),(7364,'Kil','XXN',205,205,1,1),(7365,'Kiruna','KRN',205,205,1,1),(7366,'Koping','XXI',205,205,1,1),(7367,'Kramfors/Solleftea','KRF',205,205,1,1),(7368,'Kristianstad','KID',205,205,1,1),(7369,'Kristinehamn','XYN',205,205,1,1),(7370,'Kumla','XXV',205,205,1,1),(7371,'Landskrona','JLD',205,205,1,1),(7372,'Leksand','XXO',205,205,1,1),(7373,'Lidkoping','LDK',205,205,1,1),(7374,'Linkoping','LPI',205,205,1,1),(7375,'Lulea','LLA',205,205,1,1),(7376,'Lund','XGC',205,205,1,1),(7377,'Lycksele','LYC',205,205,1,1),(7378,'Malmo','MMX',205,205,1,1),(7380,'Mjolby','XXM',205,205,1,1),(7381,'Mora','MXX',205,205,1,1),(7382,'Nassjo','XWX',205,205,1,1),(7383,'Norrkoping','NRK',205,205,1,1),(7384,'Nykoping','XWZ',205,205,1,1),(7385,'Orebro','ORB',205,205,1,1),(7386,'Ornskoldsvik','OER',205,205,1,1),(7387,'Oskarshamn','OSK',205,205,1,1),(7388,'Ostersund Are','OSD',205,205,1,1),(7389,'Pajala','PJA',205,205,1,1),(7390,'Ronneby Karlskrona','RNB',205,205,1,1),(7391,'Sala','XYX',205,205,1,1),(7392,'Sjuntorp','SJ1',205,205,1,1),(7393,'Skelleftea','SFT',205,205,1,1),(7394,'Skovde','KVB',205,205,1,1),(7395,'Soderhamn','SOO',205,205,1,1),(7396,'Sodertalje','JSO',205,205,1,1),(7397,'Solvesborg','XYU',205,205,1,1),(7398,'Storuman','SQO',205,205,1,1),(7399,'Strangnas','XFH',205,205,1,1),(7400,'Sundsvall/Harnosand','SDL',205,205,1,1),(7401,'Sveg','EVG',205,205,1,1),(7402,'Tierp','XFU',205,205,1,1),(7403,'Torsby','TYF',205,205,1,1),(7404,'Trollhattan/Vanersborg','THN',205,205,1,1),(7405,'Umea','UME',205,205,1,1),(7406,'Uppsala C','QYX',205,205,1,1),(7407,'Varberg','XWV',205,205,1,1),(7408,'Vastervik','VVK',205,205,1,1),(7409,'Vaxjo','VXO',205,205,1,1),(7410,'Vilhelmina','VHM',205,205,1,1),(7411,'Visby','VBY',205,205,1,1),(7412,'Aarau','ZDA',206,206,1,1),(7413,'Adelboden','ZDB',206,206,1,1),(7414,'Aigle','ZDC',206,206,1,1),(7415,'Altenrhein','ACH',206,206,1,1),(7416,'Appenzell','ZAP',206,206,1,1),(7417,'Arbon','ZDD',206,206,1,1),(7418,'Arosa','ZDE',206,206,1,1),(7419,'Baden','ZDG',206,206,1,1),(7420,'Bellinzona','ZDI',206,206,1,1),(7421,'Berna','BRN',206,206,1,1),(7422,'Biel','ZDK',206,206,1,1),(7423,'Brig','ZDL',206,206,1,1),(7424,'Buchs Sg','ZDO',206,206,1,1),(7425,'Burgdorf','ZDP',206,206,1,1),(7426,'Champery','ZDQ',206,206,1,1),(7427,'Chateau D Oex','ZDR',206,206,1,1),(7428,'Chiasso','ZDS',206,206,1,1),(7429,'Chur','ZDT',206,206,1,1),(7430,'Davos','ZDV',206,206,1,1),(7431,'Delemont','ZDW',206,206,1,1),(7432,'Dietikon','ZDX',206,206,1,1),(7433,'Einsiedeln','ZDZ',206,206,1,1),(7434,'Embrach','QEQ',206,206,1,1),(7435,'Emmen','EML',206,206,1,1),(7436,'Engelberg','ZHB',206,206,1,1),(7437,'Fluelen','ZHD',206,206,1,1),(7438,'Frauenfeld','ZHE',206,206,1,1),(7439,'Fribourg','ZHF',206,206,1,1),(7440,'Ginebra','GVA',206,206,1,1),(7441,'Glarus','ZHG',206,206,1,1),(7442,'Gossau Sg','ZHH',206,206,1,1),(7443,'Grenchen Sued','ZHI',206,206,1,1),(7444,'Grindelwald','ZHJ',206,206,1,1),(7445,'Gstaad','ZHK',206,206,1,1),(7446,'Heerbrugg','ZHL',206,206,1,1),(7447,'Herzogenbuchsee','ZHN',206,206,1,1),(7448,'Interlaken Ost','ZIN',206,206,1,1),(7449,'Kandersteg','ZHR',206,206,1,1),(7450,'Klosters','ZHS',206,206,1,1),(7451,'Kreuzlingen','ZHU',206,206,1,1),(7452,'La Chaux De Fonds','ZHV',206,206,1,1),(7453,'Langenthal','ZHW',206,206,1,1),(7454,'Lausanne','QLS',206,206,1,1),(7455,'Le Locle','ZJA',206,206,1,1),(7456,'Lenzburg','ZJC',206,206,1,1),(7457,'Lenzerheide Lai','ZJD',206,206,1,1),(7458,'Locarno','ZJI',206,206,1,1),(7459,'Lucerne','QLJ',206,206,1,1),(7460,'Lugano','LUG',206,206,1,1),(7461,'Lyss','ZJL',206,206,1,1),(7462,'Martigny','ZJM',206,206,1,1),(7463,'Montreux','ZJP',206,206,1,1),(7464,'Morges','ZJQ',206,206,1,1),(7465,'Neuchatel','QNC',206,206,1,1),(7466,'Nyon','ZRN',206,206,1,1),(7467,'Olten','ZJU',206,206,1,1),(7468,'Pontresina','ZJV',206,206,1,1),(7469,'Rapperswil','ZJW',206,206,1,1),(7470,'Rorschach','ZJZ',206,206,1,1),(7471,'Saas Fee','ZKI',206,206,1,1),(7472,'Saint Gallen','QGL',206,206,1,1),(7473,'Saint Margrethen','ZKF',206,206,1,1),(7474,'Sargans','ZKA',206,206,1,1),(7475,'Sarnen','ZKC',206,206,1,1),(7476,'Schaffhausen','ZKJ',206,206,1,1),(7477,'Schwyz','ZKK',206,206,1,1),(7478,'Sierre','ZKO',206,206,1,1),(7479,'Sion','SIR',206,206,1,1),(7480,'Solothurn','ZKS',206,206,1,1),(7481,'St  Moritz','ZKH',206,206,1,1),(7482,'St Moritz','SMV',206,206,1,1),(7483,'Sursee','ZKU',206,206,1,1),(7484,'Thalwil','ZKV',206,206,1,1),(7485,'Thun','ZTK',206,206,1,1),(7486,'Uzwil','ZKX',206,206,1,1),(7487,'Verbier','ZKY',206,206,1,1),(7488,'Vevey Bvb','ZKZ',206,206,1,1),(7489,'Villars Bvb','ZLA',206,206,1,1),(7490,'Visp','ZLB',206,206,1,1),(7491,'Waedenswil','ZLC',206,206,1,1),(7492,'Weinfelden','ZLD',206,206,1,1),(7493,'Wengen','ZLE',206,206,1,1),(7494,'Wettingen','ZLF',206,206,1,1),(7495,'Wetzikon','ZKW',206,206,1,1),(7496,'Wil','ZLH',206,206,1,1),(7497,'Winterthur','ZLI',206,206,1,1),(7498,'Yverdon les Bains','ZLJ',206,206,1,1),(7499,'Zermatt','QZB',206,206,1,1),(7500,'Zofingen','ZLL',206,206,1,1),(7501,'Zug','ZLM',206,206,1,1),(7502,'Zurich','ZRH',206,206,1,1),(7503,'Al Thaurah','SOR',207,207,1,1),(7504,'Aleppo','ALP',207,207,1,1),(7505,'As Suwayda','QSW',207,207,1,1),(7506,'Damasco','DAM',207,207,1,1),(7507,'Daraa','QDR',207,207,1,1),(7508,'Deir Ez Zor','DEZ',207,207,1,1),(7509,'Hama','QHM',207,207,1,1),(7510,'Homs','QHS',207,207,1,1),(7511,'Kameshly','KAC',207,207,1,1),(7512,'Latakia','LTK',207,207,1,1),(7513,'Palmyra','PMS',207,207,1,1),(7514,'Tartus','QTR',207,207,1,1),(7515,'Chi Mei','CMJ',208,208,1,1),(7516,'Chiayi','CYI',208,208,1,1),(7517,'Green Island','GNI',208,208,1,1),(7518,'Hengchun','HCN',208,208,1,1),(7519,'Hsinchu','HSZ',208,208,1,1),(7520,'Hualien','HUN',208,208,1,1),(7521,'Kaohsiung','KHH',208,208,1,1),(7522,'Kinmen','KNH',208,208,1,1),(7523,'Lishan','LHN',208,208,1,1),(7524,'Makung','MZG',208,208,1,1),(7525,'Matsu','MFK',208,208,1,1),(7526,'Orchid Island','KYD',208,208,1,1),(7527,'Pingtung','PIF',208,208,1,1),(7528,'Sun Moon Lake','SMT',208,208,1,1),(7529,'Taichung','TXG',208,208,1,1),(7530,'Tainan','TNN',208,208,1,1),(7531,'Taipei','TSA',208,208,1,1),(7533,'Taitung','TTT',208,208,1,1),(7534,'Wonan','WOT',208,208,1,1),(7535,'Dushanbe','DYU',209,209,1,1),(7536,'Khudzand','LBD',209,209,1,1),(7537,'Kulyab','TJU',209,209,1,1),(7538,'Qurghonteppa','KQT',209,209,1,1),(7539,'Arusha','ARK',210,210,1,1),(7540,'Bukoba','BKZ',210,210,1,1),(7541,'Dar Es Salaam','DAR',210,210,1,1),(7542,'Dodoma','DOD',210,210,1,1),(7543,'Geita','GIT',210,210,1,1),(7544,'Iringa','IRI',210,210,1,1),(7545,'Kigoma','TKQ',210,210,1,1),(7546,'Kilimanjaro','JRO',210,210,1,1),(7547,'Kilwa','KIY',210,210,1,1),(7548,'Lake Manyara','LKY',210,210,1,1),(7549,'Lindi','LDI',210,210,1,1),(7550,'Lushoto','LUY',210,210,1,1),(7551,'Mafia','MFA',210,210,1,1),(7552,'Masasi','XMI',210,210,1,1),(7553,'Mbeya','MBI',210,210,1,1),(7554,'Moshi','QSI',210,210,1,1),(7555,'Mtwara','MYW',210,210,1,1),(7556,'Musoma','MUZ',210,210,1,1),(7557,'Mwadui','MWN',210,210,1,1),(7558,'Mwanza','MWZ',210,210,1,1),(7559,'Nachingwea','NCH',210,210,1,1),(7560,'Njombe','JOM',210,210,1,1),(7561,'Pemba','PMA',210,210,1,1),(7562,'Seronera','SEU',210,210,1,1),(7563,'Shinyanga','SHY',210,210,1,1),(7564,'Songea','SGX',210,210,1,1),(7565,'Sumbawanga','SUT',210,210,1,1),(7566,'Tabora','TBO',210,210,1,1),(7567,'Tanga','TGT',210,210,1,1),(7568,'Zanzibar','ZNZ',210,210,1,1),(7569,'Ban Mak Khaen','BAO',211,211,1,1),(7570,'Bangkok','DMK',211,211,1,1),(7572,'Buri Ram','BFV',211,211,1,1),(7573,'Chiang Mai','CNX',211,211,1,1),(7574,'Chiang Rai','CEI',211,211,1,1),(7575,'Chonburi','QHI',211,211,1,1),(7576,'Chumphon','CJM',211,211,1,1),(7577,'Hat Yai','HDY',211,211,1,1),(7578,'Hua Hin','HHQ',211,211,1,1),(7579,'Khon Kaen','KKC',211,211,1,1),(7580,'Koh Samui','USM',211,211,1,1),(7581,'Krabi','KBV',211,211,1,1),(7582,'Lampang','LPT',211,211,1,1),(7583,'Loei','LOE',211,211,1,1),(7584,'Lop Buri','KKM',211,211,1,1),(7585,'Mae Hong Son','HGN',211,211,1,1),(7586,'Mae Sot','MAQ',211,211,1,1),(7587,'Nakhon Phanom','KOP',211,211,1,1),(7588,'Nakhon Ratchasima','NAK',211,211,1,1),(7589,'Nakon Si Thammarat','NST',211,211,1,1),(7590,'Nan','NNT',211,211,1,1),(7591,'Narathiwat','NAW',211,211,1,1),(7592,'Nong Khai','QJX',211,211,1,1),(7593,'Pai','PYY',211,211,1,1),(7594,'Patong Beach','PBS',211,211,1,1),(7595,'Pattani','PAN',211,211,1,1),(7596,'Pattaya','PYX',211,211,1,1),(7597,'Phanom Sarakham','PMM',211,211,1,1),(7598,'Phetchabun','PHY',211,211,1,1),(7599,'Phi Phi Island','PHZ',211,211,1,1),(7600,'Phitsanulok','PHS',211,211,1,1),(7601,'Phrae','PRH',211,211,1,1),(7602,'Phuket','HKT',211,211,1,1),(7603,'Ranong','UNN',211,211,1,1),(7604,'Roi Et','ROI',211,211,1,1),(7605,'Sakon Nakhon','SNO',211,211,1,1),(7606,'Songkhla','SGZ',211,211,1,1),(7607,'Sukhothai','THS',211,211,1,1),(7608,'Surat Thani','URT',211,211,1,1),(7609,'Tak','TKT',211,211,1,1),(7610,'Takhli','TKH',211,211,1,1),(7611,'Trang','TST',211,211,1,1),(7612,'Trat','TDX',211,211,1,1),(7613,'Ubon Ratchathani','UBP',211,211,1,1),(7614,'Udon Thani','UTH',211,211,1,1),(7615,'Utapao','UTP',211,211,1,1),(7616,'Uttaradit','UTR',211,211,1,1),(7617,'Lama Kara','LRL',212,212,1,1),(7618,'Lome','LFW',212,212,1,1),(7619,'Eua','EUA',214,214,1,1),(7620,'Ha\'apai','HPA',214,214,1,1),(7621,'Niuafo\'ou','NFO',214,214,1,1),(7622,'Niuatoputapu','NTT',214,214,1,1),(7623,'Tongatapu','TBU',214,214,1,1),(7624,'Vava\'u','VAV',214,214,1,1),(7625,'Port Of Spain','POS',215,215,1,1),(7626,'Tobago','TAB',215,215,1,1),(7627,'Bizerte','QIZ',216,216,1,1),(7628,'Djerba','DJE',216,216,1,1),(7629,'El Borma','EBM',216,216,1,1),(7630,'Enfidha','NBE',216,216,1,1),(7631,'Gabes','GAE',216,216,1,1),(7632,'Gafsa','GAF',216,216,1,1),(7633,'Kairouan','QKN',216,216,1,1),(7634,'Monastir','MIR',216,216,1,1),(7635,'Sfax','SFA',216,216,1,1),(7636,'Sousse','QSO',216,216,1,1),(7637,'Tabarka','TBJ',216,216,1,1),(7638,'Tozeur','TOE',216,216,1,1),(7639,'Tunez','TUN',216,216,1,1),(7640,'Adana','ADA',217,217,1,1),(7641,'Adiyaman','ADF',217,217,1,1),(7642,'Afyon','AFY',217,217,1,1),(7643,'Agri','AJI',217,217,1,1),(7644,'Amasya','MZH',217,217,1,1),(7645,'Ankara','ESB',217,217,1,1),(7647,'Antalya','AYT',217,217,1,1),(7648,'Artvin','XHQ',217,217,1,1),(7649,'Balikesir','MQJ',217,217,1,1),(7651,'Bandirma','BDM',217,217,1,1),(7652,'Batman','BAL',217,217,1,1),(7653,'Bodrum','BXN',217,217,1,1),(7655,'Bursa','YEI',217,217,1,1),(7657,'Canakkale','CKZ',217,217,1,1),(7658,'Dalaman','DLM',217,217,1,1),(7659,'Denizli','DNZ',217,217,1,1),(7660,'Diyarbakir','DIY',217,217,1,1),(7661,'Edremit','EDO',217,217,1,1),(7662,'Elazig','EZS',217,217,1,1),(7663,'Erzincan','ERC',217,217,1,1),(7664,'Erzurum','ERZ',217,217,1,1),(7665,'Eskisehir','AOE',217,217,1,1),(7667,'Estambul','SAW',217,217,1,1),(7669,'Gaziantep','GZT',217,217,1,1),(7670,'Gokceada','GKD',217,217,1,1),(7671,'Hatay','HTY',217,217,1,1),(7672,'Isparta','ISE',217,217,1,1),(7673,'Izmir','IGL',217,217,1,1),(7676,'Izmit','QST',217,217,1,1),(7677,'Kahramanmaras','KCM',217,217,1,1),(7678,'Kars','KSY',217,217,1,1),(7679,'Kastamonu','KFS',217,217,1,1),(7680,'Kayseri','ASR',217,217,1,1),(7681,'Kocaeli','KCO',217,217,1,1),(7682,'Konya','KYA',217,217,1,1),(7683,'Kusadasi','XKU',217,217,1,1),(7684,'Malatya','MLX',217,217,1,1),(7685,'Mardin','MQM',217,217,1,1),(7686,'Marmaris','QRQ',217,217,1,1),(7687,'Mersin','QIN',217,217,1,1),(7688,'Mus','MSR',217,217,1,1),(7689,'Nevsehir','NAV',217,217,1,1),(7690,'Ordu','QOR',217,217,1,1),(7691,'Rize','QRI',217,217,1,1),(7692,'Samsun','SZF',217,217,1,1),(7694,'Sanliurfa','SFQ',217,217,1,1),(7695,'Siirt','SXZ',217,217,1,1),(7696,'Sinop','SIC',217,217,1,1),(7698,'Sivas','VAS',217,217,1,1),(7699,'Tekirdag','TEQ',217,217,1,1),(7700,'Tokat','TJK',217,217,1,1),(7701,'Trabzon','TZX',217,217,1,1),(7702,'Usak','USQ',217,217,1,1),(7703,'Van','VAN',217,217,1,1),(7704,'Zonguldak','ONQ',217,217,1,1),(7705,'Ashgabad','ASB',218,218,1,1),(7706,'Krasnowodsk','KRW',218,218,1,1),(7707,'Mary','MYP',218,218,1,1),(7708,'Tashauz','TAZ',218,218,1,1),(7709,'Turkmenabad','CRZ',218,218,1,1),(7710,'Grand Turk Island','GDT',219,219,1,1),(7711,'Middle Caicos','MDS',219,219,1,1),(7712,'North Caicos','NCA',219,219,1,1),(7713,'Pine Cay','PIC',219,219,1,1),(7714,'Providenciales','PLS',219,219,1,1),(7715,'Salt Cay','SLX',219,219,1,1),(7716,'South Caicos','XSC',219,219,1,1),(7717,'Funafuti Atol','FUN',220,220,1,1),(7718,'Arua','RUA',221,221,1,1),(7719,'Entebbe','EBB',221,221,1,1),(7720,'Gulu','ULU',221,221,1,1),(7721,'Jinja','JIN',221,221,1,1),(7722,'Kabalega Falls','KBG',221,221,1,1),(7723,'Kampala','KLA',221,221,1,1),(7724,'Kasese','KSE',221,221,1,1),(7725,'Masindi','KCU',221,221,1,1),(7726,'Mbarara','MBQ',221,221,1,1),(7727,'Moyo','OYG',221,221,1,1),(7728,'Pakuba','PAF',221,221,1,1),(7729,'Soroti','SRT',221,221,1,1),(7730,'Tororo','TRY',221,221,1,1),(7731,'Berdyansk','ERD',222,222,1,1),(7732,'Cherkassy','CKC',222,222,1,1),(7733,'Chernovtsy','CWC',222,222,1,1),(7734,'Dnepropetrovsk','DNK',222,222,1,1),(7735,'Donetsk','DOK',222,222,1,1),(7736,'Ivano Frankovsk','IFO',222,222,1,1),(7737,'Kamenets Podolski','KCP',222,222,1,1),(7738,'Kerch','KHC',222,222,1,1),(7739,'Kharkov','HRK',222,222,1,1),(7740,'Kherson','KHE',222,222,1,1),(7741,'Khmelnitskiy','HMJ',222,222,1,1),(7742,'Kiew','IEV',222,222,1,1),(7744,'Kirovograd','KGO',222,222,1,1),(7745,'Kramatorsk','KRQ',222,222,1,1),(7746,'Kremenchug','KHU',222,222,1,1),(7747,'Krivoy Rog','KWG',222,222,1,1),(7748,'Luhansk','VSG',222,222,1,1),(7749,'Lutsk','UCK',222,222,1,1),(7750,'Lviv','LWO',222,222,1,1),(7751,'Mariupol','MPW',222,222,1,1),(7752,'Mirgorod','MXR',222,222,1,1),(7753,'Nikolaev','NLV',222,222,1,1),(7754,'Odessa','ODS',222,222,1,1),(7755,'Poltava','PLV',222,222,1,1),(7756,'Rovno','RWN',222,222,1,1),(7757,'Sevastopol','UKS',222,222,1,1),(7758,'Severodoneck','SEV',222,222,1,1),(7759,'Simferopol','SIP',222,222,1,1),(7760,'Sumy','UMY',222,222,1,1),(7761,'Ternopol','TNL',222,222,1,1),(7762,'Uzhgorod','UDJ',222,222,1,1),(7763,'Vinnica','VIN',222,222,1,1),(7764,'Zaporozhye','OZH',222,222,1,1),(7765,'Zhitomir','ZTR',222,222,1,1),(7766,'Abu Dhabi','AZI',223,223,1,1),(7768,'Ajman City','QAJ',223,223,1,1),(7769,'Al Ain','AAN',223,223,1,1),(7770,'Dubai','DXB',223,223,1,1),(7771,'Fujairah','FJR',223,223,1,1),(7772,'Ras Al Khaimah','RKT',223,223,1,1),(7773,'Sharjah','SHJ',223,223,1,1),(7774,'Umm Al Quwain','QIW',223,223,1,1),(7775,'Aberdeen','ABZ',224,224,1,1),(7776,'Alderney','ACI',224,224,1,1),(7777,'Andover','ADV',224,224,1,1),(7778,'Anglesey','VLY',224,224,1,1),(7779,'Ashford','QDH',224,224,1,1),(7780,'Bally Kelly','BOL',224,224,1,1),(7781,'Barra','BRR',224,224,1,1),(7782,'Barrow In Furness','BWF',224,224,1,1),(7783,'Basingstoke','XQB',224,224,1,1),(7784,'Bath','QQX',224,224,1,1),(7785,'Bedford','XQD',224,224,1,1),(7786,'Belfast','BFS',224,224,1,1),(7788,'Bembridge','BBP',224,224,1,1),(7789,'Benbecula','BEB',224,224,1,1),(7790,'Benson','BEX',224,224,1,1),(7791,'Berwick U Tweed','XQG',224,224,1,1),(7792,'Birmingham','BHX',224,224,1,1),(7793,'Blackbush','BBS',224,224,1,1),(7794,'Blackpool','BLK',224,224,1,1),(7795,'Bournemouth','BOH',224,224,1,1),(7796,'Bradford','BRF',224,224,1,1),(7797,'Brandon','LKZ',224,224,1,1),(7798,'Brighton','BSH',224,224,1,1),(7799,'Bristol','BRS',224,224,1,1),(7800,'Britrail Pass','QQB',224,224,1,1),(7801,'Britrail Rail Zon','QQJ',224,224,1,1),(7808,'Britrail Rail Zone T','QQT',224,224,1,1),(7809,'Brize Norton','BZZ',224,224,1,1),(7810,'Bury St Edmunds','BEQ',224,224,1,1),(7811,'Cambridge','CBG',224,224,1,1),(7812,'Campbeltown','CAL',224,224,1,1),(7813,'Cardiff','CWL',224,224,1,1),(7814,'Carlisle','CAX',224,224,1,1),(7815,'Chester','CEG',224,224,1,1),(7816,'Chesterfield','ZFI',224,224,1,1),(7817,'Chichester Goodwood','QUG',224,224,1,1),(7818,'Coll Island','COL',224,224,1,1),(7819,'Colonsay Is','CSA',224,224,1,1),(7820,'Coltishall','CLF',224,224,1,1),(7821,'Coningsby','QCY',224,224,1,1),(7822,'Coventry','CVT',224,224,1,1),(7823,'Crewe','XVC',224,224,1,1),(7824,'Darlington','XVG',224,224,1,1),(7825,'Dewsbury','ZEQ',224,224,1,1),(7826,'Didcot Parkway','XPW',224,224,1,1),(7827,'Doncaster','DSA',224,224,1,1),(7829,'Dornoch','DOC',224,224,1,1),(7830,'Dover Rail','QQD',224,224,1,1),(7831,'Dundee','DND',224,224,1,1),(7832,'Durham','XVU',224,224,1,1),(7833,'Durham Tees Valley','MME',224,224,1,1),(7834,'Duxford','QFO',224,224,1,1),(7835,'Eday','EOI',224,224,1,1),(7836,'Edimburgo','EDI',224,224,1,1),(7837,'Enniskillen','ENK',224,224,1,1),(7838,'Exeter','EXT',224,224,1,1),(7839,'Fair Isle','FIE',224,224,1,1),(7840,'Fairford','FFD',224,224,1,1),(7841,'Family Pass','QQC',224,224,1,1),(7842,'Farnborough','FAB',224,224,1,1),(7843,'Fetlar','FEA',224,224,1,1),(7844,'Filton','FZO',224,224,1,1),(7845,'Flexi Pass','QQF',224,224,1,1),(7846,'Flotta','FLH',224,224,1,1),(7847,'Forres','FSS',224,224,1,1),(7848,'Fort William','FWM',224,224,1,1),(7849,'Foula','FOA',224,224,1,1),(7850,'Glasgow','PIK',224,224,1,1),(7852,'Gloucester','GLO',224,224,1,1),(7853,'Grantham','XGM',224,224,1,1),(7854,'Grimsby','GSY',224,224,1,1),(7855,'Guernsey','GCI',224,224,1,1),(7856,'Harrogate','HRT',224,224,1,1),(7857,'Harwich','QQH',224,224,1,1),(7858,'Haverfordwest','HAW',224,224,1,1),(7859,'High Wycombe','HYC',224,224,1,1),(7860,'Holyhead','HLY',224,224,1,1),(7861,'Hotel Generic','XHZ',224,224,1,1),(7862,'Hoy Island','HOY',224,224,1,1),(7863,'Humberside','HUY',224,224,1,1),(7864,'Huntingdon','XHU',224,224,1,1),(7865,'Inverness','INV',224,224,1,1),(7866,'Ipswich','IPW',224,224,1,1),(7867,'Islay','ILY',224,224,1,1),(7868,'Isle Of Man','IOM',224,224,1,1),(7869,'Isle Of Skye','SKL',224,224,1,1),(7870,'Isles Of Scilly','ISC',224,224,1,1),(7871,'Isleworth','QIF',224,224,1,1),(7872,'Jersey','JER',224,224,1,1),(7873,'Kings Lynn','KNF',224,224,1,1),(7874,'Kirkwall','KOI',224,224,1,1),(7875,'Lancaster','XQL',224,224,1,1),(7876,'Lands End','LEQ',224,224,1,1),(7877,'Lasham','QLA',224,224,1,1),(7878,'Leeds','LBA',224,224,1,1),(7879,'Leicester','QEW',224,224,1,1),(7880,'Lichfield','XQT',224,224,1,1),(7881,'Liverpool','LPL',224,224,1,1),(7882,'Londonderry','LDY',224,224,1,1),(7883,'Londres','LTN',224,224,1,1),(7890,'Lossiemouth','LMO',224,224,1,1),(7891,'Lydd','LYX',224,224,1,1),(7892,'Lyneham','LYE',224,224,1,1),(7893,'Macclesfield','XMZ',224,224,1,1),(7894,'Manchester','MAN',224,224,1,1),(7895,'Mansfield','ZMA',224,224,1,1),(7896,'Manston','MSE',224,224,1,1),(7897,'Market Harborough','XQM',224,224,1,1),(7898,'Mildenhall','MHZ',224,224,1,1),(7899,'Milton Keynes','KYN',224,224,1,1),(7900,'Motherwell','XQW',224,224,1,1),(7901,'Mull','ULL',224,224,1,1),(7902,'Newcastle','NCL',224,224,1,1),(7903,'Newcastle Under Lyme Uk','VLF',224,224,1,1),(7904,'Newport Gwent','XNE',224,224,1,1),(7905,'Newquay','NQY',224,224,1,1),(7906,'North Ronaldsay','NRL',224,224,1,1),(7907,'Northallerton','XNO',224,224,1,1),(7908,'Northampton','ORM',224,224,1,1),(7909,'Northolt','NHT',224,224,1,1),(7910,'Norwich','NWI',224,224,1,1),(7911,'Nottingham','NQT',224,224,1,1),(7913,'Nuneaton','XNV',224,224,1,1),(7914,'Oakham','OKH',224,224,1,1),(7915,'Oban','OBN',224,224,1,1),(7916,'Odiham','ODH',224,224,1,1),(7917,'Outer Skerries','OUK',224,224,1,1),(7918,'Oxford','OXF',224,224,1,1),(7919,'Papa Stour','PSV',224,224,1,1),(7920,'Papa Westray','PPW',224,224,1,1),(7921,'Penrith','XPF',224,224,1,1),(7922,'Penzance','PZE',224,224,1,1),(7923,'Perth','PSL',224,224,1,1),(7924,'Peterborough','XVH',224,224,1,1),(7925,'Plymouth','PLH',224,224,1,1),(7926,'Portsmouth','PME',224,224,1,1),(7927,'Preston','XPT',224,224,1,1),(7928,'Ramsgate Rail','QQR',224,224,1,1),(7929,'Reading','XRE',224,224,1,1),(7930,'Redhill','KRH',224,224,1,1),(7931,'Rochester','RCS',224,224,1,1),(7932,'Rugby','XRU',224,224,1,1),(7933,'Rugeley Tv','XRG',224,224,1,1),(7934,'Runcorn','XRC',224,224,1,1),(7935,'Salisbury','XSR',224,224,1,1),(7936,'Sanday','NDY',224,224,1,1),(7937,'Scampton','SQZ',224,224,1,1),(7938,'Sheffield','SZD',224,224,1,1),(7939,'Shetland Islands','SDZ',224,224,1,1),(7942,'Shoreham By Sea','ESH',224,224,1,1),(7943,'Southampton','SOU',224,224,1,1),(7944,'Southeast Pass','QQL',224,224,1,1),(7945,'Southend','SEN',224,224,1,1),(7946,'St Andrews','ADX',224,224,1,1),(7947,'Stafford','XVB',224,224,1,1),(7948,'Stevenage','XVJ',224,224,1,1),(7949,'Stirling','XWB',224,224,1,1),(7950,'Stockport','XVA',224,224,1,1),(7951,'Stoke On Trent','XWH',224,224,1,1),(7952,'Stornoway','SYY',224,224,1,1),(7953,'Stronsay','SOY',224,224,1,1),(7954,'Suttonheath','WOB',224,224,1,1),(7955,'Swansea','SWS',224,224,1,1),(7956,'Swindon','XWS',224,224,1,1),(7957,'Thirsk','XTK',224,224,1,1),(7958,'Tiree','TRE',224,224,1,1),(7959,'Tottenham Hale Station','TTK',224,224,1,1),(7960,'Unst Shetland Is','UNT',224,224,1,1),(7961,'Upavon','UPV',224,224,1,1),(7962,'Voucher','QQV',224,224,1,1),(7963,'Wakefield Westgate','XWD',224,224,1,1),(7964,'Warrington','XWN',224,224,1,1),(7965,'Wellingborough','XWE',224,224,1,1),(7966,'West Malling','WEM',224,224,1,1),(7967,'Westray','WRY',224,224,1,1),(7968,'Whalsay','WHS',224,224,1,1),(7969,'Wick','WIC',224,224,1,1),(7970,'Wigan Nw','XWI',224,224,1,1),(7971,'Woking','XWO',224,224,1,1),(7972,'Wolverhampton','XVW',224,224,1,1),(7973,'Wyton','QUY',224,224,1,1),(7974,'Yeovilton','YEO',224,224,1,1),(7975,'York','QQY',224,224,1,1),(7976,'Aberdeen','HQM',225,225,1,1),(7979,'Abilene','ABI',225,225,1,1),(7980,'Abingdon','VJI',225,225,1,1),(7981,'Ada','ADT',225,225,1,1),(7982,'Adak Island','ADK',225,225,1,1),(7983,'Adrian','ADG',225,225,1,1),(7984,'Afton','AFO',225,225,1,1),(7985,'Aiken','AIK',225,225,1,1),(7986,'Ainsworth','ANW',225,225,1,1),(7987,'Akiak','AKI',225,225,1,1),(7988,'Akron','AKO',225,225,1,1),(7989,'Akron Canton','CAK',225,225,1,1),(7990,'Akutan','KQA',225,225,1,1),(7991,'Alakanuk','AUK',225,225,1,1),(7992,'Alameda','NGZ',225,225,1,1),(7993,'Alamogordo','ALM',225,225,1,1),(7994,'Alamosa','ALS',225,225,1,1),(7995,'Albany','ABY',225,225,1,1),(7997,'Albert Lea','AEL',225,225,1,1),(7998,'Albuquerque','ABQ',225,225,1,1),(7999,'Alejandria','ESF',225,225,1,1),(8002,'Aleknagik','WKK',225,225,1,1),(8003,'Aleneva','AED',225,225,1,1),(8004,'Alexander City','ALX',225,225,1,1),(8005,'Alexandria Bay','AXB',225,225,1,1),(8006,'Algona','AXG',225,225,1,1),(8007,'Alice','ALI',225,225,1,1),(8008,'Aliceville','AIV',225,225,1,1),(8009,'Allakaket','AET',225,225,1,1),(8010,'Allentown Bthlehm','ABE',225,225,1,1),(8011,'Alliance','AIA',225,225,1,1),(8012,'Alma','AMN',225,225,1,1),(8013,'Alpena','APN',225,225,1,1),(8014,'Alpine','ALE',225,225,1,1),(8015,'Alton','ALN',225,225,1,1),(8016,'Altoona','AOO',225,225,1,1),(8017,'Altus','LTS',225,225,1,1),(8018,'Alyeska','AQY',225,225,1,1),(8019,'Amarillo','AMA',225,225,1,1),(8020,'Ambler','ABL',225,225,1,1),(8021,'Amchitka','AHT',225,225,1,1),(8022,'Amery','AHH',225,225,1,1),(8023,'Ames','AMW',225,225,1,1),(8024,'Amityville','AYZ',225,225,1,1),(8025,'Amook','AOS',225,225,1,1),(8026,'Anacortes','OTS',225,225,1,1),(8027,'Anaheim','ANA',225,225,1,1),(8028,'Anaktuvuk','AKP',225,225,1,1),(8029,'Anchorage','ANC',225,225,1,1),(8031,'Anderson','AND',225,225,1,1),(8033,'Andrews','ADR',225,225,1,1),(8034,'Angel Fire','AXX',225,225,1,1),(8035,'Angola','ANQ',225,225,1,1),(8036,'Angoon','AGN',225,225,1,1),(8037,'Anguilla','RFK',225,225,1,1),(8038,'Aniak','ANI',225,225,1,1),(8039,'Anita Bay','AIB',225,225,1,1),(8040,'Ann Arbor','ARB',225,225,1,1),(8041,'Annapolis','ANP',225,225,1,1),(8042,'Annette Island','ANN',225,225,1,1),(8043,'Anniston','ANB',225,225,1,1),(8044,'Anthony','ANY',225,225,1,1),(8045,'Antlers','ATE',225,225,1,1),(8046,'Anvik','ANV',225,225,1,1),(8047,'Apalachicola','AAF',225,225,1,1),(8048,'Apple Valley','APV',225,225,1,1),(8049,'Appleton','ATW',225,225,1,1),(8050,'Arapahoe','AHF',225,225,1,1),(8051,'Arcata','ACV',225,225,1,1),(8052,'Arctic Village','ARC',225,225,1,1),(8053,'Ardmore','ADM',225,225,1,1),(8054,'Arlington Heights','JLH',225,225,1,1),(8055,'Artesia','ATS',225,225,1,1),(8056,'Asbury Park','ARX',225,225,1,1),(8057,'Asheville','AVL',225,225,1,1),(8058,'Ashland','AHM',225,225,1,1),(8060,'Ashley','ASY',225,225,1,1),(8061,'Aspen','ASE',225,225,1,1),(8062,'Astoria','AST',225,225,1,1),(8063,'Atenas','AHN',225,225,1,1),(8064,'Athens','MMI',225,225,1,1),(8066,'Atka','AKB',225,225,1,1),(8067,'Atlanta','ATL',225,225,1,1),(8068,'Atlantic','AIO',225,225,1,1),(8069,'Atlantic City','AIY',225,225,1,1),(8071,'Atmautluak','ATT',225,225,1,1),(8072,'Atqasuk','ATK',225,225,1,1),(8073,'Attu Island','ATU',225,225,1,1),(8074,'Auburn','AUO',225,225,1,1),(8076,'Augusta','AGS',225,225,1,1),(8078,'Aurora','AUZ',225,225,1,1),(8079,'Austin','AUM',225,225,1,1),(8082,'Avon Park','AVO',225,225,1,1),(8083,'Baca Grande','BCJ',225,225,1,1),(8084,'Bagdad','BGT',225,225,1,1),(8085,'Bainbridge','BGE',225,225,1,1),(8086,'Baker','BKE',225,225,1,1),(8087,'Baker Island','BAR',225,225,1,1),(8088,'Bakersfield','BFL',225,225,1,1),(8089,'Bandon','BDY',225,225,1,1),(8090,'Bangor','BGR',225,225,1,1),(8091,'Banning','BNG',225,225,1,1),(8092,'Bar Harbor','BHB',225,225,1,1),(8093,'Barbers Point','NAX',225,225,1,1),(8094,'Bardstown','BRY',225,225,1,1),(8095,'Barnwell','BNL',225,225,1,1),(8096,'Barrow','BRW',225,225,1,1),(8097,'Barter Island','BTI',225,225,1,1),(8098,'Bartlesville','BVO',225,225,1,1),(8099,'Bartletts','BSZ',225,225,1,1),(8100,'Bartow','BOW',225,225,1,1),(8101,'Batesville','HLB',225,225,1,1),(8103,'Baton Rouge','BTR',225,225,1,1),(8104,'Battle Creek','BTL',225,225,1,1),(8105,'Battle Mountain','BAM',225,225,1,1),(8106,'Baudette','BDE',225,225,1,1),(8107,'Bay City','BBC',225,225,1,1),(8108,'Baytown','HPY',225,225,1,1),(8109,'Bear Creek','BCC',225,225,1,1),(8110,'Beatrice','BIE',225,225,1,1),(8111,'Beatty','BTY',225,225,1,1),(8112,'Beaufort','BFT',225,225,1,1),(8113,'Beaumont','BPT',225,225,1,1),(8114,'Beaver','WBQ',225,225,1,1),(8115,'Beaver Creek','ZBV',225,225,1,1),(8116,'Beaver Falls','BFP',225,225,1,1),(8117,'Beaver Inlet','BVD',225,225,1,1),(8118,'Beckley','BKW',225,225,1,1),(8119,'Bedford','BFR',225,225,1,1),(8120,'Bedford Hanscom','BED',225,225,1,1),(8121,'Beeville','NIR',225,225,1,1),(8122,'Bellaire','ACB',225,225,1,1),(8123,'Belle Chasse','BCS',225,225,1,1),(8124,'Belleville','BLV',225,225,1,1),(8125,'Bellingham','BLI',225,225,1,1),(8126,'Belmar','BLM',225,225,1,1),(8127,'Beltsville','ZFE',225,225,1,1),(8128,'Beluga','BVU',225,225,1,1),(8129,'Bemidji','BJI',225,225,1,1),(8130,'Bennettsville','BTN',225,225,1,1),(8131,'Benson','BBB',225,225,1,1),(8132,'Benton Harbor','BEH',225,225,1,1),(8133,'Berkeley','JBK',225,225,1,1),(8134,'Berlin','BML',225,225,1,1),(8135,'Bethel','BET',225,225,1,1),(8136,'Bethpage','BPA',225,225,1,1),(8137,'Bettles','BTT',225,225,1,1),(8138,'Beverly','BVY',225,225,1,1),(8139,'Big Bear','RBF',225,225,1,1),(8140,'Big Creek','BIC',225,225,1,1),(8141,'Big Delta','BIG',225,225,1,1),(8142,'Big Lake','BGQ',225,225,1,1),(8143,'Big Mountain','BMX',225,225,1,1),(8144,'Big Piney','BPI',225,225,1,1),(8145,'Big Rapids','WBR',225,225,1,1),(8146,'Big Spring','HCA',225,225,1,1),(8147,'Billings','BIL',225,225,1,1),(8148,'Biloxi','BIX',225,225,1,1),(8149,'Binghamton','BGM',225,225,1,1),(8150,'Birch Creek','KBC',225,225,1,1),(8151,'Birmingham','BHM',225,225,1,1),(8152,'Bisbee','BSQ',225,225,1,1),(8153,'Bishop','BIH',225,225,1,1),(8154,'Bismarck','BIS',225,225,1,1),(8155,'Blacksburg','BCB',225,225,1,1),(8156,'Blackstone','BKT',225,225,1,1),(8157,'Blackwell','BWL',225,225,1,1),(8158,'Blaine','BWS',225,225,1,1),(8159,'Blairsville','BSI',225,225,1,1),(8160,'Blakely Island','BYW',225,225,1,1),(8161,'Blanding','BDG',225,225,1,1),(8162,'Block Island','BID',225,225,1,1),(8163,'Bloomington','BMG',225,225,1,1),(8165,'Blue Canyon','BLU',225,225,1,1),(8166,'Blue Fox Bay','BFB',225,225,1,1),(8167,'Bluefield','BLF',225,225,1,1),(8168,'Blythe','BLH',225,225,1,1),(8169,'Blytheville','BYH',225,225,1,1),(8170,'Boca Raton','BCT',225,225,1,1),(8171,'Bogalusa','BXA',225,225,1,1),(8172,'Boise','BOI',225,225,1,1),(8173,'Boone','BNW',225,225,1,1),(8174,'Borger','BGD',225,225,1,1),(8175,'Bornite','RLU',225,225,1,1),(8176,'Borrego Springs','BXS',225,225,1,1),(8177,'Boston','BOS',225,225,1,1),(8178,'Boswell Bay','BSW',225,225,1,1),(8179,'Boulder','WBU',225,225,1,1),(8180,'Boundary','BYA',225,225,1,1),(8181,'Bountiful','BTF',225,225,1,1),(8182,'Bowling Green','APH',225,225,1,1),(8184,'Bowman','BWM',225,225,1,1),(8185,'Boxborough','BXC',225,225,1,1),(8186,'Bozeman','BZN',225,225,1,1),(8187,'Bradford','BFD',225,225,1,1),(8189,'Brady','BBD',225,225,1,1),(8190,'Brainerd','BRD',225,225,1,1),(8191,'Branson','BKG',225,225,1,1),(8192,'Branson/Point Lookout','PLK',225,225,1,1),(8193,'Brawley','BWC',225,225,1,1),(8194,'Brazoria','BZT',225,225,1,1),(8195,'Breckenridge','BKD',225,225,1,1),(8197,'Bremerton','PWT',225,225,1,1),(8198,'Bridgeport','BDR',225,225,1,1),(8199,'Brigham City','BMC',225,225,1,1),(8200,'Bristol','TRI',225,225,1,1),(8201,'Britton','TTO',225,225,1,1),(8202,'Broadus','BDX',225,225,1,1),(8203,'Broken Bow','BBW',225,225,1,1),(8204,'Brookings','BOK',225,225,1,1),(8206,'Brooklyn','QFF',225,225,1,1),(8208,'Brooks Lake','BKF',225,225,1,1),(8209,'Brooks Lodge','RBH',225,225,1,1),(8210,'Broomfield','BJC',225,225,1,1),(8211,'Brownsville','BRO',225,225,1,1),(8212,'Brownwood','BWD',225,225,1,1),(8213,'Brunswick','BQK',225,225,1,1),(8216,'Bryan','CFD',225,225,1,1),(8217,'Bryce','BCE',225,225,1,1),(8218,'Buckeye','BXK',225,225,1,1),(8219,'Buckland','BKC',225,225,1,1),(8220,'Buffalo','BYG',225,225,1,1),(8222,'Bullfrog Basin','BFG',225,225,1,1),(8223,'Bullhead City','IFP',225,225,1,1),(8224,'Burbank','BUR',225,225,1,1),(8225,'Burley','BYI',225,225,1,1),(8226,'Burlington','BBF',225,225,1,1),(8229,'Burns','BNO',225,225,1,1),(8230,'Burwell','BUB',225,225,1,1),(8231,'Butler','BTP',225,225,1,1),(8233,'Butte','BTM',225,225,1,1),(8234,'Cabin Creek','CBZ',225,225,1,1),(8235,'Cadillac','CAD',225,225,1,1),(8236,'Cairo','CIR',225,225,1,1),(8237,'Caldwell','CDW',225,225,1,1),(8238,'Calexico','CXL',225,225,1,1),(8239,'Calipatria','CLR',225,225,1,1),(8240,'Callaway Gardens','CWG',225,225,1,1),(8241,'Calverton','CTO',225,225,1,1),(8242,'Cambridge','CGE',225,225,1,1),(8244,'Camden','CDN',225,225,1,1),(8246,'Camp Douglas','VOK',225,225,1,1),(8247,'Camp Springs','ADW',225,225,1,1),(8248,'Campo','CZZ',225,225,1,1),(8249,'Candle','CDL',225,225,1,1),(8250,'Canon City','CNE',225,225,1,1),(8251,'Canton','CTK',225,225,1,1),(8252,'Cape Girardeau','CGI',225,225,1,1),(8253,'Cape Lisburne','LUR',225,225,1,1),(8254,'Cape Newenham','EHM',225,225,1,1),(8255,'Cape Pole','CZP',225,225,1,1),(8256,'Cape Romanzof','CZF',225,225,1,1),(8257,'Cape Spencer','CSP',225,225,1,1),(8258,'Carbondale','MDH',225,225,1,1),(8259,'Caribou','CAR',225,225,1,1),(8260,'Carlsbad','CNM',225,225,1,1),(8261,'Carrizo Springs','CZT',225,225,1,1),(8262,'Carroll','CIN',225,225,1,1),(8263,'Carson City','CSN',225,225,1,1),(8264,'Casa Grande','CGZ',225,225,1,1),(8265,'Cascade Locks','CZK',225,225,1,1),(8266,'Casper','CPR',225,225,1,1),(8267,'Catalina Island','AVX',225,225,1,1),(8268,'Cedar City','CDC',225,225,1,1),(8269,'Cedar Key','CDK',225,225,1,1),(8270,'Cedar Rapids','CID',225,225,1,1),(8271,'Center Island','CWS',225,225,1,1),(8272,'Centerville','GHM',225,225,1,1),(8273,'Central','CEM',225,225,1,1),(8274,'Centralia','ENL',225,225,1,1),(8275,'Chadron','CDR',225,225,1,1),(8276,'Chalkyitsik','CIK',225,225,1,1),(8277,'Challis','CHL',225,225,1,1),(8278,'Champaign','CMI',225,225,1,1),(8279,'Chandalar','WCR',225,225,1,1),(8280,'Chanute','CNU',225,225,1,1),(8281,'Charles City','CCY',225,225,1,1),(8282,'Charleston','CHS',225,225,1,1),(8284,'Charlotte','CLT',225,225,1,1),(8285,'Charlottesville','CHO',225,225,1,1),(8286,'Chattanooga','CHA',225,225,1,1),(8287,'Chehalis','CLS',225,225,1,1),(8288,'Chena Hot Springs','CEX',225,225,1,1),(8289,'Cheraw','HCW',225,225,1,1),(8290,'Cherokee','CKA',225,225,1,1),(8292,'Chevak','VAK',225,225,1,1),(8293,'Cheyenne','CYS',225,225,1,1),(8294,'Chicago','MDW',225,225,1,1),(8300,'Chickasha','CHK',225,225,1,1),(8301,'Chicken','CKX',225,225,1,1),(8302,'Chico','CIC',225,225,1,1),(8303,'Chicopee','CEF',225,225,1,1),(8305,'Chignik','KCL',225,225,1,1),(8306,'Childress','CDS',225,225,1,1),(8307,'Chiloquin','CHZ',225,225,1,1),(8308,'Chincoteague','WAL',225,225,1,1),(8309,'Chino','CNO',225,225,1,1),(8310,'Chisana','CZN',225,225,1,1),(8311,'Chistochina','CZO',225,225,1,1),(8312,'Chitina','CXC',225,225,1,1),(8313,'Chomley','CIV',225,225,1,1),(8314,'Chuathbaluk','CHU',225,225,1,1),(8315,'Cincinnati','LUK',225,225,1,1),(8317,'Circle','IRC',225,225,1,1),(8318,'Circle Hot Spring','CHP',225,225,1,1),(8319,'Claremont','CNH',225,225,1,1),(8320,'Clarinda','ICL',225,225,1,1),(8321,'Clarks Point','CLP',225,225,1,1),(8322,'Clarksburg','CKB',225,225,1,1),(8323,'Clarksdale','CKM',225,225,1,1),(8324,'Clarksville','CKV',225,225,1,1),(8325,'Clayton','CAO',225,225,1,1),(8326,'Clear Lake','CKE',225,225,1,1),(8327,'Clearlake','CLC',225,225,1,1),(8328,'Clearwater','CLW',225,225,1,1),(8329,'Clemson','CEU',225,225,1,1),(8330,'Cleveland','CGF',225,225,1,1),(8332,'Clifton','CFT',225,225,1,1),(8333,'Clinton','CWI',225,225,1,1),(8336,'Clintonville','CLI',225,225,1,1),(8337,'Clovis','CVN',225,225,1,1),(8338,'Coalinga','CLG',225,225,1,1),(8339,'Coatesville','CTH',225,225,1,1),(8340,'Cocoa','COI',225,225,1,1),(8341,'Cody','COD',225,225,1,1),(8342,'Coeur D\'Alene','COE',225,225,1,1),(8343,'Coffee Point','CFA',225,225,1,1),(8344,'Coffeyville','CFV',225,225,1,1),(8345,'Colby','CBK',225,225,1,1),(8346,'Cold Bay','CDB',225,225,1,1),(8347,'Coldfoot','CXF',225,225,1,1),(8348,'Coleman','COM',225,225,1,1),(8349,'College Park','CGS',225,225,1,1),(8350,'College Station','CLL',225,225,1,1),(8351,'Colorado Creek','KCR',225,225,1,1),(8352,'Colorado Springs','COS',225,225,1,1),(8354,'Columbia','COU',225,225,1,1),(8358,'Columbus','LCK',225,225,1,1),(8367,'Compton','CPM',225,225,1,1),(8368,'Concord','CCR',225,225,1,1),(8370,'Concordia','CNK',225,225,1,1),(8371,'Connersville','CEV',225,225,1,1),(8372,'Conroe','CXO',225,225,1,1),(8373,'Cooper Lodge','JLA',225,225,1,1),(8374,'Cooperstown','COP',225,225,1,1),(8375,'Copper Centre','CZC',225,225,1,1),(8376,'Copper Mountain','QCE',225,225,1,1),(8377,'Corcoran','CRO',225,225,1,1),(8378,'Cordova','CDV',225,225,1,1),(8379,'Corinth','CRX',225,225,1,1),(8380,'Corner Bay','CBA',225,225,1,1),(8381,'Corpus Christi','CRP',225,225,1,1),(8382,'Corsicana','CRS',225,225,1,1),(8383,'Cortez','CEZ',225,225,1,1),(8384,'Cortland','CTX',225,225,1,1),(8385,'Corvallis','CVO',225,225,1,1),(8386,'Cottonwood','CTW',225,225,1,1),(8387,'Cotulla','COT',225,225,1,1),(8388,'Council','CIL',225,225,1,1),(8389,'Council Bluffs','CBF',225,225,1,1),(8390,'Craig','CIG',225,225,1,1),(8392,'Crane','CCG',225,225,1,1),(8393,'Crane Island','CKR',225,225,1,1),(8394,'Crescent City','CEC',225,225,1,1),(8395,'Crested Butte','CSE',225,225,1,1),(8396,'Creston','CSQ',225,225,1,1),(8397,'Crestview','CEW',225,225,1,1),(8398,'Crooked Creek','CKD',225,225,1,1),(8399,'Crookston','CKN',225,225,1,1),(8400,'Cross City','CTY',225,225,1,1),(8401,'Crossett','CRT',225,225,1,1),(8402,'Crossville','CSV',225,225,1,1),(8403,'Crows Landing','NRC',225,225,1,1),(8404,'Crystal Lake','CYE',225,225,1,1),(8405,'Cube Cove','CUW',225,225,1,1),(8406,'Culver City','CVR',225,225,1,1),(8407,'Cumberland','CBE',225,225,1,1),(8408,'Cushing','CUH',225,225,1,1),(8409,'Cut Bank','CTB',225,225,1,1),(8410,'Daggett','DAG',225,225,1,1),(8411,'Dahl Creek','DCK',225,225,1,1),(8412,'Dahlgren','DGN',225,225,1,1),(8413,'Dalhart','DHT',225,225,1,1),(8414,'Dallas','DAL',225,225,1,1),(8417,'Dalton','DNN',225,225,1,1),(8418,'Danbury','DXR',225,225,1,1),(8419,'Danger Bay','DGB',225,225,1,1),(8420,'Dansville','DSV',225,225,1,1),(8421,'Danville','DAN',225,225,1,1),(8423,'Davenport','DVN',225,225,1,1),(8424,'Dayton','DAY',225,225,1,1),(8425,'Daytona Beach','DAB',225,225,1,1),(8426,'De Ridder','DRI',225,225,1,1),(8427,'Deaborn','DEO',225,225,1,1),(8428,'Death Valley','DTH',225,225,1,1),(8429,'Decatur','DEC',225,225,1,1),(8432,'Decatur Island','DTR',225,225,1,1),(8433,'Decorah','DEH',225,225,1,1),(8434,'Deep Bay','WDB',225,225,1,1),(8435,'Deer Harbor','DHB',225,225,1,1),(8436,'Deer Park','DPK',225,225,1,1),(8437,'Deering','DRG',225,225,1,1),(8438,'Defiance','DFI',225,225,1,1),(8439,'Del Rio','DRT',225,225,1,1),(8440,'Delta','DTA',225,225,1,1),(8441,'Delta Junction','DJN',225,225,1,1),(8442,'Deming','DMN',225,225,1,1),(8443,'Denison','DNS',225,225,1,1),(8444,'Denver','APA',225,225,1,1),(8446,'Des Moines','DSM',225,225,1,1),(8447,'Destin','DSI',225,225,1,1),(8448,'Detroit','DTT',225,225,1,1),(8452,'Detroit Lakes','DTL',225,225,1,1),(8453,'Devils Lake','DVL',225,225,1,1),(8454,'Dickinson','DIK',225,225,1,1),(8455,'Dillingham','DLG',225,225,1,1),(8456,'Dillon','DLN',225,225,1,1),(8458,'Diomede Island','DIO',225,225,1,1),(8459,'Dodge City','DDC',225,225,1,1),(8460,'Dolomi','DLO',225,225,1,1),(8461,'Dora Bay','DOF',225,225,1,1),(8462,'Dothan','DHN',225,225,1,1),(8463,'Douglas','DGW',225,225,1,1),(8465,'Dover Cheswold','DOV',225,225,1,1),(8466,'Downey','JDY',225,225,1,1),(8467,'Doylestown','DYL',225,225,1,1),(8468,'Drift River','DRF',225,225,1,1),(8469,'Drummond','DRU',225,225,1,1),(8470,'Drummond Island','DRE',225,225,1,1),(8471,'Dublin','PSK',225,225,1,1),(8473,'Dubois','DBS',225,225,1,1),(8475,'Dubuque','DBQ',225,225,1,1),(8476,'Duck','DUF',225,225,1,1),(8477,'Dugway','DPG',225,225,1,1),(8478,'Duluth','DLH',225,225,1,1),(8479,'Duncan','DUC',225,225,1,1),(8480,'Dunquerque','DKK',225,225,1,1),(8481,'Durango','DRO',225,225,1,1),(8482,'Durant','DUA',225,225,1,1),(8483,'Dutch Harbor','DUT',225,225,1,1),(8484,'Eagle','EAA',225,225,1,1),(8485,'Eagle Lake','ELA',225,225,1,1),(8486,'Eagle Pass','EGP',225,225,1,1),(8487,'Eagle River','EGV',225,225,1,1),(8488,'East Fork','EFO',225,225,1,1),(8489,'East Hampton','HTO',225,225,1,1),(8490,'East Hartford','EHT',225,225,1,1),(8491,'East Stroudsburg','ESP',225,225,1,1),(8492,'East Tawas','ECA',225,225,1,1),(8493,'Eastland','ETN',225,225,1,1),(8494,'Easton','ESN',225,225,1,1),(8496,'Eastsound','ESD',225,225,1,1),(8497,'Eau Claire','EAU',225,225,1,1),(8498,'Edenton','EDE',225,225,1,1),(8499,'Edgewood','EDG',225,225,1,1),(8500,'Edna Bay','EDA',225,225,1,1),(8501,'Eek','EEK',225,225,1,1),(8502,'Egegik','EGX',225,225,1,1),(8503,'Eight Fathom Bigh','EFB',225,225,1,1),(8504,'Ekuk','KKU',225,225,1,1),(8505,'Ekwok','KEK',225,225,1,1),(8506,'El Cajon','CJN',225,225,1,1),(8507,'El Centro','NJK',225,225,1,1),(8509,'El Dorado','ELD',225,225,1,1),(8511,'El Monte','EMT',225,225,1,1),(8512,'El Paso','BIF',225,225,1,1),(8514,'Eldred Rock','ERO',225,225,1,1),(8515,'Elfin Cove','ELV',225,225,1,1),(8516,'Elim','ELI',225,225,1,1),(8517,'Elizabeth City','ECG',225,225,1,1),(8518,'Elizabethtown','EKX',225,225,1,1),(8519,'Elk City','ELK',225,225,1,1),(8520,'Elkhart','EKI',225,225,1,1),(8521,'Elkins','EKN',225,225,1,1),(8522,'Elko','EKO',225,225,1,1),(8523,'Ellamar','ELW',225,225,1,1),(8524,'Ellensburg','ELN',225,225,1,1),(8525,'Elmira','ELM',225,225,1,1),(8526,'Ely','ELY',225,225,1,1),(8528,'Emeryville','JEM',225,225,1,1),(8529,'Emmonak','EMK',225,225,1,1),(8530,'Emporia','EMP',225,225,1,1),(8531,'Englewood','QTS',225,225,1,1),(8532,'Enid','WDG',225,225,1,1),(8533,'Enterprise','ETS',225,225,1,1),(8534,'Ephrata','EPH',225,225,1,1),(8535,'Erie','ERI',225,225,1,1),(8536,'Errol','ERR',225,225,1,1),(8537,'Escanaba','ESC',225,225,1,1),(8538,'Espanola','ESO',225,225,1,1),(8539,'Estherville','EST',225,225,1,1),(8540,'Eufaula','EUF',225,225,1,1),(8541,'Eugene','EUG',225,225,1,1),(8542,'Eunice','UCE',225,225,1,1),(8543,'Eureka','EUE',225,225,1,1),(8544,'Evadale','EVA',225,225,1,1),(8545,'Evanston','EVW',225,225,1,1),(8546,'Evansville','EVV',225,225,1,1),(8547,'Eveleth','EVM',225,225,1,1),(8548,'Everett','PAE',225,225,1,1),(8549,'Fairbanks','FAI',225,225,1,1),(8551,'Fairbury','FBY',225,225,1,1),(8552,'Fairfield','SUU',225,225,1,1),(8554,'Fairmont','FRM',225,225,1,1),(8555,'Fallon','NFL',225,225,1,1),(8556,'Falls Bay','FLJ',225,225,1,1),(8557,'Falmouth','FMH',225,225,1,1),(8558,'False Island','FAK',225,225,1,1),(8559,'False Pass','KFP',225,225,1,1),(8560,'Farewell','FWL',225,225,1,1),(8561,'Fargo','FAR',225,225,1,1),(8562,'Faribault','FBL',225,225,1,1),(8563,'Farmingdale','FRG',225,225,1,1),(8564,'Farmington','FAM',225,225,1,1),(8566,'Fayetteville','FYV',225,225,1,1),(8570,'Fergus Falls','FFM',225,225,1,1),(8571,'Filadelfia','PNE',225,225,1,1),(8574,'Fillmore','FIL',225,225,1,1),(8575,'Fin Creek','FNK',225,225,1,1),(8576,'Findlay','FDY',225,225,1,1),(8577,'Fire Cove','FIC',225,225,1,1),(8578,'Fishers Island','FID',225,225,1,1),(8579,'Five Finger','FIV',225,225,1,1),(8580,'Five Mile','FMC',225,225,1,1),(8581,'Flat','FLT',225,225,1,1),(8582,'Flaxman Island','FXM',225,225,1,1),(8583,'Flint','FNT',225,225,1,1),(8584,'Flippin','FLP',225,225,1,1),(8585,'Florencia','FLO',225,225,1,1),(8586,'Foley','NHX',225,225,1,1),(8587,'Fond Du Lac','FLD',225,225,1,1),(8588,'Forest City','FXY',225,225,1,1),(8589,'Forest Park','FOP',225,225,1,1),(8590,'Forrest City','FCY',225,225,1,1),(8591,'Fort Belvoir','DAA',225,225,1,1),(8592,'Fort Bragg','FBG',225,225,1,1),(8594,'Fort Bridger','FBR',225,225,1,1),(8595,'Fort Collins Love','FNL',225,225,1,1),(8596,'Fort Dix','WRI',225,225,1,1),(8597,'Fort Dodge','FOD',225,225,1,1),(8598,'Fort Eustis','FAF',225,225,1,1),(8599,'Fort Huachuca','FHU',225,225,1,1),(8600,'Fort Indiantown','MUI',225,225,1,1),(8601,'Fort Irwin','BYS',225,225,1,1),(8602,'Fort Jefferson','RBN',225,225,1,1),(8603,'Fort Knox','FTK',225,225,1,1),(8604,'Fort Lauderdale','FLL',225,225,1,1),(8606,'Fort Madison','FMS',225,225,1,1),(8607,'Fort Meade','FME',225,225,1,1),(8608,'Fort Myers','FMY',225,225,1,1),(8610,'Fort Pierce','FPR',225,225,1,1),(8611,'Fort Polk','POE',225,225,1,1),(8612,'Fort Richardson','FRN',225,225,1,1),(8613,'Fort Riley','FRI',225,225,1,1),(8614,'Fort Scott','FSK',225,225,1,1),(8615,'Fort Sheridan','FSN',225,225,1,1),(8616,'Fort Sill','FSI',225,225,1,1),(8617,'Fort Smith','FSM',225,225,1,1),(8618,'Fort Stockton','FST',225,225,1,1),(8619,'Fort Sumner','FSU',225,225,1,1),(8620,'Fort Washington','QFW',225,225,1,1),(8621,'Fort Wayne','FWA',225,225,1,1),(8623,'Fort Yukon','FYU',225,225,1,1),(8624,'Fortuna Ledge','FTL',225,225,1,1),(8625,'Fox','FOX',225,225,1,1),(8626,'Franconia','ZFO',225,225,1,1),(8627,'Frankfort','FFT',225,225,1,1),(8628,'Franklin','FKN',225,225,1,1),(8630,'Frederick','FDR',225,225,1,1),(8632,'Freeport','FEP',225,225,1,1),(8633,'Fremont','FET',225,225,1,1),(8634,'French Lick','FRH',225,225,1,1),(8635,'Frenchville','WFK',225,225,1,1),(8636,'Fresh Water Bay','FRP',225,225,1,1),(8637,'Fresno','FAT',225,225,1,1),(8638,'Friday Harbor','FRD',225,225,1,1),(8639,'Front Royal','FRR',225,225,1,1),(8640,'Fryeburg','FRY',225,225,1,1),(8641,'Ft  Benning','QFE',225,225,1,1),(8642,'Ftlenwood Tribune','TBN',225,225,1,1),(8643,'Fullerton','FUL',225,225,1,1),(8644,'Funter Bay','FNR',225,225,1,1),(8645,'Gabbs','GAB',225,225,1,1),(8646,'Gadsden','GAD',225,225,1,1),(8647,'Gage','GAG',225,225,1,1),(8648,'Gainesville','GNV',225,225,1,1),(8651,'Gaithersburg','GAI',225,225,1,1),(8652,'Gakona','GAK',225,225,1,1),(8653,'Galbraith Lake','GBH',225,225,1,1),(8654,'Galena','GAL',225,225,1,1),(8655,'Galesburg','GBG',225,225,1,1),(8656,'Galion','GQQ',225,225,1,1),(8657,'Gallup','GUP',225,225,1,1),(8658,'Galveston','GLS',225,225,1,1),(8659,'Gambell','GAM',225,225,1,1),(8660,'Ganes Creek','GEK',225,225,1,1),(8661,'Garden City','JHC',225,225,1,1),(8663,'Gardner','GDM',225,225,1,1),(8664,'Gatlinburg','GKT',225,225,1,1),(8665,'Gaylord','GLR',225,225,1,1),(8666,'Georgetown','GED',225,225,1,1),(8668,'Gettysburg','GTY',225,225,1,1),(8669,'Gillette','GCC',225,225,1,1),(8670,'Glacier Creek','KGZ',225,225,1,1),(8671,'Gladwin','GDW',225,225,1,1),(8672,'Glasgow','GGW',225,225,1,1),(8674,'Glendale','JGX',225,225,1,1),(8676,'Glendive','GDV',225,225,1,1),(8677,'Glennallen','GLQ',225,225,1,1),(8678,'Glens Falls','GFL',225,225,1,1),(8679,'Glenview','NBU',225,225,1,1),(8680,'Glenwood Springs','GWS',225,225,1,1),(8681,'Glynco','NEA',225,225,1,1),(8682,'Gold Beach','GOL',225,225,1,1),(8683,'Goldsboro','GSB',225,225,1,1),(8684,'Golovin','GLV',225,225,1,1),(8685,'Gooding','GNG',225,225,1,1),(8686,'Goodland','GLD',225,225,1,1),(8687,'Goodnews Bay','GNU',225,225,1,1),(8688,'Goodyear','GYR',225,225,1,1),(8689,'Gordon','GRN',225,225,1,1),(8690,'Gordonsville','GVE',225,225,1,1),(8691,'Goshen','GSH',225,225,1,1),(8692,'Grand Canyon','GCN',225,225,1,1),(8694,'Grand Canyon West','GCW',225,225,1,1),(8695,'Grand Forks','GFK',225,225,1,1),(8696,'Grand Island','GRI',225,225,1,1),(8697,'Grand Junction','GJT',225,225,1,1),(8698,'Grand Marais','GRM',225,225,1,1),(8699,'Grand Rapids','GPZ',225,225,1,1),(8701,'Grandview','GVW',225,225,1,1),(8702,'Granite Mountain','GMT',225,225,1,1),(8703,'Grants','GNT',225,225,1,1),(8704,'Grantsburg','GTG',225,225,1,1),(8705,'Grayling','KGX',225,225,1,1),(8706,'Great Barrington','GBR',225,225,1,1),(8707,'Great Bend','GBD',225,225,1,1),(8708,'Great Falls','GTF',225,225,1,1),(8709,'Greeley','GXY',225,225,1,1),(8710,'Green Bay','GRB',225,225,1,1),(8711,'Green River','RVR',225,225,1,1),(8712,'Greenfield','GFD',225,225,1,1),(8713,'Greensboro','GSO',225,225,1,1),(8714,'Greenville','GSP',225,225,1,1),(8720,'Greenwich Mean TM','ZZZ',225,225,1,1),(8721,'Greenwood','GWO',225,225,1,1),(8723,'Greybull','GEY',225,225,1,1),(8724,'Groton','GON',225,225,1,1),(8725,'Gulf Shores','GUF',225,225,1,1),(8726,'Gulfport','GPT',225,225,1,1),(8727,'Gulkana','GKN',225,225,1,1),(8728,'Gunnison','GUC',225,225,1,1),(8729,'Gustavus','GST',225,225,1,1),(8730,'Guthrie','GOK',225,225,1,1),(8731,'Guymon','GUY',225,225,1,1),(8732,'Hagerstown','HGR',225,225,1,1),(8733,'Hailey','SUN',225,225,1,1),(8734,'Haines','HNS',225,225,1,1),(8735,'Half Moon Bay','HAF',225,225,1,1),(8736,'Hamilton','HAO',225,225,1,1),(8738,'Hampton','HPT',225,225,1,1),(8740,'Hana','HNM',225,225,1,1),(8741,'Hanapepe','PAK',225,225,1,1),(8742,'Hancock','CMX',225,225,1,1),(8743,'Hanksville','HVE',225,225,1,1),(8744,'Hanna','HNX',225,225,1,1),(8745,'Hanus Bay','HBC',225,225,1,1),(8746,'Harlingen','HRL',225,225,1,1),(8747,'Harrisburg','MDT',225,225,1,1),(8750,'Harrison','HRO',225,225,1,1),(8751,'Hartford','BDL',225,225,1,1),(8753,'Hartsville','HVS',225,225,1,1),(8754,'Hastings','HSI',225,225,1,1),(8755,'Hatteras','HNC',225,225,1,1),(8756,'Hattiesburg','HBG',225,225,1,1),(8757,'Havasupai','HAE',225,225,1,1),(8758,'Havre','HVR',225,225,1,1),(8759,'Hawthorne','HTH',225,225,1,1),(8761,'Haycock','HAY',225,225,1,1),(8762,'Hayden','SBS',225,225,1,1),(8764,'Hays','HYS',225,225,1,1),(8765,'Hayward','HWD',225,225,1,1),(8767,'Hazleton','HZL',225,225,1,1),(8768,'Healy Lake','HKB',225,225,1,1),(8769,'Helena','HEE',225,225,1,1),(8771,'Hemet','HMT',225,225,1,1),(8772,'Herendeen','HED',225,225,1,1),(8773,'Herlong','AHC',225,225,1,1),(8774,'Hermiston','HES',225,225,1,1),(8775,'Hibbing','HIB',225,225,1,1),(8776,'Hickory','HKY',225,225,1,1),(8777,'Hidden Falls','HDA',225,225,1,1),(8778,'Hill City','HLC',225,225,1,1),(8779,'Hillsboro','HIO',225,225,1,1),(8780,'Hilo','ITO',225,225,1,1),(8781,'Hilton Head','HHH',225,225,1,1),(8782,'Hinesville','LIY',225,225,1,1),(8783,'Hobart','HBR',225,225,1,1),(8784,'Hobart Bay','HBH',225,225,1,1),(8785,'Hobbs','HOB',225,225,1,1),(8786,'Hoffman','HFF',225,225,1,1),(8787,'Hogatza','HGZ',225,225,1,1),(8788,'Holdrege','HDE',225,225,1,1),(8789,'Holikachu','HOL',225,225,1,1),(8790,'Holland','HLM',225,225,1,1),(8791,'Hollis','HYL',225,225,1,1),(8792,'Hollister','HLI',225,225,1,1),(8793,'Hollywood','HWO',225,225,1,1),(8794,'Holy Cross','HCR',225,225,1,1),(8795,'Homer','HOM',225,225,1,1),(8796,'Homeshore','HMS',225,225,1,1),(8797,'Homestead','HST',225,225,1,1),(8798,'Honolulu','HNL',225,225,1,1),(8799,'Hoolehua','MKK',225,225,1,1),(8800,'Hoonah','HNH',225,225,1,1),(8801,'Hooper Bay','HPB',225,225,1,1),(8802,'Hopkinsville','HOP',225,225,1,1),(8803,'Hot Springs','HSP',225,225,1,1),(8805,'Houghton','HTL',225,225,1,1),(8806,'Houlton','HUL',225,225,1,1),(8807,'Houma','HUM',225,225,1,1),(8808,'Houston','EFD',225,225,1,1),(8813,'Hudson','HCC',225,225,1,1),(8814,'Hughes','HUS',225,225,1,1),(8815,'Hugo','HUJ',225,225,1,1),(8816,'Humboldt','HBO',225,225,1,1),(8818,'Huntingburg','HNB',225,225,1,1),(8819,'Huntington','HTW',225,225,1,1),(8821,'Huntsville','HTV',225,225,1,1),(8824,'Huron','HON',225,225,1,1),(8825,'Huslia','HSL',225,225,1,1),(8826,'Hutchison','HUT',225,225,1,1),(8827,'Hyannis','HYA',225,225,1,1),(8828,'Hydaburg','HYG',225,225,1,1),(8829,'Icy Bay','ICY',225,225,1,1),(8830,'Ida Grove','IDG',225,225,1,1),(8831,'Idaho Falls','IDA',225,225,1,1),(8832,'Igiugig','IGG',225,225,1,1),(8833,'Iliamna','ILI',225,225,1,1),(8834,'Immokalee','IMM',225,225,1,1),(8835,'Imperial','IML',225,225,1,1),(8836,'Imperial Beach','NRS',225,225,1,1),(8837,'Independence','IDP',225,225,1,1),(8839,'Indian Springs','INS',225,225,1,1),(8840,'Indiana','IDI',225,225,1,1),(8841,'Indianapolis','IND',225,225,1,1),(8842,'International Falls','INL',225,225,1,1),(8843,'Inyokern','IYK',225,225,1,1),(8844,'Iowa Ciudad','IOW',225,225,1,1),(8845,'Iowa Falls','IFA',225,225,1,1),(8846,'Iraan','IRB',225,225,1,1),(8847,'Iron Mountain','IMT',225,225,1,1),(8848,'Ironwood','IWD',225,225,1,1),(8849,'Isabel Pass','ISL',225,225,1,1),(8850,'Islip','ISP',225,225,1,1),(8851,'Ithaca','ITH',225,225,1,1),(8852,'Ivishak','IVH',225,225,1,1),(8853,'Jackpot','KPT',225,225,1,1),(8854,'Jackson','MJQ',225,225,1,1),(8859,'Jacksonville','OAJ',225,225,1,1),(8864,'Jaffrey','AFN',225,225,1,1),(8865,'Jahrom','JAR',225,225,1,1),(8866,'Jamestown','JMS',225,225,1,1),(8868,'Janesville','JVL',225,225,1,1),(8869,'Jasper','JAS',225,225,1,1),(8871,'Jefferson','EFW',225,225,1,1),(8873,'Jefferson City','JEF',225,225,1,1),(8874,'John Day','JDA',225,225,1,1),(8875,'Johnson','JCY',225,225,1,1),(8876,'Johnstown','JST',225,225,1,1),(8877,'Joliet','JOT',225,225,1,1),(8878,'Jolon','HGT',225,225,1,1),(8879,'Jonesboro','JBR',225,225,1,1),(8880,'Joplin','JLN',225,225,1,1),(8881,'Jordan','JDN',225,225,1,1),(8882,'Junction','JCT',225,225,1,1),(8883,'Juneau','UNU',225,225,1,1),(8885,'Kaanapali Maui','HKP',225,225,1,1),(8886,'Kagvik Creek','KKF',225,225,1,1),(8887,'Kahului','OGG',225,225,1,1),(8888,'Kaiser Lake Ozark','AIZ',225,225,1,1),(8889,'Kake','KAE',225,225,1,1),(8890,'Kakhonak','KNK',225,225,1,1),(8891,'Kalamazoo','AZO',225,225,1,1),(8892,'Kalaupapa','LUP',225,225,1,1),(8893,'Kalispell','FCA',225,225,1,1),(8894,'Kalskag','KLG',225,225,1,1),(8895,'Kaltag','KAL',225,225,1,1),(8896,'Kamuela','MUE',225,225,1,1),(8897,'Kanab','KNB',225,225,1,1),(8898,'Kankakee','IKK',225,225,1,1),(8899,'Kansas City','MCI',225,225,1,1),(8901,'Kapalua','JHM',225,225,1,1),(8902,'Karluk','KYK',225,225,1,1),(8903,'Kasigluk','KUK',225,225,1,1),(8904,'Kauai Island','LIH',225,225,1,1),(8905,'Kavik','VIK',225,225,1,1),(8906,'Kayenta','MVM',225,225,1,1),(8907,'Kearney','EAR',225,225,1,1),(8908,'Keene','EEN',225,225,1,1),(8909,'Kekaha','BKH',225,225,1,1),(8910,'Kelly Bar','KEU',225,225,1,1),(8911,'Kelp Bay','KLP',225,225,1,1),(8912,'Kelso','KLS',225,225,1,1),(8913,'Kemerer','EMM',225,225,1,1),(8914,'Kenai','ENA',225,225,1,1),(8915,'Kenmore Air Harbo','KEH',225,225,1,1),(8916,'Kennett','KNT',225,225,1,1),(8917,'Kenosha','ENW',225,225,1,1),(8918,'Kentland','KKT',225,225,1,1),(8919,'Keokuk','EOK',225,225,1,1),(8920,'Kerrville','ERV',225,225,1,1),(8921,'Ketchikan','KTN',225,225,1,1),(8922,'Key Largo','KYL',225,225,1,1),(8923,'Key West','EYW',225,225,1,1),(8924,'Keystone','QKS',225,225,1,1),(8925,'Kiana','IAN',225,225,1,1),(8926,'Kill Devil Hills','FFA',225,225,1,1),(8927,'Killeen','HLR',225,225,1,1),(8930,'King City','KIC',225,225,1,1),(8931,'King Cove','KVC',225,225,1,1),(8932,'King Of Prussia','KPD',225,225,1,1),(8933,'King Salmon','AKN',225,225,1,1),(8934,'Kingman','IGM',225,225,1,1),(8935,'Kingsville','NQI',225,225,1,1),(8936,'Kinston','ISO',225,225,1,1),(8937,'Kirksville','IRK',225,225,1,1),(8938,'Kissimmee','ISM',225,225,1,1),(8939,'Kitoi Bay','KKB',225,225,1,1),(8940,'Kivalina','KVL',225,225,1,1),(8941,'Kizhuyak','KZH',225,225,1,1),(8942,'Klag Bay','KBK',225,225,1,1),(8943,'Klamath Falls','LMT',225,225,1,1),(8944,'Klawock','KLW',225,225,1,1),(8945,'Knoxville','TYS',225,225,1,1),(8946,'Kobuk','OBU',225,225,1,1),(8947,'Kodiak','ADQ',225,225,1,1),(8948,'Kokomo','OKK',225,225,1,1),(8949,'Kona','KOA',225,225,1,1),(8950,'Kongiganak','KKH',225,225,1,1),(8951,'Kosciusko','OSX',225,225,1,1),(8952,'Kotlik','KOT',225,225,1,1),(8953,'Kotzebue','OTZ',225,225,1,1),(8954,'Koyuk','KKA',225,225,1,1),(8955,'Koyukuk','KYU',225,225,1,1),(8956,'Kugururok River','KUW',225,225,1,1),(8957,'Kulik Lake','LKK',225,225,1,1),(8958,'Kuparuk','UUK',225,225,1,1),(8959,'Kwethluk','KWT',225,225,1,1),(8960,'Kwigillingok','KWK',225,225,1,1),(8961,'La Crosse','LSE',225,225,1,1),(8962,'La Grande','LGD',225,225,1,1),(8963,'La Grange','LGC',225,225,1,1),(8964,'La Verne','POC',225,225,1,1),(8965,'Labouchere Bay','WLB',225,225,1,1),(8966,'Laconia','LCI',225,225,1,1),(8967,'Lafayette','LAF',225,225,1,1),(8969,'Lago De Ginebra','XES',225,225,1,1),(8970,'Lake Charles','LCH',225,225,1,1),(8971,'Lake City','LCQ',225,225,1,1),(8972,'Lake Havasu City','HII',225,225,1,1),(8973,'Lake Jackson','LJN',225,225,1,1),(8974,'Lake Minchumina','LMA',225,225,1,1),(8975,'Lake Placid','LKP',225,225,1,1),(8976,'Lake Tahoe South','TVL',225,225,1,1),(8977,'Lakehurst','NEL',225,225,1,1),(8978,'Lakeland','LAL',225,225,1,1),(8979,'Lakeside','LKS',225,225,1,1),(8980,'Lakeview','LKV',225,225,1,1),(8981,'Lamar','LAA',225,225,1,1),(8982,'Lanai City','LNY',225,225,1,1),(8983,'Lancaster','LNS',225,225,1,1),(8985,'Lander','LND',225,225,1,1),(8986,'Lansing','LAN',225,225,1,1),(8987,'Laporte','LPO',225,225,1,1),(8988,'Laramie','LAR',225,225,1,1),(8989,'Laredo','LRD',225,225,1,1),(8990,'Las Cruces','LRU',225,225,1,1),(8991,'Las Vegas','LVS',225,225,1,1),(8996,'Lathrop','LRO',225,225,1,1),(8997,'Lathrop Wells','LTH',225,225,1,1),(8998,'Latrobe','LBE',225,225,1,1),(8999,'Laurel','LUL',225,225,1,1),(9001,'Lawrence','LWM',225,225,1,1),(9003,'Lawrenceville','LVL',225,225,1,1),(9006,'Lawton','LAW',225,225,1,1),(9007,'Leadville','LXV',225,225,1,1),(9008,'Lebanon','LEB',225,225,1,1),(9009,'Leesburg','LEE',225,225,1,1),(9010,'Lemars','LRJ',225,225,1,1),(9011,'Lemmon','LEM',225,225,1,1),(9012,'Lemoore','NLC',225,225,1,1),(9013,'Leonardtown','LTW',225,225,1,1),(9014,'Levelock','KLL',225,225,1,1),(9015,'Lewisburg','LWB',225,225,1,1),(9016,'Lewiston','LEW',225,225,1,1),(9018,'Lewistown','LWT',225,225,1,1),(9019,'Lexington','LXN',225,225,1,1),(9021,'Liberal','LBL',225,225,1,1),(9022,'Lima','AOH',225,225,1,1),(9023,'Lime Village','LVD',225,225,1,1),(9024,'Limestone','LIZ',225,225,1,1),(9025,'Limon','LIC',225,225,1,1),(9026,'Lincoln','LNK',225,225,1,1),(9027,'Linden','LDJ',225,225,1,1),(9028,'Little Naukati','WLN',225,225,1,1),(9029,'Little Port Walte','LPW',225,225,1,1),(9030,'Little Rock','LIT',225,225,1,1),(9031,'Livengood','LIV',225,225,1,1),(9032,'Livermore','LVK',225,225,1,1),(9033,'Livingston','LVM',225,225,1,1),(9034,'Lock Haven','LHV',225,225,1,1),(9035,'Lockport','LOT',225,225,1,1),(9036,'Logan','LGU',225,225,1,1),(9037,'Lompoc','LPC',225,225,1,1),(9038,'Londres','LOZ',225,225,1,1),(9039,'Lone Rock','LNR',225,225,1,1),(9040,'Lonely','LNI',225,225,1,1),(9041,'Long Beach','LGB',225,225,1,1),(9042,'Long Island','LIJ',225,225,1,1),(9043,'Longmount','QWM',225,225,1,1),(9044,'Longview','GGG',225,225,1,1),(9046,'Lopez Island','LPS',225,225,1,1),(9047,'Lordsburg','LSB',225,225,1,1),(9048,'Loring','WLR',225,225,1,1),(9049,'Los Alamos','LAM',225,225,1,1),(9050,'Los angeles','VNY',225,225,1,1),(9052,'Los Banos','LSN',225,225,1,1),(9053,'Lost Harbor','LHB',225,225,1,1),(9054,'Lost River','LSR',225,225,1,1),(9055,'Louisa','LOW',225,225,1,1),(9056,'Louisburg','LFN',225,225,1,1),(9057,'Louisville','LOU',225,225,1,1),(9060,'Loveland','QWH',225,225,1,1),(9061,'Lovelock','LOL',225,225,1,1),(9062,'Lubbock','LBB',225,225,1,1),(9063,'Ludington','LDM',225,225,1,1),(9064,'Lufkin','LFK',225,225,1,1),(9066,'Lumberton','LBT',225,225,1,1),(9067,'Lusk','LSK',225,225,1,1),(9068,'Lynchburg','LYH',225,225,1,1),(9069,'Lyndonville','LLX',225,225,1,1),(9070,'Lyons','LYO',225,225,1,1),(9071,'Machesney Rockford','RMC',225,225,1,1),(9072,'Mackinac Island','MCD',225,225,1,1),(9073,'Macomb','MQB',225,225,1,1),(9074,'Macon','MCN',225,225,1,1),(9075,'Madera','MAE',225,225,1,1),(9076,'Madison','MSN',225,225,1,1),(9080,'Madras','MDJ',225,225,1,1),(9081,'Magnolia','AGO',225,225,1,1),(9082,'Malad City','MLD',225,225,1,1),(9083,'Malden','MAW',225,225,1,1),(9084,'Malta','MLK',225,225,1,1),(9085,'Mammoth Lakes','MMH',225,225,1,1),(9086,'Manassas','MNZ',225,225,1,1),(9087,'Manchester','MHT',225,225,1,1),(9088,'Manhattan','MHK',225,225,1,1),(9089,'Manila','MXA',225,225,1,1),(9090,'Manistee','MBL',225,225,1,1),(9091,'Manistique','ISQ',225,225,1,1),(9092,'Manitowoc','MTW',225,225,1,1),(9093,'Mankato','MKT',225,225,1,1),(9094,'Manley Hot Spring','MLY',225,225,1,1),(9095,'Mansfield','MFD',225,225,1,1),(9096,'Manteo','MEO',225,225,1,1),(9097,'Manti','NTJ',225,225,1,1),(9098,'Manville','JVI',225,225,1,1),(9099,'Marana','MZJ',225,225,1,1),(9100,'Marathon','MTH',225,225,1,1),(9101,'Marble Canyon','MYH',225,225,1,1),(9102,'Marco Island','MRK',225,225,1,1),(9103,'Marfa','MRF',225,225,1,1),(9104,'Marguerite Bay','RTE',225,225,1,1),(9105,'Marietta','MGE',225,225,1,1),(9106,'Marinette Menomin','MNM',225,225,1,1),(9107,'Marion','MZZ',225,225,1,1),(9110,'Marks','MMS',225,225,1,1),(9111,'Marlborough','MXG',225,225,1,1),(9112,'Marquette','MQT',225,225,1,1),(9113,'Marshall','MML',225,225,1,1),(9116,'Marshall Fortuna','MLL',225,225,1,1),(9117,'Marshalltown','MIW',225,225,1,1),(9118,'Marshfield','MFI',225,225,1,1),(9119,'Marthas Vineyard','MVY',225,225,1,1),(9120,'Martinsburg','MRB',225,225,1,1),(9121,'Marysville','MYV',225,225,1,1),(9122,'Mason City','MCW',225,225,1,1),(9123,'Massena','MSS',225,225,1,1),(9124,'Mattoon','MTO',225,225,1,1),(9125,'Maxton','MXE',225,225,1,1),(9126,'May Creek','MYK',225,225,1,1),(9127,'Mayport','NRB',225,225,1,1),(9128,'Mc Alester','MLC',225,225,1,1),(9129,'Mc Rae','MQW',225,225,1,1),(9130,'McAllen','MFE',225,225,1,1),(9131,'Mccall','MYL',225,225,1,1),(9132,'Mccarthy','MXY',225,225,1,1),(9133,'Mccomb','MCB',225,225,1,1),(9134,'McCook','MCK',225,225,1,1),(9135,'McGrath','MCG',225,225,1,1),(9136,'Mcminnville','RNC',225,225,1,1),(9137,'Mcpherson','MPR',225,225,1,1),(9138,'Meadville','MEJ',225,225,1,1),(9139,'Medford','MDF',225,225,1,1),(9141,'Medfra','MDR',225,225,1,1),(9142,'Mekoryuk','MYU',225,225,1,1),(9143,'Melbourne','MLB',225,225,1,1),(9144,'Melfa','MFV',225,225,1,1),(9145,'Melville','ZMV',225,225,1,1),(9146,'Memphis','NQA',225,225,1,1),(9148,'Merced','MCE',225,225,1,1),(9150,'Mercury','DRA',225,225,1,1),(9151,'Meridian','MEI',225,225,1,1),(9152,'Merrill','RRL',225,225,1,1),(9153,'Mesa','MSC',225,225,1,1),(9154,'Mesquite','MFH',225,225,1,1),(9157,'Miami','MIA',225,225,1,1),(9160,'Michigan City','MGC',225,225,1,1),(9161,'Middleton Island','MDO',225,225,1,1),(9162,'Middletown','MWO',225,225,1,1),(9163,'Midland','MAF',225,225,1,1),(9164,'Miles City','MLS',225,225,1,1),(9165,'Milford','MLF',225,225,1,1),(9166,'Milledgeville','MLJ',225,225,1,1),(9167,'Millinocket','MLT',225,225,1,1),(9168,'Millville','MIV',225,225,1,1),(9169,'Milton','NSE',225,225,1,1),(9170,'Milwaukee','MKE',225,225,1,1),(9171,'Minchumina','MHM',225,225,1,1),(9172,'Minden','MEV',225,225,1,1),(9173,'Mineapolis','MSP',225,225,1,1),(9174,'Mineral Wells','MWL',225,225,1,1),(9175,'Minocqua','ARV',225,225,1,1),(9176,'Minot','MOT',225,225,1,1),(9177,'Minto','MNT',225,225,1,1),(9178,'Missoula','MSO',225,225,1,1),(9179,'Mitchell','MHE',225,225,1,1),(9180,'Moab','CNY',225,225,1,1),(9181,'Moberly','MBY',225,225,1,1),(9182,'Mobile','MOB',225,225,1,1),(9184,'Mobridge','MBG',225,225,1,1),(9185,'Modesto','MOD',225,225,1,1),(9186,'Mojave','MHV',225,225,1,1),(9187,'Moline','MLI',225,225,1,1),(9188,'Monahans','MIF',225,225,1,1),(9189,'Monroe','MLU',225,225,1,1),(9190,'Monroeville','MVC',225,225,1,1),(9191,'Montague','SIY',225,225,1,1),(9192,'Montauk','MTP',225,225,1,1),(9193,'Monterey','MRY',225,225,1,1),(9195,'Montevideo','MVE',225,225,1,1),(9196,'Montgomery','MGJ',225,225,1,1),(9198,'Monticello','MXC',225,225,1,1),(9201,'Montpelier','MPV',225,225,1,1),(9202,'Montrose','MTJ',225,225,1,1),(9203,'Montvale','QMV',225,225,1,1),(9204,'Monument Valley','GMV',225,225,1,1),(9205,'Morgan City','PTN',225,225,1,1),(9206,'Morganton','MRN',225,225,1,1),(9207,'Morgantown','MGW',225,225,1,1),(9208,'Morris','MOX',225,225,1,1),(9209,'Morristown','MMU',225,225,1,1),(9211,'Moser Bay','KMY',225,225,1,1),(9212,'Moses Lake','MWH',225,225,1,1),(9213,'Moses Point','MOS',225,225,1,1),(9214,'Moultrie','MGR',225,225,1,1),(9215,'Mount Holly','LLY',225,225,1,1),(9216,'Mount McKinley','MCL',225,225,1,1),(9217,'Mount Pleasant','MOP',225,225,1,1),(9219,'Mount Shasta','MHS',225,225,1,1),(9220,'Mount Union','MUU',225,225,1,1),(9221,'Mount Vernon','MVW',225,225,1,1),(9222,'Mount Wilson','MWS',225,225,1,1),(9223,'Mountain Home','MUO',225,225,1,1),(9225,'Mountain View','NUQ',225,225,1,1),(9226,'Mountain Village','MOU',225,225,1,1),(9227,'Mt Clemens','MTC',225,225,1,1),(9228,'Mt Pleasant','MPZ',225,225,1,1),(9230,'Mt Pocono','MPO',225,225,1,1),(9231,'Mt Vernon','MVN',225,225,1,1),(9232,'Mullen','MHN',225,225,1,1),(9233,'Muncie','MIE',225,225,1,1),(9234,'Murray','CEY',225,225,1,1),(9235,'Muscatine','MUT',225,225,1,1),(9236,'Muscle Shoals','MSL',225,225,1,1),(9237,'Muskegon','MKG',225,225,1,1),(9238,'Muskogee','MKO',225,225,1,1),(9239,'Myrtle Beach','MYR',225,225,1,1),(9240,'N Bend Coosbay','OTH',225,225,1,1),(9241,'Naknek','NNK',225,225,1,1),(9242,'Nakolik River','NOL',225,225,1,1),(9243,'Nantucket','ACK',225,225,1,1),(9244,'Nanwalek','KEB',225,225,1,1),(9245,'Napa','APC',225,225,1,1),(9246,'Napaiskak','PKA',225,225,1,1),(9247,'Napoles','APF',225,225,1,1),(9248,'Nashua','ASH',225,225,1,1),(9249,'Nashville','BNA',225,225,1,1),(9250,'Natchez','HEZ',225,225,1,1),(9251,'Naukiti','NKI',225,225,1,1),(9252,'Needles','EED',225,225,1,1),(9253,'Nelson Lagoon','NLG',225,225,1,1),(9254,'Nenana','ENN',225,225,1,1),(9255,'Neosho','EOS',225,225,1,1),(9256,'Nephi','NPH',225,225,1,1),(9257,'Nevada','NVD',225,225,1,1),(9258,'New Bedford','EWB',225,225,1,1),(9259,'New Bern','EWN',225,225,1,1),(9260,'New Chenega','NCN',225,225,1,1),(9261,'New Haven','ZVE',225,225,1,1),(9263,'New Iberia','ARA',225,225,1,1),(9264,'New Koliganek','KGK',225,225,1,1),(9265,'New Philadelphia','PHD',225,225,1,1),(9266,'New Richmond','RNH',225,225,1,1),(9267,'New Stuyahok','KNW',225,225,1,1),(9268,'New Ulm','ULM',225,225,1,1),(9269,'Newburgh','SWF',225,225,1,1),(9270,'Newcastle','ECS',225,225,1,1),(9271,'Newport','NWH',225,225,1,1),(9275,'Newport Beach','JNP',225,225,1,1),(9276,'Newtok','WWT',225,225,1,1),(9277,'Newton','TNU',225,225,1,1),(9279,'Niagara Falls','IAG',225,225,1,1),(9280,'Niblack','NIE',225,225,1,1),(9281,'Nichen Cove','NKV',225,225,1,1),(9282,'Nightmute','NME',225,225,1,1),(9283,'Nikolai','NIB',225,225,1,1),(9284,'Nikolski','IKO',225,225,1,1),(9285,'Niles','NLE',225,225,1,1),(9286,'Ninilchik','NIN',225,225,1,1),(9287,'Noatak','WTK',225,225,1,1),(9288,'Nogales','OLS',225,225,1,1),(9289,'Nome','OME',225,225,1,1),(9290,'Nondalton','NNL',225,225,1,1),(9291,'Noorvik','ORV',225,225,1,1),(9292,'Norfolk','NGU',225,225,1,1),(9295,'Norman','OUN',225,225,1,1),(9296,'Norridgewock','OWK',225,225,1,1),(9297,'North Platte','LBF',225,225,1,1),(9298,'Northbrook','OBK',225,225,1,1),(9299,'Northway','ORT',225,225,1,1),(9300,'Norwalk','ORQ',225,225,1,1),(9301,'Norwich','OIC',225,225,1,1),(9302,'Norwood','OWD',225,225,1,1),(9303,'Novato','NOT',225,225,1,1),(9304,'Nueva Orleans','NBG',225,225,1,1),(9306,'Nueva York','LGA',225,225,1,1),(9310,'Nuiqsut','NUI',225,225,1,1),(9311,'Nulato','NUL',225,225,1,1),(9312,'Nunapitchuk','NUP',225,225,1,1),(9313,'Nyac','ZNC',225,225,1,1),(9314,'Oahu','HDH',225,225,1,1),(9315,'Oak Brook','QHO',225,225,1,1),(9316,'Oak Harbor','ODW',225,225,1,1),(9317,'Oakland','ODM',225,225,1,1),(9319,'Oaktown','OTN',225,225,1,1),(9320,'Ocala','OCF',225,225,1,1),(9321,'Ocean City','OCE',225,225,1,1),(9322,'Ocean Reef','OCA',225,225,1,1),(9323,'Oceanic','OCI',225,225,1,1),(9324,'Oceanside','OCN',225,225,1,1),(9325,'Ogallala','OGA',225,225,1,1),(9326,'Ogden','OGD',225,225,1,1),(9327,'Ogdensburg','OGS',225,225,1,1),(9328,'Oil City','OIL',225,225,1,1),(9329,'Okeechobee','OBE',225,225,1,1),(9330,'Oklahoma City','TIK',225,225,1,1),(9332,'Okmulgee','OKM',225,225,1,1),(9333,'Old Town','OLD',225,225,1,1),(9334,'Olean','OLE',225,225,1,1),(9335,'Olive Branch','OLV',225,225,1,1),(9336,'Olney','ONY',225,225,1,1),(9338,'Olympia','OLM',225,225,1,1),(9339,'Omaha','OFF',225,225,1,1),(9341,'Omak','OMK',225,225,1,1),(9342,'Oneill','ONL',225,225,1,1),(9343,'Oneonta','ONH',225,225,1,1),(9344,'Onion Bay','ONN',225,225,1,1),(9345,'Ontario','ONT',225,225,1,1),(9347,'Opelousas','OPL',225,225,1,1),(9348,'Orange','JOR',225,225,1,1),(9349,'Orangeburg','OGB',225,225,1,1),(9350,'Orlando','SFB',225,225,1,1),(9354,'Oroville','OVE',225,225,1,1),(9355,'Osage Beach','OSB',225,225,1,1),(9356,'Osceola','OEO',225,225,1,1),(9357,'Oscoda','OSC',225,225,1,1),(9358,'Oshkosh','OKS',225,225,1,1),(9360,'Oskaloosa','OOA',225,225,1,1),(9361,'Otto','OTO',225,225,1,1),(9362,'Ottumwa','OTM',225,225,1,1),(9363,'Owatonna','OWA',225,225,1,1),(9364,'Owensboro','OWB',225,225,1,1),(9365,'Oxford','OXD',225,225,1,1),(9368,'Oxnard','OXR',225,225,1,1),(9369,'Ozark','OZR',225,225,1,1),(9370,'Ozona','OZA',225,225,1,1),(9371,'Pacific City','PFC',225,225,1,1),(9372,'Pack Creek','PBK',225,225,1,1),(9373,'Paducah','PAH',225,225,1,1),(9374,'Paf Warren','PFA',225,225,1,1),(9375,'Page','PGA',225,225,1,1),(9376,'Pagosa Springs','PGO',225,225,1,1),(9377,'Pahokee','PHK',225,225,1,1),(9378,'Paimiut','PMU',225,225,1,1),(9379,'Painesville','PVZ',225,225,1,1),(9380,'Painter Creek','PCE',225,225,1,1),(9381,'Palacios','PSX',225,225,1,1),(9382,'Palestine','PSN',225,225,1,1),(9383,'Palm Springs','PSP',225,225,1,1),(9384,'Palmdale','PMD',225,225,1,1),(9385,'Palmer','PMX',225,225,1,1),(9387,'Palo Alto','PAO',225,225,1,1),(9388,'Pampa','PPA',225,225,1,1),(9389,'Panama Ciudad','PAM',225,225,1,1),(9391,'Panguitch','PNU',225,225,1,1),(9392,'Paonia','WPO',225,225,1,1),(9393,'Paragould','PGR',225,225,1,1),(9394,'Paris','PRX',225,225,1,1),(9396,'Park Falls','PKF',225,225,1,1),(9397,'Park Rapids','PKD',225,225,1,1),(9398,'Parkersburg','PKB',225,225,1,1),(9399,'Pasadena','JPD',225,225,1,1),(9400,'Pascagoula','PGL',225,225,1,1),(9401,'Pasco','PSC',225,225,1,1),(9402,'Paso Robles','PRB',225,225,1,1),(9403,'Payson','PJB',225,225,1,1),(9404,'Peach Springs','PGS',225,225,1,1),(9405,'Pecos City','PEQ',225,225,1,1),(9406,'Pedro Bay','PDB',225,225,1,1),(9407,'Pell City','PLR',225,225,1,1),(9408,'Pellston','PLN',225,225,1,1),(9409,'Pembina','PMB',225,225,1,1),(9410,'Pendleton','PDT',225,225,1,1),(9411,'Pensacola','PNS',225,225,1,1),(9412,'Peoria','PIA',225,225,1,1),(9413,'Perry','FPY',225,225,1,1),(9415,'Peru','GUS',225,225,1,1),(9417,'Petersburg','PSG',225,225,1,1),(9420,'Peterson\'s Point','PNF',225,225,1,1),(9421,'Philip','PHP',225,225,1,1),(9422,'Philipsburg','PSB',225,225,1,1),(9423,'Phoenix','AZA',225,225,1,1),(9426,'Picayune','PCU',225,225,1,1),(9427,'Pickens','LQK',225,225,1,1),(9428,'Pierre','PIR',225,225,1,1),(9429,'Pilot Point','PIP',225,225,1,1),(9430,'Pilot Station','PQS',225,225,1,1),(9431,'Pine Bluff','PBF',225,225,1,1),(9432,'Pine Mountain','PIM',225,225,1,1),(9433,'Pine Ridge','XPR',225,225,1,1),(9434,'Pittsburg','PTS',225,225,1,1),(9435,'Pittsburgh','PIT',225,225,1,1),(9436,'Pittsfield','PSF',225,225,1,1),(9437,'Placerville','PVF',225,225,1,1),(9438,'Plainview','PVW',225,225,1,1),(9439,'Platinum','PTU',225,225,1,1),(9440,'Plattsburgh','PBG',225,225,1,1),(9441,'Pleasant Harbour','PTR',225,225,1,1),(9442,'Pleasanton','JBS',225,225,1,1),(9443,'Plentywood','PWD',225,225,1,1),(9444,'Plymouth','PYM',225,225,1,1),(9446,'Pocahontas','POH',225,225,1,1),(9447,'Pocatello','PIH',225,225,1,1),(9448,'Pohakuloa','BSF',225,225,1,1),(9449,'Point Hope','PHO',225,225,1,1),(9450,'Point Lay','PIZ',225,225,1,1),(9451,'Polacca','PXL',225,225,1,1),(9452,'Polk Inlet','POQ',225,225,1,1),(9453,'Pompano Beach','PPM',225,225,1,1),(9454,'Ponca City','PNC',225,225,1,1),(9455,'Pontiac','PTK',225,225,1,1),(9456,'Pope Vanoy','PVY',225,225,1,1),(9457,'Poplar Bluff','POF',225,225,1,1),(9458,'Porcupine Creek','PCK',225,225,1,1),(9459,'Port Alexander','PTD',225,225,1,1),(9460,'Port Alice','PTC',225,225,1,1),(9461,'Port Alsworth','PTA',225,225,1,1),(9462,'Port Angeles','CLM',225,225,1,1),(9463,'Port Armstrong','PTL',225,225,1,1),(9464,'Port Canaveral','XPC',225,225,1,1),(9465,'Port Clarence','KPC',225,225,1,1),(9466,'Port Frederick','PFD',225,225,1,1),(9467,'Port Graham','PGM',225,225,1,1),(9468,'Port Heiden','PTH',225,225,1,1),(9469,'Port Hueneme','NTD',225,225,1,1),(9470,'Port Huron','PHN',225,225,1,1),(9471,'Port Johnson','PRF',225,225,1,1),(9472,'Port Oceanic','PRL',225,225,1,1),(9473,'Port Protection','PPV',225,225,1,1),(9474,'Port San Juan','PJS',225,225,1,1),(9475,'Port Townsend','TWD',225,225,1,1),(9476,'Port Walter','PWR',225,225,1,1),(9477,'Portage Creek','PCA',225,225,1,1),(9478,'Porterville','PTV',225,225,1,1),(9479,'Portland','PWM',225,225,1,1),(9481,'Portsmouth','PMH',225,225,1,1),(9483,'Poteau','RKR',225,225,1,1),(9484,'Pottstown','PTW',225,225,1,1),(9485,'Poughkeepsie','POU',225,225,1,1),(9486,'Poulsbo','PUL',225,225,1,1),(9487,'Powell','POY',225,225,1,1),(9488,'Prairie Du Chien','PCD',225,225,1,1),(9489,'Pratt','PTT',225,225,1,1),(9490,'Prentice','PRW',225,225,1,1),(9491,'Prescott','PRC',225,225,1,1),(9492,'Presque Isle','PQI',225,225,1,1),(9493,'Price','PUC',225,225,1,1),(9494,'Princeton','PNN',225,225,1,1),(9496,'Prineville','PRZ',225,225,1,1),(9497,'Prospect Creek','PPC',225,225,1,1),(9498,'Providence','PVD',225,225,1,1),(9499,'Provincetown','PVC',225,225,1,1),(9500,'Provo','PVU',225,225,1,1),(9501,'Prudhoe Bay','PUO',225,225,1,1),(9502,'Prudhoe Bay Deadh','SCC',225,225,1,1),(9503,'Pueblo','PUB',225,225,1,1),(9504,'Pullman','PUW',225,225,1,1),(9505,'Punta Gorda','PGD',225,225,1,1),(9506,'Quakertown','UKT',225,225,1,1),(9507,'Quantico','NYG',225,225,1,1),(9508,'Queen','UQE',225,225,1,1),(9509,'Quillayute','UIL',225,225,1,1),(9510,'Quincy','UIN',225,225,1,1),(9512,'Quinhagak','KWN',225,225,1,1),(9513,'Quonset Point','NCO',225,225,1,1),(9514,'Racine','RAC',225,225,1,1),(9515,'Rail Generic','XZU',225,225,1,1),(9516,'Raleigh Durham','RDU',225,225,1,1),(9517,'Rampart','RMP',225,225,1,1),(9518,'Rancho','RBK',225,225,1,1),(9519,'Rangely','RNG',225,225,1,1),(9520,'Ranger','RGR',225,225,1,1),(9521,'Rapid City','RAP',225,225,1,1),(9522,'Raspberry Strait','RSP',225,225,1,1),(9523,'Raton','RTN',225,225,1,1),(9524,'Rawlins','RWL',225,225,1,1),(9525,'Reading','RDG',225,225,1,1),(9526,'Red Bluff','RBL',225,225,1,1),(9527,'Red Devil','RDV',225,225,1,1),(9528,'Red Dog','RDB',225,225,1,1),(9529,'Redding','RDD',225,225,1,1),(9530,'Redmond','RDM',225,225,1,1),(9531,'Redwood Falls','RWF',225,225,1,1),(9532,'Reed City','RCT',225,225,1,1),(9533,'Reedsville','RED',225,225,1,1),(9534,'Refugio','RFG',225,225,1,1),(9535,'Rehoboth Beach','REH',225,225,1,1),(9536,'Reno','RNO',225,225,1,1),(9537,'Rensselaer','RNZ',225,225,1,1),(9538,'Renton','RNT',225,225,1,1),(9539,'Rhinelander','RHI',225,225,1,1),(9540,'Rice Lake','RIE',225,225,1,1),(9541,'Richfield','RIF',225,225,1,1),(9542,'Richland','RLD',225,225,1,1),(9543,'Richmond','RID',225,225,1,1),(9545,'Rifle','RIL',225,225,1,1),(9546,'Riverside','RAL',225,225,1,1),(9547,'Riverton','RIW',225,225,1,1),(9548,'Roanoke','ROA',225,225,1,1),(9549,'Roanoke Rapids','RZZ',225,225,1,1),(9550,'Roche Harbor','RCE',225,225,1,1),(9551,'Rochester','RCR',225,225,1,1),(9554,'Rock Hill','RKH',225,225,1,1),(9555,'Rock Springs','RKS',225,225,1,1),(9556,'Rockdale','RCK',225,225,1,1),(9557,'Rockland','RKD',225,225,1,1),(9558,'Rockport','RKP',225,225,1,1),(9559,'Rockwood','RKW',225,225,1,1),(9560,'Rocky Mount','RWI',225,225,1,1),(9561,'Rogers','ROG',225,225,1,1),(9562,'Rolla','RLA',225,225,1,1),(9563,'Roma','FAL',225,225,1,1),(9564,'Rome','REO',225,225,1,1),(9567,'Roosevelt','ROL',225,225,1,1),(9568,'Roseau','ROX',225,225,1,1),(9569,'Roseburg','RBG',225,225,1,1),(9570,'Roswell','ROW',225,225,1,1),(9571,'Rotunda','RTD',225,225,1,1),(9572,'Round Trip Flights','ZQB',225,225,1,1),(9573,'Roundup','RPX',225,225,1,1),(9574,'Rouses Point','RSX',225,225,1,1),(9575,'Rowan Bay','RWB',225,225,1,1),(9576,'Ruby','RBY',225,225,1,1),(9577,'Ruidoso','RUI',225,225,1,1),(9578,'Russell','RSL',225,225,1,1),(9579,'Ruston','RSN',225,225,1,1),(9580,'Rutland','RUT',225,225,1,1),(9581,'Sacramento','SMF',225,225,1,1),(9586,'Safford','SAD',225,225,1,1),(9587,'Saginaw','MBS',225,225,1,1),(9588,'Saginaw Bay','SGW',225,225,1,1),(9589,'Sagwon','SAG',225,225,1,1),(9590,'Saint Cloud','STC',225,225,1,1),(9591,'Saint George','SGU',225,225,1,1),(9592,'Saint Marys','KSM',225,225,1,1),(9593,'Saint Paul Island','SNP',225,225,1,1),(9594,'Saint Petersburg','SPG',225,225,1,1),(9595,'Salem','SLO',225,225,1,1),(9597,'Salida','SLT',225,225,1,1),(9598,'Salina','SBO',225,225,1,1),(9600,'Salinas','SNS',225,225,1,1),(9601,'Salisbury','SRW',225,225,1,1),(9602,'Salisbury-Ocean City','SBY',225,225,1,1),(9603,'Salmon','SMN',225,225,1,1),(9604,'Salt Lake City','SLC',225,225,1,1),(9605,'Salton City','SAS',225,225,1,1),(9606,'San Angelo','SJT',225,225,1,1),(9607,'San Antonio','RND',225,225,1,1),(9610,'San Bernardino','SBT',225,225,1,1),(9611,'San Carlos','SQL',225,225,1,1),(9612,'San Diego','NZY',225,225,1,1),(9616,'San Fernando','SFR',225,225,1,1),(9617,'San Francisco','SFO',225,225,1,1),(9618,'San Jose','SJC',225,225,1,1),(9619,'San Luis','STL',225,225,1,1),(9620,'San Luis Obispo','CSL',225,225,1,1),(9622,'San Miguel','SYL',225,225,1,1),(9623,'San Pedro','SPQ',225,225,1,1),(9624,'San Rafael','SRF',225,225,1,1),(9625,'Sand Point','SDP',225,225,1,1),(9626,'Sandusky','SKY',225,225,1,1),(9627,'Sandy River','KSR',225,225,1,1),(9628,'Sanford','SFM',225,225,1,1),(9629,'Santa Ana','SNA',225,225,1,1),(9630,'Santa Barbara','SBA',225,225,1,1),(9631,'Santa Clara','ZSM',225,225,1,1),(9632,'Santa Cruz','SRU',225,225,1,1),(9633,'Santa Fe','SAF',225,225,1,1),(9635,'Santa Maria','SMX',225,225,1,1),(9636,'Santa Monica','SMO',225,225,1,1),(9637,'Santa Paula','SZP',225,225,1,1),(9638,'Santa Rosa','STS',225,225,1,1),(9639,'Santa Ynez','SQA',225,225,1,1),(9640,'Saranac Lake','SLK',225,225,1,1),(9641,'Sarasota','SRQ',225,225,1,1),(9642,'Saratoga','SAA',225,225,1,1),(9643,'Sarichef','WSF',225,225,1,1),(9644,'Sault Ste Marie','SSM',225,225,1,1),(9646,'Sausalito','JMC',225,225,1,1),(9647,'Savannah','SAV',225,225,1,1),(9649,'Savoonga','SVA',225,225,1,1),(9650,'Schaumburg','JMH',225,225,1,1),(9651,'Schenectady','SCH',225,225,1,1),(9652,'Scottsbluff','BFF',225,225,1,1),(9653,'Scottsdale','ZSY',225,225,1,1),(9654,'Scranton','AVP',225,225,1,1),(9655,'Scribner','SCB',225,225,1,1),(9656,'Seal Bay','SYB',225,225,1,1),(9657,'Searcy','SRC',225,225,1,1),(9658,'Seattle','LKE',225,225,1,1),(9661,'Sebring','SEF',225,225,1,1),(9662,'Sedalia','DMO',225,225,1,1),(9663,'Sedona','SDX',225,225,1,1),(9664,'Selawik','WLK',225,225,1,1),(9665,'Seldovia','SOV',225,225,1,1),(9666,'Selinsgrove','SEG',225,225,1,1),(9667,'Selma','SES',225,225,1,1),(9668,'Sequim','SQV',225,225,1,1),(9669,'Sewanee','UOS',225,225,1,1),(9670,'Seward','SWD',225,225,1,1),(9671,'Seymour','SER',225,225,1,1),(9672,'Shafter','MIT',225,225,1,1),(9673,'Shageluk','SHX',225,225,1,1),(9674,'Shaktoolik','SKK',225,225,1,1),(9675,'Shangri La','NRI',225,225,1,1),(9676,'Shawnee','SNL',225,225,1,1),(9677,'Sheboygan','SBM',225,225,1,1),(9678,'Sheep Mountain','SMU',225,225,1,1),(9679,'Shelby','SBX',225,225,1,1),(9680,'Shelbyville','SYI',225,225,1,1),(9681,'Shelton','SHN',225,225,1,1),(9682,'Shemya','SYA',225,225,1,1),(9683,'Sheridan','SHR',225,225,1,1),(9684,'Sherman Denison','PNX',225,225,1,1),(9685,'Shirley','WSH',225,225,1,1),(9686,'Shishmaref','SHH',225,225,1,1),(9687,'Shoal Cove','HCB',225,225,1,1),(9688,'Show Low','SOW',225,225,1,1),(9689,'Shreveport','SHV',225,225,1,1),(9690,'Shungnak','SHG',225,225,1,1),(9691,'Sidney','SNY',225,225,1,1),(9694,'Sikeston','SIK',225,225,1,1),(9695,'Siloam Springs','SLG',225,225,1,1),(9696,'Silver City','SVC',225,225,1,1),(9697,'Sioux City','SUX',225,225,1,1),(9698,'Sioux Falls','FSD',225,225,1,1),(9699,'Sitka','SIT',225,225,1,1),(9700,'Sitkinak Island','SKJ',225,225,1,1),(9701,'Skagway','SGY',225,225,1,1),(9702,'Skwentna','SKW',225,225,1,1),(9703,'Sleetmute','SLQ',225,225,1,1),(9704,'Smith Cove','SCJ',225,225,1,1),(9705,'Smithfield','SFZ',225,225,1,1),(9706,'Smyrna','MQY',225,225,1,1),(9707,'Snyder','SNK',225,225,1,1),(9708,'Socorro','ONM',225,225,1,1),(9709,'Soldotna','SXQ',225,225,1,1),(9710,'Solomon','SOL',225,225,1,1),(9711,'Somerset','SME',225,225,1,1),(9712,'South Bend','SBN',225,225,1,1),(9713,'South Naknek','WSN',225,225,1,1),(9714,'South Weymouth','NZW',225,225,1,1),(9715,'Southern Pines','SOP',225,225,1,1),(9716,'Sparta','CMY',225,225,1,1),(9718,'Spearfish','SPF',225,225,1,1),(9719,'Spencer','SPW',225,225,1,1),(9720,'Spirit Lake','RTL',225,225,1,1),(9721,'Spokane','GEG',225,225,1,1),(9723,'Springdale','SPZ',225,225,1,1),(9724,'Springfield','SGH',225,225,1,1),(9728,'St Augustine','UST',225,225,1,1),(9729,'St George Island','STG',225,225,1,1),(9730,'St Johns','SJN',225,225,1,1),(9731,'St Joseph','STJ',225,225,1,1),(9732,'St Marys','STQ',225,225,1,1),(9734,'St Michael','SMK',225,225,1,1),(9735,'St Paul','STP',225,225,1,1),(9736,'Stamford','ZTF',225,225,1,1),(9737,'Stanton','SYN',225,225,1,1),(9738,'State College','SCE',225,225,1,1),(9739,'Statesboro','TBR',225,225,1,1),(9740,'Statesville','SVH',225,225,1,1),(9741,'Staunton','SHD',225,225,1,1),(9742,'Stebbins','WBB',225,225,1,1),(9743,'Stephenville','SEP',225,225,1,1),(9744,'Sterling','STK',225,225,1,1),(9745,'Sterling Rockfls','SQI',225,225,1,1),(9746,'Stevens Point','STE',225,225,1,1),(9747,'Stevens Village','SVS',225,225,1,1),(9748,'Stillwater','SWO',225,225,1,1),(9749,'Stony River','SRV',225,225,1,1),(9750,'Storm Lake','SLB',225,225,1,1),(9751,'Stow','MMN',225,225,1,1),(9752,'Stowe','MVL',225,225,1,1),(9753,'Stratford','JSD',225,225,1,1),(9754,'Stroud','SUD',225,225,1,1),(9755,'Stuart','SUA',225,225,1,1),(9756,'Stuart Island','SSW',225,225,1,1),(9757,'Sturgeon Bay','SUE',225,225,1,1),(9758,'Sturgis','IRS',225,225,1,1),(9759,'Stuttgart','SGT',225,225,1,1),(9760,'Sugar Land','SGR',225,225,1,1),(9761,'Sullivan','SIV',225,225,1,1),(9762,'Sulphur Springs','SLR',225,225,1,1),(9763,'Summit','UMM',225,225,1,1),(9764,'Sumter','SSC',225,225,1,1),(9765,'Sun River','SUO',225,225,1,1),(9766,'Sundance','SUC',225,225,1,1),(9767,'Susanville','SVE',225,225,1,1),(9768,'Sweetwater','SWW',225,225,1,1),(9769,'Sylvester','SYV',225,225,1,1),(9770,'Syracuse','SYR',225,225,1,1),(9771,'Tacoma','GRF',225,225,1,1),(9773,'Tahneta Pass Lodg','HNE',225,225,1,1),(9774,'Takotna','TCT',225,225,1,1),(9775,'Talkeetna','TKA',225,225,1,1),(9776,'Talladega','ASN',225,225,1,1),(9777,'Tallahassee','TLH',225,225,1,1),(9778,'Tampa','TPA',225,225,1,1),(9781,'Tanacross','TSG',225,225,1,1),(9782,'Tanalian Point','TPO',225,225,1,1),(9783,'Tanana','TAL',225,225,1,1),(9784,'Taos','TSM',225,225,1,1),(9785,'Tatitlek','TEK',225,225,1,1),(9786,'Taylor','TYZ',225,225,1,1),(9788,'Tehachapi','TSP',225,225,1,1),(9789,'Telida','TLF',225,225,1,1),(9790,'Teller','TLA',225,225,1,1),(9791,'Teller Mission','KTS',225,225,1,1),(9792,'Telluride','ZTL',225,225,1,1),(9794,'Temple','TPL',225,225,1,1),(9795,'Terre Haute','HUF',225,225,1,1),(9796,'Terrell','TRL',225,225,1,1),(9797,'Teterboro','TEB',225,225,1,1),(9798,'Tetlin','TEH',225,225,1,1),(9799,'Texarkana','TXK',225,225,1,1),(9800,'The Dalles','DLS',225,225,1,1),(9801,'Thermal','TRM',225,225,1,1),(9802,'Thermopolis','THP',225,225,1,1),(9803,'Thief River Falls','TVF',225,225,1,1),(9804,'Thomasville','TVI',225,225,1,1),(9805,'Thompsonfield','THM',225,225,1,1),(9806,'Thorne Bay','KTB',225,225,1,1),(9807,'Thousand Oaks','JTO',225,225,1,1),(9808,'Three Rivers','HAI',225,225,1,1),(9809,'Tifton','TMA',225,225,1,1),(9810,'Tioga','VEX',225,225,1,1),(9811,'Titusville','TIX',225,225,1,1),(9812,'Toccoa','TOC',225,225,1,1),(9813,'Togiak Fish','GFB',225,225,1,1),(9814,'Togiak Village','TOG',225,225,1,1),(9815,'Tok','TKJ',225,225,1,1),(9816,'Tokeen','TKI',225,225,1,1),(9817,'Toksook Bay','OOK',225,225,1,1),(9818,'Toledo','TOL',225,225,1,1),(9820,'Toms River','MJX',225,225,1,1),(9821,'Tonopah','TPH',225,225,1,1),(9822,'Topeka','TOP',225,225,1,1),(9824,'Torrance','TOA',225,225,1,1),(9825,'Torrington','TOR',225,225,1,1),(9826,'Traverse City','TVC',225,225,1,1),(9827,'Tremonton','TRT',225,225,1,1),(9828,'Trenton','TRX',225,225,1,1),(9829,'Trinidad','TAD',225,225,1,1),(9830,'Trona','TRH',225,225,1,1),(9831,'Troutdale','TTD',225,225,1,1),(9832,'Troy','TOI',225,225,1,1),(9833,'Truckee','TKF',225,225,1,1),(9834,'Truth Or Conseque','TCS',225,225,1,1),(9835,'Tuba City','TBC',225,225,1,1),(9836,'Tucson','TUS',225,225,1,1),(9837,'Tucumcari','TCC',225,225,1,1),(9838,'Tulare','TLR',225,225,1,1),(9839,'Tullahoma','THA',225,225,1,1),(9840,'Tulsa','TUL',225,225,1,1),(9841,'Tuluksak','TLT',225,225,1,1),(9842,'Tunica','UTM',225,225,1,1),(9843,'Tuntutuliak','WTL',225,225,1,1),(9844,'Tununak','TNK',225,225,1,1),(9845,'Tupelo','TUP',225,225,1,1),(9846,'Tuscaloosa','TCL',225,225,1,1),(9847,'Tuskegee','TGE',225,225,1,1),(9848,'Twentynine Palms','TNP',225,225,1,1),(9849,'Twin Falls','TWF',225,225,1,1),(9850,'Twin Hills','TWA',225,225,1,1),(9851,'Tyler','TYR',225,225,1,1),(9852,'Tyonek','TYE',225,225,1,1),(9853,'Uganik','UGI',225,225,1,1),(9854,'Ugashik','UGS',225,225,1,1),(9855,'Ukiah','UKI',225,225,1,1),(9856,'Umiat','UMT',225,225,1,1),(9857,'Umnak Island','UMB',225,225,1,1),(9858,'Unalakleet','UNK',225,225,1,1),(9859,'Union City','UCY',225,225,1,1),(9860,'Union S Carolina','USC',225,225,1,1),(9861,'Upland Cable','CCB',225,225,1,1),(9862,'Upolu Point','UPP',225,225,1,1),(9863,'Utica','UIZ',225,225,1,1),(9865,'Uvalde','UVA',225,225,1,1),(9866,'Vail Eagle','EGE',225,225,1,1),(9868,'Valdez','VDZ',225,225,1,1),(9869,'Valdosta','VLD',225,225,1,1),(9870,'Valentine','VTN',225,225,1,1),(9871,'Valle','VLE',225,225,1,1),(9872,'Vallejo','VLO',225,225,1,1),(9873,'Valparaiso','VPZ',225,225,1,1),(9875,'Van Horn','VHN',225,225,1,1),(9876,'Vandalia','VLA',225,225,1,1),(9877,'Venecia','VNC',225,225,1,1),(9878,'Venetie','VEE',225,225,1,1),(9879,'Vernal','VEL',225,225,1,1),(9880,'Vero Beach','VRB',225,225,1,1),(9881,'Versalles','VRS',225,225,1,1),(9882,'Vichy','VIH',225,225,1,1),(9883,'Vicksburg','VKS',225,225,1,1),(9884,'Victoria','VCT',225,225,1,1),(9885,'Victorville','VCV',225,225,1,1),(9886,'Vidalia','VDI',225,225,1,1),(9887,'View Cove','VCB',225,225,1,1),(9888,'Vincennes','OEA',225,225,1,1),(9889,'Visalia','VIS',225,225,1,1),(9890,'Waco','CNW',225,225,1,1),(9892,'Wahpeton','WAH',225,225,1,1),(9893,'Waikoloa','WKL',225,225,1,1),(9894,'Waimanalo','BLW',225,225,1,1),(9895,'Wainwright','AIN',225,225,1,1),(9896,'Waldron Island','WDN',225,225,1,1),(9897,'Wales','WAA',225,225,1,1),(9898,'Walla Walla','ALW',225,225,1,1),(9899,'Walnut Ridge','ARG',225,225,1,1),(9900,'Walterboro','RBW',225,225,1,1),(9901,'Waltham','WLM',225,225,1,1),(9902,'Wapakoneta','AXV',225,225,1,1),(9903,'Ware','UWA',225,225,1,1),(9904,'Warner Robins Georgia','WRR',225,225,1,1),(9905,'Warrensburg','SZL',225,225,1,1),(9906,'Warroad','RRT',225,225,1,1),(9907,'Washington','OCW',225,225,1,1),(9913,'Wasilla','WWA',225,225,1,1),(9914,'Waterloo','ALO',225,225,1,1),(9915,'Watertown','ATY',225,225,1,1),(9917,'Waterville','WVL',225,225,1,1),(9918,'Watsonville','WVI',225,225,1,1),(9919,'Waukegan','UGN',225,225,1,1),(9920,'Waukesha','UES',225,225,1,1),(9921,'Waukon','UKN',225,225,1,1),(9922,'Wausau','AUW',225,225,1,1),(9924,'Waycross','AYS',225,225,1,1),(9925,'Waynesburg','WAY',225,225,1,1),(9926,'Weatherford','WEA',225,225,1,1),(9927,'Webster City','EBS',225,225,1,1),(9928,'Weeping Water','EPG',225,225,1,1),(9929,'Wells','LWL',225,225,1,1),(9930,'Wellsville','ELZ',225,225,1,1),(9931,'Wenatchee','EAT',225,225,1,1),(9932,'Wendover','ENV',225,225,1,1),(9933,'West Bend','ETB',225,225,1,1),(9934,'West Kavik','VKW',225,225,1,1),(9935,'West Kuparuk','XPU',225,225,1,1),(9936,'West Memphis','AWM',225,225,1,1),(9937,'West Palm Beach','PBI',225,225,1,1),(9939,'West Yellowstone','WYS',225,225,1,1),(9940,'Westchester County','HPN',225,225,1,1),(9941,'Westerly','WST',225,225,1,1),(9942,'Westfield','BAF',225,225,1,1),(9943,'Westhampton','FOK',225,225,1,1),(9944,'Westsound','WSX',225,225,1,1),(9945,'Whale Pass','WWP',225,225,1,1),(9946,'Wharton','WHT',225,225,1,1),(9947,'Wheatland','EAN',225,225,1,1),(9948,'Wheeling','HLG',225,225,1,1),(9949,'Whidbey Island','NUW',225,225,1,1),(9950,'White Mountain','WMO',225,225,1,1),(9951,'White River','WTR',225,225,1,1),(9952,'White Sands','WSD',225,225,1,1),(9953,'White Sulphur','SSU',225,225,1,1),(9954,'Whitefield','HIE',225,225,1,1),(9955,'Whitehouse','NEN',225,225,1,1),(9956,'Whitesburg','BRG',225,225,1,1),(9957,'Wichita','ICT',225,225,1,1),(9958,'Wichita Falls','SPS',225,225,1,1),(9959,'Wildwood','WWD',225,225,1,1),(9960,'Wilkesboro','IKB',225,225,1,1),(9961,'Williamsport','IPT',225,225,1,1),(9962,'Williston','ISN',225,225,1,1),(9963,'Willmar','ILL',225,225,1,1),(9964,'Willoughby','LNN',225,225,1,1),(9965,'Willow','WOW',225,225,1,1),(9966,'Willow Grove','NXX',225,225,1,1),(9967,'Willows','WLW',225,225,1,1),(9968,'Wilmington','ILM',225,225,1,1),(9971,'Wilton','QCW',225,225,1,1),(9972,'Winchester','WGO',225,225,1,1),(9973,'Winder','WDR',225,225,1,1),(9974,'Windom','MWM',225,225,1,1),(9975,'Winfield','WLD',225,225,1,1),(9976,'Wink','INK',225,225,1,1),(9977,'Winnemucca','WMC',225,225,1,1),(9978,'Winona','ONA',225,225,1,1),(9979,'Winslow','INW',225,225,1,1),(9980,'Winston Salem','INT',225,225,1,1),(9981,'Winter Haven','GIF',225,225,1,1),(9982,'Winter Park','QWP',225,225,1,1),(9983,'Wiscasset','ISS',225,225,1,1),(9984,'Wisconsin Rapids','ISW',225,225,1,1),(9985,'Wise','LNP',225,225,1,1),(9986,'Wiseman','WSM',225,225,1,1),(9987,'Woburn','WBN',225,225,1,1),(9988,'Wolf Point','OLF',225,225,1,1),(9989,'Wood River','WOD',225,225,1,1),(9990,'Woodchopper','WOO',225,225,1,1),(9991,'Woodward','WWR',225,225,1,1),(9992,'Wooster','BJJ',225,225,1,1),(9993,'Worcester','ORH',225,225,1,1),(9994,'Worland','WRL',225,225,1,1),(9995,'Worthington','OTG',225,225,1,1),(9996,'Wrangell','WRG',225,225,1,1),(9997,'Wrench Creek','WRH',225,225,1,1),(9998,'Yakataga','CYT',225,225,1,1),(9999,'Yakima','YKM',225,225,1,1),(10000,'Yakutat','YAK',225,225,1,1),(10001,'Yankton','YKN',225,225,1,1),(10002,'Yerington','EYR',225,225,1,1),(10003,'York','THV',225,225,1,1),(10004,'Yosemite Ntl Park','OYS',225,225,1,1),(10005,'Youngstown Warren','YNG',225,225,1,1),(10006,'Yreka','RKC',225,225,1,1),(10007,'Yucca Flat','UCC',225,225,1,1),(10008,'Yuma','YUM',225,225,1,1),(10009,'Zanesville','ZZV',225,225,1,1),(10010,'Zephyrhills','ZPH',225,225,1,1),(10011,'Johnston Island','JON',226,226,1,1),(10012,'Midway Island','MDY',226,226,1,1),(10013,'Wake Island','AWK',226,226,1,1),(10014,'Artigas','ATI',227,227,1,1),(10015,'Bella Union','BUV',227,227,1,1),(10016,'Colonia','CYR',227,227,1,1),(10017,'Durazno','DZO',227,227,1,1),(10018,'Melo','MLZ',227,227,1,1),(10019,'Montevideo','MVD',227,227,1,1),(10020,'Paysandu','PDU',227,227,1,1),(10021,'Punta del Este','PDP',227,227,1,1),(10022,'Rivera','RVY',227,227,1,1),(10023,'Salto','STY',227,227,1,1),(10024,'Tacuarembo','TAW',227,227,1,1),(10025,'Treinta y Tres','TYT',227,227,1,1),(10026,'Vichadero','VCH',227,227,1,1),(10027,'Andizhan','AZN',228,228,1,1),(10028,'Bukhara','BHK',228,228,1,1),(10029,'Fergana','FEG',228,228,1,1),(10030,'Karshi','KSQ',228,228,1,1),(10031,'Namangan','NMA',228,228,1,1),(10032,'Nukus','NCU',228,228,1,1),(10033,'Samarkand','SKD',228,228,1,1),(10034,'Tashkent','TAS',228,228,1,1),(10035,'Termez Uz','TMJ',228,228,1,1),(10036,'Urgench','UGC',228,228,1,1),(10037,'Aneityum','AUY',229,229,1,1),(10038,'Aniwa','AWD',229,229,1,1),(10039,'Big Bay','GBA',229,229,1,1),(10040,'Craig Cove','CCV',229,229,1,1),(10041,'Dillons Bay','DLY',229,229,1,1),(10042,'Emae','EAE',229,229,1,1),(10043,'Epi','EPI',229,229,1,1),(10044,'Espiritu Santo','SON',229,229,1,1),(10045,'Futuna Island','FTA',229,229,1,1),(10046,'Gaua','ZGU',229,229,1,1),(10047,'Ipota','IPA',229,229,1,1),(10048,'Lamap','LPM',229,229,1,1),(10049,'Lamen Bay','LNB',229,229,1,1),(10050,'Longana','LOD',229,229,1,1),(10051,'Lonorore','LNE',229,229,1,1),(10052,'Maewo','MWF',229,229,1,1),(10053,'Mota Lava','MTV',229,229,1,1),(10054,'Norsup','NUS',229,229,1,1),(10055,'Olpoi','OLJ',229,229,1,1),(10056,'Paama','PBJ',229,229,1,1),(10057,'Port Vila','VLI',229,229,1,1),(10058,'Quine Hill','UIQ',229,229,1,1),(10059,'Redcliffe','RCL',229,229,1,1),(10060,'Sara','SSR',229,229,1,1),(10061,'Sola','SLH',229,229,1,1),(10062,'South West Bay','SWJ',229,229,1,1),(10063,'Tanna','TAH',229,229,1,1),(10064,'Tongoa','TGH',229,229,1,1),(10065,'Torres','TOH',229,229,1,1),(10066,'Ulei','ULB',229,229,1,1),(10067,'Valesdir','VLS',229,229,1,1),(10068,'Walaha','WLH',229,229,1,1),(10069,'Acarigua','AGV',230,230,1,1),(10070,'Anaco','AAO',230,230,1,1),(10071,'Barcelona','BLA',230,230,1,1),(10072,'Barinas','BNS',230,230,1,1),(10073,'Barquisimeto','BRM',230,230,1,1),(10074,'Cabimas','CBS',230,230,1,1),(10075,'Caicara De Oro','CXA',230,230,1,1),(10076,'Calabozo','CLZ',230,230,1,1),(10077,'Canaima','CAJ',230,230,1,1),(10078,'Caracas','CCS',230,230,1,1),(10079,'Carora','VCR',230,230,1,1),(10080,'Carupano','CUP',230,230,1,1),(10081,'Casigua','CUV',230,230,1,1),(10082,'Ciudad Bolivar','CBL',230,230,1,1),(10083,'Ciudad Guayana','CGU',230,230,1,1),(10084,'Coro','CZE',230,230,1,1),(10085,'Cumana','CUM',230,230,1,1),(10086,'El Dorado','EOR',230,230,1,1),(10087,'El Tigre','ELX',230,230,1,1),(10088,'El Vigia','VIG',230,230,1,1),(10089,'Elorza','EOZ',230,230,1,1),(10090,'Guanare','GUQ',230,230,1,1),(10091,'Guasdualito','GDO',230,230,1,1),(10092,'Guiria','GUI',230,230,1,1),(10093,'Icabaru','ICA',230,230,1,1),(10094,'Kamarata','KTV',230,230,1,1),(10095,'Kavanayen','KAV',230,230,1,1),(10096,'La Fria','LFR',230,230,1,1),(10097,'La Guaira','LAG',230,230,1,1),(10098,'Lagunillas','LGY',230,230,1,1),(10099,'Las Piedras','LSP',230,230,1,1),(10100,'Los Roques','LRV',230,230,1,1),(10101,'Maracaibo','MAR',230,230,1,1),(10102,'Maracay','MYC',230,230,1,1),(10103,'Maturin','MUN',230,230,1,1),(10104,'Merida','MRD',230,230,1,1),(10105,'Palmarito','PTM',230,230,1,1),(10106,'Pedernales','PDZ',230,230,1,1),(10107,'Peraitepuy','PPH',230,230,1,1),(10108,'Pijiguaos','LPJ',230,230,1,1),(10109,'Porlamar','PMV',230,230,1,1),(10110,'Puerto Ayacucho','PYH',230,230,1,1),(10111,'Puerto Cabello','PBL',230,230,1,1),(10112,'Puerto La Cruz','QUC',230,230,1,1),(10113,'Puerto Ordaz','PZO',230,230,1,1),(10114,'Puerto Paez','PPZ',230,230,1,1),(10115,'S Fern De Apure','SFD',230,230,1,1),(10116,'San Antonio','SVZ',230,230,1,1),(10117,'San Cristobal','SCI',230,230,1,1),(10118,'San Felipe','SNF',230,230,1,1),(10119,'San Felix','SFX',230,230,1,1),(10120,'San Salvador De','SVV',230,230,1,1),(10121,'San Tome','SOM',230,230,1,1),(10122,'Santa Barbara Ba','SBB',230,230,1,1),(10123,'Santa Barbara Ed','STB',230,230,1,1),(10124,'Santa Elena','SNV',230,230,1,1),(10125,'Santo Domingo','STD',230,230,1,1),(10126,'Tucupita','TUV',230,230,1,1),(10127,'Tumeremo','TMO',230,230,1,1),(10128,'Uriman','URM',230,230,1,1),(10129,'Valencia','VLN',230,230,1,1),(10130,'Valera','VLV',230,230,1,1),(10131,'Valle De Pascua','VDP',230,230,1,1),(10132,'Wonken','WOK',230,230,1,1),(10133,'Banmethuot','BMV',231,231,1,1),(10134,'Ca Mau','CAH',231,231,1,1),(10135,'Can Tho','VCA',231,231,1,1),(10136,'Cape St Jacques','CSJ',231,231,1,1),(10137,'Da Nang','DAD',231,231,1,1),(10138,'Dalat','DLI',231,231,1,1),(10139,'Dien Bien Phu','DIN',231,231,1,1),(10140,'Dong Hoi','VDH',231,231,1,1),(10141,'Haiphong','HPH',231,231,1,1),(10142,'Hanoi','HAN',231,231,1,1),(10143,'Ho Chi Minh Ciudad','SGN',231,231,1,1),(10144,'Hue','HUI',231,231,1,1),(10145,'Kontum','KON',231,231,1,1),(10146,'Long Xuyen','XLO',231,231,1,1),(10147,'Nha Trang','CXR',231,231,1,1),(10149,'Phan Rang','PHA',231,231,1,1),(10150,'Phan Thiet','PHH',231,231,1,1),(10151,'Phu Bon','HBN',231,231,1,1),(10152,'Phu Quoc','PQC',231,231,1,1),(10153,'Phu Vinh','PHU',231,231,1,1),(10154,'Phuoclong','VSO',231,231,1,1),(10155,'Pleiku','PXU',231,231,1,1),(10156,'Quanduc','HOO',231,231,1,1),(10157,'Quang Ngai','XNG',231,231,1,1),(10158,'Qui Nhon','UIH',231,231,1,1),(10159,'Rach Gia','VKG',231,231,1,1),(10160,'Soc Trang','SOA',231,231,1,1),(10161,'Son La','SQH',231,231,1,1),(10162,'Tamky','TMK',231,231,1,1),(10164,'Tuy Hoa','TBB',231,231,1,1),(10165,'Vinh Ciudad','VII',231,231,1,1),(10166,'Vinh Long','XVL',231,231,1,1),(10167,'Vung Tau','VTG',231,231,1,1),(10168,'Anegada','NGD',232,232,1,1),(10169,'Beef Island','EIS',232,232,1,1),(10170,'Tortola','TOV',232,232,1,1),(10171,'Virgin Gorda','VIJ',232,232,1,1),(10172,'Saint Thomas','STT',233,233,1,1),(10173,'St Croix Island','STX',233,233,1,1),(10174,'St John Island','SJF',233,233,1,1),(10175,'Futuna Island','FUT',234,234,1,1),(10176,'Wallis Island','WLS',234,234,1,1),(10177,'Abbs','EAB',236,236,1,1),(10178,'Aden','ADE',236,236,1,1),(10179,'Al Bayda','BYD',236,236,1,1),(10180,'Al Buq','BUK',236,236,1,1),(10181,'Al Ghaydah','AAY',236,236,1,1),(10182,'Aljouf','AJO',236,236,1,1),(10183,'Ataq','AXK',236,236,1,1),(10184,'Beihan','BHN',236,236,1,1),(10185,'Dathina','DAH',236,236,1,1),(10186,'Dhala','DHL',236,236,1,1),(10187,'Dhamar','DMR',236,236,1,1),(10188,'Hodeidah','HOD',236,236,1,1),(10189,'Kamaran Island','KAM',236,236,1,1),(10190,'Lawdar','LDR',236,236,1,1),(10191,'Mareb','MYN',236,236,1,1),(10192,'Mayfa\'ah','MFY',236,236,1,1),(10193,'Mukalla','RIY',236,236,1,1),(10194,'Mukeiras','UKR',236,236,1,1),(10195,'Qishn','IHN',236,236,1,1),(10196,'Rawdah','RXA',236,236,1,1),(10197,'Sadah','SYE',236,236,1,1),(10198,'Sanaa','SAH',236,236,1,1),(10199,'Sayun','GXF',236,236,1,1),(10200,'Shihr','QER',236,236,1,1),(10201,'Socotra','SCT',236,236,1,1),(10202,'Taizz','TAI',236,236,1,1),(10203,'Wadi Ain','WDA',236,236,1,1),(10204,'Chingola','CGJ',238,238,1,1),(10205,'Chipata','CIP',238,238,1,1),(10206,'Kabwe','QKE',238,238,1,1),(10207,'Kalabo','KLB',238,238,1,1),(10208,'Kaoma','KMZ',238,238,1,1),(10209,'Kasaba Bay','ZKB',238,238,1,1),(10210,'Kasama','KAA',238,238,1,1),(10211,'Kasompe','ZKP',238,238,1,1),(10212,'Kitwe','KIW',238,238,1,1),(10213,'Livingstone','LVI',238,238,1,1),(10214,'Lukulu','LXU',238,238,1,1),(10215,'Lusaka','LUN',238,238,1,1),(10216,'Mansa','MNS',238,238,1,1),(10217,'Mbala','MMQ',238,238,1,1),(10218,'Mfuwe','MFU',238,238,1,1),(10219,'Mongu','MNR',238,238,1,1),(10220,'Ndola','NLA',238,238,1,1),(10221,'Ngoma','ZGM',238,238,1,1),(10222,'Senanga','SXG',238,238,1,1),(10223,'Sesheke','SJQ',238,238,1,1),(10224,'Solwezi','SLI',238,238,1,1),(10225,'Zambezi','BBZ',238,238,1,1),(10226,'Buffalo Range','BFO',239,239,1,1),(10227,'Bulawayo','BUQ',239,239,1,1),(10228,'Bumi Hills','BZH',239,239,1,1),(10229,'Chipinge','CHJ',239,239,1,1),(10230,'Gweru','GWE',239,239,1,1),(10231,'Harare','HRE',239,239,1,1),(10232,'Hwange','WKI',239,239,1,1),(10233,'Hwange National Park','HWN',239,239,1,1),(10234,'Kariba','KAB',239,239,1,1),(10235,'Mahenye','MJW',239,239,1,1),(10236,'Masvingo','MVZ',239,239,1,1),(10237,'Mutare','UTA',239,239,1,1),(10238,'Victoria Falls','VFA',239,239,1,1),(10239,'Wankie Rhodesia','WKM',239,239,1,1),(10242,'Quindío','QND',47,245,0,1),(10243,'Puerto Triunfo','090',47,47,0,1),(10244,'Guatape','091',47,47,0,1);
/*!40000 ALTER TABLE `#__cp_prm_city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_country`
--

DROP TABLE IF EXISTS `#__cp_prm_country`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_country` (
  `country_id` int(11) NOT NULL auto_increment,
  `country_name` varchar(100) NOT NULL default '',
  `country_code` char(2) NOT NULL default '',
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=242 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_country`
--

LOCK TABLES `#__cp_prm_country` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_country` DISABLE KEYS */;
INSERT INTO `#__cp_prm_country` (`country_id`, `country_name`, `country_code`, `published`) VALUES (1,'Afghanistan','AF',0),(2,'Albania','AL',0),(3,'Algeria','DZ',0),(4,'American Samoa','AS',0),(5,'Andorra','AD',0),(6,'Angola','AO',0),(7,'Anguilla','AI',0),(8,'Antarctica','AQ',0),(9,'Antigua and Barbuda','AG',0),(10,'Argentina','AR',0),(11,'Armenia','AM',0),(12,'Aruba','AW',0),(13,'Australia','AU',0),(14,'Austria','AT',0),(15,'Azerbaijan','AZ',0),(16,'Bahamas','BS',0),(17,'Bahrain','BH',0),(18,'Bangladesh','BD',0),(19,'Barbados','BB',0),(20,'Belarus','BY',0),(21,'Belgium','BE',0),(22,'Belize','BZ',0),(23,'Benin','BJ',0),(24,'Bermuda','BM',0),(25,'Bhutan','BT',0),(26,'Bolivia','BO',0),(27,'Bosnia and Herzegovina','BA',0),(28,'Botswana','BW',0),(29,'Bouvet Island','BV',0),(30,'Brazil','BR',0),(31,'British Indian Ocean Territory','IO',0),(32,'Brunei','BN',0),(33,'Bulgaria','BG',0),(34,'Burkina Faso','BF',0),(35,'Burundi','BI',0),(36,'Cambodia','KH',0),(37,'Cameroon','CM',0),(38,'Canada','CA',1),(39,'Cape Verde','CV',0),(40,'Cayman Islands','KY',0),(41,'Central African Republic','CF',0),(42,'Chad','TD',0),(43,'Chile','CL',1),(44,'China','CN',0),(45,'Christmas Island','CX',0),(46,'Cocos (Keeling) Islands','CC',0),(47,'Colombia','CO',1),(48,'Comoros','KM',0),(49,'Congo','CG',0),(50,'Congo, The Democratic Republic of the','CD',0),(51,'Cook Islands','CK',0),(52,'Costa Rica','CR',1),(53,'Côte d?Ivoire','CI',0),(54,'Croatia','HR',0),(55,'Cuba','CU',1),(56,'Cyprus','CY',0),(57,'Czech Republic','CZ',0),(58,'Denmark','DK',0),(59,'Djibouti','DJ',0),(60,'Dominica','DM',0),(61,'Dominican Republic','DO',1),(62,'East Timor','TP',0),(63,'Ecuador','EC',0),(64,'Egypt','EG',0),(65,'El Salvador','SV',0),(66,'Equatorial Guinea','GQ',0),(67,'Eritrea','ER',0),(68,'Estonia','EE',0),(69,'Ethiopia','ET',0),(70,'Falkland Islands','FK',0),(71,'Faroe Islands','FO',0),(72,'Fiji Islands','FJ',0),(73,'Finland','FI',0),(74,'Francia','FR',1),(75,'French Guiana','GF',0),(76,'French Polynesia','PF',0),(77,'French Southern territories','TF',0),(78,'Gabon','GA',0),(79,'Gambia','GM',0),(80,'Georgia','GE',0),(81,'Germany','DE',0),(82,'Ghana','GH',0),(83,'Gibraltar','GI',0),(84,'Greece','GR',0),(85,'Greenland','GL',0),(86,'Grenada','GD',0),(87,'Guadeloupe','GP',0),(88,'Guam','GU',0),(89,'Guatemala','GT',1),(90,'Guinea','GN',0),(91,'Guinea-Bissau','GW',0),(92,'Guyana','GY',0),(93,'Haiti','HT',0),(94,'Heard Island and McDonald Islands','HM',0),(95,'Holy See (Vatican City State)','VA',0),(96,'Honduras','HN',0),(97,'Hong Kong','HK',0),(98,'Hungary','HU',0),(99,'Iceland','IS',0),(100,'India','IN',0),(101,'Indonesia','ID',0),(102,'Iran','IR',0),(103,'Iraq','IQ',0),(104,'Ireland','IE',0),(105,'Israel','IL',0),(106,'Italy','IT',0),(107,'Jamaica','JM',0),(108,'Japan','JP',0),(109,'Jordan','JO',0),(110,'Kazakstan','KZ',0),(111,'Kenya','KE',0),(112,'Kiribati','KI',0),(113,'Kuwait','KW',0),(114,'Kyrgyzstan','KG',0),(115,'Laos','LA',0),(116,'Latvia','LV',0),(117,'Lebanon','LB',0),(118,'Lesotho','LS',0),(119,'Liberia','LR',0),(120,'Libyan Arab Jamahiriya','LY',0),(121,'Liechtenstein','LI',0),(122,'Lithuania','LT',0),(123,'Luxembourg','LU',0),(124,'Macao','MO',0),(125,'Macedonia','MK',0),(126,'Madagascar','MG',0),(127,'Malawi','MW',0),(128,'Malaysia','MY',0),(129,'Maldives','MV',0),(130,'Mali','ML',0),(131,'Malta','MT',0),(132,'Marshall Islands','MH',0),(133,'Martinique','MQ',1),(134,'Mauritania','MR',0),(135,'Mauritius','MU',0),(136,'Mayotte','YT',0),(137,'Mexico','MX',1),(138,'Micronesia, Federated States of','FM',0),(139,'Moldova','MD',0),(140,'Monaco','MC',0),(141,'Mongolia','MN',0),(142,'Montserrat','MS',1),(143,'Morocco','MA',0),(144,'Mozambique','MZ',0),(145,'Myanmar','MM',0),(146,'Namibia','NA',0),(147,'Nauru','NR',0),(148,'Nepal','NP',0),(149,'Netherlands','NL',0),(150,'Netherlands Antilles','AN',0),(151,'New Caledonia','NC',0),(152,'New Zealand','NZ',0),(153,'Nicaragua','NI',0),(154,'Niger','NE',0),(155,'Nigeria','NG',0),(156,'Niue','NU',0),(157,'Norfolk Island','NF',0),(158,'North Korea','KP',0),(159,'Northern Mariana Islands','MP',0),(160,'Norway','NO',0),(161,'Oman','OM',0),(162,'Pakistan','PK',0),(163,'Palau','PW',0),(164,'Palestine','PS',0),(165,'Panama','PA',0),(166,'Papua New Guinea','PG',0),(167,'Paraguay','PY',0),(168,'Peru','PE',1),(169,'Philippines','PH',0),(170,'Pitcairn','PN',0),(171,'Poland','PL',0),(172,'Portugal','PT',0),(173,'Puerto Rico','PR',0),(174,'Qatar','QA',0),(175,'Réunion','RE',0),(176,'Romania','RO',0),(177,'Russian Federation','RU',0),(178,'Rwanda','RW',0),(179,'Saint Helena','SH',0),(180,'Saint Kitts and Nevis','KN',0),(181,'Saint Lucia','LC',0),(182,'Saint Pierre and Miquelon','PM',0),(183,'Saint Vincent and the Grenadines','VC',0),(184,'Samoa','WS',0),(185,'San Marino','SM',0),(186,'Sao Tome and Principe','ST',0),(187,'Saudi Arabia','SA',0),(188,'Senegal','SN',0),(189,'Seychelles','SC',0),(190,'Sierra Leone','SL',0),(191,'Singapore','SG',0),(192,'Slovakia','SK',0),(193,'Slovenia','SI',0),(194,'Solomon Islands','SB',0),(195,'Somalia','SO',0),(196,'South Africa','ZA',0),(197,'South Georgia and the South Sandwich Islands','GS',0),(198,'South Korea','KR',0),(199,'Spain','ES',0),(200,'Sri Lanka','LK',0),(201,'Sudan','SD',0),(202,'Suriname','SR',0),(203,'Svalbard and Jan Mayen','SJ',0),(204,'Swaziland','SZ',0),(205,'Sweden','SE',0),(206,'Switzerland','CH',0),(207,'Syria','SY',0),(208,'Taiwan','TW',0),(209,'Tajikistan','TJ',0),(210,'Tanzania','TZ',0),(211,'Thailand','TH',0),(212,'Togo','TG',0),(213,'Tokelau','TK',0),(214,'Tonga','TO',0),(215,'Trinidad and Tobago','TT',0),(216,'Tunisia','TN',0),(217,'Turkey','TR',0),(218,'Turkmenistan','TM',0),(219,'Turks and Caicos Islands','TC',0),(220,'Tuvalu','TV',0),(221,'Uganda','UG',0),(222,'Ukraine','UA',0),(223,'United Arab Emirates','AE',0),(224,'United Kingdom','GB',0),(225,'United States','US',1),(226,'United States Minor Outlying Islands','UM',0),(227,'Uruguay','UY',0),(228,'Uzbekistan','UZ',0),(229,'Vanuatu','VU',0),(230,'Venezuela','VE',1),(231,'Vietnam','VN',0),(232,'Virgin Islands, British','VG',0),(233,'Virgin Islands, U.S.','VI',0),(234,'Wallis and Futuna','WF',0),(235,'Western Sahara','EH',0),(236,'Yemen','YE',0),(237,'Yugoslavia','YU',0),(238,'Zambia','ZM',0),(239,'Zimbabwe','ZW',0);
/*!40000 ALTER TABLE `#__cp_prm_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_currency`
--

DROP TABLE IF EXISTS `#__cp_prm_currency`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_currency` (
  `currency_id` int(11) NOT NULL auto_increment,
  `currency_name` varchar(100) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `approx` tinyint(4) NOT NULL,
  `trm` double NOT NULL,
  `published` tinyint(4) NOT NULL,
  `default_currency` tinyint(4) NOT NULL,
  PRIMARY KEY  (`currency_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_currency`
--

LOCK TABLES `#__cp_prm_currency` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_currency` DISABLE KEYS */;
INSERT INTO `#__cp_prm_currency` (`currency_id`, `currency_name`, `currency_code`, `approx`, `trm`, `published`, `default_currency`) VALUES (1,'Peso Colombiano','COP',1,1,1,0),(2,'Dólar Americano','USD',7,1,1,1),(5,'Libra Esterlina','GBP',1,2918.3,0,0),(4,'Bolívares Fuertes','BsF',2,423.5,0,0),(3,'Euro','EUR',7,2331.24,1,0),(6,'Dólar Canadiense','CAD',1,1868.4,0,0),(16,'Yen','JPY',1,22.31,0,0);
/*!40000 ALTER TABLE `#__cp_prm_currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_hotels_amenity`
--

DROP TABLE IF EXISTS `#__cp_prm_hotels_amenity`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_hotels_amenity` (
  `amenity_id` int(11) NOT NULL auto_increment,
  `amenity_name` varchar(100) NOT NULL,
  `imageurl` varchar(100) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`amenity_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_hotels_amenity`
--

LOCK TABLES `#__cp_prm_hotels_amenity` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_hotels_amenity` DISABLE KEYS */;
INSERT INTO `#__cp_prm_hotels_amenity` (`amenity_id`, `amenity_name`, `imageurl`, `published`) VALUES (1,'Amenities/Facilities ejemplo','images/amenities/bar1.png',1),(2,'Bar','images/amenities/bar1.png',1),(3,'Gym','images/amenities/gym1.png',1);
/*!40000 ALTER TABLE `#__cp_prm_hotels_amenity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_hotels_category`
--

DROP TABLE IF EXISTS `#__cp_prm_hotels_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_hotels_category` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(100) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_hotels_category`
--

LOCK TABLES `#__cp_prm_hotels_category` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_hotels_category` DISABLE KEYS */;
INSERT INTO `#__cp_prm_hotels_category` (`category_id`, `category_name`, `published`) VALUES (1,'Tipo de alojamiento ejemplo',1),(2,'Hotel',1),(3,'Cabaño',1),(4,'Finca',1);
/*!40000 ALTER TABLE `#__cp_prm_hotels_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_hotels_param1`
--

DROP TABLE IF EXISTS `#__cp_prm_hotels_param1`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_hotels_param1` (
  `param1_id` int(11) NOT NULL auto_increment,
  `param1_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param1_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_hotels_param1`
--

LOCK TABLES `#__cp_prm_hotels_param1` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_hotels_param1` DISABLE KEYS */;
INSERT INTO `#__cp_prm_hotels_param1` (`param1_id`, `param1_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Ejemplo Tipo de habitación',NULL,NULL,NULL,1),(2,'Standard',NULL,NULL,NULL,1),(3,'Suite',NULL,NULL,NULL,1),(4,'Deluxe',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `#__cp_prm_hotels_param1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_hotels_param2`
--

DROP TABLE IF EXISTS `#__cp_prm_hotels_param2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_hotels_param2` (
  `param2_id` int(11) NOT NULL auto_increment,
  `param2_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param2_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_hotels_param2`
--

LOCK TABLES `#__cp_prm_hotels_param2` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_hotels_param2` DISABLE KEYS */;
INSERT INTO `#__cp_prm_hotels_param2` (`param2_id`, `param2_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Ejemplo de acomodación sencilla',NULL,NULL,1,1),(2,'Doble',NULL,NULL,2,1),(3,'Triple',NULL,NULL,3,1);
/*!40000 ALTER TABLE `#__cp_prm_hotels_param2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_hotels_param3`
--

DROP TABLE IF EXISTS `#__cp_prm_hotels_param3`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_hotels_param3` (
  `param3_id` int(11) NOT NULL auto_increment,
  `param3_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param3_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_hotels_param3`
--

LOCK TABLES `#__cp_prm_hotels_param3` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_hotels_param3` DISABLE KEYS */;
INSERT INTO `#__cp_prm_hotels_param3` (`param3_id`, `param3_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Plan de alimentacion ejemplo',NULL,NULL,NULL,1),(2,'PAM',NULL,NULL,NULL,1),(3,'FULL',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `#__cp_prm_hotels_param3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_plans_category`
--

DROP TABLE IF EXISTS `#__cp_prm_plans_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_plans_category` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(100) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_plans_category`
--

LOCK TABLES `#__cp_prm_plans_category` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_plans_category` DISABLE KEYS */;
INSERT INTO `#__cp_prm_plans_category` (`category_id`, `category_name`, `published`) VALUES (1,'Ejemplo Categoría de paquetes',1);
/*!40000 ALTER TABLE `#__cp_prm_plans_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_plans_param1`
--

DROP TABLE IF EXISTS `#__cp_prm_plans_param1`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_plans_param1` (
  `param1_id` int(11) NOT NULL auto_increment,
  `param1_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param1_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_plans_param1`
--

LOCK TABLES `#__cp_prm_plans_param1` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_plans_param1` DISABLE KEYS */;
INSERT INTO `#__cp_prm_plans_param1` (`param1_id`, `param1_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Hotel Ejemplo para paquetes','Calle Av 30 # 80-30',4,NULL,1);
/*!40000 ALTER TABLE `#__cp_prm_plans_param1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_plans_param2`
--

DROP TABLE IF EXISTS `#__cp_prm_plans_param2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_plans_param2` (
  `param2_id` int(11) NOT NULL auto_increment,
  `param2_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param2_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_plans_param2`
--

LOCK TABLES `#__cp_prm_plans_param2` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_plans_param2` DISABLE KEYS */;
INSERT INTO `#__cp_prm_plans_param2` (`param2_id`, `param2_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Ejemplo de acomodación para paquetes',NULL,NULL,1,1);
/*!40000 ALTER TABLE `#__cp_prm_plans_param2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_plans_param3`
--

DROP TABLE IF EXISTS `#__cp_prm_plans_param3`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_plans_param3` (
  `param3_id` int(11) NOT NULL auto_increment,
  `param3_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param3_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_plans_param3`
--

LOCK TABLES `#__cp_prm_plans_param3` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_plans_param3` DISABLE KEYS */;
INSERT INTO `#__cp_prm_plans_param3` (`param3_id`, `param3_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Plan de alimentacion ejemplo para paquetes',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `#__cp_prm_plans_param3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_product_type`
--

DROP TABLE IF EXISTS `#__cp_prm_product_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_product_type` (
  `product_type_id` int(11) NOT NULL auto_increment,
  `product_type_name` varchar(100) NOT NULL,
  `product_type_code` varchar(10) NOT NULL,
  `image_url` varchar(100) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`product_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_product_type`
--

LOCK TABLES `#__cp_prm_product_type` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_product_type` DISABLE KEYS */;
INSERT INTO `#__cp_prm_product_type` (`product_type_id`, `product_type_name`, `product_type_code`, `image_url`, `published`) VALUES (1,'Alojamientos','hotels','assets/images/hotel.png',1),(2,'Paquetes','plans','assets/images/plan.png',1),(3,'Autos','cars','assets/images/car.png',0),(4,'Traslados','transfers','assets/images/transfer.png',0),(5,'Tours','tours','assets/images/transfer.png',1);
/*!40000 ALTER TABLE `#__cp_prm_product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_region`
--

DROP TABLE IF EXISTS `#__cp_prm_region`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_region` (
  `region_id` int(11) NOT NULL auto_increment,
  `region_name` varchar(100) NOT NULL,
  `region_code` char(5) NOT NULL,
  `country_id` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`region_id`)
) ENGINE=MyISAM AUTO_INCREMENT=249 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_region`
--

LOCK TABLES `#__cp_prm_region` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_region` DISABLE KEYS */;
INSERT INTO `#__cp_prm_region` (`region_id`, `region_name`, `region_code`, `country_id`, `published`) VALUES (1,'General','GEN',1,1),(2,'General','GEN',2,1),(3,'General','GEN',3,1),(4,'General','GEN',4,1),(5,'General','GEN',5,1),(6,'General','GEN',6,1),(7,'General','GEN',7,1),(8,'General','GEN',8,1),(9,'General','GEN',9,1),(10,'General','GEN',10,1),(11,'General','GEN',11,1),(12,'General','GEN',12,1),(13,'General','GEN',13,1),(14,'General','GEN',14,1),(15,'General','GEN',15,1),(16,'General','GEN',16,1),(17,'General','GEN',17,1),(18,'General','GEN',18,1),(19,'General','GEN',19,1),(20,'General','GEN',20,1),(21,'General','GEN',21,1),(22,'General','GEN',22,1),(23,'General','GEN',23,1),(24,'General','GEN',24,1),(25,'General','GEN',25,1),(26,'General','GEN',26,1),(27,'General','GEN',27,1),(28,'General','GEN',28,1),(29,'General','GEN',29,1),(30,'General','GEN',30,1),(31,'General','GEN',31,1),(32,'General','GEN',32,1),(33,'General','GEN',33,1),(34,'General','GEN',34,1),(35,'General','GEN',35,1),(36,'General','GEN',36,1),(37,'General','GEN',37,1),(38,'General','GEN',38,1),(39,'General','GEN',39,1),(40,'General','GEN',40,1),(41,'General','GEN',41,1),(42,'General','GEN',42,1),(43,'General','GEN',43,1),(44,'General','GEN',44,1),(45,'General','GEN',45,1),(46,'General','GEN',46,1),(47,'General Col','GEN',47,1),(48,'General','GEN',48,1),(49,'General','GEN',49,1),(50,'General','GEN',50,1),(51,'General','GEN',51,1),(52,'General','GEN',52,1),(53,'General','GEN',53,1),(54,'General','GEN',54,1),(55,'General','GEN',55,1),(56,'General','GEN',56,1),(57,'General','GEN',57,1),(58,'General','GEN',58,1),(59,'General','GEN',59,1),(60,'General','GEN',60,1),(61,'General','GEN',61,1),(62,'General','GEN',62,1),(63,'General','GEN',63,1),(64,'General','GEN',64,1),(65,'General','GEN',65,1),(66,'General','GEN',66,1),(67,'General','GEN',67,1),(68,'General','GEN',68,1),(69,'General','GEN',69,1),(70,'General','GEN',70,1),(71,'General','GEN',71,1),(72,'General','GEN',72,1),(73,'General','GEN',73,1),(74,'General','GEN',74,1),(75,'General','GEN',75,1),(76,'General','GEN',76,1),(77,'General','GEN',77,1),(78,'General','GEN',78,1),(79,'General','GEN',79,1),(80,'General','GEN',80,1),(81,'General','GEN',81,1),(82,'General','GEN',82,1),(83,'General','GEN',83,1),(84,'General','GEN',84,1),(85,'General','GEN',85,1),(86,'General','GEN',86,1),(87,'General','GEN',87,1),(88,'General','GEN',88,1),(89,'General','GEN',89,1),(90,'General','GEN',90,1),(91,'General','GEN',91,1),(92,'General','GEN',92,1),(93,'General','GEN',93,1),(94,'General','GEN',94,1),(95,'General','GEN',95,1),(96,'General','GEN',96,1),(97,'General','GEN',97,1),(98,'General','GEN',98,1),(99,'General','GEN',99,1),(100,'General','GEN',100,1),(101,'General','GEN',101,1),(102,'General','GEN',102,1),(103,'General','GEN',103,1),(104,'General','GEN',104,1),(105,'General','GEN',105,1),(106,'General','GEN',106,1),(107,'General','GEN',107,1),(108,'General','GEN',108,1),(109,'General','GEN',109,1),(110,'General','GEN',110,1),(111,'General','GEN',111,1),(112,'General','GEN',112,1),(113,'General','GEN',113,1),(114,'General','GEN',114,1),(115,'General','GEN',115,1),(116,'General','GEN',116,1),(117,'General','GEN',117,1),(118,'General','GEN',118,1),(119,'General','GEN',119,1),(120,'General','GEN',120,1),(121,'General','GEN',121,1),(122,'General','GEN',122,1),(123,'General','GEN',123,1),(124,'General','GEN',124,1),(125,'General','GEN',125,1),(126,'General','GEN',126,1),(127,'General','GEN',127,1),(128,'General','GEN',128,1),(129,'General','GEN',129,1),(130,'General','GEN',130,1),(131,'General','GEN',131,1),(132,'General','GEN',132,1),(133,'General','GEN',133,1),(134,'General','GEN',134,1),(135,'General','GEN',135,1),(136,'General','GEN',136,1),(137,'General','GEN',137,1),(138,'General','GEN',138,1),(139,'General','GEN',139,1),(140,'General','GEN',140,1),(141,'General','GEN',141,1),(142,'General','GEN',142,1),(143,'General','GEN',143,1),(144,'General','GEN',144,1),(145,'General','GEN',145,1),(146,'General','GEN',146,1),(147,'General','GEN',147,1),(148,'General','GEN',148,1),(149,'General','GEN',149,1),(150,'General','GEN',150,1),(151,'General','GEN',151,1),(152,'General','GEN',152,1),(153,'General','GEN',153,1),(154,'General','GEN',154,1),(155,'General','GEN',155,1),(156,'General','GEN',156,1),(157,'General','GEN',157,1),(158,'General','GEN',158,1),(159,'General','GEN',159,1),(160,'General','GEN',160,1),(161,'General','GEN',161,1),(162,'General','GEN',162,1),(163,'General','GEN',163,1),(164,'General','GEN',164,1),(165,'General','GEN',165,1),(166,'General','GEN',166,1),(167,'General','GEN',167,1),(168,'General','GEN',168,1),(169,'General','GEN',169,1),(170,'General','GEN',170,1),(171,'General','GEN',171,1),(172,'General','GEN',172,1),(173,'General','GEN',173,1),(174,'General','GEN',174,1),(175,'General','GEN',175,1),(176,'General','GEN',176,1),(177,'General','GEN',177,1),(178,'General','GEN',178,1),(179,'General','GEN',179,1),(180,'General','GEN',180,1),(181,'General','GEN',181,1),(182,'General','GEN',182,1),(183,'General','GEN',183,1),(184,'General','GEN',184,1),(185,'General','GEN',185,1),(186,'General','GEN',186,1),(187,'General','GEN',187,1),(188,'General','GEN',188,1),(189,'General','GEN',189,1),(190,'General','GEN',190,1),(191,'General','GEN',191,1),(192,'General','GEN',192,1),(193,'General','GEN',193,1),(194,'General','GEN',194,1),(195,'General','GEN',195,1),(196,'General','GEN',196,1),(197,'General','GEN',197,1),(198,'General','GEN',198,1),(199,'General','GEN',199,1),(200,'General','GEN',200,1),(201,'General','GEN',201,1),(202,'General','GEN',202,1),(203,'General','GEN',203,1),(204,'General','GEN',204,1),(205,'General','GEN',205,1),(206,'General','GEN',206,1),(207,'General','GEN',207,1),(208,'General','GEN',208,1),(209,'General','GEN',209,1),(210,'General','GEN',210,1),(211,'General','GEN',211,1),(212,'General','GEN',212,1),(213,'General','GEN',213,1),(214,'General','GEN',214,1),(215,'General','GEN',215,1),(216,'General','GEN',216,1),(217,'General','GEN',217,1),(218,'General','GEN',218,1),(219,'General','GEN',219,1),(220,'General','GEN',220,1),(221,'General','GEN',221,1),(222,'General','GEN',222,1),(223,'General','GEN',223,1),(224,'General','GEN',224,1),(225,'General','GEN',225,1),(226,'General','GEN',226,1),(227,'General','GEN',227,1),(228,'General','GEN',228,1),(229,'General','GEN',229,1),(230,'General','GEN',230,1),(231,'General','GEN',231,1),(232,'General','GEN',232,1),(233,'General','GEN',233,1),(234,'General','GEN',234,1),(235,'General','GEN',235,1),(236,'General','GEN',236,1),(237,'General','GEN',237,1),(238,'General','GEN',238,1),(239,'General','GEN',239,1),(240,'Campeche','2333',137,1),(241,'Jalisco','MEX02',137,1),(242,'Chiapas','MEX03',137,1),(243,'Chihuahua','MEX04',137,1),(244,'Vera cruz','MEX05',137,1),(245,'Eje Cafetero','eje',47,1),(247,'test','12312',1,1),(248,'Región Caribe','RCA',47,1);
/*!40000 ALTER TABLE `#__cp_prm_region` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_season`
--

DROP TABLE IF EXISTS `#__cp_prm_season`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_season` (
  `season_id` int(11) NOT NULL auto_increment,
  `season_name` varchar(100) NOT NULL,
  `day1` tinyint(4) NOT NULL,
  `day2` tinyint(4) NOT NULL,
  `day3` tinyint(4) NOT NULL,
  `day4` tinyint(4) NOT NULL,
  `day5` tinyint(4) NOT NULL,
  `day6` tinyint(4) NOT NULL,
  `day7` tinyint(4) NOT NULL,
  `is_special` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  PRIMARY KEY  (`season_id`),
  KEY `season_is_special_index` (`is_special`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_season`
--

LOCK TABLES `#__cp_prm_season` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_season` DISABLE KEYS */;
INSERT INTO `#__cp_prm_season` (`season_id`, `season_name`, `day1`, `day2`, `day3`, `day4`, `day5`, `day6`, `day7`, `is_special`, `created`, `created_by`, `modified`, `modified_by`) VALUES (1,'Vigencia Estándar Ejemplo',1,1,1,1,1,1,1,0,'2013-08-29 18:38:19',62,'2013-08-29 18:38:19',62);
/*!40000 ALTER TABLE `#__cp_prm_season` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_season_date`
--

DROP TABLE IF EXISTS `#__cp_prm_season_date`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_season_date` (
  `season_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  UNIQUE KEY `season_date_unique` (`start_date`,`end_date`,`season_id`),
  KEY `season_date_season_id_index` (`season_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_season_date`
--

LOCK TABLES `#__cp_prm_season_date` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_season_date` DISABLE KEYS */;
INSERT INTO `#__cp_prm_season_date` (`season_id`, `start_date`, `end_date`) VALUES (1,'2013-08-29','2013-09-30');
/*!40000 ALTER TABLE `#__cp_prm_season_date` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_season_product_type`
--

DROP TABLE IF EXISTS `#__cp_prm_season_product_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_season_product_type` (
  `season_id` int(11) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  PRIMARY KEY  (`season_id`,`product_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_season_product_type`
--

LOCK TABLES `#__cp_prm_season_product_type` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_season_product_type` DISABLE KEYS */;
INSERT INTO `#__cp_prm_season_product_type` (`season_id`, `product_type_id`) VALUES (1,1),(1,2),(1,5);
/*!40000 ALTER TABLE `#__cp_prm_season_product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_supplement`
--

DROP TABLE IF EXISTS `#__cp_prm_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_supplement` (
  `supplement_id` int(11) NOT NULL auto_increment,
  `supplement_name` varchar(50) NOT NULL,
  `supplement_code` varchar(10) NOT NULL,
  `imageurl` varchar(100) default NULL,
  `description` varchar(255) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_supplement`
--

LOCK TABLES `#__cp_prm_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_prm_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_supplement_product_type`
--

DROP TABLE IF EXISTS `#__cp_prm_supplement_product_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_supplement_product_type` (
  `supplement_id` int(11) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  PRIMARY KEY  (`supplement_id`,`product_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_supplement_product_type`
--

LOCK TABLES `#__cp_prm_supplement_product_type` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_supplement_product_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_prm_supplement_product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_supplement_tourismtype`
--

DROP TABLE IF EXISTS `#__cp_prm_supplement_tourismtype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_supplement_tourismtype` (
  `supplement_id` int(11) NOT NULL,
  `tourismtype_id` int(11) NOT NULL,
  PRIMARY KEY  (`supplement_id`,`tourismtype_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_supplement_tourismtype`
--

LOCK TABLES `#__cp_prm_supplement_tourismtype` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_supplement_tourismtype` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_prm_supplement_tourismtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_supplier`
--

DROP TABLE IF EXISTS `#__cp_prm_supplier`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_supplier` (
  `supplier_id` int(11) NOT NULL auto_increment,
  `supplier_name` varchar(100) NOT NULL,
  `supplier_code` varchar(20) NOT NULL,
  `phone` varchar(15) default NULL,
  `fax` varchar(15) default NULL,
  `url` varchar(100) default NULL,
  `email` varchar(100) default NULL,
  `country_id` int(11) default NULL,
  `city_id` int(11) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_supplier`
--

LOCK TABLES `#__cp_prm_supplier` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_supplier` DISABLE KEYS */;
INSERT INTO `#__cp_prm_supplier` (`supplier_id`, `supplier_name`, `supplier_code`, `phone`, `fax`, `url`, `email`, `country_id`, `city_id`, `published`) VALUES (1,'Ejemplo de Proveedor','1','','','','',38,1618,1);
/*!40000 ALTER TABLE `#__cp_prm_supplier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_supplier_product_type`
--

DROP TABLE IF EXISTS `#__cp_prm_supplier_product_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_supplier_product_type` (
  `supplier_id` int(11) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  PRIMARY KEY  (`supplier_id`,`product_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_supplier_product_type`
--

LOCK TABLES `#__cp_prm_supplier_product_type` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_supplier_product_type` DISABLE KEYS */;
INSERT INTO `#__cp_prm_supplier_product_type` (`supplier_id`, `product_type_id`) VALUES (1,1),(1,2),(1,5);
/*!40000 ALTER TABLE `#__cp_prm_supplier_product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_tax`
--

DROP TABLE IF EXISTS `#__cp_prm_tax`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_tax` (
  `tax_id` int(11) NOT NULL auto_increment,
  `tax_name` varchar(100) NOT NULL,
  `tax_code` varchar(10) NOT NULL,
  `tax_value` double NOT NULL,
  `iva` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_tax`
--

LOCK TABLES `#__cp_prm_tax` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_prm_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_tax_product_type`
--

DROP TABLE IF EXISTS `#__cp_prm_tax_product_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_tax_product_type` (
  `tax_id` int(11) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  PRIMARY KEY  (`tax_id`,`product_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_tax_product_type`
--

LOCK TABLES `#__cp_prm_tax_product_type` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_tax_product_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_prm_tax_product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_tourismtype`
--

DROP TABLE IF EXISTS `#__cp_prm_tourismtype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;

CREATE TABLE `#__cp_prm_tourismtype` (
  `tourismtype_id` int(11) NOT NULL AUTO_INCREMENT,
  `tourismtype_name` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `publishedqs` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`tourismtype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_tourismtype`
--

LOCK TABLES `#__cp_prm_tourismtype` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_tourismtype` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_prm_tourismtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_tours_category`
--

DROP TABLE IF EXISTS `#__cp_prm_tours_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_tours_category` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(100) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_tours_category`
--

LOCK TABLES `#__cp_prm_tours_category` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_tours_category` DISABLE KEYS */;
INSERT INTO `#__cp_prm_tours_category` (`category_id`, `category_name`, `published`) VALUES (5,'Cruceros',1);
/*!40000 ALTER TABLE `#__cp_prm_tours_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_tours_param1`
--

DROP TABLE IF EXISTS `#__cp_prm_tours_param1`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_tours_param1` (
  `param1_id` int(11) NOT NULL auto_increment,
  `param1_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param1_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_tours_param1`
--

LOCK TABLES `#__cp_prm_tours_param1` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_tours_param1` DISABLE KEYS */;
INSERT INTO `#__cp_prm_tours_param1` (`param1_id`, `param1_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Hotel Hilton','Bocagrande Cra2',4,NULL,1),(2,'Hotel Decameron','',5,NULL,1),(3,'Hotel La Fontana','cra 15 # 12-80',5,NULL,1),(4,'Hotel El Dorado','',3,NULL,1),(5,'Hotel Marriot','',0,NULL,1),(6,'Shangri-La Villingili Resort','',5,NULL,1),(7,'San Miguel','Calle San andres # 45 ',4,NULL,1),(8,'Hotel Cocoplum','Avenida las flores 45 34',5,NULL,0),(9,'San Luis','Calle armenía # 34 -55',4,NULL,1),(10,'Mar Azul','Avenida las amigas 56645',3,NULL,1);
/*!40000 ALTER TABLE `#__cp_prm_tours_param1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_tours_param2`
--

DROP TABLE IF EXISTS `#__cp_prm_tours_param2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_tours_param2` (
  `param2_id` int(11) NOT NULL auto_increment,
  `param2_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param2_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_tours_param2`
--

LOCK TABLES `#__cp_prm_tours_param2` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_tours_param2` DISABLE KEYS */;
INSERT INTO `#__cp_prm_tours_param2` (`param2_id`, `param2_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Cabaña',NULL,NULL,50,1),(2,'Sencilla',NULL,NULL,1,1),(3,'Doble',NULL,NULL,2,1),(4,'Triple',NULL,NULL,3,1);
/*!40000 ALTER TABLE `#__cp_prm_tours_param2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_tours_param3`
--

DROP TABLE IF EXISTS `#__cp_prm_tours_param3`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_tours_param3` (
  `param3_id` int(11) NOT NULL auto_increment,
  `param3_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param3_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_tours_param3`
--

LOCK TABLES `#__cp_prm_tours_param3` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_tours_param3` DISABLE KEYS */;
INSERT INTO `#__cp_prm_tours_param3` (`param3_id`, `param3_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Full',NULL,NULL,NULL,1),(2,'Sin Alimentación',NULL,NULL,NULL,1),(3,'Sólo desayuno',NULL,NULL,NULL,1),(4,'Desayuno Americano',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `#__cp_prm_tours_param3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_transfers_category`
--

DROP TABLE IF EXISTS `#__cp_prm_transfers_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_transfers_category` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(100) NOT NULL,
  `transfer_type` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_transfers_category`
--

LOCK TABLES `#__cp_prm_transfers_category` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_transfers_category` DISABLE KEYS */;
INSERT INTO `#__cp_prm_transfers_category` (`category_id`, `category_name`, `transfer_type`, `published`) VALUES (1,'Hotel - Aeropuerto',1,1),(2,'Aeropuerto - Hotel',1,1),(3,'Hotel - Aeropuerto - Hotel',2,1),(4,'Aeropuerto - Hotel - Aeropuerto',2,1),(7,'bta cali',1,1);
/*!40000 ALTER TABLE `#__cp_prm_transfers_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_transfers_param1`
--

DROP TABLE IF EXISTS `#__cp_prm_transfers_param1`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_transfers_param1` (
  `param1_id` int(11) NOT NULL auto_increment,
  `param1_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param1_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_transfers_param1`
--

LOCK TABLES `#__cp_prm_transfers_param1` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_transfers_param1` DISABLE KEYS */;
INSERT INTO `#__cp_prm_transfers_param1` (`param1_id`, `param1_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Compartido',NULL,NULL,NULL,1),(2,'Privado',NULL,NULL,NULL,1),(3,'Chiva',NULL,NULL,NULL,0),(4,'Público',NULL,NULL,NULL,1),(5,'Van',NULL,NULL,NULL,1),(6,'Ferry',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `#__cp_prm_transfers_param1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_transfers_param2`
--

DROP TABLE IF EXISTS `#__cp_prm_transfers_param2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_transfers_param2` (
  `param2_id` int(11) NOT NULL auto_increment,
  `param2_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param2_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_transfers_param2`
--

LOCK TABLES `#__cp_prm_transfers_param2` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_transfers_param2` DISABLE KEYS */;
INSERT INTO `#__cp_prm_transfers_param2` (`param2_id`, `param2_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'1 persona',NULL,1,1,1),(2,'2 a 5 personas',NULL,5,2,1),(3,'6 a 8 personas',NULL,8,6,1),(4,'9 a 20 personas',NULL,20,9,1),(5,'Chiva',NULL,15,5,1),(6,'1 a 3',NULL,3,1,1);
/*!40000 ALTER TABLE `#__cp_prm_transfers_param2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_transfers_param3`
--

DROP TABLE IF EXISTS `#__cp_prm_transfers_param3`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_transfers_param3` (
  `param3_id` int(11) NOT NULL auto_increment,
  `param3_name` varchar(100) NOT NULL,
  `description` varchar(255) default NULL,
  `value` int(11) default NULL,
  `capacity` tinyint(4) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`param3_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_transfers_param3`
--

LOCK TABLES `#__cp_prm_transfers_param3` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_transfers_param3` DISABLE KEYS */;
INSERT INTO `#__cp_prm_transfers_param3` (`param3_id`, `param3_name`, `description`, `value`, `capacity`, `published`) VALUES (1,'Lugar 1',NULL,NULL,NULL,1),(2,'Lugar 2',NULL,NULL,NULL,1),(3,'Lugar 3',NULL,NULL,NULL,1),(4,'La Nubia',NULL,NULL,NULL,1),(5,'Cartagena - Boca Grande',NULL,NULL,NULL,1),(6,'Cartagena - Centro histórico',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `#__cp_prm_transfers_param3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_prm_zone`
--

DROP TABLE IF EXISTS `#__cp_prm_zone`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_prm_zone` (
  `zone_id` int(11) NOT NULL auto_increment,
  `zone_name` varchar(100) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_prm_zone`
--

LOCK TABLES `#__cp_prm_zone` WRITE;
/*!40000 ALTER TABLE `#__cp_prm_zone` DISABLE KEYS */;
INSERT INTO `#__cp_prm_zone` (`zone_id`, `zone_name`, `published`) VALUES (1,'Aeropuerto',1),(2,'Centro Histórico',1),(3,'Occidente',1),(4,'Norte',0),(5,'Bello Horizonte',1);
/*!40000 ALTER TABLE `#__cp_prm_zone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_rate_resume_log`
--

DROP TABLE IF EXISTS `#__cp_rate_resume_log`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_rate_resume_log` (
  `product_type_code` varchar(10) NOT NULL,
  `last_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_rate_resume_log`
--

LOCK TABLES `#__cp_rate_resume_log` WRITE;
/*!40000 ALTER TABLE `#__cp_rate_resume_log` DISABLE KEYS */;
INSERT INTO `#__cp_rate_resume_log` (`product_type_code`, `last_date`) VALUES ('hotels','2014-06-18'),('plans','2014-06-18'),('cars','2014-02-06'),('transfers','2014-02-06'),('tours','2014-06-18');
/*!40000 ALTER TABLE `#__cp_rate_resume_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_comments`
--

DROP TABLE IF EXISTS `#__cp_tours_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_comments` (
  `comment_id` int(11) NOT NULL auto_increment,
  `order_id` tinyint(4) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `comment_rate` int(11) NOT NULL,
  `comment_text` varchar(400) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  `end_date` date NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_comments`
--

LOCK TABLES `#__cp_tours_comments` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_tours_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_files`
--

DROP TABLE IF EXISTS `#__cp_tours_files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_files` (
  `product_id` int(11) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  UNIQUE KEY `tours_files_unique` (`product_id`,`file_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_files`
--

LOCK TABLES `#__cp_tours_files` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_tours_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_info`
--

DROP TABLE IF EXISTS `#__cp_tours_info`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_info` (
  `product_id` int(11) NOT NULL auto_increment,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(10) default NULL,
  `product_desc` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `region_id` int(11) default NULL,
  `city_id` int(11) NOT NULL,
  `featured` tinyint(4) NOT NULL,
  `latitude` varchar(255) default NULL,
  `longitude` varchar(255) default NULL,
  `ordering` int(11) NOT NULL default '0',
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `access` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `tag_name1` varchar(255) default NULL,
  `tag_content1` mediumtext,
  `tag_name2` varchar(255) default NULL,
  `tag_content2` mediumtext,
  `tag_name3` varchar(255) default NULL,
  `tag_content3` mediumtext,
  `tag_name4` varchar(255) default NULL,
  `tag_content4` mediumtext,
  `tag_name5` varchar(255) default NULL,
  `tag_content5` mediumtext,
  `tag_name6` varchar(255) default NULL,
  `tag_content6` mediumtext,
  `product_url` mediumtext,
  `supplier_id` int(11) default NULL,
  `duration` varchar(20) NOT NULL,
  `days_total` int(11) NOT NULL,
  `average_rating` tinyint(4) default '0',
  `additional_description` varchar(255) default NULL,
  `disclaimer` varchar(500) default NULL,
  `adult_without_child` int(11) default NULL,
  PRIMARY KEY  (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Guarda los productos del Catálogo.';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_info`
--

LOCK TABLES `#__cp_tours_info` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_info` DISABLE KEYS */;
INSERT INTO `#__cp_tours_info` (`product_id`, `product_name`, `product_code`, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, `created`, `created_by`, `modified`, `modified_by`, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `duration`, `days_total`, `average_rating`, `additional_description`, `disclaimer`, `adult_without_child`) VALUES (1,'Viajes algo mas','0000000004','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis,',5,47,0,2552,1,'-9.88227549342994','-58.88671875',0,'2013-08-29 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'2013-09-02 15:28:36',62,0,1,'Descripción General','','Incluye','','No incluye','','Itinerario','','Pago y condiciones del servicio','','','','',1,'Duración en días del',0,0,NULL,'Tarifas incluyen impuestos',0);
/*!40000 ALTER TABLE `#__cp_tours_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_param1`
--

DROP TABLE IF EXISTS `#__cp_tours_param1`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_param1` (
  `product_id` int(11) NOT NULL,
  `param1_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param1_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_param1`
--

LOCK TABLES `#__cp_tours_param1` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_param1` DISABLE KEYS */;
INSERT INTO `#__cp_tours_param1` (`product_id`, `param1_id`) VALUES (1,1);
/*!40000 ALTER TABLE `#__cp_tours_param1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_param2`
--

DROP TABLE IF EXISTS `#__cp_tours_param2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_param2` (
  `product_id` int(11) NOT NULL,
  `param2_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_param2`
--

LOCK TABLES `#__cp_tours_param2` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_param2` DISABLE KEYS */;
INSERT INTO `#__cp_tours_param2` (`product_id`, `param2_id`) VALUES (1,1),(1,2);
/*!40000 ALTER TABLE `#__cp_tours_param2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_param3`
--

DROP TABLE IF EXISTS `#__cp_tours_param3`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_param3` (
  `product_id` int(11) NOT NULL,
  `param3_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param3_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_param3`
--

LOCK TABLES `#__cp_tours_param3` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_param3` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_tours_param3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_rate`
--

DROP TABLE IF EXISTS `#__cp_tours_rate`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_rate` (
  `rate_id` int(11) NOT NULL auto_increment,
  `season_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `basic_price` double default NULL,
  `previous_value` double default NULL,
  `currency_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY  (`rate_id`),
  KEY `tours_rate_product_id` (`product_id`),
  KEY `tours_rate_season_id` (`season_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_rate`
--

LOCK TABLES `#__cp_tours_rate` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_rate` DISABLE KEYS */;
INSERT INTO `#__cp_tours_rate` (`rate_id`, `season_id`, `product_id`, `basic_price`, `previous_value`, `currency_id`, `created`, `created_by`, `modified`, `modified_by`) VALUES (1,1,1,10,10,0,'2013-08-29 19:03:41',62,'2013-08-29 19:03:41',62);
/*!40000 ALTER TABLE `#__cp_tours_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_rate_price`
--

DROP TABLE IF EXISTS `#__cp_tours_rate_price`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_rate_price` (
  `rate_id` int(11) NOT NULL,
  `param1` int(11) NOT NULL default '0',
  `param2` int(11) NOT NULL default '0',
  `param3` int(11) NOT NULL default '0',
  `price` double NOT NULL,
  `is_child` tinyint(4) NOT NULL default '0',
  UNIQUE KEY `tours_rate_price_unique` (`rate_id`,`param1`,`param3`,`param2`),
  KEY `tours_rate_id` (`rate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_rate_price`
--

LOCK TABLES `#__cp_tours_rate_price` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_rate_price` DISABLE KEYS */;
INSERT INTO `#__cp_tours_rate_price` (`rate_id`, `param1`, `param2`, `param3`, `price`, `is_child`) VALUES (1,1,1,0,10,0),(1,1,2,0,10,0);
/*!40000 ALTER TABLE `#__cp_tours_rate_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_rate_supplement`
--

DROP TABLE IF EXISTS `#__cp_tours_rate_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_rate_supplement` (
  `rate_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY  (`rate_id`,`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_rate_supplement`
--

LOCK TABLES `#__cp_tours_rate_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_rate_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_tours_rate_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_resume`
--

DROP TABLE IF EXISTS `#__cp_tours_resume`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_resume` (
  `product_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `stock` int(11) NOT NULL,
  `adult_price` double NOT NULL,
  `currency_id` int(11) NOT NULL,
  `child_price` double NOT NULL,
  `previous_price` double NOT NULL,
  UNIQUE KEY `tours_resume_unique_index` (`product_id`,`date`),
  KEY `tours_resume_date` (`date`),
  KEY `tours_resume_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_resume`
--

LOCK TABLES `#__cp_tours_resume` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_resume` DISABLE KEYS */;
INSERT INTO `#__cp_tours_resume` (`product_id`, `date`, `stock`, `adult_price`, `currency_id`, `child_price`, `previous_price`) VALUES (1,'2013-08-29',0,10,2,10,10),(1,'2013-08-30',0,10,2,10,10),(1,'2013-08-31',0,10,2,10,10),(1,'2013-09-01',0,10,2,10,10),(1,'2013-09-02',0,10,2,10,10),(1,'2013-09-03',0,10,2,10,10),(1,'2013-09-04',0,10,2,10,10),(1,'2013-09-05',0,10,2,10,10),(1,'2013-09-06',0,10,2,10,10),(1,'2013-09-07',0,10,2,10,10),(1,'2013-09-08',0,10,2,10,10),(1,'2013-09-09',0,10,2,10,10),(1,'2013-09-10',0,10,2,10,10),(1,'2013-09-11',0,10,2,10,10),(1,'2013-09-12',0,10,2,10,10),(1,'2013-09-13',0,10,2,10,10),(1,'2013-09-14',0,10,2,10,10),(1,'2013-09-15',0,10,2,10,10),(1,'2013-09-16',0,10,2,10,10),(1,'2013-09-17',0,10,2,10,10),(1,'2013-09-18',0,10,2,10,10),(1,'2013-09-19',0,10,2,10,10),(1,'2013-09-20',0,10,2,10,10),(1,'2013-09-21',0,10,2,10,10),(1,'2013-09-22',0,10,2,10,10),(1,'2013-09-23',0,10,2,10,10),(1,'2013-09-24',0,10,2,10,10),(1,'2013-09-25',0,10,2,10,10),(1,'2013-09-26',0,10,2,10,10),(1,'2013-09-27',0,10,2,10,10),(1,'2013-09-28',0,10,2,10,10),(1,'2013-09-29',0,10,2,10,10),(1,'2013-09-30',0,10,2,10,10);
/*!40000 ALTER TABLE `#__cp_tours_resume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_stock`
--

DROP TABLE IF EXISTS `#__cp_tours_stock`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_stock` (
  `product_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `quantity` int(11) NOT NULL,
  UNIQUE KEY `tours_stock_unique_index` (`product_id`,`param_id`,`day`),
  KEY `tours_stock_product_id_index` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_stock`
--

LOCK TABLES `#__cp_tours_stock` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_stock` DISABLE KEYS */;
INSERT INTO `#__cp_tours_stock` (`product_id`, `param_id`, `day`, `quantity`) VALUES (1,1,'2013-08-29',60),(1,1,'2013-08-30',59),(1,1,'2013-08-31',60),(1,1,'2013-09-01',60),(1,1,'2013-09-02',60),(1,1,'2013-09-03',60),(1,1,'2013-09-04',60),(1,1,'2013-09-05',60),(1,1,'2013-09-06',60),(1,1,'2013-09-07',60),(1,1,'2013-09-08',60),(1,1,'2013-09-09',60),(1,1,'2013-09-10',60),(1,1,'2013-09-11',60),(1,1,'2013-09-12',60),(1,1,'2013-09-13',60),(1,1,'2013-09-14',60),(1,1,'2013-09-15',60),(1,1,'2013-09-16',60),(1,1,'2013-09-17',60),(1,1,'2013-09-18',60),(1,1,'2013-09-19',60),(1,1,'2013-09-20',60),(1,1,'2013-09-21',60),(1,1,'2013-09-22',60),(1,1,'2013-09-23',60),(1,1,'2013-09-24',60),(1,1,'2013-09-25',60),(1,1,'2013-09-26',60),(1,1,'2013-09-27',60),(1,1,'2013-09-28',60),(1,1,'2013-09-29',60),(1,1,'2013-09-30',60),(1,1,'2013-10-01',60),(1,1,'2013-10-02',60),(1,1,'2013-10-03',60),(1,1,'2013-10-04',60),(1,1,'2013-10-05',60),(1,1,'2013-10-06',60),(1,1,'2013-10-07',60),(1,1,'2013-10-08',60),(1,1,'2013-10-09',60),(1,1,'2013-10-10',60),(1,1,'2013-10-11',60),(1,1,'2013-10-12',60),(1,1,'2013-10-13',60),(1,1,'2013-10-14',60),(1,1,'2013-10-15',60),(1,1,'2013-10-16',60),(1,1,'2013-10-17',60),(1,1,'2013-10-18',60),(1,1,'2013-10-19',60),(1,1,'2013-10-20',60),(1,1,'2013-10-21',60),(1,1,'2013-10-22',60),(1,1,'2013-10-23',60),(1,1,'2013-10-24',60),(1,1,'2013-10-25',60),(1,1,'2013-10-26',60),(1,1,'2013-10-27',60),(1,1,'2013-10-28',60),(1,1,'2013-10-29',60),(1,1,'2013-10-30',60),(1,1,'2013-10-31',60),(1,1,'2013-11-01',60),(1,1,'2013-11-02',60),(1,1,'2013-11-03',60),(1,1,'2013-11-04',60),(1,1,'2013-11-05',60),(1,1,'2013-11-06',60),(1,1,'2013-11-07',60),(1,1,'2013-11-08',60),(1,1,'2013-11-09',60),(1,1,'2013-11-10',60),(1,1,'2013-11-11',60),(1,1,'2013-11-12',60),(1,1,'2013-11-13',60),(1,1,'2013-11-14',60),(1,1,'2013-11-15',60),(1,1,'2013-11-16',60),(1,1,'2013-11-17',60),(1,1,'2013-11-18',60),(1,1,'2013-11-19',60),(1,1,'2013-11-20',60),(1,1,'2013-11-21',60),(1,1,'2013-11-22',60),(1,1,'2013-11-23',60),(1,1,'2013-11-24',60),(1,1,'2013-11-25',60),(1,1,'2013-11-26',60),(1,1,'2013-11-27',60),(1,1,'2013-11-28',60),(1,1,'2013-11-29',60),(1,1,'2013-11-30',60),(1,1,'2013-12-01',60),(1,1,'2013-12-02',60),(1,1,'2013-12-03',60),(1,1,'2013-12-04',60),(1,1,'2013-12-05',60),(1,1,'2013-12-06',60),(1,1,'2013-12-07',60),(1,1,'2013-12-08',60),(1,1,'2013-12-09',60),(1,1,'2013-12-10',60),(1,1,'2013-12-11',60),(1,1,'2013-12-12',60),(1,1,'2013-12-13',60),(1,1,'2013-12-14',60),(1,1,'2013-12-15',60),(1,1,'2013-12-16',60),(1,1,'2013-12-17',60),(1,1,'2013-12-18',60),(1,1,'2013-12-19',60),(1,1,'2013-12-20',60),(1,1,'2013-12-21',60),(1,1,'2013-12-22',60),(1,1,'2013-12-23',60),(1,1,'2013-12-24',60),(1,1,'2013-12-25',60),(1,1,'2013-12-26',60),(1,1,'2013-12-27',60),(1,1,'2013-12-28',60),(1,1,'2013-12-29',60),(1,1,'2013-12-30',60),(1,1,'2013-12-31',60),(1,1,'2014-01-01',60),(1,1,'2014-01-02',60),(1,1,'2014-01-03',60),(1,1,'2014-01-04',60),(1,1,'2014-01-05',60),(1,1,'2014-01-06',60),(1,1,'2014-01-07',60),(1,1,'2014-01-08',60),(1,1,'2014-01-09',60),(1,1,'2014-01-10',60),(1,1,'2014-01-11',60),(1,1,'2014-01-12',60),(1,1,'2014-01-13',60),(1,1,'2014-01-14',60),(1,1,'2014-01-15',60),(1,1,'2014-01-16',60),(1,1,'2014-01-17',60),(1,1,'2014-01-18',60),(1,1,'2014-01-19',60),(1,1,'2014-01-20',60),(1,1,'2014-01-21',60),(1,1,'2014-01-22',60),(1,1,'2014-01-23',60),(1,1,'2014-01-24',60),(1,1,'2014-01-25',60),(1,1,'2014-01-26',60),(1,1,'2014-01-27',60),(1,1,'2014-01-28',60),(1,1,'2014-01-29',60),(1,1,'2014-01-30',60),(1,1,'2014-01-31',60),(1,1,'2014-02-01',60),(1,1,'2014-02-02',60),(1,1,'2014-02-03',60),(1,1,'2014-02-04',60),(1,1,'2014-02-05',60),(1,1,'2014-02-06',60),(1,1,'2014-02-07',60),(1,1,'2014-02-08',60),(1,1,'2014-02-09',60),(1,1,'2014-02-10',60),(1,1,'2014-02-11',60),(1,1,'2014-02-12',60),(1,1,'2014-02-13',60),(1,1,'2014-02-14',60),(1,1,'2014-02-15',60),(1,1,'2014-02-16',60),(1,1,'2014-02-17',60),(1,1,'2014-02-18',60),(1,1,'2014-02-19',60),(1,1,'2014-02-20',60),(1,1,'2014-02-21',60),(1,1,'2014-02-22',60),(1,1,'2014-02-23',60),(1,1,'2014-02-24',60),(1,1,'2014-02-25',60),(1,1,'2014-02-26',60),(1,1,'2014-02-27',60),(1,1,'2014-02-28',60),(1,1,'2014-03-01',60),(1,1,'2014-03-02',60),(1,1,'2014-03-03',60),(1,1,'2014-03-04',60),(1,1,'2014-03-05',60),(1,1,'2014-03-06',60),(1,1,'2014-03-07',60),(1,1,'2014-03-08',60),(1,1,'2014-03-09',60),(1,1,'2014-03-10',60),(1,1,'2014-03-11',60),(1,1,'2014-03-12',60),(1,1,'2014-03-13',60),(1,1,'2014-03-14',60),(1,1,'2014-03-15',60),(1,1,'2014-03-16',60),(1,1,'2014-03-17',60),(1,1,'2014-03-18',60),(1,1,'2014-03-19',60),(1,1,'2014-03-20',60),(1,1,'2014-03-21',60),(1,1,'2014-03-22',60),(1,1,'2014-03-23',60),(1,1,'2014-03-24',60),(1,1,'2014-03-25',60),(1,1,'2014-03-26',60),(1,1,'2014-03-27',60),(1,1,'2014-03-28',60),(1,1,'2014-03-29',60),(1,1,'2014-03-30',60),(1,1,'2014-03-31',60),(1,1,'2014-04-01',60),(1,1,'2014-04-02',60),(1,1,'2014-04-03',60),(1,1,'2014-04-04',60),(1,1,'2014-04-05',60),(1,1,'2014-04-06',60),(1,1,'2014-04-07',60),(1,1,'2014-04-08',60),(1,1,'2014-04-09',60),(1,1,'2014-04-10',60),(1,1,'2014-04-11',60),(1,1,'2014-04-12',60),(1,1,'2014-04-13',60),(1,1,'2014-04-14',60),(1,1,'2014-04-15',60),(1,1,'2014-04-16',60),(1,1,'2014-04-17',60),(1,1,'2014-04-18',60),(1,1,'2014-04-19',60),(1,1,'2014-04-20',60),(1,1,'2014-04-21',60),(1,1,'2014-04-22',60),(1,1,'2014-04-23',60),(1,1,'2014-04-24',60),(1,1,'2014-04-25',60),(1,1,'2014-04-26',60),(1,1,'2014-04-27',60),(1,1,'2014-04-28',60),(1,1,'2014-04-29',60),(1,1,'2014-04-30',60),(1,1,'2014-05-01',60),(1,1,'2014-05-02',60),(1,1,'2014-05-03',60),(1,1,'2014-05-04',60),(1,1,'2014-05-05',60),(1,1,'2014-05-06',60),(1,1,'2014-05-07',60),(1,1,'2014-05-08',60),(1,1,'2014-05-09',60),(1,1,'2014-05-10',60),(1,1,'2014-05-11',60),(1,1,'2014-05-12',60),(1,1,'2014-05-13',60),(1,1,'2014-05-14',60),(1,1,'2014-05-15',60),(1,1,'2014-05-16',60),(1,1,'2014-05-17',60),(1,1,'2014-05-18',60),(1,1,'2014-05-19',60),(1,1,'2014-05-20',60),(1,1,'2014-05-21',60),(1,1,'2014-05-22',60),(1,1,'2014-05-23',60),(1,1,'2014-05-24',60),(1,1,'2014-05-25',60),(1,1,'2014-05-26',60),(1,1,'2014-05-27',60),(1,1,'2014-05-28',60),(1,1,'2014-05-29',60),(1,1,'2014-05-30',60),(1,1,'2014-05-31',60),(1,1,'2014-06-01',60),(1,1,'2014-06-02',60),(1,1,'2014-06-03',60),(1,1,'2014-06-04',60),(1,1,'2014-06-05',60),(1,1,'2014-06-06',60),(1,1,'2014-06-07',60),(1,1,'2014-06-08',60),(1,1,'2014-06-09',60),(1,1,'2014-06-10',60),(1,1,'2014-06-11',60),(1,1,'2014-06-12',60),(1,1,'2014-06-13',60),(1,1,'2014-06-14',60),(1,1,'2014-06-15',60),(1,1,'2014-06-16',60),(1,1,'2014-06-17',60),(1,1,'2014-06-18',60),(1,1,'2014-06-19',60),(1,1,'2014-06-20',60),(1,1,'2014-06-21',60),(1,1,'2014-06-22',60),(1,1,'2014-06-23',60),(1,1,'2014-06-24',60),(1,1,'2014-06-25',60),(1,1,'2014-06-26',60),(1,1,'2014-06-27',60),(1,1,'2014-06-28',60),(1,1,'2014-06-29',60),(1,1,'2014-06-30',60),(1,1,'2014-07-01',60),(1,1,'2014-07-02',60),(1,1,'2014-07-03',60),(1,1,'2014-07-04',60),(1,1,'2014-07-05',60),(1,1,'2014-07-06',60),(1,1,'2014-07-07',60),(1,1,'2014-07-08',60),(1,1,'2014-07-09',60),(1,1,'2014-07-10',60),(1,1,'2014-07-11',60),(1,1,'2014-07-12',60),(1,1,'2014-07-13',60),(1,1,'2014-07-14',60),(1,1,'2014-07-15',60),(1,1,'2014-07-16',60),(1,1,'2014-07-17',60),(1,1,'2014-07-18',60),(1,1,'2014-07-19',60),(1,1,'2014-07-20',60),(1,1,'2014-07-21',60),(1,1,'2014-07-22',60),(1,1,'2014-07-23',60),(1,1,'2014-07-24',60),(1,1,'2014-07-25',60),(1,1,'2014-07-26',60),(1,1,'2014-07-27',60),(1,1,'2014-07-28',60),(1,1,'2014-07-29',60),(1,1,'2014-07-30',60),(1,1,'2014-07-31',60),(1,1,'2014-08-01',60),(1,1,'2014-08-02',60),(1,1,'2014-08-03',60),(1,1,'2014-08-04',60),(1,1,'2014-08-05',60),(1,1,'2014-08-06',60),(1,1,'2014-08-07',60),(1,1,'2014-08-08',60),(1,1,'2014-08-09',60),(1,1,'2014-08-10',60),(1,1,'2014-08-11',60),(1,1,'2014-08-12',60),(1,1,'2014-08-13',60),(1,1,'2014-08-14',60),(1,1,'2014-08-15',60),(1,1,'2014-08-16',60),(1,1,'2014-08-17',60),(1,1,'2014-08-18',60),(1,1,'2014-08-19',60),(1,1,'2014-08-20',60),(1,1,'2014-08-21',60),(1,1,'2014-08-22',60),(1,1,'2014-08-23',60),(1,1,'2014-08-24',60),(1,1,'2014-08-25',60),(1,1,'2014-08-26',60),(1,1,'2014-08-27',60),(1,1,'2014-08-28',60),(1,1,'2014-08-29',60);
/*!40000 ALTER TABLE `#__cp_tours_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_supplement`
--

DROP TABLE IF EXISTS `#__cp_tours_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_supplement` (
  `product_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_supplement`
--

LOCK TABLES `#__cp_tours_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_tours_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_supplement_tax`
--

DROP TABLE IF EXISTS `#__cp_tours_supplement_tax`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_supplement_tax` (
  `product_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`supplement_id`,`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_supplement_tax`
--

LOCK TABLES `#__cp_tours_supplement_tax` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_supplement_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_tours_supplement_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_taxes`
--

DROP TABLE IF EXISTS `#__cp_tours_taxes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_taxes` (
  `product_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_taxes`
--

LOCK TABLES `#__cp_tours_taxes` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_taxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_tours_taxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_tours_tourismtype`
--

DROP TABLE IF EXISTS `#__cp_tours_tourismtype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_tours_tourismtype` (
  `product_id` int(11) NOT NULL,
  `tourismtype_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`tourismtype_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_tours_tourismtype`
--

LOCK TABLES `#__cp_tours_tourismtype` WRITE;
/*!40000 ALTER TABLE `#__cp_tours_tourismtype` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_tours_tourismtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_category`
--

DROP TABLE IF EXISTS `#__cp_transfers_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_category`
--

LOCK TABLES `#__cp_transfers_category` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_category` DISABLE KEYS */;
INSERT INTO `#__cp_transfers_category` (`product_id`, `category_id`) VALUES (2,1),(2,2);
/*!40000 ALTER TABLE `#__cp_transfers_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_comments`
--

DROP TABLE IF EXISTS `#__cp_transfers_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_comments` (
  `comment_id` int(11) NOT NULL auto_increment,
  `order_id` tinyint(4) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `comment_rate` int(11) NOT NULL,
  `comment_text` varchar(400) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `modified` datetime default NULL,
  `modified_by` int(11) default NULL,
  `end_date` date NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_comments`
--

LOCK TABLES `#__cp_transfers_comments` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_transfers_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_files`
--

DROP TABLE IF EXISTS `#__cp_transfers_files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_files` (
  `product_id` int(11) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  UNIQUE KEY `transfers_files_unique` (`product_id`,`file_url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_files`
--

LOCK TABLES `#__cp_transfers_files` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_transfers_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_info`
--

DROP TABLE IF EXISTS `#__cp_transfers_info`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_info` (
  `product_id` int(11) NOT NULL auto_increment,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(10) default NULL,
  `product_desc` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `region_id` int(11) default NULL,
  `city_id` int(11) NOT NULL,
  `featured` tinyint(4) NOT NULL,
  `latitude` varchar(255) default NULL,
  `longitude` varchar(255) default NULL,
  `ordering` int(11) NOT NULL default '0',
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `access` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `tag_name1` varchar(255) default NULL,
  `tag_content1` mediumtext,
  `tag_name2` varchar(255) default NULL,
  `tag_content2` mediumtext,
  `tag_name3` varchar(255) default NULL,
  `tag_content3` mediumtext,
  `tag_name4` varchar(255) default NULL,
  `tag_content4` mediumtext,
  `tag_name5` varchar(255) default NULL,
  `tag_content5` mediumtext,
  `tag_name6` varchar(255) default NULL,
  `tag_content6` mediumtext,
  `product_url` mediumtext,
  `supplier_id` int(11) default NULL,
  `average_rating` tinyint(4) default '0',
  `additional_description` varchar(255) default NULL,
  `disclaimer` varchar(500) default NULL,
  PRIMARY KEY  (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Guarda los productos del Catálogo.';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_info`
--

LOCK TABLES `#__cp_transfers_info` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_info` DISABLE KEYS */;
INSERT INTO `#__cp_transfers_info` (`product_id`, `product_name`, `product_code`, `product_desc`, `country_id`, `region_id`, `city_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, `created`, `created_by`, `modified`, `modified_by`, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `average_rating`, `additional_description`, `disclaimer`) VALUES (2,'Traslado Hotel -  Aeropuerto y viceversa','369258147','sdf asdfasdfasd',47,0,2573,0,'4.6530799182740505','-74.091796875',0,'2013-03-18 00:00:00','0000-00-00 00:00:00','2013-03-18 22:25:08',62,'2013-03-18 22:25:08',62,0,1,'Descripción General','','Incluye','','No incluye','','Itinerario','','Pago y condiciones del servicio','','','','',2,0,NULL,'Tarifas incluyen impuestos');
/*!40000 ALTER TABLE `#__cp_transfers_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_param1`
--

DROP TABLE IF EXISTS `#__cp_transfers_param1`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_param1` (
  `product_id` int(11) NOT NULL,
  `param1_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param1_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_param1`
--

LOCK TABLES `#__cp_transfers_param1` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_param1` DISABLE KEYS */;
INSERT INTO `#__cp_transfers_param1` (`product_id`, `param1_id`) VALUES (2,2);
/*!40000 ALTER TABLE `#__cp_transfers_param1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_param2`
--

DROP TABLE IF EXISTS `#__cp_transfers_param2`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_param2` (
  `product_id` int(11) NOT NULL,
  `param2_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_param2`
--

LOCK TABLES `#__cp_transfers_param2` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_param2` DISABLE KEYS */;
INSERT INTO `#__cp_transfers_param2` (`product_id`, `param2_id`) VALUES (2,6);
/*!40000 ALTER TABLE `#__cp_transfers_param2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_param3`
--

DROP TABLE IF EXISTS `#__cp_transfers_param3`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_param3` (
  `product_id` int(11) NOT NULL,
  `param3_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`param3_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_param3`
--

LOCK TABLES `#__cp_transfers_param3` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_param3` DISABLE KEYS */;
INSERT INTO `#__cp_transfers_param3` (`product_id`, `param3_id`) VALUES (2,1);
/*!40000 ALTER TABLE `#__cp_transfers_param3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_rate`
--

DROP TABLE IF EXISTS `#__cp_transfers_rate`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_rate` (
  `rate_id` int(11) NOT NULL auto_increment,
  `season_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `basic_price` double default NULL,
  `previous_value` double default NULL,
  `currency_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY  (`rate_id`),
  KEY `transfers_rate_product_id` (`product_id`),
  KEY `transfers_rate_season_id` (`season_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_rate`
--

LOCK TABLES `#__cp_transfers_rate` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_rate` DISABLE KEYS */;
INSERT INTO `#__cp_transfers_rate` (`rate_id`, `season_id`, `product_id`, `basic_price`, `previous_value`, `currency_id`, `created`, `created_by`, `modified`, `modified_by`) VALUES (2,9,2,32000,0,0,'2013-03-18 22:25:50',62,'2013-03-18 22:25:50',62);
/*!40000 ALTER TABLE `#__cp_transfers_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_rate_price`
--

DROP TABLE IF EXISTS `#__cp_transfers_rate_price`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_rate_price` (
  `rate_id` int(11) NOT NULL,
  `param1` int(11) NOT NULL default '0',
  `param2` int(11) NOT NULL default '0',
  `param3` int(11) NOT NULL default '0',
  `price` double NOT NULL,
  UNIQUE KEY `transfers_rate_price_unique` (`rate_id`,`param1`,`param3`,`param2`),
  KEY `transfers_rate_id` (`rate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_rate_price`
--

LOCK TABLES `#__cp_transfers_rate_price` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_rate_price` DISABLE KEYS */;
INSERT INTO `#__cp_transfers_rate_price` (`rate_id`, `param1`, `param2`, `param3`, `price`) VALUES (2,2,6,1,32000);
/*!40000 ALTER TABLE `#__cp_transfers_rate_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_rate_supplement`
--

DROP TABLE IF EXISTS `#__cp_transfers_rate_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_rate_supplement` (
  `rate_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY  (`rate_id`,`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_rate_supplement`
--

LOCK TABLES `#__cp_transfers_rate_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_rate_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_transfers_rate_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_resume`
--

DROP TABLE IF EXISTS `#__cp_transfers_resume`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_resume` (
  `product_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `stock` int(11) NOT NULL,
  `adult_price` double NOT NULL,
  `currency_id` int(11) NOT NULL,
  `previous_price` double NOT NULL,
  UNIQUE KEY `transfers_resume_unique_index` (`product_id`,`date`),
  KEY `transfers_resume_date` (`date`),
  KEY `transfers_resume_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_resume`
--

LOCK TABLES `#__cp_transfers_resume` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_resume` DISABLE KEYS */;
INSERT INTO `#__cp_transfers_resume` (`product_id`, `date`, `stock`, `adult_price`, `currency_id`, `previous_price`) VALUES (2,'2013-03-22',0,32000,1,0),(2,'2013-03-23',0,32000,1,0),(2,'2013-03-24',0,32000,1,0),(2,'2013-03-29',0,32000,1,0),(2,'2013-03-30',0,32000,1,0),(2,'2013-03-31',0,32000,1,0),(2,'2013-04-05',0,32000,1,0),(2,'2013-04-06',0,32000,1,0),(2,'2013-04-07',0,32000,1,0),(2,'2013-04-12',0,32000,1,0),(2,'2013-04-13',0,32000,1,0),(2,'2013-04-14',0,32000,1,0),(2,'2013-04-19',0,32000,1,0),(2,'2013-04-20',0,32000,1,0),(2,'2013-04-21',0,32000,1,0),(2,'2013-04-26',0,32000,1,0),(2,'2013-04-27',0,32000,1,0),(2,'2013-04-28',0,32000,1,0),(2,'2013-05-03',0,32000,1,0),(2,'2013-05-04',0,32000,1,0),(2,'2013-05-05',0,32000,1,0),(2,'2013-05-10',0,32000,1,0),(2,'2013-05-11',0,32000,1,0),(2,'2013-05-12',0,32000,1,0),(2,'2013-05-17',0,32000,1,0),(2,'2013-05-18',0,32000,1,0),(2,'2013-05-19',0,32000,1,0),(2,'2013-05-24',0,32000,1,0),(2,'2013-05-25',0,32000,1,0),(2,'2013-05-26',0,32000,1,0),(2,'2013-05-31',0,32000,1,0),(2,'2013-06-01',0,32000,1,0),(2,'2013-06-02',0,32000,1,0),(2,'2013-06-07',0,32000,1,0),(2,'2013-06-08',0,32000,1,0),(2,'2013-06-09',0,32000,1,0),(2,'2013-06-14',0,32000,1,0),(2,'2013-06-15',0,32000,1,0),(2,'2013-06-16',0,32000,1,0),(2,'2013-06-21',0,32000,1,0),(2,'2013-06-22',0,32000,1,0),(2,'2013-06-23',0,32000,1,0),(2,'2013-06-28',0,32000,1,0),(2,'2013-06-29',0,32000,1,0),(2,'2013-06-30',0,32000,1,0),(2,'2013-07-05',0,32000,1,0),(2,'2013-07-06',0,32000,1,0),(2,'2013-07-07',0,32000,1,0),(2,'2013-07-12',0,32000,1,0),(2,'2013-07-13',0,32000,1,0),(2,'2013-07-14',0,32000,1,0),(2,'2013-07-19',0,32000,1,0),(2,'2013-07-20',0,32000,1,0),(2,'2013-07-21',0,32000,1,0),(2,'2013-07-26',0,32000,1,0),(2,'2013-07-27',0,32000,1,0),(2,'2013-07-28',0,32000,1,0),(2,'2013-08-02',0,32000,1,0),(2,'2013-08-03',0,32000,1,0),(2,'2013-08-04',0,32000,1,0),(2,'2013-08-09',0,32000,1,0),(2,'2013-08-10',0,32000,1,0),(2,'2013-08-11',0,32000,1,0),(2,'2013-08-16',0,32000,1,0),(2,'2013-08-17',0,32000,1,0),(2,'2013-08-18',0,32000,1,0),(2,'2013-08-23',0,32000,1,0),(2,'2013-08-24',0,32000,1,0),(2,'2013-08-25',0,32000,1,0),(2,'2013-08-30',0,32000,1,0),(2,'2013-08-31',0,32000,1,0),(2,'2013-09-01',0,32000,1,0),(2,'2013-09-06',0,32000,1,0),(2,'2013-09-07',0,32000,1,0),(2,'2013-09-08',0,32000,1,0),(2,'2013-09-13',0,32000,1,0),(2,'2013-09-14',0,32000,1,0),(2,'2013-09-15',0,32000,1,0),(2,'2013-09-20',0,32000,1,0),(2,'2013-09-21',0,32000,1,0),(2,'2013-09-22',0,32000,1,0),(2,'2013-09-27',0,32000,1,0),(2,'2013-09-28',0,32000,1,0),(2,'2013-09-29',0,32000,1,0),(2,'2013-10-04',0,32000,1,0),(2,'2013-10-05',0,32000,1,0),(2,'2013-10-06',0,32000,1,0),(2,'2013-10-11',0,32000,1,0),(2,'2013-10-12',0,32000,1,0),(2,'2013-10-13',0,32000,1,0),(2,'2013-10-18',0,32000,1,0),(2,'2013-10-19',0,32000,1,0),(2,'2013-10-20',0,32000,1,0),(2,'2013-10-25',0,32000,1,0),(2,'2013-10-26',0,32000,1,0),(2,'2013-10-27',0,32000,1,0),(2,'2013-11-01',0,32000,1,0),(2,'2013-11-02',0,32000,1,0),(2,'2013-11-03',0,32000,1,0),(2,'2013-11-08',0,32000,1,0),(2,'2013-11-09',0,32000,1,0),(2,'2013-11-10',0,32000,1,0),(2,'2013-11-15',0,32000,1,0),(2,'2013-11-16',0,32000,1,0),(2,'2013-11-17',0,32000,1,0),(2,'2013-11-22',0,32000,1,0),(2,'2013-11-23',0,32000,1,0),(2,'2013-11-24',0,32000,1,0),(2,'2013-11-29',0,32000,1,0),(2,'2013-11-30',0,32000,1,0),(2,'2013-12-01',0,32000,1,0),(2,'2013-12-06',0,32000,1,0),(2,'2013-12-07',0,32000,1,0),(2,'2013-12-08',0,32000,1,0),(2,'2013-12-13',0,32000,1,0),(2,'2013-12-14',0,32000,1,0),(2,'2014-01-24',0,32000,1,0),(2,'2014-01-25',0,32000,1,0),(2,'2014-01-26',0,32000,1,0),(2,'2014-01-31',0,32000,1,0),(2,'2014-02-01',0,32000,1,0),(2,'2014-02-02',0,32000,1,0),(2,'2014-02-07',0,32000,1,0),(2,'2014-02-08',0,32000,1,0),(2,'2014-02-09',0,32000,1,0),(2,'2014-02-14',0,32000,1,0),(2,'2014-02-15',0,32000,1,0),(2,'2014-02-16',0,32000,1,0),(2,'2014-02-21',0,32000,1,0);
/*!40000 ALTER TABLE `#__cp_transfers_resume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_stock`
--

DROP TABLE IF EXISTS `#__cp_transfers_stock`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_stock` (
  `product_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `day` date NOT NULL,
  `quantity` int(11) NOT NULL,
  UNIQUE KEY `transfers_stock_unique_index` (`product_id`,`param_id`,`day`),
  KEY `transfers_stock_product_id_index` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_stock`
--

LOCK TABLES `#__cp_transfers_stock` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_transfers_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_supplement`
--

DROP TABLE IF EXISTS `#__cp_transfers_supplement`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_supplement` (
  `product_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_supplement`
--

LOCK TABLES `#__cp_transfers_supplement` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_supplement` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_transfers_supplement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_supplement_tax`
--

DROP TABLE IF EXISTS `#__cp_transfers_supplement_tax`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_supplement_tax` (
  `product_id` int(11) NOT NULL,
  `supplement_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`supplement_id`,`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_supplement_tax`
--

LOCK TABLES `#__cp_transfers_supplement_tax` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_supplement_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_transfers_supplement_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `#__cp_transfers_taxes`
--

DROP TABLE IF EXISTS `#__cp_transfers_taxes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `#__cp_transfers_taxes` (
  `product_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `#__cp_transfers_taxes`
--

LOCK TABLES `#__cp_transfers_taxes` WRITE;
/*!40000 ALTER TABLE `#__cp_transfers_taxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `#__cp_transfers_taxes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-09-05 13:28:46
