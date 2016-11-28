<?php
namespace admin\ctrl;
/**
 * albumCtrl short summary.
 *
 * albumCtrl description.
 *
 * @version 1.0
 * @author vial
 */
use admin\model\albumModel;

class albumCtrl extends commonCtrl
{
    public function addImg(){
        $model = new albumModel;
        if($_POST && $_FILES){
            if($model->addImg()){
                echo "<script>alert('添加成功');</script>";
            }else{
                echo "<script>alert('添加失败');</script>";
            }
        }else if($_POST && !$_FILES){
            echo "<script>alert('请上传图片');</script>";
        }
        header('Location: /admin/pro/image/');
    }

    public function deleteImg(){
        if($_GET && array_key_exists('deletes',$_GET)){
            $model = new albumModel;
            if($model->deleteImg()){
                echo 200; return;
            }else{
                echo 404; return;
            }
        }
    }

    public function addWater(){
        $model = new albumModel;
        if($_POST && $_FILES){
            if($model->addWater()){
                echo "<script>alert('添加成功');</script>"; 
            }else{
                echo "<script>alert('添加失败');</script>";
            }
            header('Location: /admin/pro/image/'); return;
        }else if($_GET){
            $sql = "SELECT a.id,a.name,b.path FROM shop_pro AS a INNER JOIN shop_album AS b ON a.id = b.pid WHERE a.id ={$_GET['id']}";
            $res = $model->select($sql);
            $res[0]['path'] = unserialize($res[0]['path']);
            $this->assign('data',$res[0]);
            $this->display('image/add.html');
        }
    }


}
