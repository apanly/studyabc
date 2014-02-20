<?php
require_class("Controller");
require_class("Bll_juhua10");
class Api_PlayController extends Controller
{
   public function handle_request(){
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
       echo json_encode($tmplist);exit();
   }
}
