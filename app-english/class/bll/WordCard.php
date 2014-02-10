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
require_class("Dao_WordCard");
class Bll_WordCard {
    public function addWord($data){
        $daowordcard=new Dao_WordCard();
        return $daowordcard->insert($data);
    }
} 