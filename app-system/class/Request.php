<?php
require_class("Util_StringUtils");
require_class('RequestParametersLoader');
class Request implements RequestParametersLoader {
    public function __construct() {
        $this->parameters_loader = $this;
    }

    public function __destruct() {
    }

    //

    /**
     * Router类会在在url_mapping匹配后设置
     *
     * @param unknown_type $matches
     */
    public function set_router_matches($matches) {
        $this->router_matches = $matches;
    }

    /**
     * 得到url_mapping匹配的结果
     *
     * @return array
     */
    public function get_router_matches() {
        return $this->router_matches;
    }

    private $router_matches = array();

    //

    public function is_secure() {
        return isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]!='';
    }

    public function get_method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function get_request_uri() {
        return $_SERVER['REQUEST_URI'];
    }

    public function get_script_uri() {
        return $_SERVER['SCRIPT_URI'];
    }

    public function get_request_url() {
        return "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function is_get_method() {
        return $this->get_method() == 'GET';
    }

    public function is_post_method() {
        return $this->get_method() == 'POST';
    }

    public function get_user_agent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function get_cookies() {
        return $_COOKIE;
    }

    public function get_cookie($name) {
        if (!isset($_COOKIE[$name])) return NULL;
        return $_COOKIE[$name];
    }

    /**
     * 取得http请求的参数，缺省情况包括queryString($_GET), form($_POST)和seo的参数，
     * 但不包括cookie。
     *
     * @return array ref
     */
    public function get_parameters() {
        if (!isset($this->parameters)) {
            $this->parameters = $this->parameters_loader->load_parameters();
        }
        return $this->parameters;
    }

    public function get_parameter($name) {
        if (!isset($this->parameters)) {
            $this->parameters = $this->parameters_loader->load_parameters();
        }
        if(isset($this->parameters[$name])){
            return $this->parameters[$name];
        }else{
            return NULL;
        }
    }

    protected $parameters;

    /**
     * 设置参数解析对象
     *
     * @param $loader
     */
    public function set_parameters_loader($loader) {
        $this->parameters_loader = $loader;
    }

    public function load_parameters() {
         return array_merge(
            Util_StringUtils::decode_seo_parameters($_SERVER['REQUEST_URI']),
            $_GET,
            $_POST);
    }

    protected $parameters_loader;

    //

    /**
     * 类似java HttpRequest的attributes
     *
     * @return array
     */
    public function get_attributes() {
        return $this->attributes;
    }

    public function set_attribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function get_attribute($key) {
        if(isset($this->attributes[$key])){
            return $this->attributes[$key];
        }else{
            return NULL;
        }
    }

    public function remve_attribute($key) {
        unset($this->attributes[$key]);
    }

    protected $attributes = array();

    //
    // TODO: this method does not handle CDN
    public function get_client_ip() {
        if (!isset($this->client_ip)) {
            $ip = false;
            if(!empty($_SERVER["HTTP_CLIENT_IP"])){
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            }
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
                if ($ip) {
                    array_unshift($ips, $ip);
                    $ip = false;
                }
                for ($i = 0; $i < count($ips); $i++) {
                    if (!eregi("^(10|172\.16|192\.168)\.", $ips[$i])){
                        $ip = $ips[$i];
                        break;
                    }
                }
            }
            if(!$ip){
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            $this->client_ip = $ip;
        }

        return $this->client_ip;
    }

    protected $client_ip;
}