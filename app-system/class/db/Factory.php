<?php
class DB_Factory {
    private $pdo_class = "DB_PDO";
    public $pdo_list = array();
    private static $instance;
    public static function &get_instance() {
        if (!self::$instance) {
            self::$instance = new DB_Factory();
        }
        return self::$instance;
    }
    /**
     * Returns pdo instance by given name. only one instance of pdo will be created for one name.
     *
     * @param string $name
     * @return DB_PDO
     */
    public function get_pdo($name="default") {
        if (!isset($this->pdo_list[$name])) {
            $this->pdo_list[$name] = $this->load_pdo($name);
        }
        return $this->pdo_list[$name];
    }

    /**
     * Returns new instance of pdo
     * @param string $name
     * @return  DB_PDO
     */
    public function load_pdo($name="default") {
        $dispatch = Dispatcher::getInstance();
        $dbcfg = $dispatch->get_config($name, "database");
        require_class($this->pdo_class);
        $pdo = new $this->pdo_class(
            $dbcfg['dsn'],
            @$dbcfg['username'],
            @$dbcfg['password'],
            isset($dbcfg['driver_options']) ? $dbcfg['driver_options'] : array());
        $pdo->set_name($name);
        if (isset($dbcfg['default_fetch_mode'])) {
            $pdo->set_default_fetch_mode($dbcfg['default_fetch_mode']);
        }
        if (isset($dbcfg['init_statements'])) {
            foreach ($dbcfg['init_statements'] as $sql) {
                $pdo->exec($sql);
            }
        }
        return $pdo;
    }

    public function close_pdo($name="default") {
        if (!isset($this->pdo_list[$name])) {
            return;
        }
        unset($this->pdo_list[$name]);
    }

    public function close_pdo_all() {
        if(!empty($this->pdo_list)) {
            foreach(array_keys($this->pdo_list) as $name) {
                unset($this->pdo_list[$name]);
            }
        }
    }

    private function set_pdo_class($class) {
        $this->pdo_class = $class;
    }
}
