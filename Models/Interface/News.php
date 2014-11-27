<?php
/**
 * 教材资讯接口类
 * @author michaeltsui98@qq.com
 */
class Models_Interface_News extends Models_Interface_Base {

     /**
      * 取资讯分类
      * @param string $xk GS0024
      * @return array
      */
     public function getCate($xk){
         if($xk=='GS0024'){
             $informationModel = new Modules_Sm_Models_Information();
         }elseif($xk=='GS0025'){
             $informationModel = new Modules_Xl_Models_Information();
         }
         return $informationModel->cached('getBlogElementList',array(),3600);
     }
     
     /**
      * 取资讯列表
      * @param string $xk GS0024
      * @param int $cate_id 分类ID
      * @param int $page  页数
      * @param int $limit 总页数
      * @return multitype:
      */
     public function getList($xk,$cate_id,$page,$limit){
         if($xk=='GS0024'){
             $informationModel = new Modules_Sm_Models_Information();
         }elseif($xk=='GS0025'){
             $informationModel = new Modules_Xl_Models_Information();
         }
         if($cate_id){
           return  $informationModel->getBlogListByElement($cate_id,$page,$limit);
         }else{
           return $informationModel->getBlogList($page,$limit);
         }
     }

     /**
      * 取博客日志详细页
      * @param int $id article_id
      * @return array;
      */
     public function getDetail($id){
        // $data = array();
        // $data['element_type'] = 'Blog';
        // $data['id'] = $id;
        // return Models_DDClient::init()->getDataByApi('site/getelementdatadetail', $data,0);
         //return $this->_execute('/site/getelementdatadetail', $data);
         $informationModel = new Models_Information();
         //return  $informationModel->getBlogDetail($id);
         return $informationModel->cached('getBlogDetail',array($id),3600*30);
         
     }
     /**
      * 添加举报
      * @param string $user_id 举报人用户id
      * @param string $type_id  对象ID 
      * @param string $type  对象 blog (博客) ,photo(相册), answer(回答),question（提问）
      * @param int $reason  举报类型  1，人身攻击，2，垃圾广告  3，敏感信息 4，泄露我的隐私 5，不实信息 6，冒充我
      * @param string $explain 举报内容
      * @param string $target_id 被举报用户ID
      * @param string $target_name 被举报用户姓名
      * @return Ambigous <mixed, array>
      */
     public function addReport($user_id,$type_id,$type,$reason,$explain,$target_id,$target_name){
         $api_name = "mobile/addreport";
         $data = array();
         $data['user_id'] = $user_id;         
         $data['type_id'] = $type_id;         
         $data['type'] = $type;         
         $data['reason'] = $reason;         
         $data['explain'] = $explain;         
         $data['target_id'] = $target_id;         
         $data['target_name'] = $target_name;         
        
         return Models_DDClient::init()->getDataByApi($api_name, $data);
     }
     /**
      * 提交建议
      * @param string $id  sm ='01334081' ,xl ='01095284'
      * @param string $title 标题
      * @param string $content  内容
      * @param string $siteid  sm= '98056604' ,xl = '98056819'
      * @param string $user_id  提建议用户的user_id
      * @param string $user_realname  提建议用户有名称
      * @return Ambigous <mixed, array>
      * @example http://dev-jc.dodoedu.com/interface/index/app_key/b8c8320d9c6d35c0b7dc412a44549bbb/c/Models_Interface_News/m/addSuggest?id=01334081&title=%E6%9C%89%E4%B8%AA%E6%A0%87%E9%A2%98%E6%9C%89%E9%97%AE%E9%A2%98&content=content&siteid=98056604&user_id=&user_realname=u38188559104262200088michael
      */
     public function addSuggest($id,$title,$content,$siteid,$user_id,$user_realname){
         $data = array();
         $data['element_type'] = 'Forum';
         $data['id'] = $id;
         $data['data']['elementId'] = $id;
         $data['data']['title'] = $title;
         $data['data']['content'] = $content;
         $data['data']['siteId'] = $siteid;
         $data['data']['tags'] = array();
         $data['user']['user_id'] = $user_id;
         $data['user']['user_realname'] = $user_realname;
         $api_name = 'site/updateElementDataDetail';
         return Models_DDClient::init()->getDataByApi($api_name, $data);
     }
        
}