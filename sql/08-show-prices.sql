CREATE TABLE `ticket_prices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `show_id` int(11) DEFAULT NULL,
  `code` varchar(15) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `price` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_show` (`show_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;