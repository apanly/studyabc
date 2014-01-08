<?php
require_page("Home_Base");
class Home_EngChiView extends  Home_BaseView
{
   public function get_view(){
       $req=Dispatcher::getInstance()->get_request();
       $this->assign_data("datalist",$req->get_attribute("datalist"));
       $this->assign_data("pageinfo",$req->get_attribute("pageinfo"));
       return "EngChi";
   }

    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array_merge(
            parent::use_boundable_styles(),
            array($path . 'EngChi.css')
        );
    }

    public function getType(){
        return "tinyenglish";
    }
}
