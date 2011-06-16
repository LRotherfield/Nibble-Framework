<?php

class Navigation {

  var $url = array();
  var $navigation_array = array();
  var $navigation_string_array = array();
  public static $instance;

  public function __construct($url = array()) {
    if (!empty($url))
      foreach (explode('/', $url) as $x)
        $this->url[] = !empty($this->url) ? $this->url[count($this->url) - 1] . '/' . $x : '/' . $x;
    else
      $this->url = array('/', '/' . APP);
    $this->url = array_reverse($this->url);
    $bites = R::getAll('select name from bites where status = 1');
    foreach ($bites as $bite) {
      require_once sprintf('%s/bites/%s/config.php', ROOT, $bite['name']);
      $this->addNavItem($navigation);
    }
  }

  public static function getInstance($url = array()) {
    if (!self::$instance)
      self::$instance = new Navigation($url);
    return self::$instance;
  }

  public function addNavItem($navigation) {
    $this->navigation_array = array_merge_recursive($this->navigation_array, $navigation);
  }

  public function buildNav() {
    if (!$path = $this->findPath()) {
      $this->navItem($this->navigation_array[APP], 0, 'home');
      return;
    }
    $navigation = $this->navigation_array[APP];
    foreach ($path as $tier => $match) {
      $this->navItem($navigation, $tier, $match);
      if ($match !== 0)
        $navigation = $navigation[$match];
    }
  }

  public function findPath() {
    foreach ($this->url as $url)
      if ($path = Useful::array_search_recursive('/' . APP . $url, $this->navigation_array[APP]))
        return $path;
      else if ($path = Useful::array_search_recursive($url, $this->navigation_array[APP]))
        return $path;
    return false;
  }

  public function navItem(&$array, $tier = 0, $match = false) {
    foreach ($array as $name => &$routes) {
      if (is_array($routes)) {
        $route = $routes[0];
        unset($routes[0]);
      } else
        $route = $routes;
      $class = $name === $match ? ' class="active"' : '';
      $this->navigation_string_array[$tier][] = sprintf('<li><a href="%s"%s>%s</a></li>', $route, $class, ucfirst($name));
    }
  }

  public function printNavTier($tier) {
    if (isset($this->navigation_string_array[$tier]))
      return implode("\n", $this->navigation_string_array[$tier]);
    return false;
  }

}