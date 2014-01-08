<?php
require_class("Dao_EngChiDetail");
class Bll_EngChiDetail
{
        public function getDetailByFid($id){
            $daoecdetail=new Dao_EngChiDetail();
            return $daoecdetail->getDetailByFid($id);
        }
}
