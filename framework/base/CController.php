<?php

class CController {

    public $defaultAction = 'Index';
    public $viewPath = '';
    public $view = null;
    public $name = '';
    public $model = null;

    public function __construct(){
        $this->setViewPath();
        $this->name = strtolower(str_ireplace("Controller","",get_class($this)));
        $this->view = Frame::app()->getViewRenderer();
    }

    public function run($actionID)
    {
        if($actionID==='')
            $actionID=$this->defaultAction;
        $action = 'action'.$actionID;
        if(method_exists($this,$action))
        {
            $this->$action();
        }else{
            throw new Exception($action.' Action class '.get_class($this).' does not exist.');
        }
    }

    public function __call($function,$params){
        if(!isset($params[0])) throw new Exception("缺少模版参数");
        if(!isset($params[1])) $params[1] = array();
        $this->view->$function($this->viewPath.DIRECTORY_SEPARATOR.$params[0],$params[1]);
    }

    public function assign($params){
        $this->view->assign($params);
    }

    public function pagination($total, $per_page = 15,$page = 1, $url = '?'){
        return Frame::app()->getViewRenderer()->pagination($total, $per_page,$page, $url);
    }

    public function setViewPath($path=""){
        if($path)
            $path = strtolower($path);
        else
            $path = strtolower(str_ireplace("Controller","",get_class($this)));
        $this->viewPath = $path;
    }

    public function getViewPath(){
        return $this->viewPath;
    }

    public function beforeControllerAction(){
        return true;
    }

    public function afterControllerAction(){
        if($this->model!=null)
            $this->model->db_close();
    }

    public function nullContent(){
        die("你访问的内容不存在");
    }

}