<?php
require_class("DecoratorView");
require_class('PageHelper');
require_class("uri");
require_class("dcookie");
class Wan_BaseView extends DecoratorView
{
    public function get_decorator(){
        return "play";
    }
    public function getAction(){
        $cookiloginoauth=dcookie::dgetcookie("loginoauth");
        if($cookiloginoauth){
            $seckey=dcookie::dgetcookie("seckey");
            $saltkey=dcookie::dgetcookie("saltkey");
            $saltprekey=Dispatcher::getInstance()->get_config("saltprekey","oauth");
            $saltkey=$saltprekey.$saltkey;
            list($username,$uid)=explode("|",$cookiloginoauth);
            $tmpseckey=md5(serialize($username.$uid.$saltkey));
            if($seckey==$tmpseckey){
                return "haslogin";
            }else{
                return "notlogin";
            }
        }else{
            return "notlogin";
        }
    }

    public function getUserInfo(){
        $flag=$this->getAction();
        if($flag=="haslogin"){
            $request=Dispatcher::getInstance()->get_request();
            $cookiloginoauth=$request->get_cookie("loginoauth");
            if($cookiloginoauth){
                list($username,$uid)=explode("|",$cookiloginoauth);
                if($uid && $username){
                    return array('uname'=>$username,"uid"=>$uid);
                }
            }
        }
        return array();
    }

    public function getType(){
        return null;
    }
    public static function use_component() {
        return array_merge ( parent::use_component (), array (

        ) );
    }

    public static function use_boundable_styles(){
        return array("wan/global.css");
    }
    public static function use_javascripts() {
        return array(
            array("jquery.min.js", PHP_INT_MAX, true),
//            array("bootstrap.js", PHP_INT_MAX),
//            array("html5shiv.js", PHP_INT_MAX-1)
        );
    }

    /*public static function use_styles(){
        return array(
            array("bootstrap.css", PHP_INT_MAX, true),
            //array("theme.css", PHP_INT_MAX, true)
        );
    }*/
    public function get_head_sections(){
        return array(
          '<meta name="description" content="YYabc·玩遍天下,英语abc,yyabc,在线英语学习社区,让英语学习变得高效和有趣" />',
          '<meta name="keywords" content="YYabc·玩遍天下,双语阅读,口语练习,听力练习,英语学习,英语新闻,双语新闻,单词名片,微英语,yyabc" />'
        );
    }

    public function get_title(){
        return "YYabc·玩遍天下,带你用另一种方式看世界！";
    }
}
