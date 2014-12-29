<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-11-23
 * Time: 下午4:29
 * To change this template use File | Settings | File Templates.
 */

interface count_db_interface{
    //记录
    public function record($arr);
    //当日是否已被记录过
    public function inTodayVisit($arr);
}