<?php
require_class("Httplib");
require_class("Bll_EnglishChinese");
class engchi
{
   public function run(){
        $this->getToday();
   }

    private function getToday(){
        $ruri=Dispatcher::getInstance()->get_config("engchi","crontab");
        $http=new Httplib();
        $ruri.="&ts=".time()."&date=".date("Y-m-d");
        $response=$http->get($ruri);
        if($response && $response['response']['code']==200){
            $data = $response['body'];
            $data=json_decode($data,true);
            $articles=$data['articles'];
            $bllengchi=new Bll_EnglishChinese();
            $bllengchi->save($articles);
        }
    }
}

$target=new engchi();
$target->run();

