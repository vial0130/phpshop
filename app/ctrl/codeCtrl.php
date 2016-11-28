<?php
namespace app\ctrl;
class codeCtrl extends \phpcms
{
    public function index()
    {
        $w=isset($_GET['w'])?$_GET['w']:40;
        $h=isset($_GET['h'])?$_GET['h']:30;
        $n=isset($_GET['n'])?$_GET['n']:2;
        $code = new \phpcms\code($w,$h,$n);
        $code->createImage();
        $sessions = new \phpcms\session;
        $sessions->set('verify',md5($code->getCode()));
    }
}

?>