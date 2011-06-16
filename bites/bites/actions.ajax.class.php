<?php

namespace bites\ajax;

class Actions extends \ActionBase {

  public function activateAndDeactivatePlugins($var_array = array()) {
    if (isset($var_array['id'])) {
      if ($bite = \R::load("bites", $var_array['id'])) {
        $bite->status ^= 1;
        if ($bite->status == 1)
          if ($dependents = $this->checkDependencies($bite->name))
            return ucfirst($bite->name) . ' has not been woken due to some dependencies: ' . implode(', ', $dependents);
        if ($bite->status == 0)
          if ($this->checkDependents($bite->name))
            return 2;
        $status = $bite->status == 1 ? 'woken up' : 'put to sleep';
        \R::store($bite);
        $this->updateWebDir($bite);
        if ($bite->status == 1)
          $this->checkInstall($bite);
        return $bite->status;
      }
      return 'No such bite exists';
    }
  }

  public function deactivateDependentPlugins($var_array = array()) {
    if (isset($var_array['id'])) {
      if ($bite = \R::load("bites", $var_array['id'])) {
        if ($dependents = $this->checkDependents($bite->name)) {
          foreach ($dependents as $dependent) {
            \R::exec("update bites set status = 0 where name = '{$dependent['name']}'");
            $dependent_bite = \R::load('bites', $dependent['id']);
            $this->updateWebDir($dependent_bite);
          }
        }
        $bite->status = 0;
        \R::store($bite);
        $this->updateWebDir($bite);
        $this->flash->flashMessage(ucfirst($bite->name) . ' has been put to sleep along with all dependents', 'Dependency success');
        return true;
      }
    }
    return false;
  }

  private function updateWebDir($b) {
    if ($b->status) {
      foreach (array('script', 'style', 'graphic') as $type) {
        \Useful::checkFilesInDir(sprintf('%s/bites/%s/%s', ROOT, $b->name, $type), $files);
        foreach ($files as $s)
          \Useful::copyFile(sprintf('%s/bites/%s/%s/%s', ROOT, $b->name, $type, $s), sprintf('%s/web/%s/%s', ROOT, $type, $s));
        unset($files);
      }
    } else {
      $active_bites = \R::$adapter->get("select * from bites where status = 1");
      foreach ($active_bites as $bite) {
        \Useful::checkFilesInDir(\ROOT . '/bites/' . $bite['name'] . '/script', $scripts);
        \Useful::checkFilesInDir(\ROOT . '/bites/' . $bite['name'] . '/style', $styles);
        \Useful::checkFilesInDir(\ROOT . '/bites/' . $bite['name'] . '/graphic', $graphics);
      }
      foreach (array('script', 'style', 'graphic') as $type) {
        \Useful::checkFilesInDir(\ROOT . '/web/' . $type, $files);
        foreach ($files as $file) {
          if (!in_array($file, ${$type . 's'}))
            unlink(\ROOT . '/web/' . $type . '/' . $file);
        }
        unset($files);
      }
    }
  }

  private function checkInstall($b) {
    require sprintf('%s/bites/%s/config.php', \ROOT, $b->name);
    \R::exec("show tables like '{$install['table']}'");
    if (!\R::$adapter->getAffectedRows())
      require sprintf('%s/bites/%s/schema.php', \ROOT, $b->name);
  }

  private function checkDependencies($name) {
    $dependencies = \R::getAll("select depends_on from dependencies where name = '$name'");
    $dependents = array();
    foreach ($dependencies as $dependent)
      if (!\RedBean_Plugin_Finder::where('bites', 'name = :name and status = 1', array(':name' => $dependent['depends_on'])))
        $dependents[] = ucfirst($dependent['depends_on']) . ' is not installed';
    if (!empty($dependents))
      return $dependents;
    return false;
  }

  private function checkDependents($name) {
    $dependencies = \R::getAll("select b.id,d.name from dependencies d join bites b on b.name = d.name where depends_on = '$name' && status = 1");
    return!empty($dependencies) ? $dependencies : false;
  }

}
