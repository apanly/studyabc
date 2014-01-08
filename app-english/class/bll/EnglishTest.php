<?php
require_class("Dao_EnglishTest");
class Bll_EnglishTest
{
    public function save($multidata){
        if(is_array($multidata)){
            $daoenglishtest=new Dao_EnglishTest();
            foreach($multidata as $rowdata){
                $params=array();
                $params['pdate']=$rowdata['date'];
                $params['questionjson']=json_encode(array(
                    "question"=>$rowdata['question'],
                    "choices"=>array(
                        'A'=>$rowdata['A'],
                        'B'=>$rowdata['B'],
                        'C'=>$rowdata['C'],
                        'D'=>$rowdata['D']
                    ),
                    "right"=>$rowdata['right'],
                    "tip"=>$rowdata['tip']
                ));
                $params['idate']=date("Y-m-d");
                $daoenglishtest->insert($params);
            }
        }
    }

    public function getLastestSentence(){
        $daoenglishtest=new Dao_EnglishTest();
        return $daoenglishtest->getLastestenglishtest();
    }

    public function getListPyPage($page,$pageSize=7){
        $daoenglishtest=new Dao_EnglishTest();
        return $daoenglishtest->getList($page,$pageSize);
    }

    public function getCnt(){
        $daoenglishtest=new Dao_EnglishTest();
        return $daoenglishtest->getCnt();
    }
}
