<?php
/**
 * 心理健康导航控制器
 * @author michaeltsui98@qq.com 2014-05-04
 * 
 *
 */
class Modules_Sm_Controllers_Public extends  Modules_Sm_Controllers_Base {
	

    /**
     * 心理健康，公共的头部
     * 按未登录，与未登录角色来划分
     */
    
	public function headerAction(){
	    
	    //浏览者用户角色
	    $role_code = Cola_Request::cookie('perview_role_'.$this->xk);
	   // var_dump($role_code);die;
	    //登录用户角色
	    if($this->user_info){
	    	$role_code = $this->user_info['role_code'];
	    }else{
	        $role_code = $this->getRoleCode($this->c);
	    }
	    $xk =$this->view->xk;
	    $ucxk =$this->view->ucxk;
	    //保存当前地址，跳转使用
	    $_SESSION['refer'] = Models_Resource::init()->getPageUrl();
	    if(!$role_code ){
	        $this->messagePage('/'.$xk.'/index','请您先选择角色');
	    }
	    $this->view->key = $this->getVar('key');
	    $nav = array();
	    $css = array();
	switch ($role_code) {
        	case 1:
        	    $nav[0] = array('name'=>'学习','url'=>'/'.$xk.'/study','icon'=>'icon-learn','c'=>'Modules_'.$ucxk.'_Controllers_Study');
        	    $nav[1] = array('name'=>'测评','url'=>'/'.$xk.'/evaluate','icon'=>'icon-test','c'=>'Modules_'.$ucxk.'_Controllers_Evaluate');
        	    $nav[2] = array('name'=>'课堂','url'=>'/'.$xk.'/course','icon'=>'icon-lessonLecture','c'=>'Modules_'.$ucxk.'_Controllers_Course');
        	    $nav[3] = array('name'=>'问答','url'=>'/'.$xk.'/question','icon'=>'icon-FAQs','c'=>'Modules_'.$ucxk.'_Controllers_Question');
        	    $nav[4] = array('name'=>'游戏','url'=>'/'.$xk.'/game','icon'=>'icon-game','c'=>'Modules_'.$ucxk.'_Controllers_Game');
        	    $nav[5] = array('name'=>'资讯','url'=>'/'.$xk.'/info','icon'=>'icon-news','c'=>'Modules_'.$ucxk.'_Controllers_Info');
        	    $nav[6] = array('name'=>'学生中心','url'=>'/'.$xk.'/student','icon'=>'icon-learnC','c'=>'Modules_'.$ucxk.'_Controllers_Student');
        	    $css[] = 'stu-layout.css';
        	    break;
        	case 2:
        	    $nav[0] = array('name'=>'资源','url'=>'/'.$xk.'/resource','icon'=>'icon-resource','c'=>'Modules_'.$ucxk.'_Controllers_Resource');
        	    $nav[1] = array('name'=>'研训','url'=>'/'.$xk.'/activity','icon'=>'icon-tecStudy','c'=>'Modules_'.$ucxk.'_Controllers_Activity');
        	    $nav[2] = array('name'=>'备课','url'=>'/'.$xk.'/lesson','icon'=>'icon-lessonPrepare','c'=>'Modules_'.$ucxk.'_Controllers_Lesson');
        	    $nav[3] = array('name'=>'课程','url'=>'/'.$xk.'/course','icon'=>'icon-lessonLecture','c'=>'Modules_'.$ucxk.'_Controllers_Course');
        	    $nav[4] = array('name'=>'问答','url'=>'/'.$xk.'/question','icon'=>'icon-FAQs','c'=>'Modules_'.$ucxk.'_Controllers_Question');
        	    $nav[5] = array('name'=>'资讯','url'=>'/'.$xk.'/info','icon'=>'icon-news','c'=>'Modules_'.$ucxk.'_Controllers_Info');
        	    $nav[6] = array('name'=>'教师中心','url'=>'/'.$xk.'/teacher','icon'=>'icon-learnC','c'=>'Modules_'.$ucxk.'_Controllers_Teacher');
        	    $css[] = 'tec-layout.css';
        	    break;
        	case 3:
        	    $nav[4] = array('name'=>'问答','url'=>'/'.$xk.'/question','icon'=>'icon-FAQs','c'=>'Modules_'.$ucxk.'_Controllers_Question');
        	    $nav[5] = array('name'=>'资讯','url'=>'/'.$xk.'/info','icon'=>'icon-news','c'=>'Modules_'.$ucxk.'_Controllers_Info');
        	    $nav[6] = array('name'=>'家长中心','url'=>'/'.$xk.'/parent','icon'=>'icon-learnC','c'=>'Modules_'.$ucxk.'_Controllers_Parent');
        	    $css[] = 'par-layout.css';
        	    break;
    
        }   
        $this->view->nav = $nav;
        
    	$is_access = $this->checkCrontrollerByRole($this->c, $nav);
    	if(!$is_access){
    	    $nav_arr = current($nav);
    		//$this->messagePage($nav_arr['url'],'')
    		$this->redirect($nav_arr['url']);
    	} 
	    
	    
	    $this->view->c = $this->c;
	    $this->view->css = $css;
	    
	    $this->tpl('Modules/'.ucfirst($xk).'/Views/Public/header');
	}
	public function getRoleCode($c){
	    $ucxk =$this->view->ucxk;
	    $data = array();
	    $data[1] = array('Modules_'.$ucxk.'_Controllers_Student',
	            'Modules_'.$ucxk.'_Controllers_Study',
	            'Modules_'.$ucxk.'_Controllers_Evaluate',
	            'Modules_'.$ucxk.'_Controllers_Course',
	            'Modules_'.$ucxk.'_Controllers_Game',
	            'Modules_'.$ucxk.'_Controllers_Question',
	            'Modules_'.$ucxk.'_Controllers_Info',
	    );
	    $data[2] = array('Modules_'.$ucxk.'_Controllers_Teacher',
	            'Modules_'.$ucxk.'_Controllers_Resource',
	            'Modules_'.$ucxk.'_Controllers_Activity',
	            'Modules_'.$ucxk.'_Controllers_Lesson',
	            'Modules_'.$ucxk.'_Controllers_Course',
	
	    );
	    $data[3] = array('Modules_'.$ucxk.'_Controllers_Parent',
	    );
	    //var_dump($data,$ucxk);
	
	    foreach ($data as $k=>$v){
	        foreach ($v as $value){
	            if($value==$c){
	                return $k;
	            }
	        }
	    }
	}
	public function topAction(){
	    $xk = $this->xk;
	    $this->tpl('Modules/'.ucfirst($xk).'/Views/Public/top');
	}
	public function footerAction(){
	    $xk = $this->xk;
	    $this->tpl('Modules/'.ucfirst($xk).'/Views/Public/footer');
	}
	
    function checkCrontrollerByRole($c,$nav){
        $tag = false;
    	foreach ($nav as $v){
    		if($v['c']==$c){
    			$tag = true;
    			break;
    		}
    	}
    	return $tag;
    	
    }
	
	
	 
}

?>