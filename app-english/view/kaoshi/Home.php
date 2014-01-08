<?php
require_page("Home_Base");
class KaoShi_HomeView  extends  Home_BaseView
{
    public function get_view(){
        $req=Dispatcher::getInstance()->get_request();
        $datas=$req->get_attributes();
        foreach($datas as $key=>$data){
            $this->assign_data($key,$data);
        }
        return "Home";
    }

    public function getAction(){
        if($_GET['haslogin']==1){
            return "haslogin";
        }else{
            return "notlogin";
        }
    }
    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array_merge(
            parent::use_boundable_styles(),
            array($path . 'Home.css')
        );
    }
    public function getType(){
        return "kaoshi";
    }

    public function get_title(){
        return "CET4|英语四级真题|历年大学英语四级真题|".parent::get_title();
    }
}
