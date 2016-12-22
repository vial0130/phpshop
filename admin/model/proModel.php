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
use phpcms\conf;

class proModel extends \phpcms\model
{
    protected $table = 'pro';

    /**
     * 查询所有商品
     * @author vial
     */
    public function selectPro()
    {
        if(!array_key_exists('order',$_GET)) $_GET['order'] =null;
        if(!array_key_exists('name',$_GET)) $_GET['name'] =null;
        //先实例化分页
        $return['count'] = $this->count();
        $shopnum = $_GET['page']; // 加载一页显示数量
        $page = new \phpcms\page ($return['count'],$shopnum);
        $return['page'] = $page->show();
        //在输出数据
        $pegenum = ($page->_page_num ? $page->_page_num : 1) - 1;
        $limit = [$pegenum * $shopnum,$shopnum];
        $sql ="SELECT a.*,b.name AS cname,c.path FROM shop_pro AS a INNER JOIN shop_album AS c ON a.id = c.pid LEFT JOIN shop_cate AS b ON a.cid = b.id ".
              ( $_GET['name'] ? " WHERE a.name LIKE '%".$_GET['name']."%'" : "").( $_GET['order'] ? " GROUP BY ".rawurldecode($_GET['order']) : "")." LIMIT {$limit[0]},{$limit[1]}";
        $return['list'] = $this->select($sql);
        if($return['list']){
            foreach($return['list'] as $k=>$v){
                $return['list'][$k]['path'] = unserialize($v['path']);
            }
        }
        if($return) return $return;
        return false;
    }

    /**
     * 查询一条商品
     * @author vial
     */
    public function onePro()
    {
        $sql ="SELECT a.*,b.name AS cname FROM shop_pro AS a INNER JOIN shop_cate AS b ON a.cid = b.id WHERE a.id ={$_GET['id']}";
        $return['data'] = $this->select($sql);
        $sql = "select * from shop_cate";
        $return['cate'] = $this->select($sql);
        if($return) return $return;
        return false;
    }


    /**
     * 添加商品
     * @author vial
     */
    public function addPro(){
        $upload = new \phpcms\upload;
        $upload->set('filePath',conf::get('PICPATH','config'));
        $upload->set('maxSize',conf::get('PICSIZE','config'));
        $upload->set('isRandom',conf::get('PICRANDOM','config'));
        $upload->set('allowType',conf::get('PICTYPE','config'));
        if($upload->verifyUpload('thumbs')){
            $fliename = $upload->getFileName();
            $dataArr = conf::get('PROSIZE','config'); // 加载图片尺寸数量
            if( count($fliename) ){
                foreach( $fliename as $k=>$v){
                    foreach( $dataArr as $a=>$b){
                        $thumbnail = new \phpcms\thumbnail;
                        $thumbnail->set('name',ASSIGN.'upload/picture/'.$v);
                        $thumbnail->set('width',$a);
                        $thumbnail->set('height',$b);
                        if($a == count($dataArr)-1){
                            $thumbnail->set('boolean',false);
                        }else{
                            $thumbnail->set('boolean',true);
                        }
                        $tAddress[$k][$a] = $thumbnail->thumbnail();
                    }
                }
                $data['path'] = serialize($fliename);
                //存入数据 事务
                $_POST['uptime'] = time();
                try {
                    $this->beginTransaction();
                    $this->insert($_POST);
                    $data['pid'] = $this->lastInsertId();
                    $album = new albumModel;
                    $album->insert($data);
                    $res = $this->commit();
                    if($res) return true;
                }
                catch (\PDOException $e) {
                    $this->rollback();
                    foreach($tAddress as $k=>$v){
                        foreach ($v as $a=>$b){
                            if(file_exists($b)){
                                unlink($b);
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * 更新商品
     * @author vial
     */
    public function editPro(){
        if($_FILES){
            $album = new albumModel;
            $resImg = $album->select("SELECT path FROM shop_album WHERE pid = {$_POST['id']}");
            $upload = new \phpcms\upload;
            $upload->set('filePath',conf::get('PICPATH','config'));
            $upload->set('maxSize',conf::get('PICSIZE','config'));
            $upload->set('isRandom',conf::get('PICRANDOM','config'));
            $upload->set('allowType',conf::get('PICTYPE','config'));
            if($upload->verifyUpload('thumbs')){
                $fliename = $upload->getFileName();
                $dataArr = conf::get('PROSIZE','config'); // 加载图片尺寸数量
                if( count($fliename) ){
                    foreach( $fliename as $k=>$v){
                        foreach( $dataArr as $a=>$b){
                            $thumbnail = new \phpcms\thumbnail;
                            $thumbnail->set('name',ASSIGN.'upload/picture/'.$v);
                            $thumbnail->set('width',$a);
                            $thumbnail->set('height',$b);
                            if($a == count($dataArr)-1){
                                $thumbnail->set('boolean',false);
                            }else{
                                $thumbnail->set('boolean',true);
                            }
                            $tAddress[$k][$a] = $thumbnail->thumbnail();
                        }
                    }
                    $data['path'] = serialize($fliename);
                    //修改图片数据
                    $where = $_POST['id'];
                    unset($_POST['id']);
                    try {
                        $this->beginTransaction();
                        $album->update($data,'pid = '.$where);
                        $this->update($_POST,'id = '.$where);
                        $res = $this->commit();
                        if($res){
                            $Imgs = unserialize($resImg[0]['path']);
                            foreach( $dataArr as $a=>$b){
                                @unlink(ASSIGN.'thumbnail/'.$a.'_'.$b.'/'.$Imgs[0]);
                            }
                            @unlink(ASSIGN.'upload/picture/'.$Imgs[0]);
                            return true;
                        }
                    }
                    catch (\PDOException $e) {
                        $this->rollback();
                        foreach($tAddress as $k=>$v){
                            foreach ($v as $a=>$b){
                                if(file_exists($b)){
                                    unlink($b);
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * 商品上下架
     * @author vial
     */
    public function outPro(){
        $sql = "SELECT isshow FROM shop_pro WHERE id={$_GET['id']}";
        $res = $this->select($sql);
        if($res){
            if($res[0]['isshow'] == 1){//下架
                $res[0]['isshow'] = 0;
                $data = $this->update($res[0],'id='.$_GET['id']);
            }else{//上架
                $res[0]['isshow'] = 1;
                $data = $this->update($res[0],'id='.$_GET['id']);
            }
            if($data) return true;
        }
        return false;
    }


    /**
     * 删除商品
     * @author vial
     */
    public function deletePro(){
        $where = $_GET['id'];
        try {
            $this->beginTransaction();
            $album = new albumModel;
            $dataArr = conf::get('PROSIZE','config'); // 加载图片尺寸数量
            $resImg = $album->select("SELECT path FROM shop_album WHERE pid = {$_POST['id']}");
            $album->delete('pid = '.$where);
            $this->delete('id = '.$where);
            $res = $this->commit();
            if($res){
                $Imgs = unserialize($resImg[0]['path']);
                foreach( $dataArr as $a=>$b){
                    @unlink(ASSIGN.'thumbnail/'.$a.'_'.$b.'/'.$Imgs[0]);
                }
                @unlink(ASSIGN.'upload/picture/'.$Imgs[0]);
                return true;
            }
        }
        catch (\PDOException $e) {
            $this->rollback();
        }
        return false;
    }

}