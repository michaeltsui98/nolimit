<?php
/**
 * 
 * @author michael
 * 
 */
class Orm_SysLog extends  Cola_Orm  {

    
    
    /**
     * @var 设置表名
     */
    protected $table = 'doc_down_log';
    /**
     * @var 设置主键名
     */
    protected $primaryKey = 'id'; 
    
    /**
     * 重置数据库连接
     */
    protected $connection = "dev"; 
    
    public $timestamps = false;  
    
    public function getLog($id=1){
        return $this->whereRaw("id = ?",array($id))->get()->toArray();
    }
    
    
    
    
     

}