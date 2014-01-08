<?php
require_page("Home_Base");
class KaoShi_DetailView  extends  Home_BaseView
{
    public function get_view(){
        $req=Dispatcher::getInstance()->get_request();
        $datas=$req->get_attributes();
        foreach($datas as $key=>$data){
            $this->assign_data($key,$data);
        }
        return "Detail";
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
            "Global_Header_Topbar"
        );
    }

    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array_merge(
            parent::use_boundable_styles(),
            array($path . 'Detail.css')
        );
    }
    public function getType(){
        return "kaoshi";
    }

    public function get_title(){
        $req=Dispatcher::getInstance()->get_request();
        $datas=$req->get_attributes();
        return $datas['data']['title']."|英语四六级真题|历年大学英语四六级真题|".parent::get_title();
    }
}
