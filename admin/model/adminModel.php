<?php
namespace admin\model;
/**
 * adminModel short summary.
 *
 * adminModel description.
 *
 * @version 1.0
 * @author Administrator
 */

class adminModel extends \phpcms\model
{
    protected $table = 'admin';

    /**
     * 验证超级管理员
     * @author vial
     */
    public function selectAdmin()
    {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $sql ="SELECT `id` FROM `shop_admin` WHERE ( `username` = '{$username}' ) AND ( `password` = '{$password}' ) LIMIT 1";
        $res = $this->select($sql);
        if($res){
            $_SESSION['adminId'] = $res[0]['id'];
            if($_POST['auto']){
                setcookie('adminId',$res[0]['id'],time()+7*24*3600);
            }
            return true;
        }
        return false;
    }

    /**
     * 查询所有超级管理员
     * @author vial
     */
    public function selectUser()
    {
        if(!array_key_exists('name',$_GET)) $_GET['name'] =null;
        if(!array_key_exists('page',$_GET)) $_GET['page'] =7;
        //先实例化分页
        $return['count'] = $this->count();
        $shopnum = $_GET['page'];
        $page = new \phpcms\page ($return['count'],$shopnum);
        $return['page'] = $page->show();
        //在输出数据
        $pegenum = ($page->_page_num ? $page->_page_num : 1) - 1;
        $limit = [$pegenum * $shopnum,$shopnum];
        $sql ="SELECT `id`,`username`,`email` FROM `shop_admin`".
            ( $_GET['name'] ? " WHERE username LIKE '%".$_GET['name']."%'" : "").
            " LIMIT {$limit[0]},{$limit[1]}";
        $return['list'] = $this->select($sql);
        if($return) return $return;
        return false;
    }

    /**
     * 查询一个超级管理员
     * @author vial
     */
    public function selectOne()
    {
        $sql ="SELECT * FROM `shop_admin` WHERE id ='{$_GET['id']}'";
        $res = $this->select($sql);
        if($res) return $res;
        return false;
    }


    /**
     * 添加超级管理员
     * @author vial
     */
    public function addAdmin(){
        $_POST['password'] = md5($_POST['password']);
        $res = $this->insert($_POST);
        if($res) return true;
        return false;
    }

    /**
     * 更新超级管理员
     * @author vial
     */
    public function editAdmin(){
        $where = $_POST['id'];
        unset($_POST['id']);
        $_POST['password'] = md5($_POST['password']);
        $res = $this->update($_POST,'id = '.$where);
        if($res) return true;
        return false;
    }

    /**
     * 删除超级管理员
     * @author vial
     */
    public function deleteAdmin(){
        $where = $_GET['id'];
        $res = $this->delete('id = '.$where);
        if($res) return true;
        return false;
    }

}