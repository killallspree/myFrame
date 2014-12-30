<?php
/**
 * 切换DB，请指定
 * 静态变量：public static $db_name = "ol_zhuanqu";
 * 静态方法：public static function getDbName();
 */
class App extends AModel{

    public $db_name = "bbb1";
    public $table = "app";
    public $pk = "id";
}