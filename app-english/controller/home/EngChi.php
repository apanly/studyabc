<?php
require_class("Controller");
require_class("Bll_TinyEnglish");
class Home_EngChiController extends Controller
{
    public function handle_request(){
        $req=Dispatcher::getInstance()->get_request();
        $rep=Dispatcher::getInstance()->get_response();
        $paras=$req->get_parameters();
        return "Home_EngChi";
    }
}
