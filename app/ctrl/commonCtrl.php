<?php
namespace app\ctrl;
/**
 * commonCtrl short summary.
 *
 * commonCtrl description.
 *
 * @version 1.0
 * @author vial
 */
class commonCtrl extends \phpcms
{
    public function __construct(){
        // ��ʼ����ʱ�����û�Ȩ��
        if( !isset($_SESSION['userId']) && !isset($_COOKIE['userId']) ){
            $this->display('login/login.html'); exit;
        }else if( isset($_COOKIE['userId']) ){
            return true;
        }
    }
}