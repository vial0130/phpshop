<?php
/* ========================================================================
 * 模型基类,当前继承于medoo
 * 主要用于连接数据库,并封装了四个常用操作
 * ======================================================================== */
namespace phpcms;

class model extends \PDO
{
    /**
     * 当前数据库
     */
    protected $table;

    public function __construct()
    {
        //连接数据库配置
        $dataBase = conf::all('database');
        $this->table = $dataBase['PREFIX'].$this->table;
        try{
            $pdo_values = array(self::ATTR_ERRMODE=>2,self::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8');
            parent::__construct($dataBase['DNS'],$dataBase['USERNAME'],$dataBase['PASSWORD'],$pdo_values);
            //$this->setAttribute(self::ATTR_ERRMODE,self::ERRMODE_EXCEPTION);
        }catch(\PDOException $e){
            dump($e->getMessage());
        }
    }


    /**
     * 断开连接
     */
    public function disConnect()
    {
        $this->__construct = null;
    }


    /**
     * 执行SQL语句 返回查询数据集
     * @param string sql语句
     * @param string 显示效果
     * @return $result
     */
    public function select($sql,$module='fetchAll'){
        $row = $this->query($sql);
        if($row){
            $row->setFetchMode(self::FETCH_ASSOC);
            switch($module){
                case 'fetch':
                    $data = $row->fetch();
                    break;
                case 'fetchAll':
                    $data = $row->fetchAll();
                    break;
                default:
                    return false;
            }
            return $data;
        }
        return false;
    }


    /**
     * 查询表总行数 返回查询数据
     * @param string sql语句
     * @param string 显示效果
     * @return $result
     */
    public function count(){
        if(!$this->table) return false;
        $sql = 'SELECT count(*) FROM `'.$this->table.'`';
        $row = $this->query($sql);
        $data = $row->fetch(self::FETCH_NUM);
        return $data[0];
    }
    

    /**
     * 执行SQL语句，插入操作
     * @param array $data 数据
     * @return $return
     */
    public function insert($data){
        if(!$this->table) return false;
        foreach ($data as $k => $v){
            $key[] = '`'.$k.'`';
            $value[] = "'".addslashes($v)."'";
        }
        if(empty($key) || empty($value)) return false;
        $sql = 'INSERT INTO `'.$this->table.'` ('.join(',',$key).') VALUES ('.join(',',$value).')';
        return $this->exec($sql);
    }


    /**
     * 执行SQL语句，更新操作
     * @param array $data 数据
     * @param string $where 条件
     * @param  number $limit 数量
     * @return $return
     */
    public function update($data,$where=null,$limit=null){
        if(!$this->table) return false;
        foreach ($data as $k => $v){
            $set[] = '`'.$k.'`='."'".addslashes($v)."'";
        }
        if(empty($set)) return false;
        $sql = 'UPDATE `'.$this->table.'` SET '.join(',',$set).($where ? ' WHERE '.$where : '').($limit ? ' LIMIT '.$limit : '');
        return $this->exec($sql);
    }


    /**
     * 执行SQL语句，删除操作
     * @param string $where 条件
     * @return $return
     */
    public function delete($where=NULL){
        if(!$this->table) return false;
        $sql = 'DELETE FROM `'.$this->table.'`'.($where ? ' WHERE '.$where : '');
        return $this->exec($sql);
    }

}
?>