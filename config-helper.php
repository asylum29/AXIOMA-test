<?php

/*CREATE TABLE `questionnaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sex` varchar(1) NOT NULL,
  `lastname` longtext NOT NULL,
  `firstname` longtext,
  `middlename` longtext,
  `birth` date NOT NULL,
  `color` varchar(100) DEFAULT NULL,
  `assiduity` int(1) DEFAULT NULL,
  `neatness` int(1) DEFAULT NULL,
  `selflearning` int(1) DEFAULT NULL,
  `diligence` int(1) DEFAULT NULL,
  `skills` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
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
