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

class userModel extends \phpcms\model
{
    protected $table = 'user';

    /**
     * 查询所有用户
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
        $sql ="SELECT `id`,`username`,`email`,`sex` FROM `shop_user`".
            ( $_GET['name'] ? " WHERE username LIKE '%".$_GET['name']."%'" : "").
            " LIMIT {$limit[0]},{$limit[1]}";
        $return['list'] = $this->select($sql);
        if($return) return $return;
        return false;
    }


    /**
     * 更新超级用户
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

    /**
     * 删除超级用户
     * @author vial
     */
    public function deleteUser(){
        $where = $_GET['id'];
        $res = $this->delete('id = '.$where);
        if($res) return true;
        return false;
    }

}