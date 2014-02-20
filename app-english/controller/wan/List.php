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
class Wan_ListController extends Controller {
    public function handle_request(){
        $req=Dispatcher::getInstance()->get_request();
        $blljuhua=new Bll_juhua10();
        $info=$blljuhua->getToday();
        $tmplist=array();
        if($info){
            $cdnstatichost=Dispatcher::getInstance()->get_config("cdnstatic_host","resource");
            $cdnstaticpath=Dispatcher::getInstance()->get_config("cdnstatic_path","resource");
            foreach($info as $item){
                $tmplist[]=array(
                    'desc'=>$item['CONTENT'],
                    'img'=>"http://{$cdnstatichost}{$cdnstaticpath}images/play/juhua10/{$item['IDATE']}/{$item['PICURI']}"
                );
            }
        }
        $req->set_attribute("infolist",$tmplist);
        return "Wan_List";
    }
} 