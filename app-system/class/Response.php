<?php
class Response {
    const CONFIG_N_COOKIE_DOMAIN = "cookie_domain";
    const CONFIG_N_COOKIE_PATH   = "cookie_path";

    public function __construct() {
    }

    public function __destruct() {
    }
    /**
     * 跳转指令
     * @param string $url 目的url
     * @param boolen $permanent 是否为永久跳转301，默认为临时跳转302
     * @param boolen $is_exit 是否提前终止框架运行
     */
    public function redirect($url, $permanent=false,$is_exit=true) {
        header("Location: $url", true, $permanent ? 301 : 302);
    	//如果在controller中直接退出的话，会使得一些interceptor没有执行
        //为了不影响之前的代码，加了 is_exit参数
        if ($is_exit) {
            exit(0);
        }
    }

    public function set_cookie($name, $value, $expire=0, $path=NULL, $domain=NULL, $secure=FALSE, $httponly=FALSE) {
        if (!$path) {
            $path = @Dispatcher::getInstance()->get_config(self::CONFIG_N_COOKIE_PATH);
        }
        if (!$domain) {
            $domain = @Dispatcher::getInstance()->get_config(self::CONFIG_N_COOKIE_DOMAIN);
        }
        return setcookie($name, $value,
            $expire ? time() + intval($expire) : 0,
            $path, $domain,
            $secure, $httponly);
    }

    public function remove_cookie($name, $path=NULL, $domain=NULL, $secure=FALSE, $httponly=FALSE) {
        return $this->set_cookie($name, NULL, -3600, $path, $domain, $secure, $httponly);
    }
    

    public function set_header($name, $value, $http_reponse_code=NULL) {
        header("$name: $value", TRUE, $http_reponse_code);
    }

    public function add_header($name, $value, $http_reponse_code=NULL) {
        header("$name: $value", FALSE, $http_reponse_code);
    }
    /**
     * 设置返回内容的类型和字符集。默认字符集为utf-8，也可以在common.php里面指明。
     * @param string $content_type 内容类型
     * @param string $charset 字符集
     */
    public function set_content_type($content_type, $charset=NULL) {
        // 设置文本类型的字符集
        if (!$charset && preg_match('/^text/i', $content_type)) {
            $charset = @Dispatcher::getInstance()->get_config('charset');
            if (!$charset) {
                $charset = 'utf-8';
            }
        }
        // 发送内容类型头部指令
        if ($charset) {
            $this->set_header("content-type", "$content_type; charset=$charset");
        } else {
            $this->set_header("content-type", $content_type);
        }
    }

    public function set_cache_control($value) {
        $this->set_header("cache-control", $value);
    }

    // TODO: client side cache
}
