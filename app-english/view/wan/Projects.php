<?php
require_page("Wan_Base");
class Wan_ProjectsView  extends  Wan_BaseView
{
    public function get_view(){
        $req=Dispatcher::getInstance()->get_request();
        $datas=$req->get_attributes();
        foreach($datas as $key=>$data){
            $this->assign_data($key,$data);
        }
        return "Projects";
    }

    public static function use_component() {
        return array (
            "Global_Header_wanheader"
        );
    }
    public static function use_javascripts() {
        return  array(
                array(PageHelper::pure_cdnstatic_url("jquery/jquery1.7.min.js"), PHP_INT_MAX, true),
                array(PageHelper::pure_cdnstatic_url("jquery/jquery.githubRepoWidget.js")),
            );
    }

    public function get_title(){
        $req=Dispatcher::getInstance()->get_request();
        $datas=$req->get_attributes();
        return "作品展|".parent::get_title();
    }
}
