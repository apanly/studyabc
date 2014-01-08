<?php
require_class("DB_Factory");
class Dao_EngChiDetail
{
    private $mpdo=null;
    private $_table="articles_content";
    protected $_fields = array (
        'id', 'fid', 'englishjson','chinesejson','idate'
    );
    public function __construct(){
        $this->mpdo=DB_Factory::get_instance()->get_pdo("master");
    }
    function getUpperFileds() {
        return strtoupper(implode(',', $this->_fields));
    }

    function getLowerFileds() {
        return strtolower(implode(',', $this->_fields));
    }
    public function insert($data){
        $fields = $params = $mode = array();
        foreach($data as $key=>$row) {
            $fields[] = $key;
            $params[] = $row;
            $mode[] = '?';
        }
        $sql = "INSERT INTO ".$this->_table." (".implode(',', $fields).") VALUES (".implode(',', $mode).")";
        $this->execute($sql, $params);
        return $this->mpdo->lastInsertId();
    }
    public function getDetailByFid($id){
        $sql = "SELECT ".$this->getUpperFileds()." FROM ".$this->_table;
        $sql .= " where fid=?";
        return $this->getOne($sql, array($id));
    }
    /**
     * 返回全部记录
     * @param $sql
     * @param $params
     */
    function getAll($sql, $params) {
        $sth = $this->mpdo->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll();
    }

    function getOne($sql, $params) {
        $sth = $this->mpdo->prepare($sql);
        $sth->execute($params);
        return $sth->fetch();
    }
    private function execute($sql,$params){
        $sth = $this->mpdo->prepare($sql);
        $sth->execute($params);
        return $sth->rowCount();
    }
}
