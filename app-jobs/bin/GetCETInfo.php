<?php
require_class("Httplib");
require_class("Bll_CET");
require_class("Bll_CETDetail");
class GetCETInfo
{
    public function run(){
        $bllcet=new Bll_CET();
        $bllcetdetail=new Bll_CETDetail();
        $info=$bllcet->getListInfo(100);
        if($info){
            foreach($info as $item){
                echo $item['ID'].'--'.$item['SURI']."\n";
                $tmpdesc=$this->getInfo($item['SURI']);
                //$tmpdesc=json_encode($tmpdesc,JSON_UNESCAPED_UNICODE);
                $tmpdesc=json_encode($tmpdesc);
                $bllcetdetail->save(array($item['ID'],$tmpdesc));
                $bllcet->update(
                    array("has_desc"=>1),
                    " id={$item['ID']}"
                );
            }
        }
    }
    public function getInfo($uri){
        $http = new Httplib();
        $response = $http->get($uri);
        if ($response && $response['response']['code'] == 200) {
            $data = $response['body'];
            $data = preg_replace("/<!--(.*?)-->/is", "", $data);
            $data = preg_replace("/<script(.*?)<\/script>/is", "", $data);
            preg_match("/<div\s*id=\"arctext\"\s*class=\"center\">(.*?)<div\s*id=\"yousend\">/is", $data, $matches);
            if ($matches && $matches[1]) {
                $data=$matches[1];
                $data=preg_replace("/<div\s*style=\"padding-(.*?)<\/div>/is","",$data);
                $data=explode("\n",$data);
                $result=array();
                foreach($data as $key=>$item){
                    if($key>=3){
                        $item=$this->filter_replace($item);
                        if(stripos($item,"<img")!==false){
                            //preg_match_all("/src=\"(.*)\"/is",$item,$tmpmatch);
                            preg_match_all("/<img\s*src\s*=\s*[\"|\']?\s*([^>\"\'\s]*)/is", $item,$tmpmatch);
                            $tmpitem=$tmpmatch[1];
                            if(!is_array($tmpitem)){
                                $tmp=array($tmpitem);
                            }else{
                                $tmp=$tmpitem;
                            }
                            foreach($tmp as $tmpkey=>$tmpsubitem){
                                if(!$this->isFullLink(trim($tmpsubitem))){
                                    $tmpsubitem="http://www.hxen.com".trim($tmpsubitem);
                                }else{
                                    $tmpsubitem=trim($tmpsubitem);
                                }
                                if(stripos($tmpsubitem,"downfile.jpg")!==false || stripos($tmpsubitem,"www.wmmenglish.com")!==false){
                                    $tmpsubitem=trim(preg_replace("/<(.*?)>/is","",$item));
                                }
                                $tmp[$tmpkey]=$tmpsubitem;
                            }
                        }else{
                            $tmp=trim(preg_replace("/<(.*?)>/is","",$item));
                        }
                        if(!$tmp){
                            continue;
                        }
                        if(is_array($tmp)){
                            foreach($tmp as $tmpsubitem){
                                $tmpsubitem=str_replace("%%%%%%%#####","<br/>",$tmpsubitem);
                                $result[]=$tmpsubitem;
                            }
                        }else{
                            $tmp=str_replace("%%%%%%%#####","<br/>",$tmp);
                            $result[]=$tmp;
                        }
                        if(stripos($item,'<div class="endPageNum"><div class="epages">')!==false){
                            break;
                        }
                    }
                }
                $pagenation=array_pop($result);
                $tmppage=explode("&nbsp;",$pagenation);
                $pagecnt=trim(substr($tmppage[1],stripos($tmppage[1],"/")+1));
                if($pagecnt){
                    for($i=2;$i<=$pagecnt;$i++){
                        $tmpuri=str_replace(".html","_{$i}.html",$uri);
                        $result=array_merge($result,$this->getPagenation($tmpuri));
                    }
                }
                return $result;
            }
        }
        return null;
    }
    private function getPagenation($uri){
        $http = new Httplib();
        $response = $http->get($uri);
        if ($response && $response['response']['code'] == 200) {
            $data = $response['body'];
            $data = preg_replace("/<!--(.*?)-->/is", "", $data);
            $data = preg_replace("/<script(.*?)<\/script>/is", "", $data);
            preg_match("/<div\s*id=\"arctext\"\s*class=\"center\">(.*?)<div\s*id=\"yousend\">/is", $data, $matches);
            if ($matches && $matches[1]) {
                $data=$matches[1];
                $data=preg_replace("/<div\s*style=\"padding-(.*?)<\/div>/is","",$data);
                $data=explode("\n",$data);
                $result=array();
                foreach($data as $key=>$item){
                    if($key>=3){
                        $item=$this->filter_replace($item);
                        if(stripos($item,"<img")!==false){
                            preg_match_all("/<img\s*src\s*=\s*[\"|\']?\s*([^>\"\'\s]*)/is", $item,$tmpmatch);
                            $tmpitem=$tmpmatch[1];
                            if(!is_array($tmpitem)){
                                $tmp=array($tmpitem);
                            }else{
                                $tmp=$tmpitem;
                            }
                            foreach($tmp as $tmpkey=>$tmpsubitem){
                                if(!$this->isFullLink(trim($tmpsubitem))){
                                    $tmpsubitem="http://www.hxen.com".trim($tmpsubitem);
                                }else{
                                    $tmpsubitem=trim($tmpsubitem);
                                }
                                if(stripos($tmpsubitem,"downfile.jpg")!==false || stripos($tmpsubitem,"www.wmmenglish.com")!==false){
                                    $tmpsubitem=trim(preg_replace("/<(.*?)>/is","",$item));
                                }
                                $tmp[$tmpkey]=$tmpsubitem;
                            }
                        }else{
                            $tmp=trim(preg_replace("/<(.*?)>/is","",$item));
                        }
                        if(!$tmp){
                            continue;
                        }
                        if(stripos($item,'<div class="endPageNum"><div class="epages">')!==false){
                            break;
                        }
                        if(is_array($tmp)){
                            foreach($tmp as $tmpsubitem){
                                $tmpsubitem=str_replace("%%%%%%%#####","<br/>",$tmpsubitem);
                                $result[]=$tmpsubitem;
                            }
                        }else{
                            $tmp=str_replace("%%%%%%%#####","<br/>",$tmp);
                            $result[]=$tmp;
                        }
                    }
                }
               return $result;
            }
        }
    }

    private function filter_replace($str){
        $str=preg_replace("/<br\s*\/?>/is","%%%%%%%#####",$str);
        $str=preg_replace("/<p\s*\/?>/is","%%%%%%%#####",$str);
        $str=preg_replace("/<\/?\s*p>/is","%%%%%%%#####",$str);
        return $str;
    }

    private function isFullLink($uri){
        if(stripos($uri,"http")===false){
            return false;
        }
        return true;
    }
}
$target =new GetCETInfo();
$target->run();