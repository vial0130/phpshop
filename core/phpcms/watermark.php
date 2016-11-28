<?php
/* ========================================================================
 * 图片加水印（适用于png/jpg/gif格式） 操作类
 * 返回 return
 * -1 ：原文件不是可用图片类型
 * -2 ：水印图片不是可用图片类型
 * -3 ：原文件图像对象建立失败
 * -4 ：水印文件图像对象建立失败
 * -5 ：保存失败
 * ======================================================================== */
namespace phpcms;
{
	/**
     * watermark short summary.
     *
     * watermark description.
     *
     * @version 1.0
     * @author Administrator
     */
	class watermark
	{
        protected $photo;   //原图片
        protected $water;   //水印图片
        protected $waterW;   //水印图片宽
        protected $waterH;   //水印图片高
        protected $positon; //1:顶部居左, 2:顶部居右, 3:居中, 4:底部局左, 5:底部居右
        protected $alpha;   //0:完全透明, 100:完全不透明

        /**
         * 初始化
         */
        public function __construct(){
                $this->alpha = 30;
                $this->positon = 3;
            }

        /**
         * 可以通过连贯操作一次设置多个属性值
         * @param  string $key  成员属性名
         * @param  mixed  $val  为成员属性设置的值
         * @return  object     返回自己对象$this，可以用于连贯操作
         */
        public function set($key, $val){
            if( array_key_exists( $key, get_class_vars(get_class($this)) )){//判断数组中是否有值
                $this->$key = $val;
            }
            return $this;
        }


        /**
         * 调用该方法添加水印
         * @param  string $fileFile  上传文件的表单名称
         * @return boolean
         */
        public function waterMark()
        {
            $photoinfo = getimagesize($this->photo);
            if (!$photoinfo) {
                return -1; //原文件不是可用图片类型
            }
            $waterinfo = getimagesize($this->water);
            if (!$waterinfo) {
                return -2; //水印图片不是可用图片类型
            }
            if($this->waterW && $this->waterH){
                $waterinfo[0] = $this->waterW;
                $waterinfo[1] = $this->waterH;
                $waterinfo[3] = "width=\"{$this->waterW}\" height=\"{$this->waterH}\"";
            }
            $photoObj = $this->create_from_ext($this->photo);
            if (!$photoObj) {
                return -3; //原文件图像对象建立失败
            }
            $waterObj = $this->create_from_ext($this->water);
            if (!$waterObj) {
                return -4; //水印文件图像对象建立失败
            }
            switch ($this->positon) {
                case 1: $x=$y=0; break;
                case 2: $x = $photoinfo[0]-$waterinfo[0]; $y = 0; break;
                case 3: $x = ($photoinfo[0]-$waterinfo[0])/2; $y = ($photoinfo[1]-$waterinfo[1])/2; break;
                case 4: $x = 0; $y = $photoinfo[1]-$waterinfo[1]; break;
                case 5: $x = $photoinfo[0]-$waterinfo[0]; $y = $photoinfo[1]-$waterinfo[1]; break;
                default: $x=$y=0;
            }
            imagecopymerge($photoObj, $waterObj, $x, $y, 0, 0, $waterinfo[0], $waterinfo[1], $this->alpha);
            switch ($photoinfo[2]) {
                case 1: imagegif($photoObj, $this->photo); break;
                case 2: imagejpeg($photoObj, $this->photo); break;
                case 3: imagepng($photoObj, $this->photo); break;
                default: return -5; //保存失败
            }
            imagedestroy($photoObj);
            imagedestroy($waterObj);
            return $this->photo;
        }


        /**
         * 获取图片信息
         * @param  string $fileFile  上传文件的表单名称
         * @return array
         */
        protected function create_from_ext($imgfile)
        {
            $info = getimagesize($imgfile);
            $im = null;
            switch ($info[2]) {
                case 1: $im=imagecreatefromgif($imgfile); break;
                case 2: $im=imagecreatefromjpeg($imgfile); break;
                case 3: $im=imagecreatefrompng($imgfile); break;
            }
            return $im;
        }

	}
}