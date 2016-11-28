<?php
namespace phpcms\lib\cache;

use phpcms\conf;

class file
{
    private $path;  #存储路径
    private $time;  #存活时间

    /**
     * 类自加载，设置参数
     */
    public function __construct($option)
    {
        foreach($option as $key=>$a) {
            $this->$key = $a;
        }
    }

    /**
     * 获得缓存，返回值
     * @param string $name 需要获取缓存的名字
     * @return string 获得缓存，如果没有则返回false
     */
    public function get($name)
    {
        if(is_file($this->path.'/'.$name.'.cache')) {
            $ret = json_decode(file_get_contents($this->path . '/' . $name . '.cache'), true);
            if($ret['time'] == 0 || $ret['time'] >= TIME) {
                return $ret['data'];
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * 新建缓存，返回值
     * @param string $name 设置缓存的名字
     * @param string $value 设置缓存的数据
     * @param string $time 设置缓存的时间
     * @return string 如果成功则true
     */
    public function set($name,$value,$time)
    {
        if (!is_dir($this->path))
        {
            mkdir($this->path,0755,true);
        }
        if($time === false) {
            $time  = $this->time;
            $time += TIME;
        } else if($time === 0){
            $time = 0;
        } else {
            $time += TIME;
        }
        $file = $this->path.'/'.$name.'.cache';
        $data['data'] = $value;
        $data['time'] = $time;
        return file_put_contents($file, json_encode($data));
    }

    /**
     * 删除一个缓存，返回值
     * @param string $name 需要获取缓存的名字
     * @return boolean 如果成功则true
     */
    public function del($name)
    {
        $file = $this->path.'/'.$name.'.cache';
        if (is_file($file)) {
            return unlink($file);
        } else {
            return false;
        }
    }

    /**
     * 删除全部缓存，返回值
     * @return string 如果成功则true
     */
    public function clear()
    {
        $dh=opendir($this->path);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$this->path."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }
    }
}