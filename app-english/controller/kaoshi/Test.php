<?php
require_class("Controller");
require_class("Bll_EnglishTest");
class Kaoshi_TestController extends Controller
{
    public function handle_request(){
        $req=Dispatcher::getInstance()->get_request();
        $rep=Dispatcher::getInstance()->get_response();
        $paras=$req->get_parameters();
        $page=$paras['page']?$paras['page']:1;
        if(!preg_match("/^[1-9]\d*$/",$page)){
            $page=1;
        }
        $pageSize=7;
        $bll=new Bll_EnglishTest();
        $from=($page-1)*$pageSize;
        $datalist=$bll->getListPyPage($from,$pageSize);
        $listcnt=$bll->getCnt();
        $pageCnt=ceil($listcnt/$pageSize);
        $frompage=1;
        $endpage=$pageCnt;
        if($page>5){
            $frompage=$page-5;
        }
        if($page+5<=$pageCnt){
            $endpage=$page+5;
        }
        if($datalist){
            foreach($datalist as $key=>$data){
                $tmpdate=json_decode($data['QUESTIONJSON'],true);
                $tmpdate['choicesteps']=ceil(count($tmpdate['choices'])/2);
                $tmpdate['pdate']=$data['PDATE'];
                $tmpdate['tip']=urldecode($tmpdate['tip']);
                $datalist[$key]=$tmpdate;
            }
        }
        $req->set_attribute("datalist",$datalist);
        $req->set_attribute("pageinfo",array("page"=>$page,"pageCnt"=>$pageCnt,"frompage"=>$frompage,"endpage"=>$endpage,"uri"=>'/kaoshi/test'));
        return "Kaoshi_Test";
    }
}
