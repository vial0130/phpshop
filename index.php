<?php
/* ========================================================================
 * PHPCMS入口文件
 * ======================================================================== */

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    header("Content-type: text/html; charset=utf-8");
    die('配置环境太低，建议升级 PHP5.3.0 以上版本!');
}

function module(){
    if(isset($_SERVER['REQUEST_URI'])){
        $pathstr = str_replace($_SERVER['SCRIPT_NAME'],'',$_SERVER['REQUEST_URI']);
        $path = explode("/",trim($pathstr,"/"));
        if( is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$path[0]) ){
            return $path[0];
        }
    }
}

//引导页面
$path = module() ? module() : 'app';
include './'.$path.'/index.php';
