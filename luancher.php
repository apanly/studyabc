<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
if (count($argv) < 2) {
    echo "Usage: {$argv[0]} {php_file}\n";
    exit(1);
}
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');
define('APP_NAME', 'jobs');
define('APP_PATH', dirname(__FILE__) . '/app-'.APP_NAME."/");
define('SYS_PATH', APP_PATH . "../app-system/");
$G_LOAD_PATH = array(
    APP_PATH,
    APP_PATH."../app-english/",
    APP_PATH."../app-common/",
    APP_PATH."../app-static/",
    SYS_PATH
);
$G_CONF_PATH = array(
    APP_PATH."../app-common/config/",
    APP_PATH."../app-english/config/",
    APP_PATH."../config/"
);
require_once (SYS_PATH . "functions.php");
require_class("Dispatcher");
require (APP_PATH . "bin/{$argv[1]}");
