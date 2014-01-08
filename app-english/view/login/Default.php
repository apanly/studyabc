<?php
require_page("Home_Base");
class Login_DefaultView  extends  Home_BaseView
{
    public function get_view(){
        return "Default";
    }

    public function getAction(){
        return "login";
    }
}
