<?php

class CUrlManager extends CApplicationComponent
{
    public $urlFormat;
    public $rules;

    public function parseUrl($request_url){
        foreach($this->rules as $url=>$php_path){
            if( preg_match('`^'.$url.'$`i',$request_url) ){
                $url_query_string = preg_replace('`'.$url.'`i', $php_path, $request_url);
                break;
            }
        }

        if(isset($url_query_string)){
            $path = explode("?",$url_query_string);
            if(isset($path[1])){
                foreach( explode('&',$path[1]) as $row ){
                    list($t,$t1) = explode('=',$row);
                    $_GET[$t]=$t1;
                }
            }
            return $path[0];
        }else
            return '';
    }

    public function init($conf = array())
    {
        foreach($conf as $key=>$value){
            $this->$key = $value;
        }
    }
}
