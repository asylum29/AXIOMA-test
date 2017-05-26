<?php

/*CREATE TABLE `questionnaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sex` varchar(1) NOT NULL,
  `lastname` longtext NOT NULL,
  `firstname` longtext,
  `middlename` longtext,
  `birth` bigint(11) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `personal` longtext,
  `skills` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
SELECT * FROM onepage.files;
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
