<?php

/**
 * Description of StudyResource
 * 后台资源管理
 * @author libo
 */
class Modules_Admin_Controllers_StudyResource extends Modules_Admin_Controllers_Base
{

    protected $_study_resource = '';
    protected $_xk = '';
    protected static $_subject_config = array(
        'sm' => 'GS0024',
        'xl' => 'GS0025',
    );
    protected $_evaluate_practice = 100;
    protected static $_evaluate_classify = 2;
    public function __construct()
    {
        parent::__construct();
        $this->_study_resource = Models_StudyResource_StudyResource::init();
        $this->_xk = $this->getVar('xk');
    }

    /**
     * 添加资源
     */
    public function addStudyResourceAction()
    {
        $this->view->field = $this->get('fields');
        $this->view->grade = $this->get('grade');
        $this->view->bb = $this->get('bb');
        $this->view->son_id = $this->get('son_id');
        $node_id = $this->get("node_id");
        //资源类型
        $this->view->type_list = Models_StudyResource_StudyType::init()->getTypeListByNodeId($node_id);
        //json
        $this->view->resource_json = url($this->c, 'addResourceJsonAction');
        //提交资源post
        $this->view->resource_to = url($this->c, 'addStudyResourceToAction');
        $this->tpl();
    }

    /**
     * 不同类型的资源列表
     */
    public function addResourceJsonAction()
    {
        $field = $this->get('field'); //学段
        $grade = $this->get('grade'); //年级
        $bb = $this->get('bb'); //版本
        $study_type = $this->get('study_type'); //资源类型的主键id
        $son_id = $this->get('son_id'); //知识节点
        $page = $this->get('page');
        $rows = $this->get('rows');
        $data = array();
        $resource = array();
        if(empty($study_type) || $study_type == 'undefined'){
            return false;
        }
        //获取当前资源的相关信息
        $study_type_info = Models_StudyResource_StudyType::init()->getInfoById($study_type);
        if(!empty($study_type_info)){
            //如果是第三方的
            if($study_type_info['apply_type'] == 2){

            }else{
              if(!empty($study_type_info['type_id']) && $study_type_info['type_id'] != $this->_evaluate_practice){
               $resource = $this->getResourceBySearchAction($id = NULL, $field, self::$_subject_config[$this->_xk], $bb, $grade, $son_id, NULL, $study_type_info['type_id'], $page, $rows);
              }else if(!empty($study_type_info['type_id']) && $study_type_info['type_id'] == $this->_evaluate_practice){
              //如果是测评作业的话,就要换一个方法啊
              $resource_arr = Models_Evaluate_EvaluateApi::init()->getUnitEvaluateList($field, $bb, $grade, self::$_subject_config[$this->_xk], $son_id, $page, $rows);
               if($resource_arr['status']){
                $resource['count'] = $resource_arr['data']['count'];
                $resource['data'] = $resource_arr['data']['list'];
               }else{
                $resource['count'] = 0;
                $resource['data'] = array();
              }
             }else{
                //提供model和func调用
                
              } 

            if (!empty($resource)) {
            foreach ($resource['data'] as $key => $v) {
                if($study_type_info['type_id'] != $this->_evaluate_practice){
                  $data[$key]['add'] = " <input type='checkbox' name='study_resource_id' value={$key}|{$v['id']}>";
                  $data[$key]['add'] .= "<input type='hidden' name='study_resource_title' value='{$v['title']}'>";
                  $data[$key]['add'] .= "<input type='hidden' name='study_resource_size' value={$v['pages']}>";
                  $data[$key]['title'] = $v['title'];
            }else{
                  $data[$key]['add'] = "<input type='checkbox' name='study_resource_id' value={$key}|{$v['paper_id']}>";
                  $data[$key]['add'] .= "<input type='hidden'  name='study_resource_title' value='{$v['paper_title']}'>";
                  $data[$key]['add'] .= "<input type='hidden' name='study_resource_size' value=0>";
                  $data[$key]['title'] = $v['paper_title'];  
               }  
             }
           } 

         }
         $this->view->resource = array('total' => $resource['count'], 'rows' => $data);
         $this->tpl();
      }
        
    }

    public function getResourceBySearchAction($id=NULL,$xd=NULL,$xk=NULL,$bb=NULL,$nj=NULL,$nid=NULL,$user_id=NULL,$resource_type=NULL,$page,$page_size)
    {
        $query = " * ";
        $type = " ";
        if($id){
            $query .= " id:$id ";
        }
        if($xd){
            $query .= " AND node_id:$xd ";
        }
        if($xk){
            $query .= " AND node_id:$xk ";
        }
        if($bb){
            $query .= " AND node_id:$bb ";
        }
        if($nj){
            $query .= " AND node_id:$nj ";
        }
        if($nid){
            $query .= " AND node_id:$nid ";
        }
        if($user_id){
            $query .= " AND user_id:$user_id ";
        }
        if($resource_type){
            $resource_type_arr = explode(",",$resource_type);
            foreach ($resource_type_arr as $v){
                $type .= " resource_type:$v OR";
            }
            $type = substr($type,0,-2);
            $query .= " AND (" .$type .")" ;
        }
        
        return  Helper_Search::init()->indexQuery($query,true,'on_time',false,false,$page,$page_size,true);
    
    }
    /**
     * 添加资源的post数据
     */
    public function addStudyResourceToAction()
    {
        $fields = $this->post('field'); //学段
        $grade = $this->post('grade'); //年级
        $bb = $this->post('bb'); //版本
        $study_resource_id = $this->post('study_resource_id'); //学习资源id
        $study_resource_title = $this->post('study_resource_title');
        $study_resource_size = $this->post('study_resource_size');
        $grade_node_node = $this->post('son_id'); //知识节点
        $type_id = $this->post('resource_type'); //资源类型的主键id
        if (empty($fields) || empty($grade) || empty($bb) || empty($study_resource_id) || empty($grade_node_node)) {
            return false;
        }
        $resource_id_arr = explode(",", $study_resource_id);
        $resource_title_arr = explode(',', $study_resource_title);
        $resource_size_arr = explode(',', $study_resource_size);
        foreach ($resource_id_arr as $value) {
            $k_id_arr = explode("|", $value);
            $data = array(
                'study_resource_id' => $k_id_arr[1],
                'study_resource_title' => $resource_title_arr[$k_id_arr[0]],
                'study_resource_size' => $resource_size_arr[$k_id_arr[0]],
                'study_resource_type_id' => $type_id,
                'study_resource_subject_type' => self::$_subject_config[$this->_xk],
                'study_resource_field' => $fields,
                'study_resource_version' => $bb,
                'study_resource_grade' => $grade,
                'study_resource_user_id' => $_SESSION['admin_user']['user_uid'],
                'study_resource_user_name' => $_SESSION['admin_user']['user_realname'],
                'study_grade_node_node' => $grade_node_node,
                'time' => time()
            );
            $status = $this->_study_resource->addStudyResource($data);
        }
          // $this->flash_page('jcUnitList', $status);
           $arr = array('status'=>$status,'message'=>'操作成功','success_callback'=>"ajax_flash('jcUnitList');$('#dlg').dialog('close')");
           $this->abort($arr);
          return false;

    }

    /**
     * 学习资源列表
     */
    public function studyResourceListAction()
    {
        if (!$this->request()->isAjax()) {
            $layout = $this->getCurrentLayout('common.htm');
            $this->setLayout($layout);
        }
        $this->view->title = '增加学习资源';
        //配置
        //$info = Models_StudyResource_StudyRecordConfig::init()->config();
        //学段
        //$this->view->study_field = $info['xd'];
        //年级初始化
       // $this->view->study_grade = $info['nj']['xd001'];
        //版本
        //$this->view->study_bb = $info['bb'];
        $this->view->course_ajax = url($this->c, 'getCourseListAjaxAction');
        //获取节点
        $this->view->nodeList = url($this->c,'getNodeListAction');
        $this->view->xk = $this->_xk;
        $this->tpl();
    }



  
    /**
     * 获取知识节点列表
     */
    public function getCourseListAjaxAction()
    {
        $page = $this->get('page');
        $rows = $this->get('rows');
        $node_id = $this->get("node_id");
        //获取学段,学科,版本,年级信息
        $info = $this->nodeInfoAction($node_id);
        $resource = Models_Resource::init()->getUnit($info['xd'], self::$_subject_config[$this->_xk], $info['bb'], $info['nj']);
        $config = Models_StudyResource_StudyRecordConfig::init()->config();
        if (!empty($resource)) {
            foreach ($resource as $k => $v) {
                if ($v['node_fid'] != 0) {
                    $resource[$k]['_parentId'] = $v['node_fid'];
                    //检测是否有添加的资源
                    $study_type = Models_StudyResource_StudyResource::init()->getStudyResourceType($v['id'], self::$_subject_config[$this->_xk]);
                    $resource[$k]['study_resource'] = array();
                    $study_type_arr = array();
                    if (!empty($study_type)) {
                        foreach ($study_type as $value) {
                            //获取资源分类的信息
                            $study_type_info = Models_StudyResource_StudyType::init()->getInfoById($value['study_resource_type_id']);
                            $study_type_arr[$value['study_resource_type_id']] = $study_type_info['classify_name'];
                        }
                        $resource[$k]['study_resource'] = $study_type_arr;
                    }
                }else{
                    //检测是否有电子教材,微视频和测评
                    $study_type = Models_StudyResource_StudyResource::init()->getStudyResourceType($v['id'], self::$_subject_config[$this->_xk]);
                    $resource[$k]['study_resource'] = array();
                    $arr = array();
                    if (!empty($study_type)) {
                        foreach ($study_type as $value) {
                             //获取资源分类的信息
                            $study_type_info = Models_StudyResource_StudyType::init()->getInfoById($value['study_resource_type_id']);
                            $arr[$value['study_resource_type_id']] = $study_type_info['classify_name'];
                        }
                        $resource[$k]['study_resource'] = $arr;
                    }
                }
            }
        }
        $this->view->parameter = 'fields=' . $info['xd'] . '&grade=' . $info['nj'] . '&bb=' . $info['bb'] .'&node_id='.$node_id;
        $this->view->resource = array('total' => count($resource), 'rows' => $resource);
        //增加资源
        $this->view->add_resource = url($this->c, 'addStudyResourceAction');
        //添加知识节点的描述
        $this->view->node_descript = url($this->c, 'addNodeDiscriptAction');
        //电子教材等资源数据
        $this->view->all_resource = url($this->c, 'allResourceAction');
        //测评
        $this->view->evaluate_resource = url($this->c, 'evaluateResourceAction');
        $this->tpl();
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
        $arr = array();
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

    /**
     * 获取年级,版本
     * @param  int $pid 
     * @param  string $str 
     * @return string
    */
    public function nodeInfoAction($node_id)
    {
        //判断$node_id 是否是年级id
        $nj_info = Models_Resource::init()->getNodeInfoById($node_id);
        //年级的code
        $grade_code = $this->gradeCodeAction();
        if(!in_array($nj_info['code'],$grade_code)){
            return array('xd' =>NULL,'xk'=>NULL,'bb'=>NULL,'nj' =>NULL);
        }else{
        $bb_info = Models_Resource::init()->getNodeInfoById($nj_info['pid']);
        $bb = $bb_info['code'];
        $xk_info = Models_Resource::init()->getNodeInfoById($bb_info['pid']);
        $xk = $xk_info['code'];
        $xd_info = Models_Resource::init()->getNodeInfoById($xk_info['pid']);
        $xd = $xd_info['code'];
        return array('xd' =>$xd,'xk'=>$xk,'bb'=>$bb,'nj' =>$nj_info['code']);
        }
      
    }


    /**
     * 知识节点添加描述
     */
    public function addNodeDiscriptAction()
    {
        $field = $this->get('fields');
        $grade = $this->get('grade');
        $bb = $this->get('bb');
        $son_id = $this->get('son_id');
        $this->view->field = $field;
        $this->view->grade = $grade;
        $this->view->bb = $bb;
        $this->view->son_id = $son_id;
        //编辑描述和添加描述放在一起
        $node_descript = Models_StudyResource_NodeDiscripe::init()->getDescriptData($son_id);
        $this->view->node_descript = (empty($node_descript)) ? '' : $node_descript[0]['son_node_descript'];
        //提交到
        $this->view->add_node_descript_to = url($this->c, 'addNodeDiscriptToAction');
        $this->tpl();
    }

    /**
     * 添加知识节点描述post
     */
    public function addNodeDiscriptToAction()
    {
        $field = $this->post('field');
        $grade = $this->post('grade');
        $bb = $this->post('bb');
        $son_id = $this->post('son_id');
        $node_descript = $this->post('node_descript');
        if (empty($field) || empty($grade) || empty($bb) || empty($son_id) || empty($node_descript)) {
            return false;
        }
        $data = array(
            'field' => $field,
            'version' => $bb,
            'grade' => $grade,
            'son_node' => $son_id,
            'son_node_descript' => $node_descript,
            'subject' => self::$_subject_config[$this->_xk],
            'user_id' => $_SESSION['admin_user']['user_uid']
        );
        $status = Models_StudyResource_NodeDiscripe::init()->addNodeDescript($data);
       // $this->flash_page('jcUnitList', $status);
         $arr = array('status'=>$status,'message'=>'操作成功','success_callback'=>"ajax_flash('jcUnitList');$('#dlg').dialog('close')");
         $this->abort($arr);
         return false;
    }

    /**
     * 电子教材等的列表
     */
    public function allResourceAction()
    {
        $field = $this->get("fields");
        $grade = $this->get("grade");
        $bb = $this->get("bb");
        $son_id = $this->get("son_id");
        $type = $this->get("type");
        $this->view->parameter = "?son_id=" . $son_id . "&type=" . $type;
        $this->view->title = '列表';
        $this->view->all_resource_json = url($this->c, 'allResourceJsonAction');
        $this->tpl();
    }

    /**
     * json
     */
    public function allResourceJsonAction()
    {
        $this->view->info = $this->publicPortAction();
        $this->tpl();
    }

    /**
     * 公共部分
     */
    public function publicPortAction()
    {
        $type = $this->get("type");
        $page = $this->get('page', 1);
        $rows = $this->get('rows', 20);
        $son_id = $this->get("son_id");
        $info = $this->_study_resource->getAllSelectStudyResource(self::$_subject_config[$this->_xk], $type, $son_id, $page, $rows);
        if (!$info) {
            $info = array();
        } else {
            foreach ($info['rows'] as $key => $v) {
                //删除资源的请求地址
                $del_resource = url($this->c, 'deleteStudyResourceAjaxAction');
                $info['rows'][$key]['cz'] = "<a href='javascript:;'  onclick='javascript:ui_qr(\"{$del_resource}?id={$v['resource_id']}\",this);'><span class='icon-table icon-isdel'></span>删除</a>";
                $info['rows'][$key]['study_count'] = Models_StudyResource_StudyRecord::init()->getOneStudyResourceStudyCountByResourceId($v['resource_id']);
            }
        }
        return $info;
    }
    
    
    /**
     * 删除学习资源
     */
    public function deleteStudyResourceAjaxAction()
    {
        $resource_id = $this->getVar('id');
        $status = $this->_study_resource->deleteStudyResourceById($resource_id);
        $this->flash_page('dz_resource', $status);
    }

}
