<?php
require_page("Home_Base");
class Login_ForgotView  extends  Home_BaseView
{
    public function get_view(){
        return "Forgot";
    }

    public function getAction(){
        return "forgot";
    }

}
