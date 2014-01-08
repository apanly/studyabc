<?php
require_class("Controller");
require_class("Bll_TinyEnglish");
require_class("Bll_EnglishChinese");
require_class("Bll_CET");
require_class("Bll_EnglishTest");
class Home_DefaultController extends Controller
{
   public function handle_request(){
       $req=Dispatcher::getInstance()->get_request();
       $bll=new Bll_TinyEnglish();
       $lastestsentence=$bll->getLastestSentence();
       $list=array();
       if($lastestsentence){
           $lastestsentence['IDATE']=date("Y-m-d",$lastestsentence['IDATE']);
            $lastestsentence['IMGURI']=$bll->buildDisplayUri($lastestsentence['INNERMGURI'],$lastestsentence['IDATE']);
       }
       $list['tinyenglish']=$lastestsentence;
       $bllengchi=new Bll_EnglishChinese();
       $engchiinfo=$bllengchi->getLastestEngChi();
       $list['engchiinfo']=$engchiinfo;
       $bllcet=new Bll_CET();
       $cetinfo=$bllcet->getListPyPage(1,0,12);
       if($cetinfo){
           foreach($cetinfo as $key=>$item){
               $cetinfo[$key]['CETTITLE']=mb_substr($item['CETTITLE'],0,20,"utf-8")."..";
           }
       }
       $list['cet4info']=$cetinfo;
       $cetinfo=$bllcet->getListPyPage(2,0,12);
       if($cetinfo){
           foreach($cetinfo as $key=>$item){
               $cetinfo[$key]['CETTITLE']=mb_substr($item['CETTITLE'],0,20,"utf-8")."..";
           }
       }
       $list['cet6info']=$cetinfo;
       $bllenglishtest=new Bll_EnglishTest();
       $englishtestinfo=$bllenglishtest->getLastestSentence();
       $etestinfo="";
       if($englishtestinfo){
           foreach($englishtestinfo as $item){
               $tmp=json_decode($item['QUESTIONJSON'],true);
               $etestinfo.=$tmp['question']."<br/><br/>";
           }
       }
       $list['etestinfo']=$etestinfo;
       $req->set_attribute("list",$list);
       return "Home_Default";
   }
}
