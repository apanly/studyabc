<?php
require_class("Controller");
require_class("Bll_TinyEnglish");
class Home_TinyEnglishController extends Controller
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
        $bll=new Bll_TinyEnglish();
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
                $datalist[$key]['IDATE']=date("Y-m-d",$data['IDATE']);
                $datalist[$key]['IMGURI']=$bll->buildDisplayUri($data['INNERMGURI'],$datalist[$key]['IDATE']);
            }
        }
        $req->set_attribute("datalist",$datalist);
        $req->set_attribute("pageinfo",array("page"=>$page,"pageCnt"=>$pageCnt,"frompage"=>$frompage,"endpage"=>$endpage,"uri"=>'/tinyenglish'));
        return "Home_TinyEnglish";
    }
}
