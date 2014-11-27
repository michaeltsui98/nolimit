<?php

class Modules_Sm_Controllers_Resource extends  Modules_Sm_Controllers_Base {
	
	
	public function __construct(){
	    parent::__construct();
		$this->layout = $this->getCurrentLayout('index.htm');
		//资源类型
		$this->view->resource_type = array(1=>'教案',2=>"课件",3=>'题库',4=>'素材',5=>'微视频',6=>'观摩课',8=>'备课夹');
		
	}
	
	public function indexAction(){
	    
		$res = Models_Resource::init();
		//当前用户学段
		$select_xd = $this->getVar('xd');
		
		$this->view->user_xd = isset($this->user_info['xd'])?$this->user_info['xd']:'xd001';
		$this->view->xd = $select_xd?$select_xd:$this->view->user_xd;
		 
		
		$xd_base = Cola::getConfig('_xd');
		$xk_base = Cola::getConfig('_xk');
		$xk_name =  $xk_base[$this->view->xk_code];
		$this->view->page_title = '资源中心--'.$xk_name;
		
		
		$this->view->xd_arr = $res->getSubNode(0);
		
		//var_dump($this->view->xd_arr);
		
		$this->view->xd_name = $xd_base[$this->view->xd];
		$xk = $this->view->xk_code;
		$this->view->xk_name = $xk_base[$xk];
		$this->view->bb =$bb= $res->getBbByXdAndXk($this->view->xd, $xk);
		
		$bb_code = (int)Models_Teacher_TeacherInterface::init()->getTeacherTextbookPublisher($this->user_info['user_id'],$this->view->xk_code);
		$bb_code = $this->getVar('bb',$bb_code);
		if($bb_code){
            $this->view->bb_code = $bb_code;
            $default_bb_id =  Models_Resource::init()->getNodeIdByCode($bb_code, $bb);
		}else{
    		//默认版本
    		$default_bb = array_shift($bb);
    		$default_bb_id= $default_bb['id'];
    		$bb_code= $default_bb['code'];
    		$bb_code = $this->getVar('bb',$bb_code);
    		$this->view->bb_code = $bb_code;
		}

		//默认年级
		$nj = $res->getSubNode($default_bb_id);
		sort($nj);
		$this->view->nj = $nj;
		
		//$default_nj = current($nj);
		$nj_code = $this->getVar('nj',0);
		//var_dump($nj,$nj_code);
		
		$this->view->nj_code = $nj_code; 
		
		//var_dump($this->view->xd, $xk, $bb_code, $nj_code);
		//默认知识节点
		$unit = Models_Resource::init()->getUnit($this->view->xd, $xk, $bb_code, $nj_code);
		$this->view->unit = $unit;
		
		//知识节点
		$zs_id = (int)$this->getVar('zs',0);
		$this->view->zs_id = $zs_id;
		
		
		//关键字查询条件
		$key = $this->getVar('key');
		$query = "";
		if($key){
		  $query .= " ({$key}) AND ";
		}
		$query .= " node_id:{$this->view->xd}  AND  node_id:{$xk} ";
		   
		if($bb_code){
		  $query .= " AND node_id:{$bb_code} ";
		}
		if($nj_code){
		  $query .= " AND node_id:{$nj_code} ";
		}
		
		if($zs_id){
		  $query .= " AND node_id:{$zs_id} ";
		  $query_id = '';
		  //查询字节点资源信息
		 /*  foreach ($unit as $v){
		      if($v['id']==$zs_id and $v['have_child']=1){
    		       $query_id = $v['id'];
    		       break;
		      }
		  }
		  if($query_id){
		      $node_ids = array();
		      $squery  = '';
		      //$query .= " OR ( ";
    		  foreach ($unit as $v){
    		      if($v['node_fid'] == $query_id){
    		         //$squery .= " AND node_id:{$v['id']} ";
    		         $node_ids = $v['id'];
    		      }
    		  }
    		  $from = $zs_id;
    		  $to = $node_ids;
    		  //var_dump($from,$to);die;
    		  //$query .= substr($squery, 4);
    		  
    		  //$query .= " ) ";
    		 // var_dump($query);die;
		  } */
		  
		}

		
		
		//资源类型
		$type = (int)$this->getVar('type',0); 
		$this->view->type = $type;
		 
		if($type==8){
		    $query .= "  AND (resource_type:8) ";
		}elseif($type>0){
		    $query .= "  NOT (resource_type:8) AND resource_type:$type ";
		}elseif($type==0){
		    $query .= "  NOT (resource_type:8) ";
		}
		//var_dump($query);
		//排序条件，默认按时间倒序
		$order = $this->getVar('order',1);
		$this->view->order = $order;
		if($order==1){
			$order_field = 'on_time';
			$asc = false;
		}elseif($order==2){
			$order_field = 'views';
			$asc = false;
		}
		
		$page = $this->getVar('page',1);
		$page_size = 20;
		//var_dump($query);
		//资源数据  
		 
    	$resources = Helper_Search::init()->indexQuery($query,true,$order_field,$asc,false,$page,$page_size,true);
		 
		 
		$this->view->resources = $resources['data'];
		//var_dump($resources['data']);
		
		$list_content = '';
		//资源数据对应不同的模板输出
		if($type==1 or $type==2 or $type==3){  //文档
            $list_content = $this->docList();
		}elseif($type==4  or $type== 0){  //素村
            $list_content = $this->fileList();
		}elseif($type==5 or $type == 6){ //视频
            $list_content = $this->videoList();
		}elseif($type==8){
		    //背课夹
		    $list_content = $this->bkjList();
		}
		$this->view->list_content = $list_content;
		
		$url = Cola_Model::init()->getPageUrl();
		$pager = new Cola_Com_Pager($page, $page_size, $resources['count'], $url);
		$page_html = $pager->html();
		$this->view->page_html = $page_html;
		//var_dump($resources);
		
		//$this->view->res = $res;
		
		$this->view->root_js = array('syup/syup.js','script/zy_resupload.js');
		$this->view->js = array('ziyuan/zy_list.js','common/dialog.js');
		
		//$this->view->breadcrumb = Helper_Breadcrumb::getInstance($this->xk)->append('/resource','资源');
		
		$this->setLayout($this->layout);
		$this->tpl();
	}
	
	/**
	 * 关键字索引
	 */
	public function suggestAction(){
	    
	    $key = $this->getVar('key');
		$res = Helper_Search::init()->getSearch()->getExpandedQuery($key);
		$this->abort($res);
	}
	/**
	 *
	 * @param string $data 数据
	 * @param string $source  来源，我上传的，可以删除，可以编辑，我收藏的可以删除，默认不做处理
	 * @return boolean
	 */
	public  function docList($data=null,$source=null){
	    if($data){
	    	$this->view->resources = $data;
	    }
	    if(!$this->view->resources){
	    	return false;
	    }
	    
	    $this->view->source = $source;
	    $xk = $this->view->xk;
	    $xk = ucfirst($xk);
	    return $this->tpl('Modules/'.$xk.'/Views/Resource/docList','',1);
		
	}
	public function fileList($data=null,$source=null){
	    if($data){
	        $this->view->resources = $data;
	    }
	    if(!$this->view->resources){
	        return false;
	    }
	    //var_dump($this->view->resources);die; 
	    //var_dump($this->view->xk);die;
	    $this->view->source = $source;
	    $xk = $this->view->xk;
	    $xk = ucfirst($xk);
	    return $this->tpl('Modules/'.$xk.'/Views/Resource/fileList','',1);
	}
	
	public function videoList($data=null,$source=null){
	    if($data){
	        $this->view->resources = $data;
	    }
	    if(!$this->view->resources){
	        return false;
	    }
	    $this->view->source = $source;
	    $xk = $this->view->xk;
	    $xk = ucfirst($xk);
	    return $this->tpl('Modules/'.$xk.'/Views/Resource/videoList','',1);
	}
	public function bkjList($data=null,$source=null){
	    if($data){
	        $this->view->resources = $data;
	    }
	    if(!$this->view->resources){
	        return false;
	    }
	    $this->view->source = $source;
	    $xk = $this->view->xk;
	    $xk = ucfirst($xk);
	    return $this->tpl('Modules/'.$xk.'/Views/Resource/bkjList','',1);
	}
	
	/**
	 * 取年级数据
	 */
	public function getNodeAction(){
	    $id = $this->getVar('fid');
	    $data = Models_Resource::init()->getSubNode($id);
	    $this->abort($data);
	}
	
	/**
	 * 显示知识节点
	 */
	public function getUnitAction(){
	    $xd = $this->getVar('xd');
	    $xk = $this->getVar('xk');
	    $bb = $this->getVar('bb');
	    $nj = $this->getVar('nj');
		$data = Models_Resource::init()->getUnit($xd, $xk, $bb, $nj);
		$this->abort($data);
	}
	
	
	/**
	 * 资源查看
	 */
	public function viewAction(){
	    
	    $id = $this->getVar('id',477);
	   
	    //资源节点
	    $base_node = array_merge(Cola::getConfig('_xd'),Cola::getConfig('_xk'),Cola::getConfig('_bb'),Cola::getConfig('_nj'));
	    $this->view->base_node = $base_node;
	    //资源类型
	    $this->view->resource_type = Cola::getConfig('_resourceType');
	    
	    $res = Models_Resource::init();
	    $info = $res->getResourceInfoById($id);
	    if(!$info){
	        $xk = $this->view->xk;
	        $this->messagePage('/'.$xk.'/resource','当前资源找不到了！');	
	    }
	    //取知识单元名称
	    
	   $zs =  $res->getUnitTitleById($info['nid']);
	   $this->view->unit_name = $zs['data'];
	    
	    
	    //$remarks = $res->getResourceRemarkById($id);
	    //$this->view->remarks =$remarks;
	    
	    //星星的值
	    if($info['doc_remarks']){
	        $star_val = $info['doc_remark_val']/$info['doc_remarks'];
	    }else{
	        $star_val = 0 ;
	    }
 
	    $this->view->star_val =$star_val;
	    $doc_star = $res->getStar($star_val);
	    
	    $this->view->doc_star = $doc_star;
	    
	    //文件信息
	    $this->view->info  = $info;
	    
	    $dd = new Models_DDClient();
	    //标签信息
	    $user_info = array();
	    if($info['uid']){
	       $user_info = $dd->viewUserInfo($info['uid']);
	    }
	    
	    //var_dump($info);
	    
	    $this->view->res_user_info =$user_info;
	    
	    //获取文档标签信息
	    
	    //暂时不用标签
	    //$tag_info = $dd->get_tag($id,'doc');
	    //$this->view->tags =$tag_info;
	    
	   // var_dump($tag_info);
	   
	    //资源浏览类型
	    $perview_type = Models_Resource::init()->getPerviewType($info['cate_id'], $info['doc_ext_name']);

	    $this->view->perview_type =$perview_type;
	    
	    //var_dump($this->user_info);
	    
	    
	    
	    $this->view->id = $id;
	    $this->view->uid = '';
	    if(isset($this->user_info['user_id'])){
	       $this->view->uid = $this->user_info['user_id'];
	    }
	    
	    //相关性资源
	    $relate_resource = $this->relateResource(8);
	   // var_dump($relate_resource['data']);
	    $this->view->relate_resource = $relate_resource['data'];
	    $this->view->page_title = $info['doc_title'].' - 资源查看';
	    $this->view->js = array('common/dialog.js','ziyuan/zy_preview.js');
	    $this->view->root_js = array('script/comment.js');
	    
	    $this->setLayout($this->layout);
		$this->tpl();
	}
	
	
	/**
	 * 相关性资源 
	 */
	public function relateResource($limit){
		 $info = $this->view->info;
		 $query = " node_id:{$this->view->info['xd']}  AND  node_id:{$this->view->info['xk']} AND node_id:{$this->view->info['bb']} AND node_id:{$this->view->info['nj']}";
		 $query .= " NOT id:{$this->view->info['doc_id']}  "; 
		 
		 //echo $query;
		 return Helper_Search::init()->indexQuery($query,false,null,false,false,1,$limit);
	}
	
	
	
	/**
	 * 资源搜索
	 */
	public function  searchAction(){
	    $this->view->page_title = '资源搜索';
	    $res = Models_Resource::init();
	    $this->view->xd_arr = $res->getSubNode(0);
	    $this->view->key = $this->getVar('key');
	    $this->view->order = $this->getVar('order',1);
	    //搜索热词
	    $hot_key = Helper_Search::init()->getSearch()->getHotQuery(10);
	    $this->view->hot_key = array_flip($hot_key);
	    $this->view->js = array('ziyuan/zy_search.js');
	    $this->setLayout($this->layout);
	    $this->tpl();
	}
	/**
	 * 返回搜索数据
	 */
	public function getSearchAction($is_count=false){
	    $page = $this->getVar('p',1);
	    $page_size = 20;
	    $type = (int)$this->getVar('type',0);
	    $xd = $this->getVar('xd');
	    $xk = $this->view->xk_code;
	    $bb = $this->getVar('bb');
	    $nj = $this->getVar('nj');
	    $key = $this->getVar('key');

	    $query = " ";
	    if($key){
	        $query .= " ({$key}) AND ";
	    }
	     
	    $query .= "  node_id:{$this->view->xk_code}  ";
	    if($xd){
	        $query .= " AND node_id:{$xd} ";
	    }
	    if($xk){
	        $query .= " AND node_id:{$xk} ";
	    }
	    if($bb){
	        $query .= " AND node_id:{$bb} ";
	    }
	    if($nj){
	        $query .= " AND node_id:{$nj} ";
	    }
	    
	    if($type){
	        $query .= " AND resource_type:{$type} ";
	    }
	     
	   if($type==8){
		    $query .= "  AND (resource_type:8) ";
		}elseif($type>0){
		    $query .= "  NOT (resource_type:8) AND resource_type:$type ";
		}elseif($type==0){
		    $query .= "  NOT (resource_type:8) ";
		}
	     
	     
	    //排序条件，默认按时间倒序
	    $order = $this->getVar('order',1);
	    if($order==1){
	        $order_field = 'on_time';
	        $asc = false;
	    }elseif($order==2){
	        $order_field = 'views';
	        $asc = false;
	    }elseif($order==3){
	        $order_field = 'remark';
	        $asc = false;
	    }
	     
	    //var_dump($query);
	    //资源数据
	    $resources = Helper_Search::init()->indexQuery($query,false,$order_field,$asc,false,$page,$page_size,true);
        $this->view->resources = $resources['data'];
        $list_content = '';
        if(!$is_count){
          $list_content = $this->fileList();
	    }
	    $this->echoJson('success', '',array('data'=>$list_content,'count'=>$resources['count']));
	}
	
	/**
	 * 只取总数
	 */
	public function getSearchCountAction(){
	     
		$this->getSearchAction(true);
	}
	 
	
	
	
	/**
	 * 我的资源中心
	 * 
	 */
	public function myAction(){
	    $this->checkLogin();
	    
	    $this->view->page_title = '我的资源';
	    $page = $this->getVar('page',1);
	    $page_size = 20;
	    
	    $this->view->type = $type =  (int)$this->getVar('type',0);
	    
	    $data = array();
	    $count = 0 ;
	    
	    //资源来源,默认是我上传的
	    $s  = $this->getVar('s','up');
	    $this->view->s = $s;
	    $key  = $this->getVar('key');
	    $this->view->key = $key;
	    $query = "";
	    if($key){
	        $query = " {$key}  AND ";
	    }
	    $query .= "  user_id:{$this->user_info['user_id']} AND node_id:{$this->view->xk_code}  ";
	   if($type==8){
		    $query .= "  AND (resource_type:8) ";
		}elseif($type>0){
		    $query .= "  NOT (resource_type:8) AND resource_type:$type ";
		}elseif($type==0){
		    $query .= "  NOT (resource_type:8) ";
		}
	    //echo $query;
	    //我上传的, 可以删除，可以编辑
	    if($key){
    		$resources = Helper_Search::init()->indexQuery($query,false,'on_time',false,false,$page,$page_size,true);
    	    $up_data = $resources['data'];
    	    //var_dump($query, $up_data);
    	    $this->view->up_count =  $up_count = $resources['count'];
	    }else{
	       $resources =  Models_Resource::init()->getMyUpload($this->user_info['user_id'], $this->view->xk_code, $type, $page, $page_size);
	        $up_data = $resources['rows'];
    	    //var_dump($query, $up_data);
    	    $this->view->up_count =  $up_count = $resources['total'];
	    }
	    
	    
	    
	    //我下载的
	    $downs = Models_Resource::init()->getMyDown($this->user_info['user_id'], $page, $this->view->xk_code,$type);
	    $down_data = $downs['rows'];
	    $down_count =(int)$downs['total'];
	    $this->view->down_count = $down_count;
	    
	    //我收藏的 ，可以删除 
	    $favs = Models_Resource::init()->getMyFav($this->user_info['user_id'], $page, $this->view->xk_code,$type);
    	$fav_data = $favs['rows'];
    	$fav_count = (int)$favs['total'];
	    $this->view->fav_count = $fav_count;
    	//var_dump($downs,$favs);
    	
    	
	    if($s=='up'){  //我上传的
	        $data = $up_data;
	        $count = $up_count;
	    }elseif($s=='down'){  //我下载的
	    	$data = $down_data;
	    	$count = $down_count;
	    }elseif($s=='fav'){  //我收藏的
	    	$data = $fav_data;
	    	$count = $fav_count;
	    }
	    $this->view->resources = $data;
	    
	    $list_content = '';
	    //资源数据对应不同的模板输出
	    $xk = $this->view->xk;
	    $xk = ucfirst($xk);
	    
	    if($type==1 or $type==2 or $type==3){  //文档
	        $list_content = $this->tpl('Modules/'.$xk.'/Views/Resource/mydocList','',1);
	    }elseif($type==4  or $type== 0){  //素村
	        $list_content = $this->tpl('Modules/'.$xk.'/Views/Resource/myfileList','',1);
	    }elseif($type==5 or $type == 6){ //视频
	        $list_content = $this->tpl('Modules/'.$xk.'/Views/Resource/myvideoList','',1);
	    }elseif ($type==8){
	        $list_content = $this->bkjList();
	    }
	    $this->view->list_content = $list_content;
	    
	    
	    $pager = new Cola_Com_Pager($page, $page_size, $count, Cola_Model::init()->getPageUrl());
	    $this->view->page_html = $pager->html();
	    
	    $dodo_url = DOMAIN_NAME.'/';
	    $this->view->user_dodo_space_url = $dodo_url.$this->user_info['user_id']."/Space/index";
	    $this->view->user_dodo_class_url = $dodo_url."class";
	    $this->view->user_dodo_school_url = $dodo_url."school";
	    
	    $user_school_info = Models_Circle::init()->getCurClassSchoolByUserId($this->user_info['user_id']);
	    
	    $this->view->js = array('ziyuan/zy_my.js');
	    $this->setLayout($this->layout);
	    $this->tpl();
	}

	function checkLogin(){
		if(!$this->user_info){
		    $xk = $this->view->xk;
			$this->redirect('/'.$xk.'/resource');
		}
	}
	/**
	 * 评分页面
	 */
	public function remarkAction(){
		$this->tpl();
	}
}

?>