<?php
require_page("Wan_Base");
class Home_MeView  extends  Wan_BaseView
{
    public function get_view(){
        $req=Dispatcher::getInstance()->get_request();
        $datas=$req->get_attributes();
        foreach($datas as $key=>$data){
            $this->assign_data($key,$data);
        }
        return "Me";
    }

    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array_merge(
            parent::use_boundable_styles(),
            array($path . 'Me.css')
        );
    }

    public static function use_component() {
        return array (
            "Global_Header_wanheader"
        );
    }
    public function get_title(){
        return "狂神文帝风采|".parent::get_title();
    }
}
