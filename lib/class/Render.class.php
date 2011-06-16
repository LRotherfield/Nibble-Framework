<?php

/**
 * @author Luke Rotherfield
 * @desc Class of rendering functions for rendering views and variables
 */
class Render {

  /**
   * @desc Class variables
   */
  var $variables = array(
    'page_title' => SITE_NAME
  );
  var $module;
  var $view;
  var $navigation;
  var $gjs = array('"jquery","1"');
  var $script = array('cookie.js', 'nibble.js', 'notice.js');
  var $style = array('nibble.css', 'gritter.css');
  var $scripts_loaded = false;
  var $load_array = array(
    '<link rel="stylesheet" type="text/css" media="screen" href="%s/style/%s" />',
    '<script type="text/javascript" src="%s/script/%s"></script>',
    'google.load(%s);'
  );
  var $template;
  var $meta = array(
    'description' => SITE_NAME,
    'keywords' => SITE_NAME
  );

  /**
   * @desc Initiate render class with module action and var_array referenced from initialise class
   * @param $module
   * @param $action
   * @param $var_array
   */
  public function __construct(&$module, &$action, &$var_array) {
    $this->module = &$module;
    $this->view = &$action;
    $this->var_array = &$var_array;
    $this->template = APP . '_layout';
    $this->navigation = Navigation::getInstance();
  }

  /**
   * @desc Function to set variables for this module
   */
  public function set($name, $content) {
    $this->variables[$name] = $content;
  }

  /**
   * @desc Render flash messages
   */
  public function flash() {
    foreach (Flash::getInstance()->messages as $message)
      echo $message;
  }

  /**
   * @desc Function to include form file
   */
  public function form($form = 'form', $target = '', $data = array(), $extras = false) {
    include sprintf('%s/bites/%s/forms/%s.php', ROOT, $this->module, $form);
    Bites::callHooks('onFormObject', $this->module, $this->view, false, $form, array_merge($this->variables, $this->var_array));
    $form->renderClass($this);
    $form->addData($data);
    return($form);
  }

  /**
   * @desc Function to add meta tags
   * @param <string> $name
   * @param <string> $content
   */
  public function meta($name, $content) {
    $this->meta[$name] = $content;
  }

  /**
   * @desc Function to get all meta tags and return them as a string
   * @return <string>
   */
  public function renderMeta() {
    $meta = '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />';
    foreach ($this->meta as $name => $content)
      $meta .= sprintf('<meta name="%s" content="%s" />' . "\n", $name, $content);
    return $meta;
  }

  /**
   * @desc Function to include partial set is_template to false to render bite partial, otherwise 
   * main template partail will be included
   */
  public function partial($partial, $is_template = false, $var_array = array()) {
    extract($var_array);
    ob_start();
    if (!$is_template)
      include sprintf('%s/bites/%s/partials/%s.%s.php', ROOT, $this->module, $partial, APP);
    else
      include sprintf('%s/templates/partials/%s.php', ROOT, $partial);
    $contents = ob_get_contents();
    ob_end_clean();
    Bites::callHooks('onPartial', $this->module, $this->view, false, $contents, array_merge(array('partial name' => $partial), $var_array));
    echo $contents;
  }

  /**
   * @desc This function grabs the local apps template and includes it
   */
  public function renderTemplate() {
    extract($this->variables);
    require_once sprintf('%s/templates/%s.php', ROOT, $this->template);
  }

  /**
   * @desc This function extracts all variables created by the function and includes the view
   */
  public function renderView() {
    Bites::callHooks('onVariables', $this->module, $this->view, false, $this->variables);
    extract($this->variables);
    ob_start();
    require_once sprintf('%s/bites/%s/views/%s.%s.php', ROOT, $this->module, APP, $this->view);
    $contents = ob_get_contents();
    ob_end_clean();
    Bites::callHooks('onView', $this->module, $this->view, false, $contents, array_merge(array('view name' => $this->view),  $this->variables));
    echo $contents;
  }

  /**
   * @desc Function that will include the relevant config class for the module.
   */
  public function requireApplicationConfig() {
    $bites = \R::getAll("select name from bites where status = 1 and (extends = :module or name = :module) order by type asc", array(':module'=>$this->module));
    foreach ($bites as $bite) {
      include sprintf('%s/bites/%s/config.php', ROOT, $bite['name']);
      foreach (array('script', 'style', 'gjs') as $array) {
        if (isset(${$array}) && is_array(${$array})) {
          $scripts = ${$array};
          foreach (array($this->view, 'all') as $key)
            if (isset($scripts[$key]) && is_array($scripts[$key]))
              foreach ($scripts[$key] as $script)
                $this->addScript($script, $array);
        }
      }
    }
    $this->scripts_loaded = true;
  }

  /**
   * @desc Function to add script to default script variables
   */
  public function addScript($script, $type) {
    if (!in_array($script, $this->$type))
      array_push($this->$type, $script);
  }

  /**
   * @desc Render all scripts from the defaults array and the application config class
   * @param $type: javascripts or stylesheets
   */
  public function renderScripts() {
    if (!$this->scripts_loaded)
      $this->requireApplicationConfig();
    $script_string = '<script src="http://www.google.com/jsapi" type="text/javascript"></script>
      <script type="text/javascript">';
    foreach ($this->gjs as $s) {
      $script_string .= sprintf($this->load_array[2], $s);
    }
    $script_string .= '</script>';
    foreach (array('script', 'style') as $array) {
      foreach ($this->$array as $file) {
        if (file_exists(ROOT . '/web/' . $array . '/' . $file)) {
          $script_string .= sprintf($this->load_array[($array == 'script' ? 1 : 0)] . "\n", (DEV_ENV ? '/' . INC_ROOT : ''), $file);
        }
      }
    }
    return $script_string;
  }

}
