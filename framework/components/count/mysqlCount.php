<?php
/**
 * model{
 *  ip
 *  url
 *  user_agent
 *  last_time
 *  hits
 * primary key (ip,url)
 * key ip
 * key url
 * }
 */

class mysqlCount implements count_db_interface{

    private $db;
    private $table_name;

    public function __construct($params){
        $this->db = new mysqlDB($params);
        $this->table_name = $params['table_name'];
    }

    //记录
    public function record($arr){
        if(!get_magic_quotes_gpc()) $arr = array_map("addslashes",$arr);
        $keys = array_keys($arr);
        $values = array_values($arr);
        $key_str = implode($keys,",");
        $var_str = implode($values,"','");
        $sql = "insert into $this->table_name($key_str) value('$var_str') on DUPLICATE KEY UPDATE hits=hits+1,last_time='{$arr['last_time']}';";
        if($this->db->query($sql))
            return $this->db->Insert_ID();
        else return false;
    }

    //当日是否已被记录过
    public function inTodayVisit($arr){
        $ret = $this->db->getRow("select * from this->table_name where ip='{$arr['ip']}' and url='{$arr['url']}';");
        if(empty($ret)) return false;
        else return true;
    }
}