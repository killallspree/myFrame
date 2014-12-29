<?php

class GlobalParams extends CApplicationComponent{

    public $config;

    public function init($config=array()){
        $this->config = $config;
    }

    public function defineParams(){
        foreach($this->config as $key=>$value){
            define($key,$value);
        }
    }

}