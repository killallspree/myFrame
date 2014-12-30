<?php

/**
 * 此类中的函数会被注册到模版中
 *
 * */
class SmartyFunc{

    public function img_resize($src,$size){
        $src = str_replace("http://images.liqucn.com","http://images.liqucn.com/mini/$size",$src);
        $src = preg_replace("/\.(jpg|png|bmp|jpeg)/i","_$size.$1",$src);
        return $src;
    }

    public function conver_million($count){
        if($count>10000){
            return round($count/10000,1)."万";
        }else
            return $count;
    }

    public function replace_hot_new($url,$type){
        $url = str_ireplace(array("new","hot"),$type,$url);
        if(stripos($url,$type)===false){
            $url = "$type/";
        }
        return $url;
    }

    function CloseTags($html){
        $html = preg_replace('/<[^>]*$/','',$html); // ending with fraction of open tag
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $opentags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closetags = $result[1];
        $len_opened = count($opentags);
        if (count($closetags) == $len_opened) {
            return $html;
        }
        $opentags = array_reverse($opentags);
        $sc = array('br','input','img','hr','meta','link');
        for ($i=0; $i < $len_opened; $i++){
            $ot = strtolower($opentags[$i]);
            if (!in_array($opentags[$i], $closetags) && !in_array($ot,$sc)){
                $html .= '</'.$opentags[$i].'>';
            }
            else{
                unset($closetags[array_search($opentags[$i], $closetags)]);
            }
        }
        return $html;
    }

    function OpenTags($html){
        $html = preg_replace('/<[^>]*$/','',$html); // ending with fraction of open tag
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $opentags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closetags = $result[1];
        $len_closeed = count($closetags);
        if (count($opentags) == $len_closeed) {
            return $html;
        }
        $sc = array('br','input','img','hr','meta','link');
        for ($i=0; $i < $len_closeed; $i++){
            $ot = strtolower($closetags[$i]);
            if (!in_array($closetags[$i], $opentags) && !in_array($ot,$sc)){
                $html = '<'.$closetags[$i].'>'.$html;
            }
            else{
                unset($opentags[array_search($closetags[$i], $opentags)]);
            }
        }
        return $html;
    }
}