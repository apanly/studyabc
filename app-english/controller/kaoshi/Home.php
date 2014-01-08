<?php
require_class("Controller");
require_class("Bll_CET");
class KaoShi_HomeController extends Controller
{
   public function handle_request(){
       $req=Dispatcher::getInstance()->get_request();
       $rep=Dispatcher::getInstance()->get_response();
       $paras=$req->get_parameters();
       $page=$paras['page']?$paras['page']:1;
       if(!preg_match("/^[1-9]\d*$/",$page)){
           $page=1;
       }
       $pageSize=10;
       $bllcet=new Bll_CET();
       $from=($page-1)*$pageSize;
       $datalist=$bllcet->getListPyPage(0,$from,$pageSize);
       $listcnt=$bllcet->getCnt(0);
       $pageCnt=ceil($listcnt/$pageSize);
       $frompage=1;
       $endpage=$pageCnt;
       if($page>5){
           $frompage=$page-5;
       }
       if($page+5<=$pageCnt){
           $endpage=$page+5;
       }
       $req->set_attribute("datalist",$datalist);
       $req->set_attribute("type","home");
       $req->set_attribute("pageinfo",array("page"=>$page,"pageCnt"=>$pageCnt,"frompage"=>$frompage,"endpage"=>$endpage,"uri"=>'/kaoshi'));
       return "KaoShi_Home";
   }
}
