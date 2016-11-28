<?php
/* ========================================================================
 * 路由类
 * 主要功能,解析URL
 * ======================================================================== */
namespace phpcms;

class route
{
    public $ctrl;
    public $action;
    public $path;
    public $route;
    public function __construct()
    {
        $route = conf::all('route');
        if(isset($_SERVER['REQUEST_URI'])) {
            $pathstr = str_replace($_SERVER['SCRIPT_NAME'],'',$_SERVER['REQUEST_URI']);
            //丢掉?以及后面的参数
            $path = explode('?',$pathstr);
            //去掉多余的分隔符
            $path = explode('/',trim($path[0],'/'));
            //去除模块名
            if( is_dir(PHPCMS.'/'.$path[0]) ){
                unset($path[0]);
                $path=array_values($path);
            }
            if(isset($path[0]) && $path[0]) {
                $this->ctrl = $path[0];
                unset($path[0]);
            } else {
                $this->ctrl = $route['DEFAULT_CTRL'];
            }
            //检测是否包含路由缩写
            if(isset($route['ROUTE'][$this->ctrl])) {
                $this->action = $route['ROUTE'][$this->ctrl][1];
                $this->ctrl = $route['ROUTE'][$this->ctrl][0];
            } else {
                if (isset($path[1]) && $path[1]) {
                    $have = strstr($path[1], '?', true);
                    if ($have) {
                        $this->action = $have;
                    } else {
                        $this->action = $path[1];
                    }

                } else {
                    $this->action = $route['DEFAULT_ACTION'];
                }
                unset($path[1]);
            }

            $this->path = array_merge($path);
            $pathlenth = count($path);
            $i = 0;
            while($i < $pathlenth) {
                if(isset($this->path[$i+1])) {
                    $_GET[$this->path[$i]] = $this->path[$i + 1];
                }
                $i = $i + 2;
            }
        } else {

            $this->ctrl = conf::get('DEFAULT_CTRL','route');
            $this->action = conf::get('DEFAULT_ACTION','route');
        }
    }

}
?>