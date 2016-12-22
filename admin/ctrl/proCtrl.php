<?php
namespace admin\ctrl;
/**
 * adminCtrl short summary.
 *
 * adminCtrl description.
 *
 * @version 1.0
 * @author VIAL
 */

use admin\model\proModel;
class proCtrl extends commonCtrl
{
    public function index(){
        if(!array_key_exists('page',$_GET)) $_GET['page'] =5;
        $mobel = new proModel;
        $res = $mobel->selectPro();
        $this->assign('data',$res);
        $this->display('pro/index.html');
    }

    public function image(){
        if(!array_key_exists('page',$_GET)) $_GET['page'] =3;
        $mobel = new proModel;
        $res = $mobel->selectPro();
        $this->assign('data',$res);
        $this->display('image/index.html');
    }

    public function addPro(){
        $model = new proModel;
        if($_POST && $_FILES){
            if($model->addPro()){
                echo "<script>alert('添加成功');</script>";
            }else{
                echo "<script>alert('添加失败');</script>";
            }
        }else if($_POST && !$_FILES){
            echo "<script>alert('请上传图片');</script>";
        }
        $sql = "select * from shop_cate";
        $res = $model->select($sql);
        $this->assign('data',$res);
        $this->display('pro/add.html');
    }


    public function editPro(){
        if(!count($_GET) || !count($_POST)){
            $model = new proModel;
            if($_POST){
                if($model->editPro()){
                    echo "<script>alert('编辑成功');</script>";
                    header("Location: /admin/pro/index/page/5/");
                    return true;
                }
                echo "<script>alert('编辑失败');</script>";
                header("Location: /admin/pro/index/page/5/");
                return true;
            }
            $res = $model->onePro();
            $this->assign('data',$res);
            $this->display('pro/edit.html');
        }
    }

    public function outPro(){
        if($_GET){
            $model = new proModel;
            if($model->outPro()){
                echo '200';
                return;
            }
            echo '404';
        }
    }

    public function deletePro(){
        if($_GET){
            $model = new proModel;
            if($model->deletePro()){
                echo '200';
                return;
            }
            echo '404';
        }
    }


}