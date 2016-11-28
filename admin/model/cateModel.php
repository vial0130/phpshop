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

class cateModel extends \phpcms\model
{
    protected $table = 'cate';

    /**
     * 查询所有分类
     * @author vial
     */
    public function selectCate()
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
        $sql ="SELECT `id`,`name` FROM `shop_cate`".
            ( $_GET['name'] ? " WHERE name LIKE '%".$_GET['name']."%'" : "").
            " LIMIT {$limit[0]},{$limit[1]}";
        $return['list'] = $this->select($sql);
        if($return) return $return;
        return false;
    }


    /**
     * 添加分类
     * @author vial
     */
    public function addCate(){
        $res = $this->insert($_POST);
        if($res) return true;
        return false;
    }

    /**
     * 更新分类
     * @author vial
     */
    public function editCate(){
        $where = $_POST['id'];
        unset($_POST['id']);
        $res = $this->update($_POST,'id = '.$where);
        if($res) return true;
        return false;
    }

    /**
     * 删除分类
     * @author vial
     */
    public function deleteCate(){
        $where = $_GET['id'];
        $res = $this->delete('id = '.$where);
        if($res) return true;
        return false;
    }

}