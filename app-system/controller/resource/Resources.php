<?php
require_class("Controller");

class Resource_ResourcesController extends Controller{

    const CONFIG_F_RESOURCE='resource';

    const CONFIG_N_VERSION='version';

    const CONFIG_N_PREFIX_URI='prefix_uri';

    const CONFIG_N_RESOURCE_TYPE_SINGLE='resource_type_single';

    const CONFIG_N_RESOURCE_TYPE_BOUNDABLE='resource_type_boundable';

    const DEFAULT_PREFIX_URI='res';

    const DEFAULT_RESOURCE_TYPE_SINGLE='s';

    const DEFAULT_RESOURCE_TYPE_BOUNDABLE='b';

    const CONFIG_N_MATCH_IDX_TYPE="match_type";

    const CONFIG_N_MATCH_IDX_FILE="match_file";

    const CONFIG_N_MATCH_IDX_EXT="match_ext";

    const DEFAILT_MATCH_IDX_TYPE=1;

    const DEFAILT_MATCH_IDX_FILE=2;

    const DEFAILT_MATCH_IDX_EXT=3;

    const URL_FORCE_HASH=false;

    /**
    * Returns the uri of single resource
    *
    * @param string $resource full name of the resource
    * @return string
    */
    public static function build_uri($resource){
        if(preg_match('/:\/\//',$resource)){
            // is external resource
            return $resource;
        }
        $dispatch=Dispatcher::getInstance();
        $version = @$dispatch->get_config(self::CONFIG_N_VERSION,
            self::CONFIG_F_RESOURCE);
        $prefix = @$dispatch->get_config(self::CONFIG_N_PREFIX_URI,
            self::CONFIG_F_RESOURCE);
        if(!isset($prefix)){
            trigger_error("Unable to get config \""
                . self::CONFIG_N_PREFIX_URI."\" from \""
                . self::CONFIG_F_RESOURCE . "\"",
                E_USER_NOTICE
            );
            $prefix=self::DEFAULT_PREFIX_URI;
        }
        $type = @$dispatch->get_config(self::CONFIG_N_RESOURCE_TYPE_SINGLE,
            self::CONFIG_F_RESOURCE);
        if(!isset($type)){
            trigger_error("Unable to get config \""
                . self::CONFIG_N_RESOURCE_TYPE_SINGLE."\" from \""
                . self::CONFIG_F_RESOURCE."\"",
                E_USER_NOTICE
            );
            $type=self::DEFAULT_RESOURCE_TYPE_SINGLE;
        }
        
        if(isset($version)){
            $uri="$prefix/$version/$type/$resource";
        }else{
            $uri="$prefix/$type/$resource";
        }
        if(self::is_use_redis()){
        }else{
            return $uri;
        }
    }

    /**
    * Returns the uri of boundable resource
    *
    * @param string $resource Page name
    * @param string $ext File extention without prefix dot
    * @return string
    */
    public static function build_boundable_uri($resource,$ext){
        $apf=Dispatcher::getInstance();
        $version = @$apf->get_config(self::CONFIG_N_VERSION,
            self::CONFIG_F_RESOURCE);
        $prefix = @$apf->get_config(self::CONFIG_N_PREFIX_URI,
            self::CONFIG_F_RESOURCE);
        if(!isset($prefix)){
            trigger_error("Unable to get config \""
                . self::CONFIG_N_PREFIX_URI."\" from \""
                . self::CONFIG_F_RESOURCE."\"",
                E_USER_ERROR
            );
        }
        $type = $apf->get_config(self::CONFIG_N_RESOURCE_TYPE_BOUNDABLE,
            self::CONFIG_F_RESOURCE);
        if(!isset($type)){
            trigger_error("Unable to get config \""
                . self::CONFIG_N_RESOURCE_TYPE_BOUNDABLE."\" from \""
                . self::CONFIG_F_RESOURCE."\"",
                E_USER_ERROR
            );
        }
    
        if(isset($version)){
            $uri="$prefix/$version/$type/$resource.$ext";
        }else{
            $uri="$prefix/$type/$resource.$ext";
        }

        if(self::is_use_redis()){
            apf_require_class('APF_Cache_Factory');
            $objRedis=APF_Cache_Factory::get_instance()->get_redis('redis');
            $strContentHash=$objRedis->get('url_'.$uri);
            if(false===$strContentHash){
                if(self::URL_FORCE_HASH){ //force hash
                    $o=new APF_Resource_ResourcesController();
                    $strContentHash=$o->url2hash($type,$resource,$ext,$uri,true);
                    if(''==$strContentHash){
                        return $uri;
                    }
                    $type=$apf->get_config('resource_type_hash',self::CONFIG_F_RESOURCE);
                    return $prefix.'/'.$type.'/'.$strContentHash.'.'.$ext;
                }
                return $uri;
            }else{
                $type=$apf->get_config('resource_type_hash',self::CONFIG_F_RESOURCE);
                return $prefix.'/'.$type.'/'.$strContentHash.'.'.$ext;
            }
        }else{
            return $uri;
        }
    }

    /**
     * 资源控制器入口，处理js、css等资源文件，输出资源内容
     * @see Controller::handle_request()
     */
    public function handle_request(){
        $dispatch=Dispatcher::getInstance();
        
        // TODO:use better debug output method
        $request=$dispatch->get_request();
        $response=$dispatch->get_response();
        /**
         * 资源文件请求的url匹配模式，有3个引用
         * 1、资源类型，b、s
         * 2、资源名称
         * 3、资源扩展名，css、js
         * '^/res/[^\/]+/(b|s)/(.*)\.(css|js)$'
         * '^/res/(b|s)/(.*)\.(css|js)$'
         * @var array
         */
        $matches=$request->get_router_matches();
    
        // TODO: get indexes from config
        $idx_type=self::DEFAILT_MATCH_IDX_TYPE;
        $idx_file=self::DEFAILT_MATCH_IDX_FILE;
        $idx_ext=self::DEFAILT_MATCH_IDX_EXT;
        // 资源文件类型
        $type=$matches[$idx_type];
         // 资源文件名称
        $file=$matches[$idx_file];
        $ext=$matches[$idx_ext];

        if('h'==$type){
            if($this->is_use_redis()){
            }
        }
        
        $strRedisURL=$matches[0];
        //设置文件内容类型的http头指令        
        if($ext=='css'){
            $content_type="text/css";
        }elseif($ext=='js'){
            $content_type="application/x-javascript";
        }else{
            trigger_error("Unknown extention \"$ext\"",E_USER_ERROR);
            return;
        }
        // 发送内容类型头指令
        $response->set_content_type($content_type);
        // 如果文档未改动直接返回304
        // 好像没用
        // @todo 再看看其实现
        if($this->try304()){
            return;
        }
        $this->url2hash($type,$file,$ext,$strRedisURL);
    }

    function url2hash($type,$file,$ext,$redisurl,$onlyhash=false){
        $dispatch=Dispatcher::getInstance();
        $request=$dispatch->get_request();
        $response=$dispatch->get_response();
        
        $type_single=self::DEFAULT_RESOURCE_TYPE_SINGLE;
        $type_boundable=self::DEFAULT_RESOURCE_TYPE_BOUNDABLE;
        if($this->is_use_redis()){

        }else{
            if($type==$type_boundable){
                $res=$this->fetch_boundable_resources($file,$ext,TRUE);
                if(!$res){
                    $new_file=$this->try_new_file($file);
                    if($new_file){
                        //301
                        $url="$new_file.$ext";
                        $response->redirect($url,true);
                    }else{
                        //404
                        $response->set_header("HTTP/1.1","404 Not Found","404");
                        return;
                    }
                }
                $this->passthru_boundable_resources();
            }elseif($type==$type_single){
                if(!$this->include_resource_file("$file.$ext")){
                    trigger_error("Unable to include resource \"$file.$ext\"",E_USER_WARNING);
                }
            }
        }
    }
    /**
     * 实际结果就是更新APF->boundable_resources变量，将用到的资源文件（整合）路径信息
     * 记录到boundable_resources中。
     * @todo 非递归实现
     * @param string $class 文件名<->资源文件名与页面类名是一一对应的
     * @param string $ext 文件扩展名
     * @param boolen $is_page 是否为页面类
     */
    protected function fetch_boundable_resources($class,$ext,$is_page=false){
        if($is_page){
            if($pos=strpos($class,"(")){
                $class=substr($class,0,$pos);
            }
            apf_require_page($class);
            $path="page/";
            $class=$class."Page";
        } else { // 载入组件类
            apf_require_component($class);
            $path="component/";
            $class=$class."Component";
        }
        
        if(!class_exists($class)){
            return false;
        }
        // 获取组件（页面）使用的组件列表
        eval("\$list = $class"."::use_component();");
        foreach ($list as $item) { // 载入所有子组件用到的资源文件
            $this->fetch_boundable_resources($item,$ext);
        }
        // 获取用到的资源文件相对路径
        if($ext=='js'){
            eval("\$list = $class::use_boundable_javascripts();");
        }elseif($ext=='css'){
            eval("\$list = $class::use_boundable_styles();");
        }else{
            trigger_error("Unknown extention \"$ext\"",E_USER_WARNING);
            $list=array();
        }
        
        $apf=Dispatcher::getInstance();

        foreach($list as $item) { // 获取资源文件的绝对路径
            $apf->prcess_resource_url($path,$item,$this->boundable_resources);
        }
        
        return true;
    
    }
    /**
     * 按顺序输出资源文件内容，多个资源文件合并为一个。
     */
    protected function passthru_boundable_resources(){
        if($this->boundable_resources){
            usort($this->boundable_resources,
                "APF::resource_order_comparator");
        }
        
        if(!is_array($this->boundable_resources)){
            return;
        }
        foreach($this->boundable_resources as $item){
            if(!$this->include_resource_file($item[0])){
                trigger_error("Unable to include resource \""
                    . $item[0]."\"",
                    E_USER_WARNING
                );
            }
        }
    }
    /**
     * 记录bundable类型的资源
     * @var array
     */
    private $boundable_resources;
    /**
     * 载入（执行）资源文件
     * @param string $file 文件名
     * @param string $path 文件路径
     */
    protected function include_resource_file($file,$path=NULL){
        if(isset($path)){
            if(file_exists($path.$file)){
                include_once ($path.$file);
                return true;
            }
        }else{
            global $G_LOAD_PATH;
            foreach($G_LOAD_PATH as $path){
                if(file_exists($path.$file)){
                    include_once ($path.$file);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 是否发送304未改动指令
    * @return boolean
    */
    protected function try304(){
        // 返回304需要定义发布版本
        if(!defined("RELEASE_VERSION")){
            return false;
        }
        
        $apf=Dispatcher::getInstance();
        $response=$apf->get_response();
        
        $last_modified=strtotime("2009-06-04 00:00:00");
        
        if(isset($last_modified)){
        $etag='"'.dechex($last_modified).'"';
        }
        
        if(isset($etag)){
            $none_match=@$_SERVER['HTTP_IF_NONE_MATCH'];
                if($none_match&&$none_match==$etag){
                $response->set_header("HTTP/1.1","304 ETag Matched","304");
                return true;
            }
        }
        
        if(isset($last_modified)&&isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
            $tmp=explode(";",$_SERVER['HTTP_IF_MODIFIED_SINCE']);
            $modified_since=@strtotime($tmp[0]);
            if($modified_since&&$modified_since>=$last_modified){
                $response->set_header("HTTP/1.1","304 Not Modified","304");
                return true;
            }
        }
        
        if(isset($last_modified)){
            $response->set_header("ETag",$etag);
            $response->set_header("Last-Modified",
                gmdate("D, d M Y H:i:s", $last_modified) . " GMT");
        }
        
        return false;
    }

    public function try_new_file($class){
        global $G_LOAD_PATH;
        $file=apf_classname_to_filename($class);
        $position=strrpos($file,"/");
        $pathmid="page/".substr($file,0,$position);
        $fileName=substr($file,$position+1);
        foreach($G_LOAD_PATH as $pathpre){
            $files=glob("$pathpre$pathmid/*.php");
            foreach($files as $tmpfile){
                $pos1=strrpos($tmpfile,"/")+1;
                $pos2=strrpos($tmpfile,".");
                $trueName=substr($tmpfile,$pos1,$pos2-$pos1);
                if(strtolower($fileName)==strtolower($trueName)){
                    $newClass = substr_replace($class,
                        $trueName,
                        strrpos($class,"_")+1);
                    return $newClass;
                }
            }
        }
        return null;
    }
    
    public function is_use_redis() {
        if(class_exists("redis") && Dispatcher::getInstance()->get_config("redis", "cache")){
            return true;
        } else {
            return false;
        }
    } 
}
