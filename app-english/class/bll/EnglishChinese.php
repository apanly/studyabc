<?php
require_class("Dao_EnglishChinese");
require_class("Bll_TinyEnglish");
require_class("Httplib");
require_class("Dao_EngChiDetail");
class Bll_EnglishChinese
{
     public function save($multidata){
         $daoengchi=new Dao_EnglishChinese();
         $daoimage=new Bll_TinyEnglish();
         $daodetail=new Dao_EngChiDetail();
         foreach($multidata as $rowdata){
             $params=array();
             $articleid=$rowdata['id'];
             $params['chititle']=$rowdata['chinese']['title'];
             $params['engtitle']=$rowdata['english']['title'];
             $params['idate']=date("Y-m-d");
             $params['picuri']=$daoimage->gatherImage($rowdata['picurl'],$params['idate']);
             $params['artid']=$articleid;
             $fid=$daoengchi->insert($params);
             if($fid){
                 $detailinfos=$this->getDetailContent($articleid,$params['idate']);
                 $tmpdata=array();
                 $tmpdata['fid']=$fid;
                 $tmpdata['englishjson']=json_encode($detailinfos[0]);
                 $tmpdata['chinesejson']=json_encode($detailinfos[1]);
                 $tmpdata['idate']=date("Y-m-d");
                 $daodetail->insert($tmpdata);
             }
         }
     }

    public function getDetailContent($id,$date){
        $ruri=Dispatcher::getInstance()->get_config("engchidetail","crontab");
        $ruri=$ruri."?articleId={$id}&date={$date}";
        $http=new Httplib();
        $response=$http->get($ruri);
        if($response && $response['response']['code']==200){
            $data = $response['body'];
            preg_match("/<div\s*class=\"article-content\">(.*?)<\/div>/is", $data, $matches);
            if($matches && $matches[1]){
                $info=explode("</p>",$matches[1]);
                $enginfo=$chiinfo=array();
                foreach($info as $item){
                    if(stripos($item,"english a2")){
                        $enginfo[]=trim(str_replace('<p class="english a2">','',$item));
                    }else if(stripos($item,"chinese a2")){
                        $chiinfo[]=trim(str_replace('<p class="chinese a2" style="display:none;">','',$item));
                    }
                }
                return array($enginfo,$chiinfo);
            }
        }
    }

    public function getLastestEngChi(){
        $daoengchi=new Dao_EnglishChinese();
        return $daoengchi->getLastestEngChi(date("Y-m-d"));
    }

    public function getECInfoById($id){
        $daoengchi=new Dao_EnglishChinese();
        return $daoengchi->getECInfoById($id);
    }

    public function getnNextInfoById($id){
        $daoengchi=new Dao_EnglishChinese();
        return $daoengchi->getnNextInfoById($id);
    }

    public function getnPrevInfoById($id){
        $daoengchi=new Dao_EnglishChinese();
        return $daoengchi->getnPrevInfoById($id);
    }
}
