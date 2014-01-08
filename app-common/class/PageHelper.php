<?php
class PageHelper{

	public static function static_url($uri){
		$apf=Dispatcher::getInstance();
		$host=@$apf->get_config("cdn_host","resource");
		$path=@$apf->get_config("cdn_path","resource");
		$schema=$apf->get_request()->is_secure()?"https":"http";
		return $host?"$schema://$host$path$uri":"$path$uri";
	}

	public static function pure_static_url($uri){
		$apf=Dispatcher::getInstance();
		$host=@$apf->get_config("cdn_pure_static_host","resource");
		$path=@$apf->get_config("cdn_pure_static_path","resource");
		$schema=$apf->get_request()->is_secure()?"https":"http";
		return $host?"$schema://$host$path$uri":"$path$uri";
	}

	public static function pages_url($uri,$pages=true){
		$apf=Dispatcher::getInstance();
		$base_domain=$apf->get_config("base_domain");
		$schema=$apf->get_request()->is_secure()?"https":"http";
		return $pages?"$schema://pages.{$base_domain}{$uri}":"/pages{$uri}";
	}

	public static function get_base_domain(){
		$apf=Dispatcher::getInstance();
		$base_domain=$apf->get_config("base_domain");
		return $base_domain;
	}

	public static function dfs_pic_display($uri) {
    	$apf = Dispatcher::getInstance();
        $host = @$apf->get_config("dfs_pic_display_host", "resource");
        $schema = $apf->get_request()->is_secure() ? "https" : "http";
        return "$schema://$host$uri";
    }
	public static function dfs_pic_display_by_host($uri,$hostid) {
    	$apf = Dispatcher::getInstance();
        $hostsuffix = @$apf->get_config("dfs_pic_display_host_suffix", "resource");
        $schema = $apf->get_request()->is_secure() ? "https" : "http";
        return "$schema://pic$hostid.$hostsuffix$uri";
    }
}
?>