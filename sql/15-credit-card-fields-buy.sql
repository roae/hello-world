ALTER TABLE `buys` ADD `payment_method` VARCHAR(10)  NULL  DEFAULT NULL  AFTER `confirmation_number`;
ALTER TABLE `buys` ADD `aut_code` VARCHAR(10)  NULL  DEFAULT NULL  AFTER `payment_method`;
ALTER TABLE `buys` ADD `cc_ending` VARCHAR(4)  NULL  DEFAULT NULL  AFTER `aut_code`;
ALTER TABLE `buys` ADD `cc_type` VARCHAR(20) NULL  DEFAULT NULL  AFTER `cc_ending`;
ALTER TABLE `buys` CHANGE `creater` `buyer` INT(11)  NULL  DEFAULT NULL;
