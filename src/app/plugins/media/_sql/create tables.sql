CREATE TABLE `media_mediums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `foreign_key` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `extension` varchar(32) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `description` text,
  `mime` varchar(255) NOT NULL,
  `order` int(10) NOT NULL,
  `size` int(10) NOT NULL,
  `created_by` int(10) NOT NULL,
  `modified_by` int(10) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;