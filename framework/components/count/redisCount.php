<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-11-23
 * Time: 下午8:42
 * To change this template use File | Settings | File Templates.
 */

class redisCount implements count_db_interface{

    private $db;

    public function __construct($params){
        $this->db = new Memcache;
        $this->db->connect($params['host'], $params['port']);
    }

    //记录
    public function record($arr){
        $key = $this->getKey($arr);
        if($this->inTodayVisit($arr)) $current_value = $this->db->increment($key);
        else $current_value = $this->db->set($key,1);
        if($current_value)
            return $current_value;
        else return false;
    }

    //当日是否已被记录过
    public function inTodayVisit($arr){
        $key = $this->getKey($arr);
        $ret = $this->db->get($key);
        if($ret===false) return false;
        else return true;
    }

    public function getKey($arr){
        return $arr['ip']."|".$arr['url'];
    }
}