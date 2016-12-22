<?php
/* ========================================================================
 * 验证码 操作类
 * ======================================================================== */
namespace phpcms
{
	/**
	 * code short summary.
	 *
	 * code description.
	 *
	 * @version 1.0
	 * @author vial
	 */
	class code{

        protected $width;
        protected $height;
        protected $number;
        private $code;    //验证码
		private $imag;    //图像资源


        /**
         * 构造方法,设置三个参数
         * @param int $width
         * @param int $height
         * @param int $number
         */
        public function __construct($width,$height,$number){
            $this->width = $width;
            $this->height = $height;
            $this->number = $number;
			$this->code = $this->createCode(); //调用自己的方法
        }


        /**
         * 获取字符的验证码， 用于保存在服务器中
         */
		public function getCode() {
			return $this->code;
		}


        /**
         * 输出图像
         */
		public function createImage() {
			$this->createBack();//创建背景 (颜色， 大小， 边框)
			$this->createFont();//画字 (大小， 字体颜色)
			$this->createLine();//干扰元素(点， 线条)
			$this->createImag();//输出图像
		}


        /**
         * 创建背景
         */
		private function createBack() {
			$this->imag = imagecreatetruecolor($this->width, $this->height);//创建资源
			$bgcolor =  imagecolorallocate($this->imag, 255, 255, 255);//设置随机的背景颜色
			imagefill($this->imag, 0, 0, $bgcolor);//设置背景填充
		}


        /**
         * 创建字
         */
		private function createFont() {
			for($i=0; $i<$this->number; $i++) {
				$color= imagecolorallocate($this->imag, mt_rand(0,156), mt_rand(0,156), mt_rand(0,156));
                imagettftext($this->imag,mt_rand(15,30),mt_rand(-30,30),mt_rand(1,5)+($this->width/$this->number)*$i,$this->height / 1.4,$color,'SimHei.ttf',$this->code[$i]);//画出每个字符
			}
		}


        /**
         * 创建干扰元素
         */
		private function createLine() {
            for($i=0; $i<$this->number*5; $i++) {
                $color= imagecolorallocate($this->imag, 255, 255, 255);
                imagearc($this->imag,mt_rand(-10, $this->width+10), mt_rand(-10, $this->height+10), mt_rand($this->height, $this->width), mt_rand($this->height, $this->width), $this->width,$this->height, $color);
            }
		}


        /**
         * 输出图像
         */
		private function createImag() {
			if (imagetypes() & IMG_GIF) {
                header("Content-type: image/gif");
                imagegif($this->imag);
			} elseif (function_exists("imagejpeg")) {
                header("Content-type: image/jpeg");
                imagegif($this->imag);
			} elseif (imagetypes() & IMG_PNG) {
                header("Content-type: image/png");
                imagegif($this->imag);
			}  else {
                die("No image support in this PHP server");
			}
		}


        /**
         * 生成验证码字符串
         */
		private function createCode() {
            $chars_array = array(
                "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
                "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
                "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
                "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
                "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
                "S", "T", "U", "V", "W", "X", "Y", "Z",
              );
			$code = "";
			for($i=0; $i < $this->number; $i++) {
				$code .=$chars_array[mt_rand(0,count($chars_array)-1)];
			}
			return $code;
		}


        /**
         * 用于自动销毁图像资源
         */
		function deleteImage() {
			imagedestroy($this->imag);
		}

	}
}