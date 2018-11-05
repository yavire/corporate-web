<?php

require(YAVIRE_DIR . 'libs/login.lib.php');
require '../../libsSmarty/Smarty.class.php';


// smarty configuration
class Login_Smarty extends Smarty {
    function __construct() {
      parent::__construct();
      $this->setTemplateDir(YAVIRE_DIR . 'templates');
      $this->setCompileDir(YAVIRE_DIR . 'templates_c');
      $this->setConfigDir(YAVIRE_DIR . 'configs');
      $this->setCacheDir(YAVIRE_DIR . 'cache');

      $this->force_compile = false;
      $this->debugging = false;
      $this->caching = false;
      $this->cache_lifetime = 120;

    }
}
      
?>
