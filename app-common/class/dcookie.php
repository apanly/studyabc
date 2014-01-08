<?php
class dcookie
{
    public static   function dsetcookie($var, $value = '', $life = 0, $httponly = false) {
        if($value == '' || $life < 0) {
            $value = '';
            $life = -1;
        }
        $domain=Dispatcher::getInstance()->get_config("domain","resource");
        $life = $life > 0 ? time() + $life : ($life < 0 ? time() - 31536000 : 0);
        $path = $httponly && PHP_VERSION < '5.2.0' ? '; HttpOnly' :"/";
        $secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
        if(PHP_VERSION < '5.2.0') {
            setcookie($var, $value, $life, $path, $domain, $secure);
        } else {
            setcookie($var, $value, $life, $path, $domain, $secure, $httponly);
        }
    }

    public static function dgetcookie($key) {
        //global $_G;
        //$cookiepre=$_G['config']['cookiepre'];
        //$key=$cookiepre.$key;
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : '';
    }
}
