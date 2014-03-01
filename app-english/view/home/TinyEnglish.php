<?php
require_page("Wan_Base");
class Home_TinyEnglishView extends  Wan_BaseView
{
   public function get_view(){
       $req=Dispatcher::getInstance()->get_request();
       $this->assign_data("datalist",$req->get_attribute("datalist"));
       $this->assign_data("pageinfo",$req->get_attribute("pageinfo"));
       return "TinyEnglish";
   }

    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array_merge(
            parent::use_boundable_styles(),
            array($path . 'TingEnglish.css')
        );
    }

    public function get_title(){
        return "微英语,每日一句,成就美好未来|".parent::get_title();
    }
}
