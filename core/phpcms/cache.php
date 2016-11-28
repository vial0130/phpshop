<?php
/* ========================================================================
 * 缓存类
 * 缓存驱动和储存位置请查看config/cacge.php
 * ======================================================================== */
namespace phpcms;


class cache
{
    private $caches;
    /**
     * 类自加载，获取路径
     */
    public function __construct()
    {
            $type = \phpcms\conf::get('CACHE_TYPE','cache');
            $option = \phpcms\conf::get('OPTION','cache');
            $class = '\\phpcms\\lib\\cache\\'.$type;
            $this->caches = new $class($option);
    }

    public function get($name)
    {
        return $this->caches->get($name);
    }

    public function set($name, $value, $time = false)
    {
        return $this->caches->set($name,$value,$time);
    }

    public function del($name)
    {
        return $this->caches->del($name);
    }

    public function clear()
    {
        return $this->caches->clear();
    }

}