<?php
require_class("Component");
class Global_Page_pgnationComponent extends Component
{
    public function get_view(){
        $params=$this->get_params();
        $pageinfo=$params['pageinfo'];
        $this->assign_data("frompage",$pageinfo['frompage']);
        $this->assign_data("endpage",$pageinfo['endpage']);
        $this->assign_data("uri",$pageinfo['uri']);
        $this->assign_data("page",$pageinfo['page']);
        $this->assign_data("pagecnt",$pageinfo['pageCnt']);
        return "pgnation";
    }
}
