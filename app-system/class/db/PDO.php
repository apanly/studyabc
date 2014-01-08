<?php
require_class("DB_PDOStatement");
class DB_PDO extends PDO
{
    private $i = 0;
    public function __construct($dsn, $username="", $password="", $driver_options=array()) {
        parent::__construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('DB_PDOStatement', array($this)));
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    }
    public function exec($statement) {
        $stmt = parent::exec($statement);
        if ($stmt instanceof PDOStatement) {
            $stmt->setFetchMode($this->default_fetch_mode);
        } else {
            $error_info = parent::errorInfo();
            if (parent::errorCode() !== '00000') {
                trigger_error($this->get_name().' | '.$this->config['dsn'].' | '.$statement.' | '.join(' | ',$error_info),E_USER_ERROR);
            }

        }
        return $stmt;
    }

    private $_sql = null;
    public function prepare($statement, $driver_options=array()) {
        $stmt = parent::prepare($statement, $driver_options);
        if ($stmt instanceof PDOStatement) {
            $stmt->setFetchMode($this->default_fetch_mode);
        }
        $stmt->set_i($this->i);
        $this->i++;
        $stmt->_sql = $statement;
        return $stmt;
    }

    public function query($statement, $pdo=NULL, $object=NULL) {
        if($pdo != NULL && $object != NULL){
            $stmt = parent::query($statement, $pdo, $object);
        }else{
            $stmt = parent::query($statement);
        }
        if ($stmt instanceof PDOStatement) {
            $stmt->setFetchMode($this->default_fetch_mode);
        }
        return $stmt;
    }

    public function set_name($name) {
        $this->name = $name;
        $this->config = Dispatcher::getInstance()->get_config($name, "database");
    }

    public function get_name() {
        return $this->name;
    }

    private $name;

    public $config;

    public function set_default_fetch_mode($mode) {
        $this->default_fetch_mode = $mode;
    }

    private $default_fetch_mode = PDO::FETCH_BOTH;
}
