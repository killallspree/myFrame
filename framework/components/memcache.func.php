<?php

function get_cache_hosts($cache_class='default')
{
	$host_arr = array(
        'list'=>array('url'=>'memcache.master','port'=>12122,'expires'=>3600*24*1),
        'web'=>array('url'=>'memcache.master','port'=>12123,'expires'=>3600*24*1),
        'wap'=>array('url'=>'wap.master','port'=>12124,'expires'=>3600*24*1),
        'appserver'=>array('url'=>'appserver.master','port'=>12125,'expires'=>3600*24*1),
        'search'=>array('url'=>'search.master','port'=>12126,'expires'=>3600*24*1),
        'content'=>array('url'=>'memcache.master','port'=>12133,'expires'=>3600*24*1),
        'node'=>array('url'=>'memcache.master','port'=>12128,'expires'=>3600*24*1),
        'model'=>array('url'=>'memcache.master','port'=>12129,'expires'=>3600*24*1),
        'default'=>array('url'=>'memcache.master','port'=>12130,'expires'=>3600*24*1),
        'memory_count'=>array('url'=>'memcache.master','port'=>12131,'expires'=>3600*24*1),
        'new_release'=>array('url'=>'memcache.master','port'=>12132,'expires'=>3600*24*1),
        'dj_zhuanqu'=>array('url'=>'zhuanqu.master','port'=>12134,'expires'=>3600*24*1),
        'wy_zhuanqu'=>array('url'=>'zhuanqu.master','port'=>12135,'expires'=>3600*24*1)
    );
/*/home/service/memcached/bin/memcached -d -m 500 -u root -p 12122 -P /tmp/memcached_12122.pid
/home/service/memcached/bin/memcached -d -m 1000 -u root -p 12123 -P /tmp/memcached_12123.pid
/home/service/memcached/bin/memcached -d -m 1000 -u root -p 12125 -P /tmp/memcached_12125.pid
/home/service/memcached/bin/memcached -d -m 1200 -u root -p 12133 -P /tmp/memcached_12133.pid
/home/service/memcached/bin/memcached -d -m 100 -u root -p 12128 -P /tmp/memcached_12128.pid
/home/service/memcached/bin/memcached -d -m 100 -u root -p 12129 -P /tmp/memcached_12129.pid
/home/service/memcached/bin/memcached -d -m 1200 -u root -p 12130 -P /tmp/memcached_12130.pid
/home/service/memcached/bin/memcached -d -m 200 -u root -p 12131 -P /tmp/memcached_12131.pid
/home/service/memcached/bin/memcached -d -m 200 -u root -p 12132 -P /tmp/memcached_12132.pid*/

// /home/service/memcached/bin/memcached -d -m 2000 -u root -p 12126 -P /tmp/memcached_12126.pid
// /home/service/memcached/bin/memcached -d -m 1500 -u root -p 12124 -P /tmp/memcached_12124.pid
// /home/service/memcached/bin/memcached -d -m 200 -u root -p 12134 -P /tmp/memcached_12134.pid
// /home/service/memcached/bin/memcached -d -m 200 -u root -p 12135 -P /tmp/memcached_12135.pid
	return $cache_class=='all'?$host_arr:$host_arr[$cache_class];
}
function get_cache_obj($cache_class='default',$with_info=0)
{
	$cache_key = 'my_cache_'.$cache_class.$with_info;
	if($GLOBALS[$cache_key])	return $GLOBALS[$cache_key];
	$mem = new Memcache;
	if($mem && !$GLOBALS['connect_status_false'][$cache_class]){
		$cache_host = get_cache_hosts($cache_class);
		@ $connect_status = $mem->connect($cache_host['url'], $cache_host['port']);
		if(!$connect_status)	{$GLOBALS['connect_status_false'][$cache_class]=true;return false;}
		$mem->setCompressThreshold(2000, 0.2);
		if($with_info)	$mem = array('obj'=>$mem,'expires'=>$cache_host['expires']);
		$GLOBALS[$cache_key] = $mem;
		return $GLOBALS[$cache_key];
	}
	else
		return false;
}
function cache_set($key,$value,$cache_class='',$days=1)
{
	$GLOBALS['globals-'.$key] = $value;

	$cache_obj = get_cache_obj($cache_class,1);
	if($cache_obj)
		return $cache_obj['obj']->set($key,$value,MEMCACHE_COMPRESSED,3600*24*$days);
	else
		return false;
}
function cache_get($key,$cache_class='')
{
	if(isset($GLOBALS['globals-'.$key]) && $GLOBALS['globals-'.$key]!==false)	return $GLOBALS['globals-'.$key];
	$cache_obj = get_cache_obj($cache_class,1);
	if($cache_obj){
		$cache_value = $cache_obj['obj']->get($key);
		$GLOBALS['globals-'.$key] = $cache_value;
	}
	else
		$GLOBALS['globals-'.$key] = false;
	return $GLOBALS['globals-'.$key];
}
function cache_del($key,$cache_class='')
{
	$cache_obj = get_cache_obj($cache_class,1);
	return $cache_obj?$cache_obj['obj']->delete($key):false;
}
function cache_flush($cache_class='')
{
	if($cache_class=='all'){
		$arr_cache_host = get_cache_hosts($cache_class);
		foreach($arr_cache_host as $cache_class=>$row){
			if($cache_class!='memory_count'){	//计数的别一并清空了
				$cache_obj = get_cache_obj($cache_class,1);
				if($cache_obj)	$cache_obj['obj']->flush();
			}
		}
	}
	else{
		$cache_obj = get_cache_obj($cache_class,1);
		if($cache_obj)	$cache_obj['obj']->flush();
	}
	//清空memcache外，还要删除静态缓存文件目录
	/*$local_dir_pre = "/home/web/cache/liqucn.com/";
	removeDir($local_dir_pre);*/
}
?>