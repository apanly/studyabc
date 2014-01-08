<?php
require_class("Dao_CET");
class Bll_CET
{
    public function save($multidata,$type=1){
        $daocet=new Dao_CET();
        foreach($multidata as $rowdata){
            $params=array();
            $params['cettitle']=$rowdata[1];
            $params['cetdate']=$rowdata[2];
            $params['suri']=$rowdata[0];
            $params['idate']=date("Y-m-d");
            $params['type']=$type;
            $daocet->insert($params);
        }
    }
    public function getListPyPage($type=1,$page,$pageSize=10){
        $daocet=new Dao_CET();
        return $daocet->getList($type,$page,$pageSize);
    }

    public function getListInfo($pageSize=10){
        $daocet=new Dao_CET();
        return $daocet->getListInfo($pageSize);
    }

    public function getInfoById($id){
        $daocet=new Dao_CET();
        return $daocet->getInfoById($id);
    }

    public function getPrevInfoById($id){
        $daocet=new Dao_CET();
        return $daocet->getPrevInfoById($id);
    }

    public function getNextInfoById($id){
        $daocet=new Dao_CET();
        return $daocet->getNextInfoById($id);
    }


    public function update($params,$where){
        $daocet=new Dao_CET();
        return $daocet->update($params,$where);
    }
    public function getCnt($type=1){
        $daocet=new Dao_CET();
        return $daocet->getCnt($type);
    }
}
