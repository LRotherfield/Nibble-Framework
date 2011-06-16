<?php

class ActionBase {

  protected $render;
  protected $flash;

  public function __construct($render = false) {
    $this->render = $render;
    $this->flash = Flash::getInstance();
  }

  public function __set($name, $value) {
    if ($this->render)
      $this->render->set($name, $value);
  }

}