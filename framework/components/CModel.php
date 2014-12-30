<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-11-12
 * Time: 下午2:47
 * To change this template use File | Settings | File Templates.
 */
require_once dirname(__FILE__). DS ."DBDriver". DS.'mysqlDB.php';

class CModel{

    private static $defaultDb;
    private static $db_config;
    public static $_dbMap;

    public function init($config){
        self::$defaultDb = $config['defaultDb'];
        unset($config['defaultDb']);
        self::$db_config = $config;
    }

    /**
     * @param string $db_name
     * @param int $master   是否主服务器
     * @return mixed
     */
    public static function getInstance($db_name="",$master=0){
        if(!$db_name) $db_name = self::$defaultDb;
        if($master&&isset(self::$_dbMap[$db_name]['master'])){
            self::$_dbMap[$db_name]['master']->ping();
            return self::$_dbMap[$db_name]['master'];
        }elseif($master&&isset(self::$db_config[$db_name]['master'])){
            //连接主服务器
            self::$_dbMap[$db_name]['master'] = new mysqlDB();
            self::$_dbMap[$db_name]['master']->connect(self::$db_config[$db_name]['master']);
            self::$_dbMap[$db_name]['master']->ping();
            return self::$_dbMap[$db_name]['master'];
        }else{
            if(!isset(self::$_dbMap[$db_name]['slave'])){
                $configs = self::$db_config[$db_name];
                unset($configs['master']);
                if(empty($configs)){    //如果只配了主的，查询和插入都走主的
                    $configs = self::$db_config[$db_name];
                }
                self::$_dbMap[$db_name]['slave'] = new mysqlDB();
                self::hashConnect(self::$_dbMap[$db_name]['slave'],$configs);
            }
            self::$_dbMap[$db_name]['slave']->ping();
            return self::$_dbMap[$db_name]['slave'];
        }
    }

    /**
     * @param $_db
     * @param $configs
     * @return bool
     * @throws Exception
     * message hash连库
     */
    public static function hashConnect(&$_db,$configs){
        $hash = new Flexihash();
        $hash->addTargets(array_keys($configs));
        $config_key = $hash->lookup(rand());
        $db_name = $configs[$config_key]['db_name'];
        $conf = $configs[$config_key];
        //此服务器连接不通，换下一个
        if(!$_db->connect($conf)){
            echo "$db_name:$config_key:connect failure;";
            $hash->removeTarget($config_key);
            $targets = $hash->getAllTargets();
            if(empty($targets)) throw new Exception("all DB:$db_name connect failure,no one available;");
            unset($configs[$config_key]);
            self::hashConnect($_db,$configs,$db_name);
        }else{
            return true;
        }
    }

    public static function get_tablePrefix($db_name){
        if(!$db_name) $db_name = self::$defaultDb;
        $config = current(self::$db_config[$db_name]);
        return $config['tablePrefix'];
    }

}