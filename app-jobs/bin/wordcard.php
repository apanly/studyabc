<?php
require_class("Httplib");
require_class("Bll_WordCard");
class wordcard
{
   public function run(){
        $this->getToday();
   }

    /**
     * ori_iamge  :  http://oimagec1.ydstatic.com/image?product=dict-treasury&id=2688242839290087292&w=400&h=340
     */
    private function getToday(){
        $ruri=Dispatcher::getInstance()->get_config("wordcard","crontab");
        $http=new Httplib();
        $response=$http->get($ruri);
        if($response && $response['response']['code']==200){
            $data = $response['body'];
            $data=json_decode($data,true);
            if($data){
                $bllwordcard=new Bll_WordCard();
                foreach($data as $item){
                    $celldata=array();
                    $celldata['word']=$item['word'];
                    $tmpextend=array(
                        'examplesEn'=>$item['examplesEn'],
                        'examplesZh'=>$item['examplesZh'],
                        'trans'=>$item['trans'],
                        'oriImgId'=>$item['oriImgId'],
                        'pron'=>$item['pron']
                    );
                    $celldata['extendjson']=json_encode($tmpextend);
                    $celldata['idate']=$item['date'];
                    $bllwordcard->addWord($celldata);
                }
            }

        }
    }
}

$target=new wordcard();
$target->run();

