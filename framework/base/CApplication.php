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

    //处理请求
    abstract public function processRequest();

    abstract public function run();

    public function __construct($config_dir=null)
    {
        $config=require($config_dir);
        //设置项目根目录
        $this->setBasePath($config['basePath']);

        Frame::setApplication($this);
        if(isset($config['import']))
            Frame::unshift_include_path($config['import']);

        $this->configure($config);

        $this->db_init();
        //加载核心模块
        $this->registerCoreComponents();
        //设置全局变量
        $this->setGlobalParam();

    }

    public function configure($config)
    {
        if(is_array($config))
        {
            foreach($config as $key=>$value)
                $this->$key=$value;
        }
    }

    public function db_init(){
        mysqlDB::setDBConfig($this->db);
        mysqlDB::setDBDefault($this->defaultDb);
    }

    public function setGlobalParam()
    {
        $this->getComponent('global')->defineParams();
    }

    public function setBasePath($path)
    {
        defined('BASE_PATH') or define('BASE_PATH',realpath($path));
        if(!is_dir(BASE_PATH))
            throw new CException(Yii::t('yii','Application base path "{path}" is not a valid directory.',
                array('{path}'=>$path)));
    }

    public function getBasePath()
    {
        return BASE_PATH;
    }

    public function getUrlManager()
    {
        return $this->getComponent('urlManager');
    }

    //配置核心模块的config
    protected function registerCoreComponents()
    {
        $components=array(
            'urlManager'=>array(
                'class'=>'CUrlManager',
            ),
            'db'=>array(
                'class'=>'mysqlDB',
            ),
            'view'=>array(
                'class'=>'CView',
            ),
            'global'=>array(
                'class'=>'GlobalParams',
            ),
            'messages'=>array(
                'class'=>'CPhpMessageSource',
            ),
            'errorHandler'=>array(
                'class'=>'CErrorHandler',
            ),
            'request'=>array(
                'class'=>'CHttpRequest',
            ),
            'format'=>array(
                'class'=>'CFormatter',
            ),
            'redis'=>array(
                'class'=>'CRedis',
            ),
        );

        $this->setComponents($components);
    }

    /**
     * Getter magic method.
     * This method is overridden to support accessing application components
     * like reading module properties.
     * @param string $name application component or property name
     * @return mixed the named property value
     */
    public function __get($name)
    {
        if($this->hasComponent($name))
            return $this->getComponent($name);
        else
            return null;
    }

    /**
     * Checks if a property value is null.
     * This method overrides the parent implementation by checking
     * if the named application component is loaded.
     * @param string $name the property name or the event name
     * @return boolean whether the property value is null
     */
    public function __isset($name)
    {
        if($this->hasComponent($name))
            return $this->getComponent($name)!==null;
        else
            return false;
    }

    /**
     * Checks whether the named component exists.
     * @param string $id application component ID
     * @return boolean whether the named application component exists (including both loaded and disabled.)
     */
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
            if(!isset($config['enabled']) || $config['enabled'])
            {
                unset($config['enabled']);
                $component=Frame::createComponent($config);
                return $this->_components[$id]=$component;
            }
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

    /**
     * Loads static application components.
     */
    protected function preloadComponents()
    {
        foreach($this->preload as $id)
            $this->getComponent($id);
    }

}