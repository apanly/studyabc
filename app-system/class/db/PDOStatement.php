<?php
class DB_PDOStatement extends PDOStatement {
    private $i = 0;
    protected $pdo;
	protected function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function execute($input_parameters=NULL) {
        $ret = parent::execute($input_parameters);
        if (!$ret) {
            $error_info = parent::errorInfo();
            if (parent::errorCode() !== '00000') {
                trigger_error($this->pdo->get_name().' | '.$this->pdo->config['dsn'].' | '.$this->queryString.' | '.join(' | ',$error_info),E_USER_ERROR);
            }
        }
        return $ret;
    }
    public function set_i($i){
    	$this->i = intval($i);
    }
}