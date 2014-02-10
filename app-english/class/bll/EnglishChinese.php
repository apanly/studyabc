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
         $post2qzone=array();
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
                 $post2qzone[]=array(
                     'ctitle'=>$params['chititle'],
                     'etitle'=>$params['engtitle'],
                     'contents'=>$detailinfos,
                     'id'=>$fid
                 );
             }
         }
         $this->post2qzone($post2qzone);
     }
    private function post2qzone($articles){
        require_class("Mail_mailhelp");
        $mailTo = array();
        $AddAttachment = array();
        array_push($mailTo, array("364054110@qzone.qq.com","helper"));
        array_push($mailTo, array("364054110@qq.com","helper"));
        $mailhelper=new Mail_mailhelp();
        if($articles){
            foreach($articles as $article){
                $subject = "[IT生活]双语阅读:".$article['etitle'];
                $tmpcontent=implode("<p>&nbsp;</p>",$article['contents'][0]);
                $tmpcontent=substr($tmpcontent,0,800)." <a href='http://www.yyabc.org/engchidetail?id={$article['id']}'>阅读全文...</a>";
                $body ="<span style=\"font-size:18px\">{$article['etitle']}---{$article['ctitle']}<p>&nbsp;</p>{$tmpcontent}</span>";
                $mailhelper->sendmail_sunchis_com($mailTo,$subject,$body,$AddAttachment);
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
