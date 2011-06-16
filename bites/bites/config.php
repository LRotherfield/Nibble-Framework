<?php

$script = array('managePlugins' => array('bites.js'));
$gjs = array('all' => array('"jqueryui","1"'));
$style = array();
$install = array(
    'desc' => 'Bites are plugins for Nibble, &ldquo;Bites&rdquo; is the default module for Nibble that provides a means of enabling other Bites.',
    'table' => 'bites',
    'type' => 2
);
$dependencies = array('none');

$navigation = array(
    'admin' => array(
        'home' => '/admin',
        'manage Bites (Nibble plugins)' => '/admin/bites/manage-plugins'
    ),
    'frontend' => array(
      'home' => '/'
    )
);