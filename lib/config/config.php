<?php

/*
 * Author: Luke Rotherfield
 *
 * config.php is a file to configure the project, firstly assign the project
 * an environment, dev or live, next initiate all variables that will be used
 * "globaly" throughout the code base, after all variables have been
 * assigned include any files needed in order.
 *
 * This is the first script called when the site is started.
 */
/* Site settings */

define('EMAIL', '');
define('SITE_NAME', '');
define('URL', $_SERVER['SERVER_NAME']);


/* Configure database connection variables */
define('DBHOST', '');
define('DBUSER', '');
define('DBPASS', '');
define('DBNAME', '');
define('DBTYPE', 'mysql');

/* Start the session */
session_name('nibble');
ini_set('session.gc_maxlifetime', 60*60*2);
session_set_cookie_params(60*60*2);
session_start();

date_default_timezone_set("Europe/London");

/* Auto loader */

function __autoload($class_name) {
  if (file_exists(dirname(dirname(__FILE__)) . '/class/' . $class_name . '.class.php'))
    require_once dirname(dirname(__FILE__)) . '/class/' . $class_name . '.class.php';
}

/* Project environment */
define('DEV_ENV', ($_SERVER['SERVER_ADDR'] == '127.0.0.1' ? true : false));

/* Root definition */
define('ROOT', dirname(dirname(dirname(__FILE__))));

/* If development define the include root for html elements such as css files */
if (DEV_ENV) {
  $root_array = explode(DIRECTORY_SEPARATOR, ROOT);
  define('INC_ROOT', $root_array[count($root_array) - 1]);
}
Useful::setReporting(true);
if (DBUSER !== '') {
  require(ROOT . "/lib/class/rb.php");
  R::setup(sprintf("%s:host=%s;dbname=%s", DBTYPE, DBHOST, DBNAME), DBUSER, DBPASS);


  /* If there is no bites table make it and add the bites plugin */
  R::exec("show tables like 'bites'");
  if (!R::$adapter->getAffectedRows()) {
    $item = R::dispense("bites");
    $item->name = 'bites';
    $item->desc = 'Bites are plugins for Nibble, &ldquo;Bites&rdquo; is the default module for Nibble that provides a means of enabling other Bites.';
    $item->status = '1';
    $item->type = '2';
    $id = R::store($item);
  }
}