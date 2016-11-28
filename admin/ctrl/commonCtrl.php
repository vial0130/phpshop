<?php
namespace admin\ctrl;
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
        // 初始化的时候检查用户权限
        if( !isset($_SESSION['adminId']) && !isset($_COOKIE['adminId']) ){
            $this->display('login/login.html'); exit;
        }else if( isset($_COOKIE['adminId']) ){
            return true;
        }
    }
}