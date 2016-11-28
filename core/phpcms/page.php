<?php
/* ========================================================================
 * copyright http://git.oschina.net/jiusem/PHPPage
 * ======================================================================== */
namespace phpcms;

class page{

    protected $_page_max = 0;   //最大翻页数
    protected $_total = 0;      //数据总量
    protected $_limit = 10;     //每页显示数量
    protected $_p = 'p';        //传输的指定变量
    protected $_split = '';     //输出html
    public $_page_num = 0;      //输出当前页
    
    public function __construct($total,$limit){
        $this->_page_max = ceil($total/$limit);
        $this->_total = $total;
        $this->_limit = $limit;
    }

    /**
     * 核心 拼接显示分页
     */
    public function show(){
        //根据自身路由修改
        $url=preg_replace('/p\/(\d+)/','',$_SERVER['REQUEST_URI']);
        //判断最大值
        $this->_page_num = isset($_GET[$this->_p])?intval($_GET[$this->_p]):1;
        $this->_page_num = $this->_page_num<1?1:$this->_page_num;
        $this->_page_num = $this->_page_num>$this->_page_max?$this->_page_max:$this->_page_num;

        //HTML拼接开始
        $ret = '';
        $ret .= '<ul class="pagination no-margin" >';
        //上一页
        if($this->_page_num>1){
            $last_page = $this->_page_num-1;
            $ret .= "<li><a href='$url{$this->_p}/$last_page'>上页</a></li>";
            $ret .= $this->_split;
        }
        if($this->_page_num==1){
            $ret .= '<li class="active"><a href="###" >1</a></li>';
        }else{
            $ret .= "<li><a href='$url{$this->_p}/1'>1</a></li>";
        }
        $ret .= $this->_split;

        //开始与结束标签
        $start = $this->_getStart();
        $end   = $this->_getEnd();
        if($start>2){
            $ret .= '...';
            $ret .= $this->_split;
        }
        for($i=$start;$i<=$end;$i++){
            if($this->_page_num == $i){
                $ret .= '<li class="active"><a href="###">'.$i.'</a></li>';
            }else{
                $ret .= "<li><a href='$url{$this->_p}/{$i}'>".$i.'</a></li>';
            }
            $ret .= $this->_split;
        }
        if($end<$this->_page_max-1){
            $ret .= '...';
            $ret .= $this->_split;
        }
        if($this->_page_max>1){
            if($this->_page_num==$this->_page_max){
                $ret .= '<li class="active"><a href="###">'.$this->_page_max.'</a></li>';
            }else{
                $ret .= "<li><a href='$url{$this->_p}/{$this->_page_max}'>$this->_page_max</a></li>";
            }
            $ret .= $this->_split;
        }

        //下一页
        if($this->_page_num<$this->_page_max){
            $next_page = $this->_page_num+1;
            $ret .= "<li><a href='$url{$this->_p}/$next_page'>下页</a></li>";
            $ret .= $this->_split;
        }

        //显示数据相关信息
        $ret .= '<span class="php_page_info">';
        $ret .= $this->_total.' 条数据,当前第 '.$this->_page_num.' 页,共 '.$this->_page_max.' 页';
        $ret .= '</span>';
        $ret .= '</ul>';

        return $ret;
    }


    /**
     * 设置分页默认P变量名
     * @param string $val
     */
    public function setP($val){
        $this->_p = $val;
    }

    /**
     * 设置第一页
     * @param string $this->_page_num
     */
    private function _getStart(){
        if($this->_page_num<9){
            return 2;
        }else{
            return $this->_page_num>$this->_page_max-8?$this->_page_max-8:$this->_page_num;
        }
    }

    /**
     * 设置最后一页
     * @param string $this->_page_num
     */
    private function _getEnd(){
        //$start = $this->_getStart($this->_page_num);
        if($this->_page_num<9){
            $end = 9;
            return $end>$this->_page_max-1?$this->_page_max-1:$end;
        }else{
            $end = $this->_page_num+7;
            return $end>$this->_page_max-1?$this->_page_max-1:$end;
        }
    }

}




?>