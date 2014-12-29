<?php

class CWebApplication extends CApplication
{
	public $defaultController = 'site';

	public $layout = 'main';

	public $controllerMap = array();

	public $controllerNamespace;

	private $_controllerPath;
	private $_viewPath;
	private $_controller;

    public function run()
    {
        $route = $this->processRequest();

        $this->runController($route);
    }

    public function runController($route)
    {
        if(($ca=$this->createController($route))!==null)
        {
            list($controller,$actionID)=$ca;
            $controller->basePath = $this->basePath;

            $controller->beforeControllerAction();
            $controller->run($actionID);
            $controller->afterControllerAction();
        }
        else
            throw new Exception("Unable to resolve the request {$route};");
    }

    //处理请求
	public function processRequest()
	{
		$route=$this->getUrlManager()->parseUrl($_GET['pathinfo']);

        return $route;
	}

	protected function registerCoreComponents()
	{
		parent::registerCoreComponents();

		$components=array(
			'coreseek'=>array(
				'class'=>'CoreseekSearch',
			),
		);

		$this->setComponents($components);
	}

	public function getViewRenderer()
	{
        $view = $this->getComponent('view');
		return $view;
	}

    //创建控制器
	public function createController($route)
	{
		if(($route=trim($route,'/'))===''){
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            exit("无效URL");
            $route=$this->defaultController.'/';
        }

        $pos=strpos($route,'/');
        $id=substr($route,0,$pos);
        if(!preg_match('/^\w+$/',$id))
            return null;

        $action = (string)substr($route,$pos+1);

        $basePath=$this->getControllerPath();

        $className=ucfirst($id).'Controller';
        $classFile=$basePath.DIRECTORY_SEPARATOR.$className.'.php';

        if(is_file($classFile))
        {
            if(!class_exists($className,false))
                require($classFile);
            if(class_exists($className,false) && is_subclass_of($className,'CController'))
            {
                return array(
                    new $className(),
                    $action
                );
            }
            return null;
        }else
            throw new Exception("Unable to resolve the request '{$route}'.");
	}

	public function getController()
	{
		return $this->_controller;
	}

	public function setController($value)
	{
		$this->_controller=$value;
	}

	public function getControllerPath()
	{
		return $this->_controllerPath=$this->getBasePath().DIRECTORY_SEPARATOR.'controllers';
	}

	public function getViewPath()
	{
		if($this->_viewPath!==null)
			return $this->_viewPath;
		else
			return $this->_viewPath=$this->getBasePath().DIRECTORY_SEPARATOR.'views';
	}

	public function setViewPath($path)
	{
		if(($this->_viewPath=realpath($path))===false || !is_dir($this->_viewPath))
			throw new CException(Yii::t('yii','The view path "{path}" is not a valid directory.',
				array('{path}'=>$path)));
	}

	/**
	 * Initializes the application.
	 * This method overrides the parent implementation by preloading the 'request' component.
	 */
	protected function init()
	{
	}
}
