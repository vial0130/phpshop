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

use admin\model\userModel;

class userCtrl extends commonCtrl
{
    public function index(){
        $mobel = new userModel;
        $res = $mobel->selectUser();
        $this->assign('data',$res);
        $this->display('user/index.html');
    }


    public function editUser(){
        if($_POST){
            $model = new userModel;
            if($model->editUser()){
                echo '200';
                return;
            }
            echo '404';
        }
    }

    public function deleteUser(){
        if($_GET){
            $model = new userModel;
            if($model->deleteUser()){
                echo '200';
                return;
            }
            echo '404';
        }
    }


}