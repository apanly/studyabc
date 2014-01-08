<?php

abstract class Controller
{
    protected $vars = array();
    public function __construct() {
    }

    public function __destruct() {
    }

    public function get_interceptor_index_name () {
        return __CLASS__;
    }

    abstract public function handle_request();

}
