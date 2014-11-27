<?php

/**
 * 全文索检
 * @author michael
 * @version 1.0 2013/8/12 14:17:34
 */

function json_to_array($obj) {
    $arr = array();
    foreach ((array)$obj as $k => $w) {
        if (is_object($w)) $arr[$k] = json_to_array($w);
         //判断类型是不是object
        else $arr[$k] = $w;
    }
    return $arr;
}

class Helper_Search
{
    private static $_instance = null;

    private static $_search = array();

    public static function init($config = null) {
       return self::inits($config);
    }

    /**
     * 字段范围
     * @var array
     */
    public  $range = array();
    
    private static function getSearchService($config = null) {
        if (!isset(self::$_search[$config])) {
            self::$_search[$config] = new Cola_Com_Search(Cola::getInstance()->config->get('_resource_search'));
        }
        return self::$_search[$config];
    }
    
    public  static $s;
    
    /**
     *
     * @param array $config
     * @return self
     */
    public static  function inits($config =null){
        $config or $config = Cola::getInstance()->config->get('_resource_search');
        self::$s =  new Cola_Com_Search($config);
    
        $cls =   get_called_class();
        if(self::$_instance[$cls] === NULL){
            self::$_instance[$cls] = new static();
        }
        return self::$_instance[$cls];
    
    }
    
    /**
     * 更新索引内容
     * @param int $id
     * @param array $data
     * @return boolean
     */
    function updateIndex($id, $data) {
        
        $s = self::$s;
        if (!$id) {
            throw new Exception('id is emtpy');
        }
        
        $d = $this->indexQuery(" id:{$id} ");
        
        if(!$d['data']){
            return false;
        }
        $d = $d['data'][0];
        
        if ($d) {
            $d = json_to_array($d);
            $d = reset($d);
        }
        
        /* $d = $s->get($id);
        if($d){
           $d = json_to_array($d);
           reset($d[0]);
           $d = current($d[0]);
        } */
        
        $d['id'] = $id;
        
        if (!$data) {
            return false;
        }
        foreach ($data as $k => $v) {
            $d[$k] = $v;
        }
       
        $res =  $s->set($d, false);
        //$this->getIndex()->flushIndex();
        return $res;
    }
    
    /**
     * 添加索引
     * @param array $data
     * @return boolean
     */
    function addIndex($data) {
        if (!$data) {
            return false;
        }
        $s = self::$s;
        return (bool)$s->set($data);
    }
    
    /**
     * 删除索引
     * @param unknown_type $ids
     * @param unknown_type $type
     * @return boolean
     */
    function delIndex($ids) {
        $s = self::$s;
        $res = (bool)$s->delete($ids);
       // $this->getIndex()->flushIndex();
        return $res;
    }
    /**
     * 添加搜索范围
     * @param string $field 字段
     * @param int $from 开始值
     * @param int $to 结束值
     */
    public function addRange($field, $from, $to){
            $this->range = array($field,$from,$to);
            return $this;        
    }
    
    /**
     * 索引查询
     * @param string $where  索引查询条件
     * @param bool $is_fuzzy  开启模糊查询
     * @param string $sort_field 排序字段
     * @param bool $asc true|false 升|降序
     * @param bool $relevance_first   是否优先相关性排序, 默认为否
     * @param int $page 当前页数
     * @param int $pagesize 每页数量
     * @param bool $is_page  是否分页
     * @return multitype:array
     */
    function indexQuery($where, $is_fuzzy = false, $sort_field = null, $asc = false, $relevance_first = false, $page = 1, $pagesize = 20, $is_page = false) {
//         $s = self::$s;
        $search = $this->getSearch();
        $count = 0;
        $search->setFuzzy($is_fuzzy)->setQuery($where);
        if($this->range){
            $range = $this->range;
            $search->addRange($range[0],$range[1],$range[2]);
        }
        
        if ($sort_field) {
            $search->setSort($sort_field, $asc, $relevance_first);
        }
         
        $count = $search->count();
         
        if ($pagesize) {
            $search->setLimit($pagesize, max(0, ($page - 1) * $pagesize));
        }
        
        $docs = $search->search();
        return array('data' => $docs, 'count' => $count);
    }

    public function getSearch(){
         
        return  self::$s->getSearch()->search;
    }
    public function getIndex(){
    	return self::$s->getSearch()->index;
    }
    
    /**
     * 刷新搜索日志 , dev 查一次，刷一次， 线上，查十次刷新一下
     * 
     */
    public function flushLog($config=array()){
        $key = Cola_Model::init()->getCacheKey(__FUNCTION__);
        $init_val = Cola_Model::init()->cache()->get($key)+1;
        if($init_val>10){
        	$init_val = 0 ;
        }
        Cola_Model::init()->cache()->set($key,$init_val,3600*3600*3600);
        if(!DEBUG and $init_val==10){
            Helper_Search::init($config)->getIndex()->flushLogging();
        }elseif(DEBUG){
            Helper_Search::init($config)->getIndex()->flushLogging();
        }
    }
    /**
     * 更新搜索
     */
    public function flushIndex(){
       return  $this->getIndex()->flushIndex();
    }
    
}
