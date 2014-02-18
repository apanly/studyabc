<?php
/**
 * Created by 狂神文帝.
 * User: vincent
 * Date: 2/17/14
 * Time: 10:08 PM
 * Site: tech.yyabc.org
 * Email:apanly@163.com
 * QQ:364054110
 */
require_class("Httplib");
require_class("Bll_juhua10");
class juhua10 {
    public function run(){
        $data=$this->get10juhua();
        if($data){
            $idate=date("Y-m-d");
            $blljuhua=new Bll_juhua10();
            foreach($data as $item){
               $paramdata=array();
               $inneruri=$this->gatherImage($item[1],$idate);
               $paramdata['content']=$item[0];
               $paramdata['idate']=$idate;
               $paramdata['picuri']=$inneruri;
               $blljuhua->save($paramdata);
            }
        }
    }

    private function get10juhua(){
        $returnval=array();
        $uri="http://10juhua.com/";
        $http=new Httplib();
        $response=$http->get($uri);
        if($response && $response['response']['code']==200){
            $data = $response['body'];
            preg_match("/<div\s*id=\"left\">(.*?)<div\s*id=\"right\">/is", $data, $matches);
            if ($matches && $matches[1]) {
                $data=$matches[1];
                $data=preg_replace("/<div\s*class=\"head\">(.*?)<\/div>/is","",$data);
                $data=preg_replace("/<div\s*class=\"share\">(.*?)<\/div>/is","",$data);
                preg_match_all("/<div\s*class=\"body\">(.*?)<\/div>/is", $data,$tmpmatch);
                if($tmpmatch && $tmpmatch[1]){
                    $tmpdata=$tmpmatch[1];
                    foreach($tmpdata as $tmpitem){
                        $brindex=stripos($tmpitem,"<br/>");
                        $tmpdesc=substr($tmpitem,0,$brindex);
                        $srcindex=stripos($tmpitem,"src=");
                        $tmpsrc=substr($tmpitem,$srcindex+5,-2);
                        $returnval[]=array(
                            $tmpdesc,$tmpsrc
                        );
                    }
                }
            }
        }
        return $returnval;
    }

    private  function gatherImage($uri,$idate){
        if(!$uri){
            return '';
        }
        $imagepath=Dispatcher::getInstance()->get_config("cdn_local_path","resource");
        $filedir=$imagepath."/".$idate."/";
        if(!file_exists($filedir)){
            mkdir($filedir);
        }

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
            if(!file_exists($filepath)){
                $data=$response['body'];
                file_put_contents($filepath,$data);
            }
            return $filename;
        }
        return '';
    }
}

$target=new juhua10();
$target->run();