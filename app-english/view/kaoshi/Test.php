<?php
require_page("Home_Base");
class Kaoshi_TestView extends  Home_BaseView
{
   public function get_view(){
       $req=Dispatcher::getInstance()->get_request();
       $this->assign_data("datalist",$req->get_attribute("datalist"));
       $this->assign_data("pageinfo",$req->get_attribute("pageinfo"));
       return "Test";
   }

    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array_merge(
            parent::use_boundable_styles(),
            array($path . 'Test.css')
        );
    }

    public function getType(){
        return "kaoshi";
    }

    public function get_title(){
        return "微英语,每日一句,成就美好未来|".parent::get_title();
    }
}
