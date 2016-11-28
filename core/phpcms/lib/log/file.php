<?php
/* ========================================================================
 * 日志类驱动
 * ======================================================================== */
namespace phpcms\lib\log;

use phpcms\conf;

class file
{
    public $path;
    /**
     * 类自加载，获取配置
     */
    public function __construct()
    {
        $this->path = conf::get('LOG_PATH','log');
    }

    /**
     * 创建日志文件,返回写入文件中
     * @param string $message 配置名
     * @param string $file 文件名
     * @return
     */
    public function log($message,$file = 'log')
    {
        if(!is_dir($this->path.date('YmdH'))){
            mkdir($this->path.date('YmdH'),0777,true);
        }
        return file_put_contents($this->path.date('YmdH').'/'.$file.'.php',date('Y-m-d H:i:s').json_encode($message).PHP_EOL,FILE_APPEND);
    }
}
?>