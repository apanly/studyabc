<?php
require_class("Dao_TinyEnglish");
class Bll_TinyEnglish
{
    public function save($multidata){
        if(is_array($multidata)){
            $daotiny=new Dao_TinyEnglish();
            foreach($multidata as $rowdata){
                $params=array();
                $params['idate']=strtotime($rowdata['date']);
                $params['md5hash']=md5($rowdata['date'].$rowdata['sen']);
                $params['econtent']=$rowdata['sen'];
                $params['zcontent']=$rowdata['trans'];
                if($rowdata['image']){
                    $rowdata['image']=$this->rewriteImageUri($rowdata['image']);
                    $params['innermguri']=$this->gatherImage($rowdata['image'],$rowdata['date']);
                }
                $params['imguri']=$rowdata['image']?$rowdata['image']:'';
                $params['createtime']=time();
                $daotiny->insert($params);
            }
        }
    }
   private  function rewriteImageUri($url)
    {
        $parts = parse_url($url);
        if(!isset($parts['query'])) {return false;}
        parse_str($parts['query'], $output);
        $uri=$parts['scheme']."://".$parts['host'].$parts['path']."?";
        $uri=$uri."product={$output['product']}&id={$output['id']}&w=260";
        return $uri;
    }
    public function getListPyPage($page,$pageSize=7){
        $daotiny=new Dao_TinyEnglish();
        return $daotiny->getList($page,$pageSize);
    }
    public function getCnt(){
        $daotiny=new Dao_TinyEnglish();
        return $daotiny->getCnt();
    }
    public function getLastestSentence(){
        $daotiny=new Dao_TinyEnglish();
        return $daotiny->getLastestSentence();
    }

    public function updateImageUriByid($id,$imageuri){
        $daotiny=new Dao_TinyEnglish();
        return $daotiny->updateImageUriByid($id,$imageuri);
    }

    public function buildDisplayUri($filename,$date){
        $displayhost=Dispatcher::getInstance()->get_config("cnd_display_host","resource");
        $displaypath=Dispatcher::getInstance()->get_config("cdn_display_path","resource");
        return "http://".$displayhost.$displaypath.$date."/".$filename;
    }

    public function gatherImage($uri,$idate){
        if(!$uri){
            return '';
        }
        $imagepath=Dispatcher::getInstance()->get_config("tinyenglishpic","crontab");
        $filedir=$imagepath.$idate."/";
        if(!file_exists($filedir)){
            mkdir($filedir);
        }

        require_class("Httplib");
        $http=new Httplib();
        $response=$http->get($uri);
        if($response && $response['response']['code']==200){
                $filetype="jpg";
                $rescontenttype=$response['headers']['content-type'];
                if(stripos($rescontenttype,"png")!==false){
                    $filetype="png";
                }
            $filename=md5($uri).".".$filetype;
            $filepath=$filedir.$filename;
            $data=$response['body'];
            file_put_contents($filepath,$data);
            return $filename;
        }
        return '';
    }
}
