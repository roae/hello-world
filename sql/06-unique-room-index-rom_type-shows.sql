ALTER TABLE `rooms` ADD INDEX `unique_room` (`description`, `location_id`);
ALTER TABLE `shows` ADD `room_type` VARCHAR(200)  NULL  DEFAULT NULL  AFTER `screen_name`;

