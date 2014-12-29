<?php
/**
 * 记录需求：
 *  1、记录访问ip,url
 * 附加需求：
 *  1、防刷量
 */

/*'count_db'=>array(
    'count' => false,    //是否开启统计
    'type' => 'mysql',  //统计数据库类型
    'db_host' => '127.0.0.1:3306',
    'db_name' => 'liqu_cms',
    'db_user' => 'root',
    'db_password' => 'viewo_admin_z6f2',
    'table_name' => 'cmsl_count_visit',

//        'type' => 'memcache',  //统计数据库类型
//        'db_host' => '127.0.0.1',
//        'port' => '11211',
),*/
/*if(!$config['count']) return true;
//访问统计
$count_visit = new count_visits($config);
$count_visit->visit();*/

class count_visits {
    //统计数据插入类
    private $count_db;

    public function __construct($config){
        $this->count_db = new count_db($config);
    }

    //统计当天数据并持久化到mysql
    public function countTodayVisit(){
        $this->count_db->countTodayVisit();
    }

    //访问
    public function visit(){
        $visitInfo = $this->getVisitInfo();
        $this->record($visitInfo);
    }

    //获取访问信息
    public function getVisitInfo(){
        $url = isset($_GET['path_info'])?trim($_GET['path_info']):$_SERVER["REQUEST_URI"];
        $ip = $this->getip();
        $time = time();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        return array('ip'=>$ip,'url'=>$url,'user_agent'=>$user_agent,'last_time'=>$time);
    }

    //请求记录
    public function record($visitInfo){
        $this->count_db->record($visitInfo);
    }

    //今日是否已经访问过
    public function inTodayVisit($visitInfo){
        return $this->count_db->inTodayVisit($visitInfo);
    }


    /**
     * 获得客户端真实的IP地址
     */
    public function getip()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        } else if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "unknown";
        }
        return $ip;
    }
}