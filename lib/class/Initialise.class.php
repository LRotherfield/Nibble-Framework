<?php

class Initialise {
  # Configure default module, action and default variable array

  var $module = 'bites';
  var $action = 'index';
  var $var_array = array();
  var $nav;
  var $url_array;
  var $action_class;
  var $render;

  public function __construct() {
    // Sort out url and Navigation class
    $url = isset($_GET['url']) ? $_GET['url'] : '';
    $this->url_array = explode('/', $url);
    $this->nav = Navigation::getInstance($url);

    // Open globals
    Bites::callHooks('__construct', false, false, true);

    // Initialise Render class
    $this->render = new Render($this->module, $this->action, $this->var_array);

    // Load module, action and view depending on url
    $this->loadActionClass();
    // Initialise the actions class for the module
    $action = sprintf('%s\\%s\\Actions', $this->module, APP);
    $this->action_class = new $action($this->render);
    $this->loadAction();
    Bites::callHooks('beforeRender', $this->module, $this->action, true);
    $this->executeRender();
  }

  public function loadActionClass() {
    if (array_key_exists(0, $this->url_array) && !empty($this->url_array[0]))
      if (RedBean_Plugin_Finder::where("bites", " name = :module and status = 1", array(":module" => $this->url_array[0])))
        $this->module = array_shift($this->url_array);
    // Include the module actions class
    if (file_exists(sprintf('%s/bites/%s/actions.%s.class.php', ROOT, $this->module, APP)))
      include sprintf('%s/bites/%s/actions.%s.class.php', ROOT, $this->module, APP);
    else {
      header('Location: /oops-cannot-find-page');
      exit;
    }
  }

  public function loadAction() {
    if (array_key_exists(0, $this->url_array) && !empty($this->url_array[0])) {
      //Check for hyphenated string and convert to camel case
      $action = preg_replace('/-(.?)/e', "strtoupper('$1')", array_shift($this->url_array));
      if (method_exists($this->action_class, $action)) {
        $this->action = $action;
        $this->var_array = $this->url_array;
      } else {
        header('Location: /oops-cannot-find-page');
        exit;
      }
    }
    // Check for any hooks that may need to run before the action is executed
    Bites::callHooks('beforeAction', $this->module, $this->action, false, $this->var_array);
  }

  public function executeRender() {
    // Run the function for the action from the module
    $this->action_class->{$this->action}($this->var_array);
    $this->nav->buildNav();
    $this->render->renderTemplate();
  }

}