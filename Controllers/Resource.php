<?php

/**
 * 公用附件上传以及浏览入口，（主要用于js调用）
 * @author liujie <ljyf5593@gmail.com>
 */
class Controllers_Resource extends Controllers_Base
{
    
    public function __construct() {
        parent::__construct();
        $login_actions = array('indexAction', 'uploadAction', 'deleteAction', 'listAction');
        if (empty($this->user_info) AND in_array($this->a, $login_actions)) {
            if (Cola_Request::isAjax()) {
                //echo json_encode(array('type' => 'login', 'data' => array(), 'message' => '您需要登录才可以进行此操作',));
                $this->echoJson('login', '您需要登录才可以上传');
            } else {
                $this->messagePage('/Sign/index/xk=' . $this->xk, '您需要登录才可以进行此操作');
            }
        }
    }
    
    public function indexAction() {
         
        $this->view->init_xd = $init_xd = $this->getVar('xd');
        $this->view->init_xk = $init_xk = $this->getVar('xk');
        $this->view->init_bb = $init_bb = $this->getVar('bb');
        $this->view->init_nj = $init_nj = $this->getVar('nj');
        $this->view->init_zs = $init_zs = $this->getVar('zs');
        
        $obj_type= $this->getVar('obj_type');
        $obj_id= $this->getVar('obj_id');
        
        
        $this->view->xd_arr = $xd_arr= Models_Resource::init()->getSubNode(0);
        
        $this->view->bb_arr =  Models_Resource::init()->getBbByXdAndXk($init_xd, $init_xk);
        
        //取版本ID
        $bb_id = $this->getNodeId($this->view->bb_arr, $init_bb);
        
        //通过版本取年级数组
        if($bb_id){
             $this->view->nj_arr =  Models_Resource::init()->getSubNode($bb_id);
            //取年级ID
            $nj_id = $this->getNodeId($this->view->nj_arr, $init_nj);
        }
        //取知识节点
        if(isset($nj_id) and $nj_id and $init_nj){
        	$this->view->unit_arr = Models_Resource::init()->getUnit($init_xd, $init_xk, $init_bb, $init_nj,$init_zs);
        }
       
        
        $this->view->cate_arr = json_decode(Cola_Com_Http::get(HTTP_WENKU.'interfaces_Node/getResourceType?app_key='.DD_AKEY),1);
        $this->view->file_type=$file_type = Cola_Com_Http::get(HTTP_WENKU.'interfaces_Node/getFileType?app_key='.DD_AKEY);
        //$this->view->js = array('script/ziyuan/resUpload.js');
        
        $sid = session_id();
        $this->view->vars = get_defined_vars();
        $this->tpl();
    }
    public function getNodeId($data,$code){
        $id = false;
        foreach ($data  as $v){
            if($v['code']==$code){
                $id = $v['id'];
                break;
            }
        }
        return $id;
    }
    public function updateAction()
    {
        if(!$this->user_info){
           $this->abort(array('type' => 'login', 'data' => array(), 'message' => '您需要登录才可以进行此操作'));
        }
        $file = $_FILES['Filedata']['tmp_name'];
        $size = $_FILES['Filedata']['size'];
        $ddClient = new Models_DDClient();
        $data = array(
            'app_key'=>DD_AKEY,
            'file_name'=>$_FILES['Filedata']['name'],
            'file' => '@' . $file,
            'sid'=>$this->getVar('PHPSESSID')
        );
        $url = HTTP_WENKU.'interfaces_Upload/resUpload';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        $query = json_decode($result, 1);
         
        if (isset($query['status']) && $query['status']=='error') {
            throw new Exception("上传错误" . var_export($query, 1) .
                var_export($query, 1));
        }
        $data = array();
        $data['size'] = $size ;
        $data['file_path'] = key($query);
        $data['file_name'] = current($query);
        $this->abort($data);
    }
    

    /**
     * 我上传的资源的编辑
     */
    public function editAction(){
        $s = $this->getVar('s');
        $uid = $this->getVar('uid');
        $id = $this->getVar('id');
        $user_id = $this->user_info['user_id'];
        if($uid != $user_id){
            $this->echoJson('error', '您只能修改自己的资源！');
        }
        //资源信息
        $res_info = Models_Resource::init()->getResourceInfoById($id);
        $this->view->info = $res_info;
        
        //初始化xd
        $this->view->xd_arr = $xd_arr= Models_Resource::init()->getSubNode(0);
        
        //初始化BB
        $bb_arr = Models_Resource::init()->getBbByXdAndXk($res_info['xd'], $res_info['xk']);
        $this->view->bb_arr = $bb_arr;
        $init_bb_id = Models_Resource::init()->getNodeIdByCode($res_info['bb'], $bb_arr);
        
        //初始化NJ
        
        $nj_arr = Models_Resource::init()->getSubNode($init_bb_id);
        $this->view->nj_arr = $nj_arr;
        
        //初始化知识节点
        $zs_arr = Models_Resource::init()->getUnit($res_info['xd'], $res_info['xk'], $res_info['bb'], $res_info['nj'],$res_info['nid']);
        $this->view->zs_arr = $zs_arr;
       //var_dump($zs_arr); 
       
        
        $this->tpl();
    }
    /**
     * 保存编码数据
     */
    public function editDoAction(){
        $post = $this->post('data');
        if(!$post){
            $this->echoJson('error','没有数据');
        }
        $res =  Models_Resource::init()->editResource($this->post('id'), $post['doc_title'], $post['doc_summery'], $post['xd'], $post['xk'], $post['bb'], $post['nj'], $post['zs']);
        die;
        $this->echoJson('success', '修改成功');

    }
    
	/**
	 * s=up 删除我上传的，
	 * s=fav 删除我的收藏
	 */
	public function delAction(){
		$s = $this->getVar('s');
		$uid = $this->getVar('uid');
		$id = $this->getVar('id');
		$ref =  $_SERVER['HTTP_REFERER'];
		$user_id = $this->user_info['user_id'];
		if($uid != $user_id){
			$this->echoJson('error', '不是您本人的资源，不能册!');
		}
		
		if($s=='up'){
			$res = Models_Resource::init()->delMyUpload($uid, $id);
		}elseif($s=='fav'){
			$res = Models_Resource::init()->delMyFav($uid, $id);
		}
		//Helper_Search::init()->flushIndex();
		
		if($res){
		  $this->echoJson('success','删除成功！');
		}else {
		  $this->echoJson('error','删除失败！');
		}
		
	}
	
	public function testAction(){
	   $res =  Helper_Search::init()->flushIndex();
	   var_dump($res);
	}
	
    
    
    public  function getInfoAction(){
    	$res = Models_Resource::init()->getResourceInfoById(454);
    	var_dump($res);
    }


    public function testResourceArrayAction()
    {
       var_dump(json_decode(Cola_Com_Http::get(HTTP_WENKU.'interfaces_Node/getResourceType?app_key='.DD_AKEY),1));
        var_dump( Cola_Com_Http::get(HTTP_WENKU.'interfaces_Node/getFileType?app_key='.DD_AKEY));
    }

}
