<?php

 class Modules_Admin_Controllers_StudyType extends Modules_Admin_Controllers_Base
 {
 
 	protected $_config = '';
 	protected $_study_type = '';
    protected static $_subject_config = array(
        'sm' => 'GS0024',
        'xl' => 'GS0025',
    );
 	public function __construct()
 	{
 		parent::__construct();
 		$this->_config = Models_StudyResource_StudyRecordConfig::init()->config();
 		$this->_study_type = Models_StudyResource_StudyType::init();
        $this->_xk = $this->getVar('xk');
 	}

 	/**
 	 * 添加学习资源类型
 	 */
 	public function addStudyTypeAction()
 	{
 		$node_id = $this->getVar('node_id');
 		//年级的code
 		$grade_code = $this->gradeCodeAction();
 		//判断$node_id 是否是年级id
 		$info = Models_Resource::init()->getNodeInfoById($node_id);
 		//内部应用的类型列表
 		$this->view->inside_apply_type = Models_StudyResource_StudyRecordConfig::init()->getResourceType();	
 		//应用类别
 		$this->view->apply_type = $this->_config['_apply_type'];
        //测评的类型
        $this->view->study_evaluate = $this->_config['_study_evaluate'];
        //学习资源大的分类
        $this->view->study_big_type = $this->_config['_study_big_type'];
 		//node_id
 		$this->view->node_id = $node_id;
 		$this->view->add_study_type_to = url($this->c,'addStudyTypeToAction');
 		if(empty($info)){
 			echo '无年级信息';
 		}else{
 			if(!in_array($info['code'],$grade_code)){
 				echo '无年级信息';
 			}else{
 				$this->tpl();
 			}
 		}
 		
 	}

 	public function addStudyTypeToAction()
 	{
 		$node_id = $this->post("node_id");
 		$classify_name = $this->post("classify_name");
 		$apply_type = $this->post("apply_type");
 		$type_ids = $this->post("type_classify",0);
 		$api_url = $this->post("api_url",'');
 		$model_name = $this->post("model_name",'');
 		$func_name = $this->post("func_name");
        $study_evaluate = $this->post("study_evaluate");
        $study_big_type = $this->post("study_big_type");
        if(empty($classify_name)){
            return false;
        }
 		if($apply_type == 1){
            //内部
            if(empty($type_ids) && empty($study_evaluate) && (empty($model_name) || empty($func_name))){
                return false;
            }
            $type_id = ($study_big_type == 1) ? $type_ids : $study_evaluate;
 			$api_url = '';

 		}else{
            if(empty($api_url)){
                return false;
            }
 			$type_id = 0;
 		}
 		$data = array(
 			'node_id' =>$node_id,
 			'classify_name' =>$classify_name,
 			'apply_type' =>$apply_type,
 			'type_id' =>$type_id,
 			'api_url' =>$api_url,
 			'model_name' =>$model_name,
 			'func_name' =>$func_name,
            'xk' =>self::$_subject_config[$this->_xk],
            'study_type_id' =>$study_big_type
 			);
 		  $status = $this->_study_type->addStudyType($data);
          //$this->flash_page('studyTypeList', $status);
          $arr = array('status'=>$status,'message'=>'操作成功','success_callback'=>"ajax_flash('studyTypeList');$('#dlg').dialog('close')");
          $this->abort($arr);
          return false;
 	}

 	/**
 	 * 学习资源类型列表
 	 */
 	public function studyTypeListAction()
 	{
 		if (!$this->request()->isAjax()) {
            $layout = $this->getCurrentLayout('common.htm');
            $this->setLayout($layout);
        }
 		//添加类型
 		$this->view->addType = url($this->c,'addStudyTypeAction');
 		//json
 		$this->view->typeListJson = url($this->c,'typeListJsonAction');
 		//节点
 		$this->view->nodeList = url($this->c,'getNodeListAction');
 		$this->view->title = '学习资源类型列表';
 	 	$this->tpl();
 	}

 	/**
 	 * json
 	*/
 	public function typeListJsonAction()
 	{
        //内部应用列表
        $apply_list = Models_StudyResource_StudyRecordConfig::init()->getResourceType();
 		$page = $this->get('page', 1);
        $rows = $this->get('rows', 20);
        $node_id = $this->get("node_id",0);
 		$info = $this->_study_type->getTypeList($page,$rows,$node_id,self::$_subject_config[$this->_xk]);
 		if(!empty($info['total'])){
 			foreach ($info['rows'] as $k =>$value){
                $type = '';
 				$node_info = Models_Resource::init()->getNodeInfoById($value['node_id']);
 			    $infos = $this->nodeInfoAction($node_info['pid']);
 			    $info['rows'][$k]['xd'] = $infos['xd'];
 			    $info['rows'][$k]['bb'] = $infos['bb'];
 			    $info['rows'][$k]['nj'] = $node_info['name'];
                if($value['apply_type'] == 1){
                    if($value['type_id'] == 100){
                       $info['rows'][$k]['apply_type'] = '测评';   
                    }else{
                        $type_id_arr = explode(',',$value['type_id']);
                        foreach($apply_list as $key => $v){
                            if(in_array($key,$type_id_arr)){
                                $type .= $v .',';
                            }
                        }
                        $type = trim($type,',');
                        $info['rows'][$k]['apply_type'] = $type;
                    }
                  
                }else{
                    $info['rows'][$k]['apply_type'] = '第三方';
                }
 			}
            $this->view->info = $info;
 		}else{
            $this->view->info = array('rows' =>array(),'total' =>0);
        }
 		 
 		//删除
 		$this->view->del = url($this->c,'deleteStudyTypeAction');
 		$this->view->edit = url($this->c,'editStudyTypeAction');
 		$this->tpl();
 	}

 	/**
 	 * 删除分类
 	 * @return
 	 */
 	public function deleteStudyTypeAction()
 	{
 		$id = $this->get("id");
 		$status = $this->_study_type->deleteTypeById($id);
        $this->flash_page('studyTypeList', $status);
 	}

    /**
     * 编辑类型
     * @return 
    */
 	public function editStudyTypeAction()
 	{
 		$id = $this->get("id");
 		$info = $this->_study_type->getInfoById($id);
        $arr = array();
        //内部应用的类型列表
        $inside_apply_type = Models_StudyResource_StudyRecordConfig::init()->getResourceType();
            $type_arr = explode(",",$info['type_id']);
            foreach($inside_apply_type as $key =>$value){
                if(in_array($key,$type_arr)){
                    $arr[$key]['status'] = 1;
                }else{
                    $arr[$key]['status'] = 0;
                }
                $arr[$key]['title'] = $value;
            }
 		$this->view->info = $info;
        $this->view->inside_apply_type = $arr;
 		//应用类别
 		$this->view->apply_type = $this->_config['_apply_type'];
        //测评的类型
        $this->view->study_evaluate = $this->_config['_study_evaluate'];
        //学习资源大的分类
        $this->view->study_big_type = $this->_config['_study_big_type'];
 		$this->view->edit_study_type_to = url($this->c ,'editStudyTypeToAction');
 		$this->tpl();
 	}
 
 	/**
 	 * edit to 
 	 * @return 
 	 */
 	public function editStudyTypeToAction()
 	{
 		$id = $this->post("id");
 		$node_id = $this->post("node_id");
 		$classify_name = $this->post("classify_name");
 		$apply_type = $this->post("apply_type");
 		$type_ids = $this->post("type_classify",0);
 		$api_url = $this->post("api_url",'');
 		$model_name = $this->post("model_name",'');
 		$func_name = $this->post("func_name");
        $study_evaluate = $this->post("study_evaluate");
        $study_big_type = $this->post("study_big_type");
        $type_id = 0;
 		if(empty($classify_name)){
 			return false;
 		}
 		if($apply_type == 1){
               if(empty($type_ids) && empty($study_evaluate) && (empty($model_name) || empty($func_name))){
                return false;
            }
            $type_id = ($study_big_type == 1) ? $type_ids : $study_evaluate;
 			$api_url = '';
 		}else{
              if(empty($api_url)){
                return false;
            }
 			$type_id = 0;
 		}
 		$data = array(
 			'node_id' =>$node_id,
 			'classify_name' =>$classify_name,
 			'apply_type' =>$apply_type,
 			'type_id' =>$type_id,
 			'api_url' =>$api_url,
 			'model_name' =>$model_name,
 			'func_name' =>$func_name,
            'xk' =>self::$_subject_config[$this->_xk]
 			);
 		$status = $this->_study_type->updateData($data ,$id);
      // $this->flash_page('studyTypeList', $status);
        $arr = array('status'=>$status,'message'=>'操作成功','success_callback'=>"ajax_flash('studyTypeList');$('#dlg').dialog('close')");
        $this->abort($arr);
        return false;
 	}

 	/**
 	 * 获取年级,版本
 	 * @param  int $pid 
 	 * @param  string $str 
 	 * @return string
 	 */
 	public function nodeInfoAction($pid)
 	{
 		$bb_info = Models_Resource::init()->getNodeInfoById($pid);
        $bb = $bb_info['name'];
        $xk_info = Models_Resource::init()->getNodeInfoById($bb_info['pid']);
        $xk = $xk_info['name'];
        $xd_info = Models_Resource::init()->getNodeInfoById($xk_info['pid']);
        $xd = $xd_info['name'];
        return array('xd' =>$xd,'xk'=>$xk,'bb'=>$bb);
 	}

 	/**
 	 * 获取节点
 	 * 
 	 */
 	public function getNodeListAction()
 	{
 		$grade_code = $this->gradeCodeAction();
 		$id = $this->getVar('id',0);
 		$info = Models_Resource::init()->getSubNode($id);
 		if(!empty($info)){
 			foreach($info as $key =>$v){
 				  if(substr($v['code'],0,2) == 'GS'){
                   if($v['code'] != self::$_subject_config[$this->_xk]){
                    unset($info[$key]);
                }else{
                     $arr = $v;
                     $arr['text'] = $v['name'];
                  if(!in_array($v['code'],$grade_code)){
                    $arr['state'] = 'closed';
                   }else{
                    $arr[$key]['state'] = 'open';
                 }
                 $info = array($arr);
                }
               }else{
                 $info[$key]['text'] = $v['name'];
                 if(!in_array($v['code'],$grade_code)){
                    $info[$key]['state'] = 'closed';
                 }else{
                    $info[$key]['state'] = 'open';
                 }
               }
 			}
 		}
 		echo json_encode($info);
 	}

 	/**
 	 * 年级code
 	 * @return array
 	 */
 	public function gradeCodeAction()
 	{
 		 $node_arr = array();
        $nj = Models_Resource::init()->getNjArr();
        foreach($nj as $key =>$v){
         $node_arr[] =$key;
        }
        return $node_arr;
 	}
 }


?>