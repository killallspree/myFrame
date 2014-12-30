<?php

class CWebApplication extends CApplication
{
	public $defaultController = 'site';
	public $controllerMap = array();
	private $_controllerPath;
	private $_viewPath;

    public function run()
    {
        $this->runController($this->route);
    }

    public function runController($route)
    {
        if($ca = $this->createController($route))
        {
            list($controller,$actionID) = $ca;

            $controller->beforeControllerAction();
            $controller->run($actionID);
            $controller->afterControllerAction();
        }
        else
            throw new Exception("Unable to resolve the request {$route};");
    }

    //创建控制器
    public function createController($route)
    {
        if(($route=trim($route,'/'))===''){
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
//            return false;
            $route = $this->defaultController.'/';
        }

        $pos=strpos($route,'/');
        $id=substr($route,0,$pos);
        if(!preg_match('/^\w+$/',$id)) return false;

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
            return false;
        }else
            return false;
    }

	protected function registerCoreComponents()
	{
		parent::registerCoreComponents();

		$components=array(
            'view'=>array(
                'class'=>'CView',
            ),
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

	public function getControllerPath()
	{
		return $this->_controllerPath=BASE_PATH.DS.'controllers';
	}

	public function getViewPath()
	{
		if($this->_viewPath!==null)
			return $this->_viewPath;
		else
			return $this->_viewPath = BASE_PATH.DS.'views';
	}

	public function setViewPath($path)
	{
		if(($this->_viewPath = realpath($path))===false || !is_dir($this->_viewPath))
			throw new Exception('The view path "{path}" is not a valid directory.');
	}

}
