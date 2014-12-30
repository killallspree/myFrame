<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-5-4
 * Time: 下午3:14
 * To change this template use File | Settings | File Templates.
 */

abstract class CApplication {

    private $_components=array();
    private $_componentConfig=array();

    public function __construct($config_dir=null)
    {
        $config = require($config_dir);

        $this->setBasePath($config['basePath']);    //项目根目录
        $this->setIncludePath($config['import']);   //注册include_path

        $this->configure($config);                  //配置文件

        $this->registerCoreComponents();    //注册核心组件

        $this->preExecComponents();     //执行组件

        Frame::setApplication($this);

    }

    public function setBasePath($path)
    {
        defined('BASE_PATH') or define('BASE_PATH',realpath($path));
        if(!is_dir(BASE_PATH)) throw new Exception('BASE_PATH config error;');
    }

    public function configure($config)
    {
        if(is_array($config))
        {
            foreach($config as $key=>$value)
                $this->$key=$value;
        }
    }

    public function setIncludePath($paths){
        is_dir(BASEPATH.DS."model") or mk_dir(BASEPATH.DS."model") or die("缺少model目录");
        is_dir(BASEPATH.DS."components") or mk_dir(BASEPATH.DS."components") or die("缺少components目录");
        Frame::unshift_include_path(BASEPATH.DS."model");
        Frame::unshift_include_path(BASEPATH.DS."components");
        foreach($paths as $path){
            Frame::unshift_include_path($path);
        }
    }

    public function db_init(){
        mysqlDB::setDBConfig($this->db);
        mysqlDB::setDBDefault($this->defaultDb);
    }

    //配置核心模块的config
    protected function registerCoreComponents()
    {
        $components = array(
            'urlManager'=>array(
                'class'=>'CUrlManager',
            ),
            'db'=>array(
                'class'=>'CModel',
            ),
            'redis'=>array(
                'class'=>'CRedis',
            ),
            'global'=>array(
                'class'=>'GlobalParams',
            ),
        );

        $this->setComponents($components);
    }

    public function __get($name)
    {
        if($this->hasComponent($name))
            return $this->getComponent($name);
        else
            return null;
    }

    public function __isset($name)
    {
        if($this->hasComponent($name))
            return $this->getComponent($name)!==null;
        else
            return false;
    }

    public function hasComponent($id)
    {
        return isset($this->_components[$id]) || isset($this->_componentConfig[$id]);
    }

    public function getComponent($id,$createIfNull=true)
    {
        if(isset($this->_components[$id]))
            return $this->_components[$id];
        elseif(isset($this->_componentConfig[$id]) && $createIfNull)
        {
            $config = $this->_componentConfig[$id];
            $component = Frame::createComponent($config);
            return $this->_components[$id] = $component;
        }else
            throw new Exception("Unable to load component '{$id}'.");
    }


    public function setComponent($id,$component,$merge=true)
    {
        if(isset($this->_componentConfig[$id]) && $merge)
            $this->_componentConfig[$id]=array_merge($this->_componentConfig[$id],$component);
        elseif(isset($this->components[$id]))
            $this->_componentConfig[$id]=array_merge($this->components[$id],$component);
        else
            $this->_componentConfig[$id]=$component;
    }

    public function getComponents()
    {
        return $this->_components;
    }

    public function setComponents($components,$merge=true)
    {
        foreach($components as $id=>$component)
            $this->setComponent($id,$component,$merge);
    }

    protected function preExecComponents()
    {
        $this->getComponent('global')->defineParams();    //设置全局变量
        $this->route = $this->getComponent('urlManager')->parseUrl($_GET['pathinfo']);  //路径映射
        $this->getComponent('db');    //设置全局变量
    }

}