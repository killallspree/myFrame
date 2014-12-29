<?php
header("Content-type:text/html;charset=utf-8");

$yii=dirname(__FILE__).'/../framework/Frame.php';
$config=dirname(__FILE__).'/config/main.php';

require_once($yii);
Frame::createWebApplication($config)->run();