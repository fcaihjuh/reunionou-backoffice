DROP TABLE IF EXISTS `user` ;
CREATE TABLE `user` (`id` BIGINT AUTO_INCREMENT NOT NULL,
`mail` VARCHAR(256) NOT NULL,
`fullname` VARCHAR(512) NOT NULL,
`password` CHAR(60) NOT NULL,
`token` VARCHAR(128),
PRIMARY KEY (`id`)) ENGINE=InnoDB;

INSERT INTO `user` (`id`, `mail`, `fullname`, `password`) VALUES
(1, 'a@a.com', 'Noe A', 'test'),
(2, 'b@b.com', 'Baptiste C', 'test'),
(3, 'c@c.com', 'Charles T', 'test'),
(4, 'd@d.com', 'Melissa P', 'test'),
(5, 'e@e.com', 'Sophie B', 'test');



DROP TABLE IF EXISTS `event` ;
CREATE TABLE `event` (`id` BIGINT AUTO_INCREMENT NOT NULL,
`title` VARCHAR(512) NOT NULL,
`description` VARCHAR(512) NOT NULL,
`date` DATETIME NOT NULL,
`place` VARCHAR(512) NOT NULL,
`token` VARCHAR(128),
`id_user` VARCHAR(128) NOT NULL,
PRIMARY KEY (`id`)) ENGINE=InnoDB;

INSERT INTO `event` (`id`, `title`, `description`, `date`, `place`, `id_user`) VALUES
(1, 'Anniversaire', 'Invitation à la fête anniversaire', '2022-03-25 21:30:59', 'Place Stanislas', 3),
(2, 'Mariage', 'Invitation au mariage', '2022-04-11 20:30:00', 'Wedding Queen', 4),
(3, 'Retrouvaille', 'Repas entre anciens camarades de LP CIASIE', '2022-09-05 00:00:00', 'IUT Charlemagne', 1),
(4, 'Naissance', 'Assister à notre fête pour la naissance de Charlotte', '2023-08-06 00:00:00', 'Champs Elysees', 2),
(5, 'Rencontre', 'Y a-t-il des fans de series?', '2020-07-09 16:06:59', 'Place de la République', 5);



DROP TABLE IF EXISTS `comment` ;
CREATE TABLE `comment` (`id` BIGINT AUTO_INCREMENT NOT NULL,
`id_event` BIGINT NOT NULL,
`id_user` VARCHAR(128) NOT NULL,
`content` VARCHAR(512) NOT NULL,
PRIMARY KEY (`id`)) ENGINE=InnoDB;

INSERT INTO `comment` (`id`, `id_event`, `id_user`, `content`) VALUES
(1, 3, 1, 'Cool'),
(2, 4, 5, 'Congrats'),
(3, 5, 2, 'OUIIII'),
(4, 1, 4, 'Joyeux anniversaire'),
(5, 2, 3, 'Felicitation');


DROP TABLE IF EXISTS `admin` ;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'admin@reunionou.com', '$2y$10$PmKavnVgJ6N5FCSsXscPyOsp6DHEAtMU/0pbjRT8kYILfA4oXGcDq');
