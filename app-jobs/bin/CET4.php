<?php
require_class("Httplib");
class CET4
{
    //private $uri="http://www.hxen.com/CET46/CET4/zhenti/";
    private $uri = "http://www.hxen.com/CET46/CET6/zhenti/";

    public function run()
    {
        $this->getCET6Info();
        //$this->getCET4Info();
    }

    private function getCET6Info()
    {
        $this->dealInfo("cet6.txt");
        exit();
        $pages = 3;
        $uri = $this->uri;
        $http = new Httplib();
        $result = array();
        for ($i = $pages; $i > 0; $i--) {
            if ($i != 1) {
                $ruri = $uri . "index_{$i}.html";
            } else {
                $ruri = $uri . "index.html";
            }

            $response = $http->get($ruri);
            if ($response && $response['response']['code'] == 200) {
                $data = $response['body'];
                $data = preg_replace("/<!--(.*)-->/", "", $data);
                preg_match("/<\/div>\s*<ul>(.*?)<\/ul>\s*<div\s*class=\"leftSum\">/is", $data, $matches);
                if ($matches && $matches[1]) {
                    $list = trim($matches[1]);
                    $list = explode("</li>", $list);
                    $list = array_reverse($list);
                    foreach ($list as $val) {
                        if ($val) {
                            $result[] = $val;
                        }
                    }
                }
            }
        }
        $rootpath = APP_PATH . "../";
        file_put_contents($rootpath . "cet6.txt", json_encode($result));
    }

    private function getCET4Info()
    {
        $this->dealInfo();
        exit();
        $pages = 4;
        $uri = $this->uri;
        $http = new Httplib();
        $result = array();
        for ($i = $pages; $i > 0; $i--) {
            if ($i != 1) {
                $ruri = $uri . "index_{$i}.html";
            } else {
                $ruri = $uri . "index.html";
            }

            $response = $http->get($ruri);
            if ($response && $response['response']['code'] == 200) {
                $data = $response['body'];
                $data = preg_replace("/<!--(.*)-->/", "", $data);
                preg_match("/<\/div>\s*<ul>(.*?)<\/ul>\s*<div\s*class=\"leftSum\">/is", $data, $matches);
                if ($matches && $matches[1]) {
                    $list = trim($matches[1]);
                    $list = explode("</li>", $list);
                    $list = array_reverse($list);
                    foreach ($list as $val) {
                        if ($val) {
                            $result[] = $val;
                        }
                    }
                }
            }
        }
        $rootpath = APP_PATH . "../";
        file_put_contents($rootpath . "cet4.txt", json_encode($result));
    }

    private function dealInfo($file="cet4.txt")
    {
        $rootpath = APP_PATH . "../";
        $list = file_get_contents($rootpath . $file);
        $list = json_decode($list, true);
        $result = array();
        foreach ($list as $item) {
            $tmp = array();
            preg_match("/href=\"(.*?)\"/is", $item, $matches);
            if ($matches && $matches[1]) {
                $tmp[] = "http://www.hxen.com" . $matches[1];
            }
            preg_match("/target=\"_blank\">(.*?)<\/a>/is", $item, $matches);
            if ($matches && $matches[1]) {
                $tmp[] = $matches[1];
            }
            preg_match("/class=\"c1\">\((.*?)\)<\/span>/is", $item, $matches);
            if ($matches && $matches[1]) {
                $tmp[] = $matches[1];
            }
            $result[] = $tmp;
        }
        require_class("Bll_CET");
        $bllcet = new Bll_CET();
        if($file=="cet6.txt"){
            $bllcet->save($result,2);
        }
    }
}

$target = new CET4();
$target->run();