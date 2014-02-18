<?php
require_class("Component");
class Global_Header_wanheaderComponent extends Component
{
   public function get_view(){
          $params=$this->get_params();
          $this->assign_data("userinfo",$params['userinfo']);
          return "wanheader";
   }
    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array($path."Topbar.css");
    }
}
