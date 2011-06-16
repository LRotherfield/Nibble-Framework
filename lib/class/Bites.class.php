<?php

class Bites {

  public static function checkBites() {
    $type_array = R::getAll("select * from bites");
    $type_names = array();
    foreach ($type_array as $item) {
      $type_names[] = $item['name'];
    }
    if ($handle = opendir(ROOT . '/bites')) {
      while (false !== ($item_name = readdir($handle))) {
        if ($item_name != "." && $item_name != "..") {
          if (!in_array($item_name, $type_names)) {
            require sprintf('%s/bites/%s/config.php', ROOT, $item_name);
            $item = R::dispense("bites");
            $item->name = $item_name;
            $item->type = $install['type'];
            $item->desc = $install['desc'];
            $item->status = '0';
            $item->type = $install['type'];
            $item->extends = isset($install['extends']) ? $install['extends'] : '';
            R::store($item);
            if (isset($dependencies)) {
              foreach ($dependencies as $dependent) {
                $depends_on = R::dispense("dependencies");
                $depends_on->name = $item_name;
                $depends_on->depends_on = $dependent;
                R::store($depends_on);
              }
              unset($dependencies);
            }
            unset($install);
          }
        }
      }
      closedir($handle);
    }
  }

  public static function callHooks($type, $module, $action, $global = true, &$var_array = array(), $extras = array()) {
    $where = $global ? 'where status = 1 && type = 1' :
      "where status = 1 && (type = 1 or (type = 3 && extends = '$module'))";
    $bites = R::getAll("select * from bites $where order by type asc");
    foreach ($bites as $bite) {
      require_once sprintf('%s/bites/%s/hooks.class.php', ROOT, $bite['name']);
      $class = '\\' . $bite['name'] . '\\Hooks';
      $actions = $class::getInstance();
      if (method_exists($actions, $type))
        $actions->{$type}($module, $action, $var_array, $extras);
      //call_user_func(array($actions, $type), $module, $action, $var_array);
    }
  }

}