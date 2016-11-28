<?php
/* ========================================================================
 * PHPCMS模块入口文件，用于定义常量
 * ======================================================================== */
// 根目录
define('PHPCMS',$_SERVER['DOCUMENT_ROOT']);
// 模块路径
define('MODULE',PHPCMS.'/admin/');
define('MODULECTRL','\admin\ctrl\\');

include PHPCMS.'/core/init.php';
?>