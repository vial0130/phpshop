<?php
//session_start();
/* ========================================================================
 * 框架加载文件，用于引导框架启动
 * ======================================================================== */
//系统当前时间
define('TIME',$_SERVER['REQUEST_TIME']);
//系统路径
define('CORE',PHPCMS.'/core/');#核心库
define('ASSIGN',MODULE.'assign/');#资源库
//加载公共函数库
include CORE.'function/function.php';
//加载核心文件
include CORE.'phpcms.php';

//调试模式
define('DEBUG',true);
if(DEBUG){
    //打开php.ini的错误显示
    ini_set('display_errors','on');
}else{
    //关闭php.ini的错误显示
    ini_set('display_errors','off');
}

//注册自动加载
spl_autoload_register('\phpcms::load');
//开始跑框架
\phpcms::run();

