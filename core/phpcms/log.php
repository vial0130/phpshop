<?php
/* ========================================================================
 * 日志类
 * 日志驱动和储存位置请查看config/log.php
 * 使用方法(全局中)
 * \phpcms\log::log('DEBUG','出现了一个BUG');
 * ======================================================================== */
namespace phpcms;

class log
{
    static $class;
    /**
     * 类自加载，获取路径
     */
    public static function init()
    {
        $drive = conf::get('LOG_TYPE','log');
        $class = '\phpcms\lib\log\\'.$drive;
        self::$class = new $class;
    }

    public static function log($name,$file = 'log')
    {
        self::$class->log($name,$file);
    }
}