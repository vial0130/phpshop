<?php
namespace app\ctrl;
use app\model\indexModel;

class indexCtrl extends \phpcms
{
    public function index()
    {
        $model = new indexModel;
        $res = $model->selectIndex();
        $this->assign('data',$res);
        $this->display('index/index.html');
    }

    public function details(){
        if(array_key_exists('id',$_GET)){
            $model = new indexModel;
            $res = $model->selectDetails();
            if($res){
                $this->assign('data',$res);
                $this->display('index/product.html');
            }else{
                echo '信息出现未知错误，请重试！';
            }
        }
    }

    public function classlist(){
        if(array_key_exists('id',$_GET)){
            $model = new indexModel;
            $res = $model->selectClass();
            if($res){
                $this->assign('data',$res);
                $this->display('list/class.html');
            }else{
                echo '信息出现未知错误，请重试！';
            }
        }
    }
}

?>