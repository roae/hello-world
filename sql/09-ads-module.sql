CREATE TABLE `ads_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `trash` int(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `ads` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
	`link` varchar(255) DEFAULT NULL,
	`type` varchar(15) DEFAULT NULL,
	`ads_group_id` int(11) DEFAULT NULL,
	`status` int(1) DEFAULT '1',
	`trash` int(1) DEFAULT '0',
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `fk_ads-group` (`ads_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;