<?php

class CRedis{

    public static $hosts = array();
    public $config = array();
    public $connect = false;

    public function __construct(){
        $this->redis = new Redis();
        $this->hash = new Flexihash();
    }

    public function init($config){
        $this->config = $config;
    }

    public function set_hosts($channel){
        $this->hosts = $this->config[$channel];
    }

    public function connect($key){
        if($this->connect) return true;
        $host = $this->get_host($key);
        list($host,$port) = explode(":",$host);
        if(!$this->redis->connect($host,$port)){
            throw new Exception("ERROR: Failed to connect redis host $host:$port (∩_∩)");
        }
        $this->connect = true;
    }

    public function get_host($key){
        if(count($this->hosts)==1)
            return current($this->hosts);
        foreach($this->hosts as $co){
            $this->hash->addTargets($co);
        }
        $host = $this->hash->lookup($key);
        return $host;
    }

    public function __call($function, $arguments){
        $this->connect($arguments[0]);
        if(count($arguments)===1)
            return $this->redis->$function($arguments[0]);
        elseif(count($arguments)===2)
            return $this->redis->$function($arguments[0],$arguments[1]);
        elseif(count($arguments)===3)
            return $this->redis->$function($arguments[0],$arguments[1],$arguments[2]);
    }
}