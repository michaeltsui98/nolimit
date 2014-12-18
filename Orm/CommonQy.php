<?php
/**
 * 取本地数据
 */

class Orm_CommonQy extends  Cola_Orm  {

    /**
     * @var 设置表名
     */
    protected $table = 'common_qy';
    /**
     * @var 设置主键名
     */
    protected $primaryKey = 'qy_id'; 
    
  
    public static function getLog($id=2){
        return self::whereRaw("qy_id = ?",array($id))->get()->toArray();
    }
    
     

}