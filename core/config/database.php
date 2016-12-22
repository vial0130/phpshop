<?php
/* ========================================================================
 * 数据库相关配置
 * ======================================================================== */
return array
    (
        'DNS'=>'mysql:host=127.0.0.1;dbname=shopcms',
        'TYPE' => 'mysql',
        'SERVER' => '127.0.0.1',
	    'DATABASE' => 'shopcms',
        'USERNAME'=>'root',
        'PASSWORD'=>'',
        'CHARSET' => 'utf8',
	    'PREFIX' => 'shop_',
        //sqlite示例配置
        //'database_type' => 'sqlite',
        //'database_file' => 'db/phpcms.rdb'
    );
?>