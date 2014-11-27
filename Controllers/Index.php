<?php

class Controllers_Index extends Controllers_Base
{
 
   protected static  $_model = NULL;
    
    function indexAction(){
            
    	 $page_title = '学科选择';
    	  
         $layout = 'Layout/index';
         //$this->setLayout($layout);
         
         $this->view->vars = get_defined_vars();
         $this->view->user = $this->user_info;
          
         $arr = array(
                 '1'=>'/xl/student',
                 '2'=>'/xl/teacher',
                 '3'=>'/xl/parent',
         );
         
         $this->tpl();
          
    }
    
    function resAction(){
        
        //$ar = array('a','b','c');
        //$this->abort($ar);die;
        //phpinfo();die;
        $id = $this->getVar('id',945);
    	$res = Helper_Search::inits()->indexQuery(" id:{$id}" );
    	print_r($res['data']);
    }
    
    function testAction(){
        
       //$query = " (多多教育)  AND node_id:xd001 AND node_id:GS0024 AND node_id:v11 NOT resource_type:4 NOT resource_type:8";
       //$data  = Helper_Search::inits()->getSearch()->setQuery($query)->search();
       //var_dump($data);
       $this->tpl();
       
    }
    
 
    
  
    
     
     
    
    
    
}
 

?>