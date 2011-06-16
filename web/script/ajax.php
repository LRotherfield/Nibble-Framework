<?php
/*
 * Define application
*/
define('APP', 'ajax');
include dirname(dirname(dirname(__FILE__))) . '/lib/config/config.php';
$ajax = new Ajax();
echo $ajax->runAction();