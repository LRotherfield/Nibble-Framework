<?php

class Ajax {

  protected $module = 'bites';
  protected $action = 'index';
  protected $var_array = array();

  public function __construct() {
    if(!defined('APP'))
      define ('APP', 'ajax');
    if (isset($_REQUEST['module']))
      $this->module = explode('/', $_REQUEST['module']);
    $this->module = is_dir(sprintf('%s/bites/%s', ROOT, $this->module[1])) ? $this->module[1] : $this->module[2];
    if (isset($_REQUEST['action']))
      $this->action = $_REQUEST['action'];
    if (isset($_REQUEST))
      $this->var_array = $_REQUEST;
  }

  public function runAction() {
    Bites::callHooks('beforeAjaxAction', $this->module, $this->action, FALSE, $this->var_array, array());
    include_once sprintf('%s/bites/%s/actions.ajax.class.php', ROOT, $this->module);
    $namespace = $this->module.'\\Ajax\\Actions';
    $action = new $namespace();
    return $action->{$this->action}($this->var_array);
  }

}