<?php
require_class("Controller");
require_class("Bll_EnglishChinese");
require_class("Bll_EngChiDetail");
class Home_EngChiDetailController extends Controller
{
    public function handle_request(){
        $req=Dispatcher::getInstance()->get_request();
        $rep=Dispatcher::getInstance()->get_response();
        $params=$req->get_parameters();
        $id=$params['id'];
        if(!filter_var($id, FILTER_VALIDATE_INT)){
            $rep->redirect("/");
        }
        $daoec=new Bll_EnglishChinese();
        $sinfo=$daoec->getECInfoById($id);
        $daoecdetail=new Bll_EngChiDetail();
        $cinfo=$daoecdetail->getDetailByFid($id);
        $engchiinfo=array();
        if($sinfo && $cinfo){
            $prevsinfo=$daoec->getnPrevInfoById($id);
            $nextsinfo=$daoec->getnNextInfoById($id);
            if($prevsinfo){
                $engchiinfo['prevuri']="engchidetail?id=".$prevsinfo['ID'];
            }
            if($nextsinfo){
                $engchiinfo['nexturi']="engchidetail?id=".$nextsinfo['ID'];
            }
            $engchiinfo['id']=$sinfo['ID'];
            $engchiinfo['etitle']=$sinfo['ENGTITLE'];
            $engchiinfo['ctitle']=$sinfo['CHITITLE'];
            $engchiinfo['engdetail']=json_decode($cinfo['ENGLISHJSON'],true);
            $engchiinfo['chidetail']=json_decode($cinfo['CHINESEJSON'],true);
        }else{
            $rep->redirect("/");
        }
        $req->set_attribute("engchiinfo",$engchiinfo);
        return "Home_EngChiDetail";
    }
}
