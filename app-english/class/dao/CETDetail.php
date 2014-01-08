<?php
require_class("DB_Factory");
class Dao_CETDetail
{
    private $mpdo=null;
    private $_table="cetdesc";
    protected $_fields = array (
        'id', 'content','idate'
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
        $sql = "INSERT INTO ".$this->_table." (".implode(',', $fields).") VALUES (".implode(',', $mode).") ON DUPLICATE KEY UPDATE  content=?";
        $params[]=$data['content'];
        $this->execute($sql, $params);
        return $this->mpdo->lastInsertId();
    }

    public function getInfoById($id){
        $sql = "SELECT ".$this->getUpperFileds()." FROM ".$this->_table;
        $params=array();
        $sql .= " WHERE id={$id}";
        return $this->getOne($sql,$params);
    }



    public function getListInfo($pageSize){
        $sql = "SELECT ".$this->getUpperFileds()." FROM ".$this->_table;
        $params=array();
        $sql .= " WHERE has_desc=0 order by id desc limit 0,{$pageSize}";
        return $this->getAll($sql,$params);
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
    function getColumn($sql, $params) {
        $sth = $this->mpdo->prepare($sql);
        $sth->execute($params);
        return $sth->fetchColumn();
    }
    private function execute($sql,$params){
        $sth = $this->mpdo->prepare($sql);
        $sth->execute($params);
        return $sth->rowCount();
    }
}
