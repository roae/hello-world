ALTER TABLE `locations` ADD `manager_name` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `venta_online`;
ALTER TABLE `locations` ADD `manager_email` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `manager_name`;
