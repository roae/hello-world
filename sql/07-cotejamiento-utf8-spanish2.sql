ALTER TABLE `i18n_fields` COLLATE = utf8_spanish2_ci;
ALTER TABLE `i18n_fields` CHANGE `content` `content` MEDIUMTEXT  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL;

ALTER TABLE `i18n_key_meanings` COLLATE = utf8_spanish2_ci;
ALTER TABLE `i18n_key_meanings` CHANGE `content` `content` TEXT  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL;

ALTER TABLE `articles` COLLATE = utf8_spanish2_ci;
ALTER TABLE `articles` CHANGE `autor` `autor` VARCHAR(50)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT '';

ALTER TABLE `cities` COLLATE = utf8_spanish2_ci;
ALTER TABLE `cities` CHANGE `name` `name` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;

ALTER TABLE `locations` COLLATE = utf8_spanish2_ci;
ALTER TABLE `locations` CHANGE `name` `name` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;
ALTER TABLE `locations` CHANGE `state` `state` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;
ALTER TABLE `locations` CHANGE `street` `street` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;
ALTER TABLE `locations` CHANGE `neighborhood` `neighborhood` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;

ALTER TABLE `movies` COLLATE = utf8_spanish2_ci;
ALTER TABLE `movies` CHANGE `title` `title` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;
ALTER TABLE `movies` CHANGE `synopsis` `synopsis` TEXT  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL;
ALTER TABLE `movies` CHANGE `genre` `genre` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;
ALTER TABLE `movies` CHANGE `actors` `actors` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;
ALTER TABLE `movies` CHANGE `director` `director` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;
ALTER TABLE `movies` CHANGE `language` `language` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;
ALTER TABLE `movies` CHANGE `nationality` `nationality` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;
ALTER TABLE `movies` CHANGE `music_director` `music_director` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;

ALTER TABLE `movies` COLLATE = utf8_spanish2_ci;
ALTER TABLE `services` CHANGE `name` `name` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;

ALTER TABLE `terms` COLLATE = utf8_spanish2_ci;
ALTER TABLE `terms` CHANGE `nombre` `nombre` VARCHAR(50)  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL  DEFAULT NULL;
ALTER TABLE `terms` CHANGE `descripcion` `descripcion` TEXT  CHARACTER SET utf8  COLLATE utf8_spanish2_ci  NULL;