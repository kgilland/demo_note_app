delimiter $$

CREATE DATABASE `c1` /*!40100 DEFAULT CHARACTER SET utf8 */$$

delimiter $$

CREATE TABLE `c1`.`user` (
  `id` bigint(64) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `c1`.`note` (
  `id` bigint(64) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `content` varchar(512) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `note_type_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_user_id_note` (`user_id`),
  CONSTRAINT `fk_user_id_note` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$

delimiter $$

CREATE TABLE `c1`.`note_type` (
  `id` bigint(64) NOT NULL AUTO_INCREMENT,
  `label` varchar(45) NOT NULL,
  `color` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `color_UNIQUE` (`color`),
  UNIQUE KEY `label_UNIQUE` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$

delimiter $$

INSERT INTO `c1`.`note_type` (`label`,`color`) VALUES ('General','#FFFFFF');
INSERT INTO `c1`.`note_type` (`label`,`color`) VALUES ('Personal','#ff944d');
INSERT INTO `c1`.`note_type` (`label`,`color`) VALUES ('Work','#b380ff');
INSERT INTO `c1`.`note_type` (`label`,`color`) VALUES ('Travel','#99c2ff');
INSERT INTO `c1`.`note_type` (`label`,`color`) VALUES ('Important','#ff4d4d');
INSERT INTO `c1`.`note_type` (`label`,`color`) VALUES ('ToDo','#66ff66');
INSERT INTO `c1`.`note_type` (`label`,`color`) VALUES ('Thoughts','#ffff4d');






