<?php
require_class("Httplib");
class youdaotest
{
    public function run()
    {
        $this->init();
    }

    private function init()
    {
        $this->initEnglishTest();exit();
        $ruri = Dispatcher::getInstance()->get_config("tinyenglishuri", "crontab");
        $ruri = $ruri . "?type=test&page=";
        $http = new Httplib();
        $allresult = array();
        for ($i = 18; $i > 0; $i--) {
            echo "============$ruri$i start=============\n";
            $response = $http->get($ruri . $i);
            if ($response && $response['response']['code'] == 200) {
                $data = $response['body'];
                preg_match("/var\s*datas =(.*?)<\/script>/is", $data, $matches);
                if ($matches && $matches[1]) {
                    $result = trim($matches[1]);
                    $result = substr($result, 0, -1);
                    $result = str_replace("'", "\"", $result);
                    $result = str_replace("&#39;", "'", $result);
                    $result = str_replace("type:", "\"type\":", $result);
                    $result = str_replace("date:", "\"date\":", $result);
                    $result = str_replace("question:", "\"question\":", $result);
                    $result = str_replace("A:", "\"A\":", $result);
                    $result = str_replace("B:", "\"B\":", $result);
                    $result = str_replace("C:", "\"C\":", $result);
                    $result = str_replace("D:", "\"D\":", $result);
                    $result = str_replace("right:", "\"right\":", $result);
                    $result = str_replace("tip:", "\"tip\":", $result);
                    $result = json_decode($result, true);
                    if ($result) {
                        foreach($result as $key=>$item){
                            $result[$key]['tip']=urlencode($item['tip']);
                        }
                        $allresult[] = $result;
                        echo "success\n";
                    }
                }
            }
        }
        echo json_encode($allresult);
    }

    private function initEnglishTest(){
        $rootpath=APP_PATH. "../";
        $rs=file_get_contents($rootpath."youdaotest.log");
        $data=json_decode($rs,true);
        $allresult=array();
        foreach($data as $pagerow){
            foreach($pagerow as $key=>$row){
                $allresult[$key]=$row;
            }
        }
        require_class("Bll_EnglishTest");
        $bllenglishtest=new Bll_EnglishTest();
        $bllenglishtest->save($allresult);
    }
}

$target = new youdaotest();
$target->run();
