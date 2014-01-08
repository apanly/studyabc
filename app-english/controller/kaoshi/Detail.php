<?php
require_class("Controller");
require_class("Bll_CET");
require_class("Bll_CETDetail");
class KaoShi_DetailController extends Controller
{
   public function handle_request(){
       $req=Dispatcher::getInstance()->get_request();
       $rep=Dispatcher::getInstance()->get_response();
       $params=$req->get_parameters();
       $id=$params['id'];
       if(!preg_match("/^[1-9]\d*$/",$id)){
           $rep->redirect("/");
       }
       $bllcet=new Bll_CET();
       $info=$bllcet->getInfoById($id);
       if(!$info){
           $rep->redirect("/");
       }
       $bllcetdetail=new Bll_CETDetail();
       $infodetail=$bllcetdetail->getInfoById($id);
       $tmpcontent=json_decode($infodetail['CONTENT'],true);
       foreach($tmpcontent as $key=>$val){
            if(preg_match("/.*[(jpg)|(gif)|(png)|(jpeg)]$/is",$val)){
                $tmpcontent[$key]='<img src="'.$val.'"/>';
            }
       }
       $data=array(
           'title'=>$info['CETTITLE'],
           'date'=>$info['CETDATE'],
           'content'=>$tmpcontent,
       );
       $previnfo=$bllcet->getPrevInfoById($id);
       $nextinfo=$bllcet->getNextInfoById($id);
       if($previnfo){
           $data['prevuri']="/kaoshi/detail?id=".$previnfo['ID'];
       }
       if($nextinfo){
           $data['nexturi']="/kaoshi/detail?id=".$nextinfo['ID'];
       }
       $req->set_attribute("data",$data);
       return "KaoShi_Detail";
   }
}
