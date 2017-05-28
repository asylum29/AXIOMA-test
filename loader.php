<?php

session_start();

define('APP_INTERNAL', true);
define('PARAM_RAW',    'raw');
define('PARAM_NOTAGS', 'tag');
define('PARAM_DATE',   'date');
define('PARAM_INT',    'int');
define('PARAM_FLOAT',  'float');

require_once($CONFIG->dirroot . '/lib/db.php');
require_once($CONFIG->dirroot . '/lib/auth.php');
require_once($CONFIG->dirroot . '/lib/files.php');
require_once($CONFIG->dirroot . '/AcImage/AcImage.php');
require_once($CONFIG->dirroot . '/lib/helper.php');

$DB = new DBConnection($CONFIG->dbname, $CONFIG->dbuser, $CONFIG->dbpassword);
$USER = new User();
