<?php
namespace app\ctrl;

use app\model\userModel;

class loginCtrl extends \phpcms
{
    public function index()
    {
        $this->display('login/login.html');
    }

    public function login()
    {
        if($_POST){
            $verifycd = md5($_POST['verify']);
            $verifyoe = $_SESSION['verify'];
            if($verifycd == $verifyoe){
                $model = new userModel;
                $res = $model->selectUser();
                if($res){
                    echo '200';
                }else{
                    echo '账户密码填写不正确！！！';
                }
                return;
            }
            echo '验证码填写不正确！！！';
            return;
        }
        $this->display('login/login.html');
    }


    public function register()
    {
        $this->display('login/register.html');
    }

    public function exitLogin()
    {
        $session = new \phpcms\session();
        $session->clear();
        $cookie = new \phpcms\cookie();
        $cookie->clear();
        header('Location: /');
    }
}
