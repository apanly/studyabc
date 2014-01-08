<?php
require_class("Dao_CETDetail");
class Bll_CETDetail
{
    public function save($rowdata){
        $daocet=new Dao_CETDetail();
        $params=array();
        $params['id']=$rowdata[0];
        $params['content']=$rowdata[1];
        $params['idate']=date("Y-m-d");
        $daocet->insert($params);
    }

    public function getInfoById($id){
        $daocet=new Dao_CETDetail();
        return $daocet->getInfoById($id);
    }
}
