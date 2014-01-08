<?php
require_class("Bll_TinyEnglish");
class imageInner
{
        public function run(){
            $bll=new Bll_TinyEnglish();
            $i=1;
            while(true){
                $page=($i-1)*7;
                echo "page:".$page."\n";
                $data=$bll->getListPyPage($page);
                foreach($data as $item){
                    if($item['IMGURI'] && preg_match("/^http/",$item['IMGURI']) && $item['INNERMGURI']=="''"){
                        $uri=$bll->gatherImage($item['IMGURI'],date("Y-m-d",$item['IDATE']));
                        $bll->updateImageUriByid($item['ID'],$uri);
                        echo $item['ID']."=====".$uri."\n";
                    }
                }
                if(count($data)<7){
                    break;
                }
                $i++;
                sleep(2);
            }
            echo "over";
        }
}

$target= new imageInner();
$target->run();
