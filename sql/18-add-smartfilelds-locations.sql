ALTER TABLE `locations` ADD `smart_serialpos` VARCHAR(250)  NULL  DEFAULT NULL  AFTER `description`;
ALTER TABLE `locations` ADD `smart_user` VARCHAR(250)  NULL  DEFAULT NULL  AFTER `smart_serialpos`;
ALTER TABLE `locations` ADD `smart_passwd` VARCHAR(250)  NULL  DEFAULT NULL  AFTER `smart_user`;

ALTER TABLE `locations` ADD `smart_lastlogin` VARCHAR(20)  NULL  DEFAULT ''  AFTER `smart_passwd`;
ALTER TABLE `locations` ADD `smart_lastserverkey` INT  NULL  DEFAULT NULL  AFTER `smart_lastlogin`;
ALTER TABLE `locations` CHANGE `smart_lastserverkey` `smart_lastserverkey` VARCHAR(255)  NULL  DEFAULT NULL;
ALTER TABLE `locations` ADD `smart_last_stan` INT  NULL  AFTER `smart_lastserverkey`;
ALTER TABLE `locations` ADD `smart_current_stan` INT  NULL  DEFAULT NULL  AFTER `smart_last_stan`;
