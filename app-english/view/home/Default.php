<?php
require_page("Home_Base");
class Home_DefaultView  extends  Home_BaseView
{
    public function get_view(){
        $req=Dispatcher::getInstance()->get_request();
        $datas=$req->get_attributes();
        foreach($datas as $key=>$data){
            $this->assign_data($key,$data);
        }
        return "Default";
    }


    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array_merge(
            parent::use_boundable_styles(),
            array($path . 'Default.css')
        );
    }
    public function getType(){
        return "home";
    }

    public function get_title(){
        return "英语四级真题|历年大学英语四级真题|双语学习|微英语,每日一句|".parent::get_title();
    }
}
