<?php
$default_fetch_mode = PDO::FETCH_ASSOC;
$config['master']=array(
 'dsn'=>'mysql:host=localhost;dbname=appenglish',
 'username'=>'root',
 'password'=>'root',
 'init_attributes' => array(),
 'init_statements' => array('SET CHARACTER SET utf8','SET NAMES utf8'),
 'default_fetch_mode' => $default_fetch_mode
);