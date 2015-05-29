CREATE TABLE `buys` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`movie_id` int(11) DEFAULT NULL,
	`location_id` int(11) DEFAULT NULL,
	`projection_id` int(11) DEFAULT NULL,
	`schedule` datetime DEFAULT NULL,
	`screen_name` varchar(20) DEFAULT NULL,
	`room_type` varchar(20) DEFAULT NULL,
	`seat_alloctype` int(11) DEFAULT NULL,
	`trans_id_temp` varchar(20) DEFAULT '-',
	`trans_number` varchar(20) DEFAULT '-',
	`confirmation_number` varchar(20) DEFAULT '-',
	`creater` int(11) DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `fk_movie` (`movie_id`),
	KEY `fk_location` (`location_id`),
	KEY `fk_projection` (`projection_id`),
	KEY `fk_creater` (`creater`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `buy_tickets` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`buy_id` int(11) DEFAULT NULL,
	`code` varchar(10) DEFAULT NULL,
	`description` varchar(20) DEFAULT NULL,
	`price` double DEFAULT NULL,
	`qty` int(2) DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `fk_buy` (`buy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `buy_seats` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`buy_id` int(11) DEFAULT NULL,
	`column` varchar(2) DEFAULT NULL,
	`row` varchar(2) DEFAULT NULL,
	`row_physical` varchar(2) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;