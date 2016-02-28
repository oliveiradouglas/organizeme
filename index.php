<?php

session_start();

require_once('configure/configurations.php');
require_once('configure/database.php');
require_once('app/helpers/Helpers.php');
require_once(PATH_ROOT . '/vendor/autoload.php');

if (defined('ERRORS') && ERRORS) {
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	$whoops = new Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();
} else {
	error_reporting(0);
	ini_set("display_errors", 0); 
}

new \Core\Request();

?>