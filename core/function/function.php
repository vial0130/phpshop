<?php
/* ========================================================================
 * 全局函数
 * ======================================================================== */
/**
 * 自定义的数组或变量的展现方式
 * @param string $var
 */
function dump($var)
{
    if (is_bool($var)) {
        var_dump($var);
    } else if (is_null($var)) {
        var_dump(NULL);
    } else {
        echo "<pre>" . print_r($var, true) . "</pre>";
    }
}

/**
 * 生成唯一字符串
 * @return string
 */
function md5Uniqid(){
	return md5(uniqid(microtime(true),true));
}


/**
 * 得到文件的扩展名
 * @param string $name 当前字符串
 * @param string $type 截取最后的
 * @return string
 */
function extName($type,$name){
	return strtolower(end(explode($type,$name)));//打散字符串得到最后一个默认小写
}
