<?php
require_page("Home_Base");
class Error_HTTP404View  extends  Home_BaseView
{
    public function get_view(){
        return "HTTP404";
    }
}
