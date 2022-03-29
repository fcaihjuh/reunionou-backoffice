DROP TABLE IF EXISTS `user` ;
CREATE TABLE `user` (`id` BIGINT AUTO_INCREMENT NOT NULL,
`mail` VARCHAR(256) NOT NULL,
`fullname` VARCHAR(512) NOT NULL,
`password` CHAR(60) NOT NULL,
PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO user VALUES (1, 'alexwally@gmail.com', 'Alex Wally', 'alwa', '345FCX'),
 (2, 'sophiegerr', 'Sophie Gerard', '56728FBe3'),
INSERT INTO user VALUES (2, 'sandrinedup@gmail.com', '
Sandrine Dupont', 'JH84flA');
INSERT INTO user VALUES (3, 'leoyoung@hotmail.com', 'Leo Young', 'QLek52f');

DROP TABLE IF EXISTS `event` ;
CREATE TABLE `event` (`id` BIGINT AUTO_INCREMENT NOT NULL,
`title` VARCHAR(512) NOT NULL,
`description` VARCHAR(512) NOT NULL,
`date` DATE NOT NULL,
`time` TIME NOT NULL,
`place` VARCHAR(512) NOT NULL,
`id_user` BIGINT NOT NULL,
PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO event VALUES (1, 'Anniv de Marius', 'Thème blanc/violet', '2022-04-19', 18:30:00, '11 Place de la République 75003 Paris', 1);
INSERT INTO event VALUES (2, 'Conference de stage', 'Venez nombreux !', '2022-06-09', 11:00:00, '160 rue du Temple 75003 Paris', 3);

DROP TABLE IF EXISTS `comment` ;
CREATE TABLE `comment` (`id` BIGINT AUTO_INCREMENT NOT NULL,
`id_event` BIGINT NOT NULL,
`id_user` BIGINT NOT NULL,
`content` VARCHAR(512) NOT NULL,
PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO comment VALUES (1, 1, 1, 'Je serai en retard de 40 minutes');
INSERT INTO comment VALUES (2, 2, 1, 'Je serai en voyage d"affaire ce jour là malheureusement');