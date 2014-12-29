<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-11-12
 * Time: 下午2:47
 * To change this template use File | Settings | File Templates.
 */

class CModel{

    public $db_name = "";

    public function __construct(){
        $this->db = mysqlDB::getInstance($this->db_name);;
        $this->init();
    }

    public function init(){
        return;
    }

    public function __call($class,$arguments){
        return $this->db->$class($arguments);
    }

    public function ping(){
        return $result = $this->db->ping();
    }

    public function query($query){
        return $result = $this->db->query($query);
    }

    public function getRow($query){
        return $result = $this->db->getRow($query);
    }

    public function getAll($query,$key=''){
        return $result = $this->db->getAll($query,$key);
    }

    public function Insert_ID(){
        return $result = $this->db->Insert_ID();
    }

    public function db_close(){
        return $result = $this->db->db_close();
    }

}