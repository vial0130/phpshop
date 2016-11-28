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

use admin\model\adminModel;

class adminCtrl extends commonCtrl
{
    public function index(){
        $mobel = new adminModel;
        $res = $mobel->selectUser();
        $this->assign('data',$res);
        $this->display('admin/index.html');
    }

    public function addAdmin(){
        if($_POST){
            $model = new adminModel;
            if($model->addAdmin()){
                echo '<script>window.alert("操作成功！");</script>';
            }else{
                echo '<script>window.alert("操作失败！");</script>';
            }
        }
        $this->display('admin/add.html');
    }

    public function editAdmin(){
        if($_POST){
            $model = new adminModel;
            if($model->editAdmin()){
                echo '200';
                return;
            }
            echo '404';
        }
    }

    public function deleteAdmin(){
        if($_GET){
            $model = new adminModel;
            if($model->deleteAdmin()){
                echo '200';
                return;
            }
            echo '404';
        }
    }


}