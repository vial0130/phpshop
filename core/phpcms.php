<?php
/* ========================================================================
 * ppphp核心类
 * 实现以下几个功能
 * 类自动加载
 * 启动框架
 * 引入模型
 * 引入视图
 * ======================================================================== */
class phpcms
{
    /**
     * classMap用于存放已经加载过的类文件,下次加载的时候直接返回
     */
    public static $classMap = array();
    /**
     * model用于存放已经加载的model模型,下次加载时直接返回
     */
    public $model;
    /**
     * 视图赋值
     */
    public $assign;


    /**
     * 框架启动方法,完成了两件事情
     * 1.加载route解析当前URL
     * 2.找到对应的控制以及方法,并运行
     */
    static public function run()
    {
        $route = new \phpcms\route();
        \phpcms\log::init();

        $action = $route->action;
        $ctrlFile = MODULE.'/ctrl/'.$route->ctrl.'Ctrl.php';
        $ctrlClass = MODULECTRL.$route->ctrl.'Ctrl';

        if(is_file($ctrlFile)){
            include $ctrlFile;
            \phpcms\log::log($ctrlFile);
        }else{
            throw new \Exception($ctrlClass.'是一个不存在的控制器');
        }
        $ctrl = new $ctrlClass();
        $ctrl->$action();
    }


    /**
     * 自动加载类
     * 先判断类是否已经加载,如果加载过直接从classMap中返回,如果没有加载的话,先到CORE中查找,再在整个框架中进行查找
     * @param $class 需要加载的类,需要带上命名空间
     * @return true
     */
    public static function load($class)
    {
        if(isset($classMap[$class])){
            return true;
        }else{
            $class = str_replace('\\','/',trim($class,'\\'));
            if(is_file(CORE.$class.'.php')){
                include_once CORE.$class.'.php';
                self::$classMap[] = $class;
            }else{
                if(is_file(PHPCMS.'/'.$class.'.php')) {
                    include_once PHPCMS.'/'.$class.'.php';
                    self::$classMap[] = $class;
                }
            }
        }
    }


    /**
     * 为模板对象赋值
     */
    public function assign($name,$value)
    {
        $this->assign[$name] = $value;
    }



    /**
     * 用于在控制器中加载一个模板文件
     */
    public function display($file)
    {
        if(is_file(MODULE.'views/'.$file))
        {
            @extract($this->assign);
            include MODULE.'views/'.$file;
        } else {
            throw new Exception($file.'是一个不存在的模板文件');
        }
    }
}
