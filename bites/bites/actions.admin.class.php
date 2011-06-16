<?php
namespace bites\admin;
class Actions extends \ActionBase {

  public function index($var_array = array()) {
    $this->page_title = 'Administration home page';
    $this->render->meta('author', 'Nibble Development');
  }

  public function managePlugins($var_array = array()) {
    \Bites::checkBites();
    $this->awake_bites = \R::getAll("select * from bites where status = 1");
    $this->asleep_bites = \R::getAll("select * from bites where status != 1");
  }

}