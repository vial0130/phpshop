<?php
/* ========================================================================
 * PHPCMS模块入口文件，用于定义常量
 * ======================================================================== */
 // 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    header("Content-type: text/html; charset=utf-8");
    die('配置环境太低，建议升级 PHP5.3.0 以上版本!');
}
// 根目录
define('PHPCMS',$_SERVER['DOCUMENT_ROOT']);

// 默认项目目录
define('INDEX','app');

include PHPCMS.'/core/init.php';
?>
