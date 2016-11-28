<?php

namespace admin\model;

/**
 * albumModel short summary.
 *
 * albumModel description.
 *
 * @version 1.0
 * @author vial
 */

use phpcms\conf;

class albumModel extends \phpcms\model
{
    protected $table = 'album';

    /**
     * pro模块调用方法
     * @author vial
     */
    public function addAlbum($data)
    {
        return $this->insert($data);
    }
    public function editAlbum($data,$where)
    {
        return $this->update($data,$where);
    }
    public function deleteAlbum($data)
    {
        return $this->delete($data);
    }

    /**
     * 添加图片
     * @author vial
     */
    public function addImg(){
        $upload = new \phpcms\upload;
        $upload->set('filePath',conf::get('PICPATH','config'));
        $upload->set('maxSize',conf::get('PICSIZE','config'));
        $upload->set('isRandom',conf::get('PICRANDOM','config'));
        $upload->set('allowType',conf::get('PICTYPE','config'));
        if($upload->upload('proimg')){
            $fliename = $upload->getFileName();
            $dataArr = conf::get('PROSIZE','config'); // 加载图片尺寸数量
            if( $fliename ){
                foreach( $dataArr as $a=>$b){
                    $thumbnail = new \phpcms\thumbnail;
                    $thumbnail->set('name',ASSIGN.'upload/picture/'.$fliename);
                    $thumbnail->set('width',$a);
                    $thumbnail->set('height',$b);
                    if($a == count($dataArr)-1){
                        $thumbnail->set('boolean',false);
                    }else{
                        $thumbnail->set('boolean',true);
                    }
                    $tAddress[$a] = $thumbnail->thumbnail();
                }
                //存入到图片
                $sql = "SELECT path FROM shop_album WHERE pid={$_POST['id']}";
                $res = $this->select($sql);
                if($res){
                    $data = unserialize($res[0]['path']);
                    if(count($data) < 4){
                        $data[] = $fliename;
                        $insert['path'] = serialize($data);
                        if($this->update($insert,'pid='.$_POST['id'])){
                            return true;
                        }
                    }
                }
                foreach($tAddress as $v){
                    if(file_exists($v)){
                        unlink($v);
                    }
                }
            }
        }
        return false;
    }

     /**
     * 添加水印
     * @author vial
     */
   public function addWater(){
       $sql = "SELECT path FROM shop_album WHERE pid={$_POST['id']}";
       $path = $this->select($sql);
       if($path){
           $upload = new \phpcms\upload;
           $upload->set('filePath',conf::get('WATERPATH','config'));
           $upload->set('maxSize',conf::get('PICSIZE','config'));
           $upload->set('isRandom',conf::get('PICRANDOM','config'));
           $upload->set('allowType',conf::get('PICTYPE','config'));
           if($upload->upload('waterimg')){
               $arrImg = conf::get('PROSIZE','config');
               $path =  unserialize($path[0]['path']);
               $res = array();
               $waterinfo = getimagesize($upload->getFilePath().'/'.$upload->getFileName());
               foreach($arrImg as $k=>$v){
                   foreach($path as $img){
                       $water = new \phpcms\watermark;
                       $water->set('photo',ASSIGN.'thumbnail/'.$k.'_'.$v.'/'.$img);
                       $water->set('water',$upload->getFilePath().'/'.$upload->getFileName());
                       $water->set('positon',$_POST['positon']);
                       $water->set('alpha',$_POST['alpha']);
                       $water->set('waterW',$waterinfo[0]/(800/$k));
                       $water->set('waterH',$waterinfo[1]/(800/$k));
                       $res[] = $water->waterMark();
                   }
               }
               if(file_exists($upload->getFilePath().'/'.$upload->getFileName())){
                   unlink($upload->getFilePath().'/'.$upload->getFileName());
               }
               if($res) return true;
           }
       }
       return false;
   }

    /**
     * 删除图片
     * @author vial
     */
    public function deleteImg(){
        $sql = "SELECT path FROM shop_album WHERE pid={$_GET['id']}";
        $res = $this->select($sql);
        if($res){
            $data['path'] = unserialize($res[0]['path']);
            $delete = intval($_GET['deletes'])-1;
            array_splice($data['path'],$delete,1);
            $data['path'] = serialize($data['path']);
            if($this->update($data,'pid='.$_GET['id'])){
                return true;
            }
        }
        return false;
    }


}
