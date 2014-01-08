<?php
error_reporting(0);
#error_reporting(E_ALL);
define('APP_NAME', 'english');
define('APP_PATH', dirname(__FILE__) . '/app-'.APP_NAME."/");
define('SYS_PATH', APP_PATH . "../app-system/");
$G_LOAD_PATH = array(
    APP_PATH,
    APP_PATH."../app-common/",
    APP_PATH."../app-static/",
    SYS_PATH
);
$G_CONF_PATH = array(
    APP_PATH."../app-common/config/",
    APP_PATH."config/",
    APP_PATH."../config/"
);
require_once (SYS_PATH . "functions.php");
require_class("Dispatcher");
require_class("dcookie");
Dispatcher::getInstance()->run();
