<?php
/**
 * Created by 狂神文帝.
 * User: vincent
 * Date: 2/9/14
 * Time: 12:00 AM
 * Site: tech.yyabc.org
 * Email:apanly@163.com
 * QQ:364054110
 */
require_class("Dao_juhua10");
class Bll_juhua10 {
    public function save($data){
        $daojuhua=new Dao_juhua10();
        return $daojuhua->insert($data);
    }

    public function getToday(){
        $daojuhua=new Dao_juhua10();
        $idate=date("Y-m-d");
        return $daojuhua->getTodayJuHua($idate);
    }
} 