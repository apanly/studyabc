<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 12/31/13
 * Time: 9:24 AM
 */

class uri {
    public static function englishComUri(){
        return "http://www.".self::getBaseDomain();
    }
    public static function loginUri(){
        return "http://oauth.".self::getBaseDomain()."/?from=english";
    }
    public static function loginoutUri(){
        return "http://oauth.".self::getBaseDomain()."/logout?from=english";
    }
    public static function regUri(){
        return "http://oauth.".self::getBaseDomain()."/reg?from=english";
    }

    public static function homeinterUri(){
        return "http://blog.".self::getBaseDomain();
    }
    public static function itechUri(){
        return "http://tech.".self::getBaseDomain();
    }

    public static function bookUri(){
        return "http://book.".self::getBaseDomain();
    }
    public static function playUri(){
        return "http://www.".self::getBaseDomain().'/wan/list';
    }
    protected  static function getBaseDomain(){
        $domain=$_SERVER['HTTP_HOST'];
        $strdomain=explode(".",$domain);
        if(count($strdomain)>2){
            unset($strdomain[0]);
        }
        return implode(".",$strdomain);
    }

} 