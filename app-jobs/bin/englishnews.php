<?php
require_class("Httplib");
class englishnews
{
    public function run(){
        global $argv,$argc;
        if($argc<3){
            echo "illegel";
            return;
        }
        $type=$argv[2];
        switch($type){
            case "today":
                $this->getToday();
                break;
            case "init":
                $this->gatherBefore();
                break;

        }
    }

    private function gatherBefore(){
        $ruri=Dispatcher::getInstance()->get_config("newsuri","crontab");
        $http=new Httplib();
        $pages=135;
        for($i=$pages;$i>0;$i--){
            $response=$http->get($ruri.$i);
            if($response && $response['response']['code']==200){
                $data = $response['body'];
                preg_match("/<div\s*class=\"post_list\">(.*?)<div\s*class=\"navigation\">/is", $data, $matches);
                if($matches && $matches[1]){
                    $news=explode('<div class="post_list">',$matches[1]);
                    var_dump($news[0]);exit();
                }

            }
        }
    }
}

$target=new englishnews();
$target->run();
