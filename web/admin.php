<?php

/*
 * Define application
 */
define('APP', 'admin');
include dirname(dirname(__FILE__)) . '/lib/config/config.php';
if (DBUSER == '')
  require ROOT . '/lib/config/setup.php';
else
  new Initialise();
