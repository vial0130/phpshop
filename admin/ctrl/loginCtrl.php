<?php
namespace admin\ctrl;

use admin\model\adminModel;

class loginCtrl extends \phpcms
{
    public function index()
    {
        if($_POST && array_key_exists('verify',$_SESSION)){
            $verifycd = md5($_POST['verify']);
            $verifyoe = $_SESSION['verify'];
            if($verifycd == $verifyoe){
                $model = new adminModel;
                if($model->selectAdmin()){
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

    public function exitLogin()
    {
        $session = new \phpcms\session();
        $session->clear();
        $cookie = new \phpcms\cookie();
        $cookie->clear();
        header('Location: /admin/');
    }

    public function clearCache()
    {
        $cache = new \phpcms\cache();
        $cache->clear();
        header('Location: /admin/');
    }
}
