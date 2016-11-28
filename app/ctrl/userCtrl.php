<?php
namespace app\ctrl;
/**
 * adminCtrl short summary.
 *
 * adminCtrl description.
 *
 * @version 1.0
 * @author VIAL
 */

use app\model\userModel;

class userCtrl extends \phpcms
{
    public function index(){
        if($_POST){
            $verifycd = md5($_POST['verify']);
            $verifyoe = $_SESSION['verify'];
            if($verifycd == $verifyoe){
                $model = new userModel;
                if($model->addUser()){
                    header('Location: /login/');
                }else{
                    echo '<script>window.alert("操作失败！");</script>';
                }
                $this->display('login/register.html');
                return;
            }
            echo '<script>window.alert("验证码填写不正确！");</script>';
        }
        $this->display('login/register.html');
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


}