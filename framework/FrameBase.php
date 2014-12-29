<?php

defined('FRAME_PATH') or define('FRAME_PATH',dirname(__FILE__));
defined('DS') or define('DS',DIRECTORY_SEPARATOR);


class FrameBase{

    private static $_app;

    private static $_coreClasses=array(
//        'CApplication' => '/base/CApplication.php',
//        'CApplicationComponent' => '/base/CApplicationComponent.php',
//        'CWebApplication' => '/web/CWebApplication.php',
//        'CUrlManager' => '/web/CUrlManager.php',
//        'CController' => '/web/CController.php',
//        'CModel' => '/web/CModel.php',
//        'CView' => '/web/CView.php',
//        'CRedis' => '/components/CRedis.php',
//        'Flexihash' => '/components/flexihash/include/init.php',
//        'Smarty' => '/components/smarty/Smarty.class.php',
//        'mysqlDB' => '/components/mysqlDB.php',
//        'GlobalParams' => '/web/GlobalParams.php'
    );

    public static function setApplication($app)
    {
        if(self::$_app===null || $app===null)
            self::$_app=$app;
        else
            throw new CException(Yii::t('yii','Yii application can only be created once.'));
    }

    public static function app()
    {
        return self::$_app;
    }

    //创建web应用
    public static function createWebApplication($config=null)
    {
        return self::createApplication('CWebApplication',$config);
    }

    //创建web应用
    public static function createAppApplication($config=null)
    {
        return self::createApplication('CAppApplication',$config);
    }

    //创建wap应用
    public static function createWapApplication($class,$config=null)
    {
        return self::createApplication('CWapApplication',$config);
    }

    public static function createApplication($class,$config=null)
    {
        return new $class($config);
    }

    public static function createComponent($config)
    {
        if(is_string($config))
        {
            $type=$config;
            $config=array();
        }
        elseif(isset($config['class']))
        {
            $type=$config['class'];
            unset($config['class']);
        }
        else
            throw new CException('Object configuration must be an array containing a "class" element.');

        if(($n=func_num_args())>1)
        {
            $args=func_get_args();
            if($n===2)
                $object=new $type($args[1]);
            elseif($n===3)
                $object=new $type($args[1],$args[2]);
            elseif($n===4)
                $object=new $type($args[1],$args[2],$args[3]);
        }
        else
            $object=new $type;

        $object->init($config);
        return $object;
    }

    public static function autoload($className)
    {
        // use include so that the error PHP file may appear
        if(isset(self::$_coreClasses[$className]))
            include(FRAME_PATH.self::$_coreClasses[$className]);
        else
            @include($className.".php");
    }

    public static function unshift_include_path($items)
    {
        $elements = explode(PATH_SEPARATOR, get_include_path());

        if (is_array($items))
        {
            set_include_path(implode(PATH_SEPARATOR, array_merge($items, $elements)));
        }
        else
        {
            array_unshift($elements, $items);
            set_include_path(implode(PATH_SEPARATOR, $elements));
        }
    }

}

//添加include目录
FrameBase::unshift_include_path(array(FRAME_PATH.DS."base",FRAME_PATH.DS."components"));
//将FrameBase的autoload函数注册到__autoload函数栈中
spl_autoload_register(array('FrameBase','autoload'));