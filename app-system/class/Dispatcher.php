<?php

class Dispatcher
{
    const VERSION = '0.1';
    private static $instance = null;
    const HTML_ID_PREFIX = "core_id_";
    private $configures = array ();
    private $debug_config = array();
    private $router_class = "Router";
    private $request_class = "Request";
    private $response_class = "Response";
    private $request;
    private $response;
    private $debugger;


    private $javascripts = array();
    private $styles = array();
    private $javascripts_processed = false;
    private $styles_processed = false;

    private $boundable_javascripts = array();
    private $boundable_styles = array();
    private $boundable_javascripts_processed = false;
    private $boundable_styles_processed = false;


    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function run(){
        $this->init();
        require_class($this->request_class);
        require_class($this->response_class);
        $this->request = new $this->request_class();
        $this->response = new $this->response_class();
        if(!$this->dispatch()){
            echo "error";
        }
    }
    public function dispatch() {
        try {
            require_class("Router");
            $router = new Router();
            $class = $router->mapping();
            $controller = $this->get_controller($class);
            if (!$controller) {
                return false;
            }
            $result = $controller->handle_request();
            if (is_string($result)) {
                $this->page($result);
            }
            return true;
        } catch (Exception $e) {
           throw new Exception($e->getMessage());
        }
    }
   private function init(){
       set_error_handler("customError");
       set_exception_handler('customException');
       date_default_timezone_set('Asia/Shanghai');
       require_class("dcookie");
       $cookiloginoauth=dcookie::dgetcookie("loginoauth");
       if($cookiloginoauth){
           $seckey=dcookie::dgetcookie("seckey");
           $saltkey=dcookie::dgetcookie("saltkey");
           $saltprekey=Dispatcher::getInstance()->get_config("saltprekey","oauth");
           $saltkey=$saltprekey.$saltkey;
           list($username,$uid)=explode("|",$cookiloginoauth);
           $tmpseckey=md5(serialize($username.$uid.$saltkey));
           if($seckey!=$tmpseckey){
               dcookie::dsetcookie("loginoauth",'',-86400,true);
               dcookie::dsetcookie("seckey",'',-86400,true);
               return "notlogin";
           }
       }
//       require_class("util");
//       $saltkey=dcookie::dgetcookie("saltkey");
//       if(empty($saltkey)) {
//           $tmprand=util::random(8);
//           dcookie::dsetcookie('saltkey',$tmprand, 86400 * 30, 1, 1);
//           $config['secury']['authkey']= substr(md5(util::getRemoteIp().$_SERVER['HTTP_USER_AGENT']."12321".$tmprand),0,8);
//       }
//       unset($saltkey);
   }

    /**
     * 获取配置信息
     * @param string $name 配置名
     * @param string $file 配置文件
     * @return boolean|multitype:
     */
    public function get_config($name=null, $file="common") {
        if (!isset($this->configures[$file])) {
            $config = $this->load_config($file);
            if (!$config) {
                return false;
            }
            $this->configures[$file] = $config;
        }

        return ($name == null)
            ? $this->configures[$file]
            : (isset($this->configures[$file][$name]) ? $this->configures[$file][$name] : null);
    }

    /**
     * 导入配置文件
     * @param string $file 配置文件名
     */
    public function load_config($file="common") {
        global $G_CONF_PATH;
        $route_confs = $G_CONF_PATH;
        foreach ($route_confs as $path) {
            if (file_exists("$path$file.php")) {
                include("$path$file.php");
            }
        }
        if (!isset($config)) {
            trigger_error("Variable \$config not found", E_USER_WARNING);
            return false;
        }
        return $config;
    }

    public function get_request() {
        return $this->request;
    }
    public function get_response() {
        return $this->response;
    }
    public function get_router() {
        return $this->router;
    }

    /**
     * 加载控制器
     * @param string $class 类名
     * @return APF_Controller控制器
     */
    public function get_controller($class) {
        if (!$class) {
            return false;
        }
        if (isset($this->controllers[$class])) {
            return $this->controllers[$class];
        }
        $controller = $this->load_controller($class);

        $this->controllers[$class] = $controller;
        return $controller;
    }

    /**
     * 导入制定的控制器类并初始化
     * 记录debug信息
     * @param string $class
     * @return APF_Controller
     */
    public function load_controller($class) {
        $this->debug("load controller: $class");
        require_controller($class);
        $class= $class."Controller";
        return new $class();
    }
    /**
     * 实例化页面现实逻辑
     * @param string $class 页面类
     * @return Page 可以不要……
     */
    public function page($class, $params=array()) {
        $this->register_resources($class, true);
        $page = $this->load_component(null, $class, true);
        $page->set_params($params);
        $page->execute();
        return $page;
    }

    /**
     * @param string $class
     * @return APF_Component
     */
    public function component($parent, $class, $params=array()) {
        if (!$class) {
            return false;
        }
        $component = @$this->load_component($parent, $class);
        $component->set_params($params);
        $component->execute();
        return $component;
    }

    /**
     * @todo 非递归实现
     * @param unknown_type $class
     * @param unknown_type $is_page
     */

    protected function register_resources($class, $is_page=false) {
        $this->debug("register resources: $class");
        $flag = true;
        if ($is_page) {
            $flag = require_page($class);
            $path =  "view/";
            $class = $class."View";
        } else {
            $flag = require_component($class);
            $path =  "component/";
            $class = $class."Component";
        }
        $list = call_user_func(array($class, 'use_component'));
        foreach ($list as $item) {
            $this->register_resources($item);
        }
        $list = call_user_func(array($class, 'use_boundable_javascripts'));
        foreach($list as $item) {
            $this->prcess_resource_url($path, $item, $this->boundable_javascripts);
        }
        $list = call_user_func(array($class, 'use_boundable_styles'));
        foreach($list as $item) {
            $this->prcess_resource_url($path, $item, $this->boundable_styles);
        }

        $list = call_user_func(array($class, 'use_javascripts'));
        foreach($list as $item) {
            $this->prcess_resource_url($path, $item, $this->javascripts);
        }

        $list = call_user_func(array($class, 'use_styles'));
        foreach($list as $item) {
            $this->prcess_resource_url($path, $item, $this->styles);
        }
        if ($is_page && $this->is_debug_enabled()) {
            $this->register_resources($this->debug_component);
        }

    }

    /**
     * @param string $class
     * @return Component
     */

    private $html_id = 0;
    public function load_component($parent, $class, $is_page=false) {
        $flag = true;
        if ($is_page) {
            $this->debug("load view: $class");
            $flag = require_page($class);
            $class = $class."View";
        } else {
            $this->debug("load component: $class");
            $flag = require_component($class);
            $class = $class."Component";
        }
        $this->html_id++;
        return new $class($parent, self::HTML_ID_PREFIX . $this->html_id);
    }
    /**
     * 修正资源路径
     * @param unknown_type $path
     * @param unknown_type $item
     * @param unknown_type $items
     */
    private $resource_index=0;
    public function prcess_resource_url($path, &$item, &$items) {
        if (is_array($item)) {
            $url = $item[0];
        } else {
            $url = $item;
            $item = array($url, 0);
        }
        if (!preg_match('/:\/\//', $url)) {
            $url = $path.$url;
        }
        if (is_array($items) && array_key_exists($url, $items)) {
            return;
        }
        $item[0] = $url;
        $item[3] = $this->resource_index++;
        $items[$url] = $item;
    }

    public static function resource_order_comparator($a, $b) {
        if ($a[1] == $b[1]) {
            if ($a[3] == $b[3]) {
                return 0;
            }
            return ($a[3] > $b[3]) ? 1 : -1;
        }
        return ($a[1] > $b[1]) ? -1 : 1;
    }
    /*******css和js资源*******/
    public function get_javascripts($head=false) {
        if (!$this->javascripts_processed) {
            $values = $this->javascripts;
            usort($values, "Dispatcher::resource_order_comparator");
            $this->javascripts = array(0=>array(),1=>array());
            foreach ($values as $value) {
                if (@$value[2]) {
                    $this->javascripts[0][] = $value[0];
                } else {
                    $this->javascripts[1][] = $value[0];
                }
            }
            $this->javascripts_processed = true;
        }

        if ($head) {
            return $this->javascripts[0];
        } else {
            return $this->javascripts[1];
        }
    }

    public function get_styles() {
        if (!$this->styles_processed) {
            $values = $this->styles;
            usort($values, "Dispatcher::resource_order_comparator");
            $this->styles = array();
            foreach ($values as $value) {
                $this->styles[] = $value[0];
            }
            $this->styles_processed = true;
        }
        return $this->styles;
    }

    public function get_boundable_javascripts() {
        if (!$this->boundable_javascripts_processed) {
            $values = $this->boundable_javascripts;
            usort($values, "Dispatcher::resource_order_comparator");
            $this->boundable_javascripts = array();
            foreach ($values as $value) {
                $this->boundable_javascripts[] = $value[0];
            }
            $this->boundable_javascripts_processed = true;
        }
        return $this->boundable_javascripts;
    }

    public function get_boundable_styles() {
        if (!$this->boundable_styles_processed) {
            $values = $this->boundable_styles;
            usort($values, "Dispatcher::resource_order_comparator");
            $this->boundable_styles = array();
            foreach ($values as $value) {
                $this->boundable_styles[] = $value[0];
            }
            $this->boundable_styles_processed = true;
        }
        return $this->boundable_styles;
    }


    private $script_blocks = array();
    private $script_blocks_processed = false;

    public function register_script_block($content, $order=0) {
        $this->script_blocks[] = array($content, $order, 3=>$this->resource_index++);
    }

    public function get_script_blocks() {
        if (!$this->script_blocks_processed) {
            $values = $this->script_blocks;
            usort($values, "APF::resource_order_comparator");
            $this->script_blocks = array();
            foreach ($values as $value) {
                $this->script_blocks[] = $value[0];
            }
            $this->script_blocks_processed = true;
        }
        return $this->script_blocks;
    }
    private function __construct() {
        $this->register_shutdown_function("shutdownRecordLog");
        register_shutdown_function(array($this, "shutdown"));
    }
    public function shutdown() {
        if (is_array($this->shutdown_functions)) {
            $functions = array_reverse($this->shutdown_functions);
            foreach ($functions as $function) {
                call_user_func($function);
            }
        }
    }
    public function register_shutdown_function($function) {
        $this->shutdown_functions[] = $function;
    }

    private $shutdown_functions;
    /*******debug******/

    public function debug($message) {
        if (!isset($this->debugger)) {
            return;
        }
        $this->debugger->debug($message);
    }

    public function is_debug_enabled() {
        return isset($this->debugger);
    }
}
