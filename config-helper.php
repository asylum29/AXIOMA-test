<?php

/*CREATE TABLE `questionnaires` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `sex` varchar(1) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `birth` bigint(11) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `personal` longtext,
  `skills` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
CREATE TABLE `files` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `entity` int(11) NOT NULL,
  `filearea` varchar(100) NOT NULL,
  `filename` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/

$CONFIG = new stdClass();
$CONFIG->dirroot = '';
$CONFIG->wwwroot = '';
$CONFIG->dbname = '';
$CONFIG->dbuser = '';
$CONFIG->dbpassword = '';
$CONFIG->dataroot = '';
$CONFIG->password = '';

require_once('loader.php');
