<?php
/* ========================================================================
 * 缩略图 操作类
 * ======================================================================== */
namespace phpcms;

	/**
	 * thumbnail short summary.
	 *
	 * thumbnail description.
	 *
	 * @version 1.0
	 * @author vial
	 */
	class thumbnail
	{
        protected $name;  
        protected $address; //缩略图图像资源
        protected $width;
        protected $height;
        protected $boolean = false; //是否删除源文件，默认不保留
        protected $model = false; //默认裁切
        protected $shopImg = false; //默认不显示图像

        public function __construct()
        {
            $this->address = \phpcms\conf::get('THUMADDRESS','config');
        }


        /**
         * 可以通过连贯操作一次设置多个属性值
         * @param  string $key  成员属性名
         * @param  mixed  $val  为成员属性设置的值
         * @return  object     返回自己对象$this，可以用于连贯操作
         */
        public function set($key, $val){
            if( array_key_exists( $key, get_class_vars(get_class($this)) ) ){//判断数组中是否有值
                $this->$key = $val;
            }
            return $this;
        }


        /**
         * 生成缩略图
         * @return  object   返回图片资源
         */
        public function thumbnail()
        {
            $overWidth = 0; $overHeight = 0;
            $newName = extName('/',$this->name);
            list($setWidth, $setHeight, $setType) = getimagesize($this->name);
            //拼接方法函数
            $mime=image_type_to_mime_type($setType);
            $imagecreatefrom=str_replace("/", "createfrom", $mime);
            $image=str_replace("/", null, $mime);

            $info = $imagecreatefrom($this->name);//获取信息

            //把大图缩略到缩略图指定的范围内，不留白（原图会居中缩放，把超出的部分裁剪掉）
            if($this->model){
                if($setWidth/$this->width > $setHeight/$this->height){
                    $this->height = $this->width * ($setHeight/$setWidth);
                }else{
                    $this->width = $this->height * ($setWidth/$setHeight);
                }
                $canvas = imagecreatetruecolor($this->width, $this->height);//准备画布
                imagecopyresampled($canvas,$info, 0,0, 0,0, $this->width,$this->height,$setWidth,$setHeight);//复制图片
            }else{//把大图缩略到缩略图指定的范围内,可能有留白（原图细节不丢失）
                if($setWidth/$this->width > $setHeight/$this->height){
                    $copyHeight = $this->height;
                    $copyWidth = $copyHeight*($setWidth/$setHeight);
                    $overWidth = ($copyWidth-$this->width)/2;
                }else{
                    $copyWidth = $this->width;
                    $copyHeight = $copyWidth*($setHeight/$setWidth);
                    $overHeight = ($copyHeight-$this->height)/2;
                }
                $canvasCopy = imagecreatetruecolor($copyWidth,$copyHeight);
                // 先把图像放满区域
                imagecopyresampled($canvasCopy,$info,0,0,0,0,$copyWidth,$copyHeight,$setWidth,$setHeight);
                // 再截取到指定的宽高度
                $canvas = imagecreatetruecolor($this->width,$this->height);
                imagecopyresampled($canvas,$canvasCopy,0,0,0+$overWidth,0+$overHeight,$this->width,$this->height,$copyWidth-$overWidth*2,$copyHeight-$overHeight*2);
            }

            //检测拼接保存地址
            $this->address = $this->address.$this->width.'_'.$this->height.'/'.$newName;
            if($this->address && !file_exists(dirname($this->address))){
                mkdir(dirname($this->address),0777,true);
            }

            $image($canvas,$this->address);

            //释放图像
            //imagedestroy($canvas);
            imagedestroy($info);
            if(!$this->boolean){
                unlink($this->name);
            }
            if($this->shopImg){
                $this->createImag($canvas);//输出图像
            }else{
                return $this->address; //返回地址
            }
            
        }


        /**
         * 输出图像
         * @param mixed $canvas
         */
		private function createImag($canvas) {
			if (imagetypes() & IMG_GIF) {
                header("Content-type: image/gif");
                imagegif($canvas);
			} elseif (function_exists("imagejpeg")) {
                header("Content-type: image/jpeg");
                imagegif($canvas);
			} elseif (imagetypes() & IMG_PNG) {
                header("Content-type: image/png");
                imagegif($canvas);
			}  else {
                die("No image support in this PHP server");
			}
		}


	}
