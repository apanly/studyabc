<?php
/**
 * Created by 狂神文帝.
 * User: vincent
 * Date: 2/12/14
 * Time: 12:52 PM
 * Site: tech.yyabc.org
 * Email:apanly@163.com
 * QQ:364054110
 */
require_class("Controller");
require_class("Bll_juhua10");
class Wan_ProjectsController extends Controller {
    public function handle_request(){
        $req=Dispatcher::getInstance()->get_request();
        $tmplist=array();
        $tmplist[]="apanly/studyabc";
        $tmplist[]="apanly/oauth";
        $tmplist[]="apanly/bookshare";
        $tmplist[]="apanly/sharebook";
        $tmplist[]="apanly/ansible-yyabc";
        $tmplist[]="apanly/piRobot";
        $tmplist[]="apanly/pccontrollerclinet";
        $tmplist[]="apanly/pccontrolerserver";
        $tmplist[]="apanly/vserver";
        $tmplist[]="apanly/phpframe-simple";
        $tmplist[]="apanly/RemoterUI";
        $tmplist[]="apanly/piInfrated";
        $tmplist[]="apanly/proofreadv2";

        $req->set_attribute("infolist",$tmplist);
        return "Wan_Projects";
    }
} 