<?php
namespace admin\ctrl;
/**
 * adminCtrl short summary.
 *
 * adminCtrl description.
 *
 * @version 1.0
 * @author VIAL
 */

use admin\model\cateModel;

class cateCtrl extends commonCtrl
{
    public function index(){
        $mobel = new cateModel;
        $res = $mobel->selectCate();
        $this->assign('data',$res);
        $this->display('cate/index.html');
    }

    public function addCate(){
        if($_POST){
            $model = new cateModel;
            if($model->addCate()){
                echo '200';
                return;
            }else{
                echo '404';
            }
        }
    }

    public function editCate(){
        if($_POST){
            $model = new cateModel;
            if($model->editCate()){
                echo '200';
                return;
            }
            echo '404';
        }
    }

    public function deleteCate(){
        if($_GET){
            $model = new cateModel;
            if($model->deleteCate()){
                echo '200';
                return;
            }
            echo '404';
        }
    }


}