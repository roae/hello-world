# ************************************************************
# Sequel Pro SQL dump
# Versión 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.9)
# Base de datos: citicinemas
# Tiempo de Generación: 2015-01-09 04:01:16 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Volcado de tabla acos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `acos`;

CREATE TABLE `acos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT '',
  `foreign_key` int(10) unsigned DEFAULT NULL,
  `alias` varchar(255) DEFAULT '',
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent_id`),
  KEY `model` (`model`),
  KEY `fk_model` (`foreign_key`),
  KEY `left_id` (`lft`),
  KEY `right_id` (`rght`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `acos` WRITE;
/*!40000 ALTER TABLE `acos` DISABLE KEYS */;

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`)
VALUES
	(1,0,'Group',1,'Group.1',1,2),
	(2,0,'Group',2,'Group.2',3,4),
	(3,0,'Group',3,'Group.3',5,6),
	(4,0,'Group',4,'Group.4',7,8),
	(5,NULL,NULL,NULL,'controllers',9,212),
	(6,5,NULL,NULL,'Articles',10,33),
	(7,6,NULL,NULL,'admin_index',11,12),
	(8,6,NULL,NULL,'admin_add',13,14),
	(9,6,NULL,NULL,'admin_edit',15,16),
	(10,6,NULL,NULL,'admin_delete',17,18),
	(11,6,NULL,NULL,'admin_status',19,20),
	(12,6,NULL,NULL,'index',21,22),
	(13,6,NULL,NULL,'view',23,24),
	(14,6,NULL,NULL,'get',25,26),
	(15,6,NULL,NULL,'admin_restore_images',27,28),
	(16,6,NULL,NULL,'rating',29,30),
	(17,6,NULL,NULL,'recomended',31,32),
	(18,5,NULL,NULL,'Comments',34,51),
	(19,18,NULL,NULL,'admin_index',35,36),
	(20,18,NULL,NULL,'admin_add',37,38),
	(21,18,NULL,NULL,'admin_edit',39,40),
	(22,18,NULL,NULL,'admin_delete',41,42),
	(23,18,NULL,NULL,'admin_status',43,44),
	(24,18,NULL,NULL,'add',45,46),
	(25,18,NULL,NULL,'get',47,48),
	(26,18,NULL,NULL,'admin_restore',49,50),
	(27,5,NULL,NULL,'Contacts',52,61),
	(28,27,NULL,NULL,'admin_index',53,54),
	(29,27,NULL,NULL,'admin_delete',55,56),
	(30,27,NULL,NULL,'add',57,58),
	(31,27,NULL,NULL,'captcha',59,60),
	(32,5,NULL,NULL,'Groups',62,73),
	(33,32,NULL,NULL,'admin_index',63,64),
	(34,32,NULL,NULL,'admin_add',65,66),
	(35,32,NULL,NULL,'admin_edit',67,68),
	(36,32,NULL,NULL,'admin_delete',69,70),
	(37,32,NULL,NULL,'index',71,72),
	(38,5,NULL,NULL,'Pages',74,81),
	(39,38,NULL,NULL,'display',75,76),
	(40,38,NULL,NULL,'admin_display',77,78),
	(41,38,NULL,NULL,'dashboard_display',79,80),
	(42,5,NULL,NULL,'Terms',82,95),
	(43,42,NULL,NULL,'admin_index',83,84),
	(44,42,NULL,NULL,'admin_add',85,86),
	(45,42,NULL,NULL,'admin_edit',87,88),
	(46,42,NULL,NULL,'admin_delete',89,90),
	(47,42,NULL,NULL,'get',91,92),
	(48,42,NULL,NULL,'autocomplete',93,94),
	(49,5,NULL,NULL,'Users',96,117),
	(50,49,NULL,NULL,'admin_login',97,98),
	(51,49,NULL,NULL,'admin_logout',99,100),
	(52,49,NULL,NULL,'admin_index',101,102),
	(53,49,NULL,NULL,'admin_add',103,104),
	(54,49,NULL,NULL,'admin_edit',105,106),
	(55,49,NULL,NULL,'admin_password',107,108),
	(56,49,NULL,NULL,'admin_status',109,110),
	(57,49,NULL,NULL,'admin_dashboard',111,112),
	(58,49,NULL,NULL,'login',113,114),
	(59,49,NULL,NULL,'logout',115,116),
	(60,5,NULL,NULL,'Acl',118,159),
	(61,60,NULL,NULL,'Acos',119,126),
	(62,61,NULL,NULL,'admin_index',120,121),
	(63,61,NULL,NULL,'admin_empty_acos',122,123),
	(64,61,NULL,NULL,'admin_build_acl',124,125),
	(65,60,NULL,NULL,'Aros',127,158),
	(66,65,NULL,NULL,'admin_index',128,129),
	(67,65,NULL,NULL,'admin_check',130,131),
	(68,65,NULL,NULL,'admin_users',132,133),
	(69,65,NULL,NULL,'admin_update_user_role',134,135),
	(70,65,NULL,NULL,'admin_role_permissions',136,137),
	(71,65,NULL,NULL,'admin_controller_role_permissions',138,139),
	(72,65,NULL,NULL,'admin_user_permissions',140,141),
	(73,65,NULL,NULL,'admin_controller_user_permissions',142,143),
	(74,65,NULL,NULL,'admin_empty_permissions',144,145),
	(75,65,NULL,NULL,'admin_grant_all_controllers',146,147),
	(76,65,NULL,NULL,'admin_deny_all_controllers',148,149),
	(77,65,NULL,NULL,'admin_grant_role_permission',150,151),
	(78,65,NULL,NULL,'admin_deny_role_permission',152,153),
	(79,65,NULL,NULL,'admin_grant_user_permission',154,155),
	(80,65,NULL,NULL,'admin_deny_user_permission',156,157),
	(81,5,NULL,NULL,'Debug Kit',160,167),
	(82,81,NULL,NULL,'ToolbarAccess',161,166),
	(83,82,NULL,NULL,'history_state',162,163),
	(84,82,NULL,NULL,'sql_explain',164,165),
	(85,5,NULL,NULL,'I18n',168,189),
	(86,85,NULL,NULL,'Interpreter',169,174),
	(87,86,NULL,NULL,'start',170,171),
	(88,86,NULL,NULL,'end',172,173),
	(89,85,NULL,NULL,'Js',175,180),
	(90,89,NULL,NULL,'display',176,177),
	(91,89,NULL,NULL,'content',178,179),
	(92,85,NULL,NULL,'L10n',181,188),
	(93,92,NULL,NULL,'admin_edit',182,183),
	(94,92,NULL,NULL,'admin_cancel',184,185),
	(95,92,NULL,NULL,'interpret',186,187),
	(96,5,NULL,NULL,'Media',190,211),
	(97,96,NULL,NULL,'Files',191,210),
	(98,97,NULL,NULL,'isAuthorizer',192,193),
	(99,97,NULL,NULL,'add',194,195),
	(100,97,NULL,NULL,'admin_add_files',196,197),
	(101,97,NULL,NULL,'admin_add_folder',198,199),
	(102,97,NULL,NULL,'delete',200,201),
	(103,97,NULL,NULL,'view',202,203),
	(104,97,NULL,NULL,'admin_delete_file',204,205),
	(105,97,NULL,NULL,'admin_index',206,207),
	(106,97,NULL,NULL,'admin_tiny_images',208,209),
	(107,0,'User',1,'User.1',213,214),
	(108,0,'User',2,'User.2',215,216),
	(109,0,'User',3,'User.3',217,218);

/*!40000 ALTER TABLE `acos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla aros
# ------------------------------------------------------------

DROP TABLE IF EXISTS `aros`;

CREATE TABLE `aros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT '',
  `foreign_key` int(10) unsigned DEFAULT NULL,
  `alias` varchar(255) DEFAULT '',
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent_id`),
  KEY `fk_model` (`foreign_key`),
  KEY `left_id` (`lft`),
  KEY `right_id` (`rght`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `aros` WRITE;
/*!40000 ALTER TABLE `aros` DISABLE KEYS */;

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`)
VALUES
	(1,0,'Group',1,'Group.1',1,4),
	(2,0,'Group',2,'Group.2',5,10),
	(3,0,'Group',3,'Group.3',11,12),
	(4,0,'Group',4,'Group.4',13,14),
	(5,1,'User',1,'User.1',2,3),
	(6,2,'User',2,'User.2',6,7),
	(7,2,'User',3,'User.3',8,9);

/*!40000 ALTER TABLE `aros` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla aros_acos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `aros_acos`;

CREATE TABLE `aros_acos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) unsigned NOT NULL,
  `aco_id` int(10) unsigned NOT NULL,
  `_create` char(2) NOT NULL DEFAULT '0',
  `_read` char(2) NOT NULL DEFAULT '0',
  `_update` char(2) NOT NULL DEFAULT '0',
  `_delete` char(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_aro` (`aro_id`),
  KEY `fk_aco` (`aco_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `aros_acos` WRITE;
/*!40000 ALTER TABLE `aros_acos` DISABLE KEYS */;

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`)
VALUES
	(1,1,1,'-1','-1','-1','-1'),
	(2,2,2,'-1','-1','-1','-1'),
	(3,3,3,'-1','-1','-1','-1'),
	(4,4,4,'-1','-1','-1','-1'),
	(5,5,107,'1','1','1','1'),
	(6,1,5,'1','1','1','1'),
	(7,4,12,'1','1','1','1'),
	(8,4,13,'1','1','1','1'),
	(9,4,14,'1','1','1','1'),
	(10,4,16,'1','1','1','1'),
	(11,4,17,'1','1','1','1'),
	(12,2,7,'1','1','1','1'),
	(13,2,8,'1','1','1','1'),
	(14,2,9,'1','1','1','1'),
	(15,2,10,'1','1','1','1'),
	(16,2,11,'1','1','1','1'),
	(17,2,12,'1','1','1','1'),
	(18,2,13,'1','1','1','1'),
	(19,2,14,'1','1','1','1'),
	(20,2,15,'1','1','1','1'),
	(21,2,16,'1','1','1','1'),
	(22,2,17,'1','1','1','1'),
	(23,3,12,'1','1','1','1'),
	(24,3,13,'1','1','1','1'),
	(25,3,14,'1','1','1','1'),
	(26,3,16,'1','1','1','1'),
	(27,3,17,'1','1','1','1'),
	(28,2,19,'1','1','1','1'),
	(29,2,20,'1','1','1','1'),
	(30,2,21,'1','1','1','1'),
	(31,2,22,'1','1','1','1'),
	(32,2,24,'1','1','1','1'),
	(33,2,23,'1','1','1','1'),
	(34,2,25,'1','1','1','1'),
	(35,2,26,'1','1','1','1'),
	(36,3,24,'1','1','1','1'),
	(37,4,24,'1','1','1','1'),
	(38,4,25,'1','1','1','1'),
	(39,3,25,'1','1','1','1'),
	(40,4,30,'1','1','1','1'),
	(41,4,31,'1','1','1','1'),
	(42,3,30,'1','1','1','1'),
	(43,3,31,'1','1','1','1'),
	(44,2,31,'1','1','1','1'),
	(45,2,30,'1','1','1','1'),
	(46,2,29,'1','1','1','1'),
	(47,2,28,'1','1','1','1'),
	(48,4,39,'1','1','1','1'),
	(49,4,40,'1','1','1','1'),
	(50,4,41,'1','1','1','1'),
	(51,3,41,'1','1','1','1'),
	(52,3,40,'1','1','1','1'),
	(53,3,39,'1','1','1','1'),
	(54,2,39,'1','1','1','1'),
	(55,2,40,'1','1','1','1'),
	(56,2,41,'1','1','1','1'),
	(57,2,43,'1','1','1','1'),
	(58,2,44,'1','1','1','1'),
	(59,2,45,'1','1','1','1'),
	(60,2,46,'1','1','1','1'),
	(61,2,47,'1','1','1','1'),
	(62,2,48,'1','1','1','1'),
	(63,3,47,'1','1','1','1'),
	(64,4,47,'1','1','1','1'),
	(65,2,50,'1','1','1','1'),
	(66,3,50,'1','1','1','1'),
	(67,4,50,'1','1','1','1'),
	(68,2,51,'1','1','1','1'),
	(69,2,52,'1','1','1','1'),
	(70,2,53,'1','1','1','1'),
	(71,2,54,'1','1','1','1'),
	(72,2,55,'1','1','1','1'),
	(73,2,56,'1','1','1','1'),
	(74,2,58,'1','1','1','1'),
	(75,3,58,'1','1','1','1'),
	(76,4,58,'1','1','1','1'),
	(77,4,59,'1','1','1','1'),
	(78,3,59,'1','1','1','1'),
	(79,2,59,'1','1','1','1'),
	(80,2,87,'1','1','1','1'),
	(81,2,88,'1','1','1','1'),
	(82,2,93,'1','1','1','1'),
	(83,2,94,'1','1','1','1'),
	(84,2,95,'1','1','1','1'),
	(85,3,95,'1','1','1','1'),
	(86,4,95,'1','1','1','1'),
	(87,3,87,'1','1','1','1'),
	(88,3,88,'1','1','1','1'),
	(89,4,87,'1','1','1','1'),
	(90,4,88,'1','1','1','1'),
	(91,6,108,'1','1','1','1'),
	(92,7,109,'1','1','1','1');

/*!40000 ALTER TABLE `aros_acos` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla article_relationships
# ------------------------------------------------------------

DROP TABLE IF EXISTS `article_relationships`;

CREATE TABLE `article_relationships` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `score` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificador` (`article_id`,`related_id`),
  KEY `article` (`article_id`),
  KEY `related` (`related_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Volcado de tabla articles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `autor` varchar(50) DEFAULT '',
  `views` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla articles_terms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articles_terms`;

CREATE TABLE `articles_terms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `term_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article` (`article_id`),
  KEY `term` (`term_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(30) NOT NULL,
  `foreign_id` int(11) unsigned NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `ip` varchar(16) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `model_name` (`class`),
  KEY `model_id` (`foreign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text,
  `url` varchar(300) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`id`, `name`, `parent_id`, `created`, `modified`)
VALUES
	(1,'System',NULL,'2015-01-08 15:48:24','2015-01-08 15:48:24'),
	(2,'Administration',1,'2015-01-08 15:48:49','2015-01-08 15:48:49'),
	(3,'Registered',2,'2015-01-08 15:49:19','2015-01-08 15:49:19'),
	(4,'Anonymous',2,'2015-01-08 15:49:39','2015-01-08 15:49:39');

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla i18n_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `i18n_fields`;

CREATE TABLE `i18n_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `locale` varchar(6) NOT NULL,
  `model` varchar(50) NOT NULL,
  `foreign_key` int(10) NOT NULL,
  `field` varchar(50) NOT NULL,
  `content` mediumtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificador` (`locale`,`model`,`foreign_key`,`field`),
  KEY `locale` (`locale`),
  KEY `model` (`model`),
  KEY `row_id` (`foreign_key`),
  KEY `field` (`field`),
  FULLTEXT KEY `ft_content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla i18n_key_meanings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `i18n_key_meanings`;

CREATE TABLE `i18n_key_meanings` (
  `lang_id` char(7) NOT NULL DEFAULT '',
  `key_id` int(11) NOT NULL,
  `content` text,
  PRIMARY KEY (`lang_id`,`key_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla i18n_keys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `i18n_keys`;

CREATE TABLE `i18n_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(60) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla i18n_langs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `i18n_langs`;

CREATE TABLE `i18n_langs` (
  `id` char(7) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla i18n_urls
# ------------------------------------------------------------

DROP TABLE IF EXISTS `i18n_urls`;

CREATE TABLE `i18n_urls` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(100) DEFAULT NULL,
  `ruta` varchar(11) DEFAULT NULL,
  `prefix` varchar(20) DEFAULT NULL,
  `controller` varchar(30) DEFAULT NULL,
  `action` varchar(30) DEFAULT NULL,
  `plugin` varchar(30) DEFAULT NULL,
  `lang_id` varchar(7) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pk_langs` (`lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla media_mediums
# ------------------------------------------------------------

DROP TABLE IF EXISTS `media_mediums`;

CREATE TABLE `media_mediums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `foreign_key` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `extension` varchar(32) NOT NULL,
  `mime` varchar(255) NOT NULL,
  `order` int(10) NOT NULL,
  `size` int(10) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_by` int(10) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `model_name` (`model`),
  KEY `alias_name` (`alias`),
  KEY `model_id` (`foreign_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla media_uploads
# ------------------------------------------------------------

DROP TABLE IF EXISTS `media_uploads`;

CREATE TABLE `media_uploads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `extension` varchar(4) DEFAULT NULL,
  `mime` varchar(20) DEFAULT '',
  `size` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `path` varchar(1000) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_parent` (`parent_id`),
  KEY `fk_lft` (`lft`),
  KEY `fk_rght` (`rght`),
  KEY `fk_created_by` (`created_by`),
  KEY `fk_modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla terms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `terms`;

CREATE TABLE `terms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `slug` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `class` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent_id`),
  KEY `left` (`lft`),
  KEY `right` (`rght`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Volcado de tabla users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `paterno` varchar(45) DEFAULT NULL,
  `materno` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `confirmed` datetime DEFAULT NULL,
  `signed_in` datetime DEFAULT NULL,
  `sign_in_count` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_grupo` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `group_id`, `username`, `password`, `nombre`, `paterno`, `materno`, `status`, `email`, `confirmed`, `signed_in`, `sign_in_count`, `created`, `modified`)
VALUES
	(1,1,'_system','148c8a0f03ae2d325126c928a0823056a17415cd','Efrain','Rochin','Aramburo',1,'erochin@h1webstudio.com',NULL,NULL,NULL,'2015-01-08 16:20:56','2015-01-08 16:20:56'),
	(2,2,'admin','5ab73158962262f693777163ef798975bc3a15a5','Admin','','',1,'admin@citicinemas.com',NULL,NULL,NULL,'2015-01-08 16:34:41','2015-01-08 16:34:41'),
	(3,2,'anony','5ab73158962262f693777163ef798975bc3a15a5','anony','','',1,'anony@citicinemas.com',NULL,NULL,NULL,'2015-01-08 16:35:14','2015-01-08 16:35:14');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
