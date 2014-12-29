<?php
/**
 * Created by JetBrains PhpStorm.
 * User: huangpeng
 * Date: 14-4-1
 * Time: 下午5:28
 * To change this template use File | Settings | File Templates.
 * 批量抓取类
 */
class multi_curl_fetch{
    public $mh,$conn;
    public $arr_tasks;

    //批量初始化任务
    function init($arr_tasks,$gzip=false){
        $this->mh = curl_multi_init();
        $this->arr_tasks = $arr_tasks;
        foreach ($arr_tasks as $fetch_url) {
            $this->conn[$fetch_url]=curl_init($fetch_url);
            curl_setopt($this->conn[$fetch_url],CURLOPT_RETURNTRANSFER,1);
            curl_setopt($this->conn[$fetch_url], CURLOPT_HEADER, 0);
            curl_setopt($this->conn[$fetch_url], CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($this->conn[$fetch_url], CURLOPT_TIMEOUT, 10);
            if($gzip) curl_setopt($this->conn[$fetch_url], CURLOPT_ENCODING, "gzip,deflate"); // 关键在这里
            curl_setopt($this->conn[$fetch_url], CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36');
            $random_ip=self::random_ip();
            curl_setopt($this->conn[$fetch_url], CURLOPT_HTTPHEADER, array("X-FORWARDED-FOR:{$random_ip}","CLIENT-IP:{$random_ip}")); //构造IP
            curl_multi_add_handle ($this->mh,$this->conn[$fetch_url]);
        }
    }

    //单个任务的添加
    function add_task($fetch_url){
        if(empty($this->mh)) $this->mh = curl_multi_init();
        $this->conn[$fetch_url]=curl_init($fetch_url);
        curl_setopt($this->conn[$fetch_url],CURLOPT_RETURNTRANSFER,1);
        curl_setopt($this->conn[$fetch_url], CURLOPT_HEADER, 0);
        curl_setopt($this->conn[$fetch_url], CURLOPT_TIMEOUT, 40);
        curl_setopt($this->conn[$fetch_url], CURLOPT_USERAGENT, 'Mozilla/5.0(Linux;U;Android 2.3.6;zh-cn;XT910 Build/6.5.1_GC-136-SPDU-13)AppleWebKit/533.1(KHTML,like Gecko)Version/4.0 Mobile Safari/533.1');
        $random_ip=self::random_ip();
        curl_setopt($this->conn[$fetch_url], CURLOPT_HTTPHEADER, array("X-FORWARDED-FOR:{$random_ip}","CLIENT-IP:{$random_ip}")); //构造IP
        curl_multi_add_handle ($this->mh,$this->conn[$fetch_url]);
    }

    function run(){
        do {
            $mrc = curl_multi_exec($this->mh,$active);//当无数据时或请求暂停时，active=true
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);//当正在接受数据时
        while ($active and $mrc == CURLM_OK) {//当无数据时或请求暂停时，active=true,为了减少cpu的无谓负担
            if (curl_multi_select($this->mh) != -1) {
                do {
                    usleep(500);
                    $mrc = curl_multi_exec($this->mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
    }

    function get($fetch_url){
        $ret=curl_multi_getcontent($this->conn[$fetch_url]);
        return $ret;
    }

    function curl_destruct(){
        foreach ($this->arr_tasks as $fetch_url) {
            curl_multi_remove_handle($this->mh, $this->conn[$fetch_url]);
            curl_close($this->conn[$fetch_url]);//关闭所有对象
        }
        curl_multi_close($this->mh);
    }

    public static function random_ip(){
        return rand(1,254).'.'.rand(1,254).'.'.rand(1,254).'.'.rand(1,254);
    }

    public static function curl($url,$gzip=false){
        $random_ip=self::random_ip();
        $conn = curl_init($url);
        curl_setopt($conn,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($conn, CURLOPT_HEADER, 0);
        curl_setopt($conn, CURLOPT_TIMEOUT, 40);
        if($gzip) curl_setopt($conn, CURLOPT_ENCODING, "gzip,deflate"); // 关键在这里
        curl_setopt($conn, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36');
        curl_setopt($conn, CURLOPT_HTTPHEADER, array("X-FORWARDED-FOR:{$random_ip}","CLIENT-IP:{$random_ip}")); //构造IP
        $result = curl_exec($conn);
        return $result;
    }
}