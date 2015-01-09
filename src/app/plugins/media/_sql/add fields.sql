# Solo correr estos querys cuando se este pasando a la versión nueva

ALTER TABLE `media_mediums` ADD `alt` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `extension`;

ALTER TABLE `media_mediums` ADD `description` TEXT  NULL  AFTER `alt`;
