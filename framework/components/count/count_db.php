<?php

class count_db
{
    private $db;

    public function __construct($config)
    {
        $type = $config['type'];
        switch($type){
            case "redis":
                $this->db = new CRedis();
                break;
            case "memcache":
                $this->db = new CMemcache();
                break;
            default:
                $this->db = new mysqlCount($config);
                break;
        }
    }

    //记录
    public function record($visitInfo)
    {
        return $this->db->record($visitInfo);
    }

    //今日是否已经访问过
    public function inTodayVisit()
    {
        return $this->db->inTodayVisit();
    }

    //统计当天数据并持久化到mysql
    public function countTodayVisit(){
        return $this->db->countTodayVisit();
    }

}