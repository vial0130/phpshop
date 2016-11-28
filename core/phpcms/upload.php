<?php
/* ========================================================================
 * 文件上传 操作类
 * ======================================================================== */
namespace phpcms;
/**
 * upload short summary.
 *
 * upload description.
 *
 * @version 1.0
 * @author vial
 */
class upload
{
    private $filePath; //上传文件保存的路径
    private $allowType; //设置限制上传文件的类型
    private $maxSize; //限制文件上传大小（字节）
    private $isRandom; //设置是否随机重命名文件

    private $originName; //源文件名
    private $newFileName; //新文件名
    private $tmpFileName; //临时文件名
    private $fileType;//文件类型(文件后缀)
    private $fileSize; //文件大小
    private $errorNum; //错误号
    private $errorMess; //错误报告消息


    /**
     * 初始化
     */
    public function __construct(){
        $this->errorMess = "";
        $this->errorNum = 0;
    }


    /**
     * 可以通过连贯操作一次设置多个属性值
     * @param  string $key  成员属性名
     * @param  mixed  $val  为成员属性设置的值
     * @return  object     返回自己对象$this，可以用于连贯操作
     */
    public function set($key, $val){
        if( array_key_exists( $key, get_class_vars(get_class($this)) ) ){//判断数组中是否有值
            $this->setOption($key, $val);
        }
        return $this;
    }


    /**
     * 调用该方法上传文件
     * @param  string $fileFile  上传文件的表单名称
     * @return boolean
     */
    public function upload($fileField) {
        /* 检查文件路径是否合法 */
        if( !$this->checkFilePath() ) {
            $this->errorMess = $this->setError();
            return false;
        }
        /* 将文件上传的信息取出赋给变量 */
        $name = $_FILES[$fileField]['name'];
        $tmp_name = $_FILES[$fileField]['tmp_name'];
        $size = $_FILES[$fileField]['size'];
        $error = $_FILES[$fileField]['error'];

        /* 如果是多个文件上传则$file["name"]会是一个数组 */
        if(is_Array($name)){
            /* 存放所错误信息的变量数组 */
            $errors=array();
            /* 存放所有上传后文件名的变量数组 */
            $fileNames = array();
            for($i = 0; $i < count($name); $i++){
                /*设置文件信息 */
                if($this->setFiles($name[$i],$tmp_name[$i],$size[$i],$error[$i] )) {
                    if($this->checkFileSize() && $this->checkFileType()){
                        /* 为上传文件设置新文件名 */
                        $this->setNewFileName();
                        /* 上传文件  返回0为成功， 小于0都为错误 */
                        if($this->uploadFile()){
                            $fileNames[] = $this->newFileName;
                            return true;
                        }
                    }
                }
                if($this->errorNum != 0) $errors[] = $this->setError();
            }
            //将错误信息保存在属性errorMess中
            $this->newFileName = $fileNames;
            $this->errorMess = $errors;
        } else { //上传单个文件处理方法
            /* 设置文件信息 */
            if($this->setFiles($name,$tmp_name,$size,$error)) {
                /* 上传之前先检查一下大小和类型 */
                if($this->checkFileSize() && $this->checkFileType()){
                    /* 为上传文件设置新文件名 */
                    $this->setNewFileName();
                    /* 上传文件  返回0为成功， 小于0都为错误 */
                    $this->uploadFile();
                    return true;
                }
            }
            if($this->errorNum != 0) $this->errorMess = $this->setError();
        }
    }

    /**
     * 多文件上传验证通过率再上传
     * @param  string $fileFile  上传文件的表单名称
     * @return boolean
     */
    public function verifyUpload($fileField){
        $return = false;
        /* 检查文件路径是否合法 */
        if( !$this->checkFilePath() ) {
            $this->errorMess = $this->setError();
            return false;
        }
        /* 将文件上传的信息取出赋给变量 */
        $name = $_FILES[$fileField]['name'];
        $tmp_name = $_FILES[$fileField]['tmp_name'];
        $size = $_FILES[$fileField]['size'];
        $error = $_FILES[$fileField]['error'];

        /* 如果是多个文件上传则$file["name"]会是一个数组 */
        if(is_Array($name)){
            /* 存放所错误信息的变量数组 */
            $errors=array();
            /* 存放所有上传后文件名的变量数组 */
            $fileNames = array();
            for($i = 0; $i < count($name); $i++){
                /*设置文件信息 */
                $return = false;
                if($this->setFiles($name[$i],$tmp_name[$i],$size[$i],$error[$i] )) {
                    if($this->checkFileSize() && $this->checkFileType()){
                        $return = true;
                    }
                }
                if($this->errorNum != 0) $errors[] = $this->setError();
            }
            //将错误信息保存在属性errorMess中
            $this->newFileName = $fileNames;
            $this->errorMess = $errors;
        }
        if($return){
            $this->upload($fileField);
            return true;
        }else{
            return false;
        }
    }


    /**
     * 获取上传后的文件名称
     * @param  void   没有参数
     * @return string 上传后，新文件的名称， 如果是多文件上传返回数组
     */
    public function getFileName(){
        return $this->newFileName;
    }

    /**
     * 获取上传失败的文件名称,上传出错信息
     * @param  void   没有参数
     * @return string  返回上传文件出错的信息报告，如果是多文件上传返回数组
     */
    public function getErrorMsg(){
        return $this->errorMess;
    }

    /**
     * 获取上传后的文件地址
     * @param  void   没有参数
     * @return string  返回上传文件出错的信息报告，如果是多文件上传返回数组
     */
    public function getFilePath(){
        return $this->filePath;
    }

    /**
     * 设置上传出错信息
     * @return string  返回上传文件出错的信息
     */
    private function setError() {
        $str = "上传文件<font color='red'>{$this->originName}</font>时出错 : ";
        switch ($this->errorNum) {
            case 4: $str .= "没有文件被上传"; break;
            case 3: $str .= "文件只有部分被上传"; break;
            case 2: $str .= "上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值"; break;
            case 1: $str .= "上传的文件超过了php.ini中upload_max_filesize选项限制的值"; break;
            case -1: $str .= "未允许类型"; break;
            case -2: $str .= "文件过大,上传的文件不能超过{$this->maxSize}个字节"; break;
            case -3: $str .= "上传失败"; break;
            case -4: $str .= "建立存放上传文件目录失败，请重新指定上传目录"; break;
            case -5: $str .= "必须指定上传文件的路径"; break;
            default: $str .= "未知错误";
        }
        return $str.'<br>';
    }


    /**
     * 设置和$_FILES有关的内容
     * @param  string  $name 当前名称
     * @param  string  $tmp_name 当前缓存名称
     * @param  mixed  $size 当前文件大小
     * @param  mixed  $error 当前错误信息
     * @return boolean
     */
    private function setFiles($name, $tmp_name, $size, $error) {
        if($error){
            $this->setOption('errorNum', $error);
            return false;
        }
        $this->setOption('errorNum', $error);
        $this->setOption('originName', $name);
        $this->setOption('tmpFileName',$tmp_name);
        $this->setOption('fileType', extName('.',$name));//获取文件类型
        $this->setOption('fileSize', $size);
        return true;
    }


    /**
     * 为单个成员属性设置值
     * @param  string  $key 成员对象
     * @param  mixed  $val 赋值
     * @return
     */
    private function setOption($key,$val) {
        $this->$key = $val;
    }


    /**
     * 是否设置新的文件名称
     * @return
     */
    private function setNewFileName() {
        if ($this->isRandom) {
            $this->setOption('newFileName', $this->randomName());
        } else{
            $this->setOption('newFileName', $this->originName);
        }
    }


    /**
     * 检查上传的文件是否是合法的类型
     * @return boolean
     */
    private function checkFileType() {
        if (in_array(strtolower($this->fileType), $this->allowType)) {
            //如果是图片多一层验证
            if($this->fileType=='jpeg'||$this->fileType=='jpg'||$this->fileType=='png'||$this->fileType=='gif'){
                if(!getimagesize($this->tmpFileName)){
                    $this->setOption('errorNum', -1);
                    return false;
                }
            }
            return true;
        }
        $this->setOption('errorNum', -1);
        return false;
    }


    /**
     * 检查上传的文件是否是允许的大小
     * @return boolean
     */
    private function checkFileSize() {
        if ($this->fileSize > $this->maxSize) {
            $this->setOption('errorNum', -2);
            return false;
        }else{
            return true;
        }
    }


    /**
     * 检查是否有存放上传文件的目录,没有则是否创建成功
     * @return boolean
     */
    private function checkFilePath() {
        if(empty($this->filePath)){
            $this->setOption('errorNum', -5);
            return false;
        }
        if ( !is_dir($this->filePath) ) {
            if ( !mkdir($this->filePath,0777,true) ) {
                $this->setOption('errorNum', -4);
                return false;
            }
        }
        return true;
    }


    /**
     * 设置随机文件名
     * @return string 返回随机文件名
     */
    private function randomName() {
        $fileName = date('YmdHis')."_".rand(100,999);
        return $fileName.'.'.$this->fileType;
    }


    /**
     * 复制上传文件到指定的位置
     * @return boolean
     */
    private function uploadFile() {
        if(!$this->errorNum) {
            $path = rtrim($this->filePath, '/').'/'.$this->newFileName;
            if(move_uploaded_file($this->tmpFileName, $path)) {
                return true;
            }
        }
        $this->setOption('errorNum', -3);
        return false;
    }


}