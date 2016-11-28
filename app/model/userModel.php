<?php
namespace app\model;
/**
 * adminModel short summary.
 *
 * adminModel description.
 *
 * @version 1.0
 * @author Administrator
 */
use \phpcms\conf;

class userModel extends \phpcms\model
{
    protected $table = 'user';

    /**
     * 验证用户
     * @author vial
     */
    public function selectUser()
    {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $sql ="SELECT `id` FROM `shop_user` WHERE ( `username` = '{$username}' ) AND ( `password` = '{$password}' ) LIMIT 1";
        $res = $this->select($sql);
        if($res){
            $_SESSION['userId'] = $res[0]['id'];
            if($_POST['auto']){
                setcookie('userId',$res[0]['id'],time()+7*24*3600);
            }
            return true;
        }
        return false;
    }


    /**
     * 添加超级管理员
     * @author vial
     */
    public function addUser(){
        $_POST['password'] = md5($_POST['password']);
        unset($_POST['verify']);
        $_POST['regtime'] = time();
        $upload = new \phpcms\upload;
        $upload->set('filePath',ASSIGN.'upload/loginFace/'.$_POST['username']);
        $upload->set('maxSize',conf::get('PICSIZE','config'));
        $upload->set('isRandom',false);
        $upload->set('allowType',conf::get('PICTYPE','config'));
        $upload->upload('face');
        if($upload->getFileName()){
            $_POST['face']=$upload->getFileName();
            $res = $this->insert($_POST);
            if($res) return true;
        }
        if(file_exists($upload->getFilePath().'/'.$upload->getFileName())){
            unlink($upload->getFilePath().'/'.$upload->getFileName());
        }
        return false;
    }


    /**
     * 更新用户
     * @author vial
     */
    public function editUser(){
        $where = $_POST['id'];
        unset($_POST['id']);
        $_POST['password'] = md5($_POST['password']);
        $res = $this->update($_POST,'id = '.$where);
        if($res) return true;
        return false;
    }

}