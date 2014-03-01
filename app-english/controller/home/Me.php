<?php
/**
 * Created by 狂神文帝.
 * User: vincent
 * Date: 2/28/14
 * Time: 10:50 PM
 * Site: tech.yyabc.org
 * Email:apanly@163.com
 * QQ:364054110
 */
require_class("Controller");
class Home_MeController extends Controller
{
    public function handle_request(){
        $req=Dispatcher::getInstance()->get_request();
        $cdn_host=Dispatcher::getInstance()->get_config("cdnstatic_host","resource");
        $cdn_path=Dispatcher::getInstance()->get_config("cdnstatic_path","resource");
        $followwx="http://{$cdn_host}{$cdn_path}images/weixin/follow.jpeg";
        $req->set_attribute("wxfollowimage",$followwx);
        return "Home_Me";
    }
}
