<?php
function __autoload($classname){

}

function require_class($class, $prefix="class" , $firelog = true){
    if($prefix=="class" && class_exists($class)){
        return true;
    }
    $file = classname_to_filename($class);
    $flag = true;
    $filename=$file.".php";
    if (!require_file($filename, $prefix)) {
        return false;
    }
    return $flag;
}


function classname_to_filename($class , $explode = '_') {
    $paths = explode($explode, $class);
    $count = count($paths) - 1;
    $path = "";
    for ($i = 0; $i < $count; $i++) {
        $path .= strtolower($paths[$i]) . "/";
    }
    $class = $paths[$count];
    return "$path$class";
}


/**
 * 导入文件，对php原生require_once的封装和扩展
 * @param string $file 文件名
 * @param string $prefix 上一级目录
 * @return boolean
 */
function require_file($file, $prefix="lib") {
    global $G_LOAD_PATH;
    foreach ($G_LOAD_PATH as $path) {
        if (file_exists("$path$prefix/$file")) {
            if (!required_files($file,$prefix)) {
                require_once("$path$prefix/$file");
            }
            return true;
        }
    }
    return false;
}

function required_files ($file,$prefix) {
    global $cached_files;
    $f = $prefix . "/" . $file;
    if (in_array($f,$cached_files)) {
        return true;
    } else {
        $cached_files[] = $f;
    }
    return false;
}

/**
 * 导入控制器，require_class的简单封装。
 * @param string $class 类名
 * @param string $firelog 日志开关
 * @return boolean
 */
function require_controller($class, $firelog=true) {
    if(class_exists($class."Controller")){
        return true;
    }
    return require_class($class, "controller" , $firelog);
}

/**
 * 导入页面，require_class的简单封装。
 * @param string $class 类名
 * @return boolean
 */
function require_page($class) {
    if(class_exists($class."View")){
        return true;
    }
    return require_class($class, "view");
}

/**
 * 导入v2组件，apf_require_class的简单封装。
 * @param string $class 类名
 * @return boolean
 */
function require_component($class) {
    if(class_exists($class."Component")){
        return true;
    }
    return require_class($class, "component");
}
/**
 * 类名转换成文件路径
 * 例如V2b_Solr_Property则返回v2b/solr/
 * @param string $class Class name
 * @return string Relative path
 */
function classname_to_path($class) {
    $paths = @split("_", $class);
    $count = count($paths) - 1;
    $path = "";
    for ($i = 0; $i < $count; $i++) {
        $path .= strtolower($paths[$i]) . "/";
    }
    return $path;
}

/**
 * 自动转换字符集 支持数组转换
 *
 * @param string $from
 * @param string $to
 * @param mixed  $data
 * @return mixed
 */
function iconvs($from, $to, $data) {
    $from = strtoupper($from) == 'UTF8' ? 'UTF-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'UTF-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($data) || (is_scalar($data) && !is_string($data))) {
        //如果编码相同或者非字符串标量则不转换
        return $data;
    }
    if (is_string($data)) {
        if (function_exists('iconv')) {
            $to = substr($to, -8) == '//IGNORE' ? $to : $to . '//IGNORE';
            return iconv($from, $to, $data);
        } elseif (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($data, $to, $from);
        } else {
            return $data;
        }
    }
    elseif (is_array($data)) {
        foreach ($data as $key => $val) {
            $_key = iconvs($from, $to, $key);
            $data[$_key] = iconvs($from, $to, $val);
            if ($key != $_key) {
                unset($data[$key]);
            }
        }
        return $data;
    }
    else {
        return $data;
    }
}

function customError($errno, $errstr, $errfile, $errline)
{
    $tmp= "<b>Custom error:</b> [$errno] $errstr<br />";
    $tmp.= " Error on line $errline in $errfile<br />";
    customlog($tmp);
}

function customException($e){
    $tmp= "Exception:".$e->getMessage();
    $tmp.=var_export(debug_backtrace(),true);
    customlog($tmp);
}
function upf_error($error, $errno = 500) {
    throw new Exception($error, $errno);
}
function customlog($message){
    openlog("phpsimple", LOG_PID, LOG_USER);
    syslog(LOG_INFO, $message);
    closelog();
}