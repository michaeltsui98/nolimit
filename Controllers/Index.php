<?php

class Controllers_Index extends Controllers_Base
{
 
   protected static  $_model = NULL;
    
    function indexAction(){
            
    	 $page_title = '学科选择';
    	  
         $layout = 'Layout/index';
         $this->setLayout($layout);
         Cola::getConfig('_db');
         $this->view->vars = get_defined_vars();
         $this->view->user = $this->user_info;
          
         $arr = array(
                 '1'=>'/xl/student',
                 '2'=>'/xl/teacher',
                 '3'=>'/xl/parent',
         );
         
         $this->tpl();
          
    }
    
    /**
     * 测试集成orm组件到Cola 中来
     */
    function resAction(){
       
        $log =  new Orm_SysLog;
        $log->uid = 'aaaaa';
        $log->cost = 111;
        $log->save();
        $ids = $log->id;
         
        var_dump($ids);
        die;
    }
    function delAction(){
        $id = $this->getVar('id',45);
        $res= Orm_SysLog::find($id)->delete();
        var_dump($res);
    }
    
    function desAction(){
        $id = $this->getVar('id',46);
        $res= Orm_SysLog::destroy(array(51,52));
        var_dump($res);
    }
    function fisAction(){
      
        $l= new Orm_SysLog;
        $res = $l->newQuery()->where('id','=','50')->get()->toArray();
        
       // $res= Orm_SysLog::where('id','=','50')->update(array('uid'=>'bbbbbb'));
        var_dump($res);
    }
    
    
    
    function testAction(){
        
        phpinfo();
       //$query = " (多多教育)  AND node_id:xd001 AND node_id:GS0024 AND node_id:v11 NOT resource_type:4 NOT resource_type:8";
       //$data  = Helper_Search::inits()->getSearch()->setQuery($query)->search();
       //var_dump($data);
       //$this->tpl();
       
    }
    
 
    
  
    
     
     
    
    
    
}
 

?>