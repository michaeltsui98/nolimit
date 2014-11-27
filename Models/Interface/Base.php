<?php
/**
 * 手机接口基类
 * @author michaeltsui98@qq.com
 */
class Models_Interface_Base extends Cola_Model {
     
    
   
     /**
      * 过滤数组中的Null为''空值
      * @return array
      */
     public function filterNull(array $data){
         if(!empty($data)){
            return  array_map(array($this,'opNull'), $data);
         }else{
         	return $data;
         }
     }
     public function opNull($val){
             if(is_null($val) or $val==''){
                 return '';
             }elseif(is_array($val)){
                 return $this->filterNull($val);
             }else{
                 return $val;
             }
     }
 
}