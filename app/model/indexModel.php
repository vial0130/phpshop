<?php

namespace app\model;
{
	/**
	 * indexModel short summary.
	 *
	 * indexModel description.
	 *
	 * @version 1.0
	 * @author Administrator
	 */
	class indexModel extends \phpcms\model
	{
        protected $table = 'user';

        /**
         * 查询首页要的资源
         * @author vial
         */
        public function selectIndex(){
            if(array_key_exists('userId',$_SESSION)){
                $sql = "SELECT username FROM shop_user WHERE id={$_SESSION['userId']}";
                $data['user'] = $this->select($sql);
            }
            $sql = "SELECT * FROM shop_cate";
            $data['cate'] = $this->select($sql);
            foreach($data['cate'] as $v){
                $sql ="SELECT a.*,c.path FROM shop_pro AS a INNER JOIN shop_album AS c ON a.id = c.pid WHERE cid={$v['id']} AND a.isshow=1 ORDER BY id DESC LIMIT 4";
                $data['pro'][$v['id']][] = $this->select($sql);
                $sql ="SELECT a.*,c.path FROM shop_pro AS a INNER JOIN shop_album AS c ON a.id = c.pid WHERE cid={$v['id']} AND a.isshow=1 ORDER BY id ASC LIMIT 4";
                $data['pro'][$v['id']][] = $this->select($sql);
            }
            if($data) return $data;
            return false;
        }


        /**
         * 查询详情页要的资源
         * @author vial
         */
        public function selectDetails(){
            $sql = "SELECT a.*,b.name AS cname,c.path FROM shop_pro AS a INNER JOIN shop_album AS c ON a.id = c.pid LEFT JOIN shop_cate AS b ON a.cid = b.id WHERE a.id={$_GET['id']}";
            $data = $this->select($sql);
            if($data){
                $data[0]['path'] = unserialize($data[0]['path']);
            }
            if($data) return $data;
            return false;
        }


        /**
         * 查询分类页要的资源
         * @author vial
         */
        public function selectClass(){
            $sql = "SELECT a.*,b.name AS cname,c.path FROM shop_pro AS a INNER JOIN shop_album AS c ON a.id = c.pid LEFT JOIN shop_cate AS b ON a.cid = b.id WHERE a.id={$_GET['id']}";
            $data = $this->select($sql);
            if($data){
                $data[0]['path'] = unserialize($data[0]['path']);
            }
            if($data) return $data;
            return false;
        }


	}
}