DROP TABLE IF EXISTS `user` ;
CREATE TABLE `user` (`id` VARCHAR(128) NOT NULL,
`mail` VARCHAR(256) NOT NULL,
`fullname` VARCHAR(512) NOT NULL,
`username` VARCHAR(256) NOT NULL,
`password` CHAR(60) NOT NULL,
`token` VARCHAR(128),
PRIMARY KEY (`id`)) ENGINE=InnoDB;

DROP TABLE IF EXISTS `event` ;
CREATE TABLE `event` (`id` BIGINT AUTO_INCREMENT NOT NULL,
`title` VARCHAR(512) NOT NULL,
`description` VARCHAR(512) NOT NULL,
`date` DATETIME NOT NULL,
`place` VARCHAR(512) NOT NULL,
`token` VARCHAR(128),
`id_user` VARCHAR(128) NOT NULL,
PRIMARY KEY (`id`)) ENGINE=InnoDB;

DROP TABLE IF EXISTS `comment` ;
CREATE TABLE `comment` (`id` BIGINT AUTO_INCREMENT NOT NULL,
`id_event` BIGINT NOT NULL,
`id_user` VARCHAR(128) NOT NULL,
`content` VARCHAR(512) NOT NULL,
PRIMARY KEY (`id`)) ENGINE=InnoDB;
