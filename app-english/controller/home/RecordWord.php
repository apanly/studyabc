<?php
require_class("Controller");
require_class("DB_Factory");
class Home_RecordWordController extends Controller
{
    public function handle_request(){
        $req=Dispatcher::getInstance()->get_request();
        $rep=Dispatcher::getInstance()->get_response();
        $db=DB_Factory::get_instance()->get_pdo("master");
        $req->set_attribute("test","hello world");
        return "home_default";
    }
}
