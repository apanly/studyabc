<?php
require_page("Wan_Base");
class Wan_ListView  extends  Wan_BaseView
{
    public function get_view(){
        $req=Dispatcher::getInstance()->get_request();
        $datas=$req->get_attributes();
        foreach($datas as $key=>$data){
            $this->assign_data($key,$data);
        }
        return "List";
    }

    public function getAction(){
        if($_GET['haslogin']==1){
            return "haslogin";
        }else{
            return "notlogin";
        }
    }
    public static function use_component() {
        return array (
            "Global_Header_wanheader"
        );
    }

    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array_merge(
            parent::use_boundable_styles(),
            array($path . 'List.css')
        );
    }

    public function get_title(){
        $req=Dispatcher::getInstance()->get_request();
        $datas=$req->get_attributes();
        return "每日10句俏皮话|".parent::get_title();
    }
}
