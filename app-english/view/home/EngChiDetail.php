<?php
require_page("Home_Base");
class Home_EngChiDetailView extends  Home_BaseView
{
   public function get_view(){
       $req=Dispatcher::getInstance()->get_request();
       $params=$req->get_attributes();
       foreach($params as $key=>$data){
           $this->assign_data($key,$data);
       }
       return "EngChiDetail";
   }

    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array_merge(
            parent::use_boundable_styles(),
            array($path."TingEnglish.css",$path . 'EngChiDetail.css')
        );
    }

    public static function use_boundable_javascripts(){
        $path = classname_to_path(__CLASS__);
        return array_merge(
            parent::use_boundable_javascripts(),
            array($path . 'EngChiDetail.js')
        );
    }

    public function getType(){
        return "engchi";
    }

    public function get_title(){
        $req=Dispatcher::getInstance()->get_request();
        $params=$req->get_attributes();
        $engchiinfo=$params['engchiinfo'];
        return $engchiinfo['etitle']."|".$engchiinfo['ctitle']."|".parent::get_title();
    }
}
