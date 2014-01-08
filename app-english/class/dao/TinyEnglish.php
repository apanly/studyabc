<?php
require_class("DB_Factory");
class Dao_TinyEnglish
{
    private $mpdo=null;
    private $_table="tinyenglish";
    protected $_fields = array (
        'id', 'idate', 'md5hash', 'econtent', 'zcontent', 'imguri','createtime','innermguri'
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
        return $this->execute($sql, $params);
    }

    public function getList($page,$pageSize,$params=array()){
        $sql = "SELECT ".$this->getUpperFileds()." FROM ".$this->_table;
        $sql .= " order by id desc limit {$page},{$pageSize}";
        return $this->getAll($sql, $params);
    }
    public function getCnt(){
        $sql = "SELECT count(*) as num FROM ".$this->_table;
        return $this->getColumn($sql, array());
    }

    public function getLastestSentence(){
        $sql = "SELECT ".$this->getUpperFileds()." FROM ".$this->_table;
        $sql .= " order by id desc limit 1";
        return $this->getOne($sql, array());
    }

    public function updateImageUriByid($id,$imguri){
        $sql="UPDATE ".$this->_table." set innermguri=? where id=?";
        return $this->execute($sql, array($imguri,$id));
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
