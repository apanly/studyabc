<?php
require_class("Component");
class Global_Header_TopbarComponent extends Component
{
   public function get_view(){
          $params=$this->get_params();
          $this->assign_data("action",$params['action']);
          $this->assign_data("type",$params['type']);
          $this->assign_data("userinfo",$params['userinfo']);
          return "Topbar";
   }
    public static function use_boundable_styles(){
        $path = classname_to_path(__CLASS__);
        return array($path."Topbar.css");
    }
}
