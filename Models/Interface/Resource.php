<?php
/**
 * 资源接口类
 * @author michaeltsui98@qq.com
 */
class Models_Interface_Resource extends Models_Interface_Base {
     
    /**
     * 资源分类
     * @return array  1=>'教案',2=>"课件",3=>'题库',5=>'微视频',6=>'观摩课',
     */
     public function getCate(){
     	 $arr = Cola::getConfig('_resourceType');
     	 unset($arr[4]);
     	 $data = array();
     	 foreach ($arr  as $k=>$v){
     	 	$data[] = array('id'=>$k,'name'=>$v);
     	 }
     	 return $data;
     }
     /**
      * 取学段,学科，版本，年级 基础节点信息
      * @param int $id 节点的id
      * @return array array(0=>array(id=>''
      * ,pid=>'',
      * code='',
      * is_ok=>'',
      * node_order=>'',
      * is_gd=>'',
      * name=>''))
      */
     public function getBaseNode($id){
         $id = $id?$id:0;
         return Models_Resource::init()->cached('getSubNode',array($id),3600);
     	//return Models_Resource::init()->getSubNode($id);
     }
     /**
      * 取知识节点
      * @param string $xd 学段  xd001,xd002,xd003 (*)
      * @param string $xk 学科 GS0024,GS0025  (*)
      * @param string $bb 版本 v01,v02,v03  (*)
      * @param string $nj 年级  GO003,GO004,GO004  (*)
      * @return mixed 返回知识节点数组
      */
     public function getUnit($xd,$xk,$bb,$nj){
        return  Models_Resource::init()->cached('getUnit',array($xd, $xk, $bb, $nj,null,'cat'),3600);
     	//return Models_Resource::init()->getUnit($xd, $xk, $bb, $nj,null,'cat');
     }
     /**
      * 取资源列表信息
      * @param int $id 资源ID 
      * @param string $xd 学段code xd001
      * @param string $xk 学科code GS0024
      * @param string $bb 版本code v01
      * @param string $nj 年级code GO003
      * @param int $nid  知识节点ID
      * @param string $user_id 用户ID
      * @param int $resource_type  资源类型 1=>'教案',2=>"课件",3=>'题库',5=>'微视频',6=>'观摩课'
      * @param int $page 页数 （*）
      * @param int $page_size 每页多少条  (*)
      * @param string $order_filed 排序字段 默认是on_time 
      * @param string $key 搜索关键字 (可空)
      * @return Ambigous <multitype:array, multitype:number unknown >
      */
     public function getResourceList($id=null,$xd=null,$xk=null,$bb=null,$nj=null,$nid=null,$user_id=null,$resource_type=null,
             $page=1,$page_size=20,$order_filed='on_time',$key='') {
     	 
         $data =  Models_Resource::init()
     	          ->getResourceBySearchForMobile($id,$xd,$xk,$bb,$nj,$nid,$user_id,
     	        $resource_type,$page,$page_size,$order_filed?$order_filed:'on_time',false,$key);
         
         
     	$arr = array();
     	foreach ($data['data'] as $v) {
     	    $row = array();
     	    $row = current($v);
     	    $row['file_size'] = (float)$row['file_size'];
     	    $row['on_time'] = (float)$row['on_time'];
     	    $arr[] = $row;
     	}
     	//var_dump($data['count']);
     	$res = array('data'=>$arr,'count'=>$data['count']);
     	return $res;
     	
     }
     /**
      * 我收藏的资源
      * @param string $user_id (*)
      * @param string $xk 学科code GS0024 (*)
      * @param int $resource_type 资源类型 1=>'教案',2=>"课件",3=>'题库',5=>'微视频',6=>'观摩课'
      * @param int $page  (*)
      * @param int $page_size (*)
      * @return array
      */
     public function getMyFavResourceList($user_id,$xk,$resource_type=null,$page=1,$page_size=20){
        $data =  Models_Resource::init()->getMyFav($user_id, $page, $xk,$resource_type,$page_size);
        if($data){
            return  array_combine(array('data','total'), array_values($data));
        }else{
            return  array('data'=>'','total'=>'');
        }
     }
     /**
      * 我载下的资源列表
      * @param string $user_id (*)
      * @param string $xk 学科code (*)
      * @param int $resource_type
      * @param int $page (*)
      * @param int $page_size (*)
      * @return array
      */
     public function getMyDownResourceList($user_id,$xk,$resource_type=null,$page=1,$page_size=20) {
        $data = (array) Models_Resource::init()->getMyDown($user_id, $page, $xk,$resource_type,$page_size);
        if($data){
            return  array_combine(array('data','total'), array_values($data));
        }else{
            return  array('data'=>'','total'=>'');
        }
     }
     /**
      * 通过资源ID取资源信息
      * @param int $id  968 (*)
      * @param int $cate_id 1为文档型资源，5为视频资源
      * @return mixed  array(
      *  doc_id=>'773'  资源ID
      *  doc_title=>'' 资源标题
      *  doc_summery=>'' 资源描述
      *  doc_page_key=>'4aaf7c6e6dfdaecc.png' 封面图片
      *  完整路径:http://dev-images.dodoedu.com/wenku/767f7c2390b5d775.png
      *  doc_pdf_key=>'242257ce637900cf.pdf' pdf 文件
      *  完整路径:http://dev-images.dodoedu.com/wenku/242257ce637900cf.pdf
      *  @example http://dev-jc.dodoedu.com/interface/index/app_key/b8c8320d9c6d35c0b7dc412a44549bbb/c/Models_Interface_Resource/m/getResourceInfo?id=1131&cate_id=1
      * );
      */
     public function getResourceInfo($id,$cate_id){
     	$data =  Models_Resource::init()->cached('getResourceInfoById',array($id,$cate_id),3600);
     	if($data){
     	  $tags = $this->cached('getResourceTag',array($id),3600);
     	  $tmp = array();
     	  foreach ($tags as $v){
     	      $tmp[] = $v;
     	  }
     	  $data['tags'] = $tmp;
     	}
     	
     	return $data;
     }
     
     /**
      * 资源评价
      * @param string $user_id  用户ID
      * @param int $id 资源ID
      * @param int $zs 知识含量得分 10分制，5星表示，每颗星2分 ,半颗星1分
      * @param int $ty 阅读体验得分  10分制，5星表示，每颗星2分 ,半颗星1分
      * @return mixed
      * @example 
      */
     public function remarkResource($user_id,$id,$zs,$ty){
         $this->claerResourceCache($id);
         return Models_Resource::init()->remarkResource($user_id, $id, $zs, $ty);
     	
     }
     /**
      * 清除资源缓存
      * @param int $id
      */
     public function claerResourceCache($id){
         $info = $this->getResourceInfo($id, 1);
         $cate_id = $info['cate_id'];
         return Models_Resource::init()->unSetCache('getResourceInfoById',array($id,$cate_id));
     }
     /**
      * 添加收藏资源
      * @param string $user_id 用户ID
      * @param id $id 资源ID
      * @return mixed
      */
     public function favResource($user_id,$id){
          return  Models_Resource::init()->favResource($id, $user_id);
          
     }
     /**
      * 删除我收藏的
      * @param string $user_id
      * @param int $id
      * @return mixed
      */
     public function delFavResource($user_id,$id){
         return Models_Resource::init()->delMyFav($user_id, $id);
     }
     /**
      * 删除我上传的资源
      * @param string $user_id
      * @param int $id
      */
     public function delMyUploadResource($user_id,$id){
     	return Models_Resource::init()->delMyUpload($user_id, $id);
     }
     /**
      * 取资源/研训评论接口
      * @param string $type 评论对象    resource  sm_activity_comment
      * @param int $type_id 对象ID  填资源ID  研训ID
      * @param int $start 开始条数 默认为 0
      * @param int $limit 每页多少条
      * @return Ambigous <multitype:, mixed, array>
      */
     public function getCommentList($type, $type_id, $start=0, $limit=10){
     	return Models_Comment_Comment::init()->commentList($type, $type_id, $start, $limit);
     }
     /**
      * 添加资源/研训 评论接口
      * @param string $type  评论对象    resource  sm_activity_comment
      * @param int $id 对象ID  填资源ID  研训ID
      * @param string $content 内容
      * @param string $user_id  用户ID
      * @param string $user_name 用户名
      * @param string $reply_id 用户回复某条评论的评论id
      * @return Ambigous <multitype:, mixed, array>
      */
     public function addComment($type, $id, $content, $user_id,$user_name, $reply_id = NULL){
        return Models_Comment_Comment::init()->addComment($type, $id, $content, $user_id,$user_name, $reply_id);
    }
     

     /**
      * 取资源标签
      * @param unknown $id
      */
     public function getResourceTag($id){
          return $tag_info = Models_DDClient::init()->get_tag($id,'doc');
          //var_dump($tag_info);die;
          
     }
     /**
      * 统计用户上传的资源个数
      * @param string $user_id 用户ID
      * @param string $xk 学科 eg GS0024
      * @return Ambigous <unknown, mixed>
      */
     public function getCountUploadResource($user_id,$xk){
          return Models_Resource::init()->getCountUploadResource($user_id,$xk);
     }
     /**
      * 取资源下载地址
      * @param int $id 资源ID
      * @param string $user_id  用户id
      * @return mixed
      * @example  http://dev-jc.dodoedu.com/interface/index/app_key/b8c8320d9c6d35c0b7dc412a44549bbb/c/Models_Interface_Resource/m/getDownloadResourceUrl?id=1131&user_id=m36359802300862200030
      */
     public function getDownloadResourceUrl($id,$user_id){
          return Models_Resource::init()->download($id,$user_id);
     }
     
    
     
     
}