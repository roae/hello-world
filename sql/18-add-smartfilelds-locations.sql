ALTER TABLE `locations` ADD `smart_serialpos` VARCHAR(250)  NULL  DEFAULT NULL  AFTER `description`;
ALTER TABLE `locations` ADD `smart_user` VARCHAR(250)  NULL  DEFAULT NULL  AFTER `smart_serialpos`;
ALTER TABLE `locations` ADD `smart_passwd` VARCHAR(250)  NULL  DEFAULT NULL  AFTER `smart_user`;
