<?php
require_class("Httplib");
require_class("htmlparser");
require_class("Bll_TinyEnglish");
class TinyEnglishJob{
    public function run(){
         global $argv,$argc;
         if($argc<3){
              echo "illegel";
             return;
         }
         $type=$argv[2];
         switch($type){
             case "sentence":
                 $this->getTodaySentence();
                 break;
             case "init":
                 $this->gatherSentence();
                 break;

         }
    }
    private function getTodaySentence(){
        $ruri=Dispatcher::getInstance()->get_config("tinyenglishuri","crontab");
        $ruri=$ruri."?type=sentence&page=1";
        $http=new Httplib();
        $allresult=array();
        echo "============{$ruri} start=============\n";
        $response=$http->get($ruri);
        if($response && $response['response']['code']==200){
            $data = $response['body'];
            preg_match("/var\s*loaded_data\s*=(.*?)<\/script>/is", $data, $matches);
            if($matches && $matches[1]){
                $result=trim($matches[1]);
                $result=substr($result,0,-1);
                $result=str_replace("'","\"",$result);
                $result=str_replace("&#39;","'",$result);
                $result=str_replace("type:","\"type\":",$result);
                $result=str_replace("date:","\"date\":",$result);
                $result=str_replace("image:","\"image\":",$result);
                $result=str_replace("sen:","\"sen\":",$result);
                $result=str_replace("trans:","\"trans\":",$result);
                $result=str_replace("rel:","\"rel\":",$result);
                $result=json_decode($result,true);
                if($result){
                    $allresult[]=$result;
                    echo "success\n";
                }else{
                    echo "fail\n";
                }
            }
        }else{
            var_dump($response);
        }
        $todayresult=array();
        foreach($allresult as $pagerow){
            foreach($pagerow as $key=>$row){
                $todayresult[$key]=$row;
            }
        }
        $blltinyenglish=new Bll_TinyEnglish();
        $blltinyenglish->save($todayresult);
    }
    private function gatherSentence(){
        $this->initTinyEnglish();exit();
        $ruri=Dispatcher::getInstance()->get_config("tinyenglishuri","crontab");
        $ruri=$ruri."?type=sentence&page=1";
        $http=new Httplib();
        $allresult=array();
        echo "============{$ruri} start=============\n";
        $response=$http->get($ruri.$i);
        if($response && $response['response']['code']==200){
                $data = $response['body'];
                preg_match("/var datas =(.*?)<\/script>/is", $data, $matches);
                if($matches && $matches[1]){
                    $result=trim($matches[1]);
                    $result=substr($result,0,-1);
                    $result=str_replace("'","\"",$result);
                    $result=str_replace("&#39;","'",$result);
                    $result=str_replace("type:","\"type\":",$result);
                    $result=str_replace("date:","\"date\":",$result);
                    $result=str_replace("image:","\"image\":",$result);
                    $result=str_replace("sen:","\"sen\":",$result);
                    $result=str_replace("trans:","\"trans\":",$result);
                    $result=str_replace("rel:","\"rel\":",$result);
                    $result=json_decode($result,true);
                    if($result){
                        $allresult[]=$result;
                        echo "success\n";
                    }
                }
        }
        $this->saveTinyEgnlish($allresult);
    }

    private function saveTinyEgnlish($result){
            echo json_encode($result);
    }

    private function initTinyEnglish(){
        $rootpath=APP_PATH. "../";
        $rs1=file_get_contents($rootpath."result.txt");
        $rs2=file_get_contents($rootpath."result2.txt");
        $data1=json_decode($rs1,true);
        $data2=json_decode($rs2,true);
        $allresult=array();
        foreach($data1 as $pagerow){
            foreach($pagerow as $key=>$row){
                $allresult[$key]=$row;
            }
        }
        foreach($data2 as $pagerow){
            foreach($pagerow as $key=>$row){
                $allresult[$key]=$row;
            }
        }
        Ksort($allresult);
        $blltinyenglish=new Bll_TinyEnglish();
        $blltinyenglish->save($allresult);
    }
}

$target=new TinyEnglishJob();
$target->run();