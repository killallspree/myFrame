<?php
defined("BASEPATH") or define("BASEPATH",realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'));

return array(
	'basePath'=>BASEPATH,
	'name'=>'酷说客，小说阅读网。',

	'import'=>array(    //类文件被默认加载的目录。类名必须与文件名同。
        BASEPATH.DIRECTORY_SEPARATOR."model",
        BASEPATH.DIRECTORY_SEPARATOR."components",
	),

	'defaultController'=>'site',

    'defaultDb'=>'book',    //默认连接的数据库

    'db' => array(
        'book' => array(    //数据库名为book
            "slave_1" => array(     //支持多个从数据库,分担查询压力
                'db_host' => '127.0.0.1',
                'db_name' => 'book',
                'db_user' => 'user1',
                'db_password' => 'passwd1',
                'tablePrefix' => 'cmsl_',   //表前缀
            ),
            "slave_2" => array(
                'db_host' => '127.0.0.2',
                'db_name' => 'book',
                'db_user' => 'user2',
                'db_password' => 'passwd2',
                'tablePrefix' => 'cmsl_',
            ),
        ),
        'count' => array(    //数据库名为count(支持多数据库)
            "slave_1" => array(     //支持多个从数据库,分担查询压力
                'db_host' => '127.0.0.1',
                'db_name' => 'count',
                'db_user' => 'user1',
                'db_password' => 'passwd1',
                'tablePrefix' => 'cmsl_',
            )
        )
    ),

	'components'=>array(

        'view' => array(
            'debugging' => false,
            'caching' => false,
            'cache_lifetime' => 120,
            'template_dir' => BASEPATH.DIRECTORY_SEPARATOR."templates",  //模版目录
            'compile_dir' => BASEPATH.DIRECTORY_SEPARATOR."templates_c",  //模版编译目录
            'register_class' => 'tpl_global_func',  //注册类到模版中(类文件在components目录下，模板中通过{func::方法名($param)}来调用类中的方法)

            //模版中用的常量
            'URL' => "http://www.kutalk.com/",
            'JS_PATH' => "http://www.kutalk.com/js/",
            'CSS_PATH' => "http://skin.liqucn.com/wap/wap_touch/css/",
            'IMG_PATH' => "http://www.kutalk.com/images/",
        ),

		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
                //路径映射规则
                '/' => '/Site/Index/?isSerial=1',
                '/cate/(\d+)_?(\d*)\.wml' => '/Cate/Category/?cate_id=$1&page=$2&isSerial=1',
                '/book/(\d+)\.wml' => '/Chapter/chapter/?book_id=$1',
                '/(\w+)/(\w+)/' => '/$1/$2/'
			),
		),

        'global'=>array(    //定义常量。如'URL',等同define('URL','http://www.kutalk.com/')
            'URL' => "http://www.kutalk.com/",
            'basePath'=> BASEPATH,
            "SEARCH_SDK" => "/home/service/xunsearch/sdk/php/lib/XS.php",
            "SEARCH_INI" => BASEPATH.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."book.ini",
        ),

	),
);