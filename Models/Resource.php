<?php
/**
 * 学段相关数据模型
 */
class Models_Resource extends  Cola_Model {

    
    
    const  WK_URL = HTTP_WENKU;
     
    
    //获取资源信息http://dev-wenku.dodoedu.com/Interfaces_Resource/getResourceInfo/id/454

    /**
     * 获取资源信息
     * @param int $id
     * @return mixed
     * @example http://dev-wenku.dodoedu.com/interface/index/app_key/2a42f76304529c8174f11ca6ad3573a6/
     * c/Models_Interface_Resource/m/getInfo?id=1152&cate_id=1
     */
    public function getResourceInfoById($id,$cate_id=1){
    	$url = self::WK_URL."/interface/index/app_key/".DD_AKEY."/c/Models_Interface_Resource/m/getInfo?id=$id&cate_id=$cate_id";
    	return $this->getInterface($url);
    }
    /**
     * 取版本
     * @param string $xd
     * @param string $xk
     * @return mixed
     */
    public function getBbByXdAndXk($xd,$xk){
    	$url = HTTP_WENKU.'Interfaces_Node/getBbByXdAndXk';
    	$json =  Cola_Com_Http::post($url, array('xd'=>$xd,'xk'=>$xk,'app_key'=>DD_AKEY));
    	$arr =  json_decode($json,1);
    	$bb_base = Cola::getConfig('_bb');
    	foreach ($arr as $k=>$v){
    		$arr[$k]['name'] = $bb_base[$v['code']];
    	}
    	return $arr;
    }
    /**
     * 获取基础子节点
     * @param int $id
     * @return mixed
     */
    public function getSubNode($id){
        $key = $this->getCacheKey(__FUNCTION__,func_get_args());
        $arr  = $this->cache()->get($key);
        if($arr){
        	return $arr;
        }
    	$url = HTTP_WENKU.'Interfaces_Node/getSubNode';
    	$json =  Cola_Com_Http::post($url, array('id'=>$id,'app_key'=>DD_AKEY));
    	$arr =  json_decode($json,1);
    	if($arr){
    		$this->cache->set($key,$arr,3600*24);
    	}
    	return $arr;
    }
    /**
     * 初始化基础节点ID
     * @param string $code
     * @param array $data
     */
    public function getNodeIdByCode($code,$data){
        $id = 0;
        foreach ($data as $v){
            if($v['code'] == $code){
                $id = (int)$v['id'];
                break;
            }
        }
        return $id;
    }
    /**
     * 取知识节点
     * @param string $xd
     * @param string $xk
     * @param string $bb
     * @param string $nj
     * @return mixed
     */
    public function getUnit($xd,$xk,$bb,$nj,$select=null,$type='option'){
       /*  $key = $this->getCacheKey(__FUNCTION__,func_get_args());
        $arr  = $this->cache()->get($key);
        if($arr){
            return $arr;
        } */
    	$url = HTTP_WENKU.'Interfaces_Node/getUnit';
    	$json =  Cola_Com_Http::post($url, array('xd'=>$xd,'xk'=>$xk,'bb'=>$bb,'nj'=>$nj,'select'=>$select,'type'=>$type,'app_key'=>DD_AKEY));
    	$arr =  json_decode($json,1);
    	/* if($arr){
    	    $this->cache->set($key,$arr,3600*24);
    	} */
    	
    	return $arr;
    }
    /**
     * 取基础节点信息
     * @param int $id
     * @return mixed
     */
    public function getNodeInfoById($id){
    	$url = HTTP_WENKU.'Interfaces_Node/getNodeInfoById';
    	$json =  Cola_Com_Http::post($url, array('id'=>$id,'app_key'=>DD_AKEY));
    	$arr =  json_decode($json,1);
    	return $arr;
    }
    /**
     * 取节点ID
     * @param string $xd
     * @param string $xk
     * @param string $bb
     * @param string $nj
     * @return mixed
     */
    public function getNodeInfoByXdXkBbNj($xd,$xk,$bb,$nj){
    	$url = HTTP_WENKU.'Interfaces_Node/getNodeInfoByXdXkBbNj';
    	$params = array(
    		'xd'=>$xd,
    		'xk'=>$xk,
    		'bb'=>$bb,
    		'nj'=>$nj,
	        'app_key'=>DD_AKEY
    	);
    	$json =  Cola_Com_Http::post($url, $params );
    	$arr =  json_decode($json,1);
    	return $arr;
    }
    /**
     * 资源的文件类型
     * @return mixed
     */
    public function getFileType(){
      $json =   Cola_Com_Http::get(HTTP_WENKU.'interfaces_Node/getFileType?app_key='.DD_AKEY);
      return json_decode($json,1);
    }
    /**
     * 资源类型
     * @return mixed
     */
    public function getResourceType(){
      $json =   Cola_Com_Http::get(HTTP_WENKU.'interfaces_Node/getResourceType?app_key='.DD_AKEY);
      return json_decode($json,1);
    }
    /**
     * 取nj
     * @return mixed
     */
    public function getNjArr(){
      $json =   Cola_Com_Http::get(HTTP_WENKU.'interfaces_Node/getNj?app_key='.DD_AKEY);
      return json_decode($json,1);
    }
    /**
     * 取版本
     * @return mixed
     */
    public function getBbArr(){
      $json =   Cola_Com_Http::get(HTTP_WENKU.'interfaces_Node/getBb?app_key='.DD_AKEY);
      return json_decode($json,1);
    }
    /**
     * 取学科
     * @return mixed
     */
    public function getXkArr(){
      $json =   Cola_Com_Http::get(HTTP_WENKU.'interfaces_Node/getXk?app_key='.DD_AKEY);
      return json_decode($json,1);
    }
    /**
     * 取所有的节点
     * @return mixed
     */
    public function getAllNode(){
      $json =   Cola_Com_Http::get(HTTP_WENKU.'interfaces_Node/getTreeNode?app_key='.DD_AKEY);
      return json_decode($json,1);
    }
    

    /**
     * 取知识子节点
     * @param int $id
     * @return mixed
     */
    public function getSubUnit($id){
    	$url = HTTP_WENKU.'Interfaces_Node/getSubUnit';
    	$json =  Cola_Com_Http::post($url, array('id'=>$id,'app_key'=>DD_AKEY));
    	$arr =  json_decode($json,1);
    	return $arr;
    }
    
    public  function getMyUpload($user_id,$xk,$cate_id,$page,$limit,$order_field='doc_id',$order='desc'){
        //http://dev-wenku.dodoedu.com/interface/index/access_token/a89c46e06c00ec82088899eba75f29c2/c/Models_Interface_Resource/m/getMyUpload?user_id=s35951247001862320095&xk=&type=0&page=1&limit=10&order_field=doc_id&order=desc
        $url = self::WK_URL."/interface/index/app_key/".DD_AKEY."/c/Models_Interface_Resource/m/getMyUpload?";
        $url .= "user_id=$user_id&xk=$xk&type=$cate_id&page=$page&limit=$limit&order_field=$order_field&order=$order";
        return $this->getInterface($url);
    }
    /**
     * 我下载的资源
     * @param string $user_id
     * @param int $page
     * @param string $xk 学科
     * @param int $type 资源类型 （可选）
     * @return mixed
     */
    public function getMyDown($user_id,$page,$xk,$type=null,$page_size=20){
        $url = HTTP_WENKU.'Interfaces_Resource/getMyDown';
        $json =  Cola_Com_Http::post($url, array('user_id'=>$user_id,'page'=>$page,'xk'=>$xk,'cate_id'=>$type,'limit'=>$page_size,'app_key'=>DD_AKEY));
        $arr =  json_decode($json,1);
        if($arr['status']!='ok'){
    		return $arr;
    	}
    	return $arr['data'];
    }
    public function getCountUploadResource($user_id,$xk){
        $url = HTTP_WENKU.'Interfaces_Resource/getCountUploadResource';
        $json =  Cola_Com_Http::post($url, array('user_id'=>$user_id,'xk'=>$xk,'app_key'=>DD_AKEY));
        $arr =  json_decode($json,1);
        if($arr['status']!='ok'){
            return $arr;
        }
        return $arr['data'];
    }
    /**
     * 我收藏的资源
     * @param string $user_id
     * @param int $page
     * @param string $xk
     * @param int $type 资源类型 （可选）
     * @return mixed
     */
    public function getMyFav($user_id,$page,$xk,$type=null,$page_size=20){
        $url = HTTP_WENKU.'Interfaces_Resource/getMyFav';
        $json =  Cola_Com_Http::post($url, array('user_id'=>$user_id,'page'=>$page,'xk'=>$xk,'cate_id'=>$type,'limit'=>$page_size,'app_key'=>DD_AKEY));
        $arr =  json_decode($json,1);
        if($arr['status']!='ok'){
    		return $arr;
    	}
    	return $arr['data'];
    }
    /**
     * 收藏资源
     * @param int $id 资源ID
     * @param string $user_id 
     * @return mixed
     */
    public function favResource($id,$user_id){
        $url = HTTP_WENKU.'Interfaces_Resource/fav';
        $json =  Cola_Com_Http::post($url, array('user_id'=>$user_id,'id'=>$id,'app_key'=>DD_AKEY));
        $arr =  json_decode($json,1);
        if($arr['status']!='ok'){
    		return $arr;
    	}
    	return $arr;
    }
    

    /**
     * 删除我收藏的
     * @param string $uid
     * @param int $id
     */
    public function delMyFav($user_id,$id){
    	$url = HTTP_WENKU.'Interfaces_Resource/delMyFav';
        $json =  Cola_Com_Http::post($url, array('user_id'=>$user_id,'id'=>$id,'app_key'=>DD_AKEY));
        $arr =  json_decode($json,1);
        if($arr['status']!='ok'){
    		return $arr;
    	}
    	return $arr;
    }
    /**
     * 更新资源信息
     */
    public function editResource($id,$title,$summery,$xd,$xk,$bb,$nj,$zs){
    	$url = HTTP_WENKU.'Interfaces_Resource/editResource';
    	$json =  Cola_Com_Http::post($url, array('id'=>$id,'title'=>$title,'summery'=>$summery,
    	        'xd'=>$xd,
    	        'xk'=>$xk,
    	        'bb'=>$bb,
    	        'nj'=>$nj,
    	        'zs'=>$zs,
    	        'app_key'=>DD_AKEY));
        $arr =  json_decode($json,1);

        if($arr['status']!='ok'){
    		throw  new Exception($arr['data']);
    	}

    	//更新标签
    	$dd = new Models_DDClient();
    	$data['target_id'] = $id;
        $data['target'] = 'doc';
        if($_REQUEST['tags']==null)return false;
        $data['tags_array'] = json_encode(array_values($_REQUEST['tags']));
        $data['creater_id'] = $_SESSION['user']['user_id'];
    	$dd->add_tag($data);

    	return $arr['data'];
    }
    /**
     * 删除我上传的
     * @param string $uid
     * @param int $id
     */
    public function delMyUpload($user_id,$id){
    	$url = HTTP_WENKU.'Interfaces_Resource/delMyUpload';
        $json =  Cola_Com_Http::post($url, array('user_id'=>$user_id,'id'=>$id,'app_key'=>DD_AKEY));
        $arr =  json_decode($json,1);
        if($arr['status']!='ok'){
    		return $arr;
    	}
    	return $arr;
    }

    /**
     * 取知识节点标题
     * @param int $id
     * @return mixed
     */
    public function getUnitTitleById($id){
        $url = HTTP_WENKU.'Interfaces_Node/getUnitTitleById';
        $json =  Cola_Com_Http::post($url, array('id'=>$id,'app_key'=>DD_AKEY));
        $arr =  json_decode($json,1);
        return $arr;
    }

    /**
     * 取知识节点信息
     * @param int $id
     * @param string $filed  字段名，多个用逗号隔开
     * @return mixed
     */
    public function getUnitInfoById($id,$filed="*"){
        $url = HTTP_WENKU.'Interfaces_Node/getUnitInfoById';
        $json =  Cola_Com_Http::post($url, array('id'=>$id,'filed'=>$filed,'app_key'=>DD_AKEY));
        $arr =  json_decode($json,1);
        return $arr;
    }
    /**
     * 通过全文索引来取各种资源
     * @param string $id  资源ID
     * @param string $xd  学段
     * @param string $xk  学科
     * @param string $bb  版本编号
     * @param string $nj  年级编号
     * @param int $nid  知识节点
     * @param string $user_id 用户ID
     * @param int $resource_type  资源类型
     */
    public function getResourceBySearch($id=NULL,$xd=NULL,$xk=NULL,$bb=NULL,$nj=NULL,$nid=NULL,$user_id=NULL,$resource_type=NULL,$page=1,$page_size=20,$order_filed='on_time',$debug=false){
        $query = "";
        if($id){
            $query .= " id:$id ";
        }
        if($xd){
            if($query){
                $query .= " AND node_id:$xd ";
            }else{
                $query .= "  node_id:$xd ";
            }
        }
        if($xk){
            if($query){
                $query .= " AND node_id:$xk ";
            }else{
                $query .= " node_id:$xk ";
            }
        }
        if($bb){
            if($query){
                $query .= " AND node_id:$bb ";
            }else{
                $query .= " node_id:$bb ";
            }
        }
        if($nj){
            if($query){
                $query .= " AND node_id:$nj ";
            }else{
                $query .= " node_id:$nj ";
            }
        }
        if($nid){
            if($query){
                $query .= " AND node_id:$nid ";
            }else{
                $query .= " node_id:$nid ";
            }
        }
        if($user_id){
            if($query){
                $query .= " AND user_id:$user_id  ";
            }else{
                $query .= " user_id:$user_id  ";
            }
        }
        if($resource_type){
            if($query){
                $query .= " AND resource_type:$resource_type ";
            }else{
                $query .= " resource_type:$resource_type  ";
            }
        }
        if($debug){
          echo $query;
        }
        return  Helper_Search::init()->indexQuery($query,false,$order_filed,false,false,$page,$page_size,true);
    }
    public function getResourceBySearchForMobile($id=NULL,$xd=NULL,$xk=NULL,$bb=NULL,$nj=NULL,$nid=NULL,
            $user_id=NULL,$resource_type=NULL,$page=1,$page_size=20,$order_filed='on_time',$debug=false,$key=''){
        $query = "";
        
        if($key){
	        $query .= "  ({$key})  ";
	    }
	    
        if($id){
            $query .= " AND id:$id ";
        }
        if($xd){
            if($query){
                $query .= " AND node_id:$xd ";
            }else{
                $query .= "  node_id:$xd ";
            }
        }
        if($xk){
            if($query){
                $query .= " AND node_id:$xk ";
            }else{
                $query .= " node_id:$xk ";
            }
        }
        if($bb){
            if($query){
                $query .= " AND node_id:$bb ";
            }else{
                $query .= " node_id:$bb ";
            }
        }
        if($nj){
            if($query){
                $query .= " AND node_id:$nj ";
            }else{
                $query .= " node_id:$nj ";
            }
        }
        if($nid){
            if($query){
                $query .= " AND node_id:$nid ";
            }else{
                $query .= " node_id:$nid ";
            }
        }
        if($user_id){
            if($query){
                $query .= " AND user_id:$user_id  ";
            }else{
                $query .= " user_id:$user_id  ";
            }
        }
        if($resource_type){
            if($query){
                $query .= " AND resource_type:$resource_type ";
            }else{
                $query .= " resource_type:$resource_type  ";
            }
        }
        if($query){
        	$query .= " NOT resource_type:4 NOT resource_type:8 ";
        }
        if($debug){
          echo $query;
          die;
        }
        //var_dump($page_size);die;
        return  Helper_Search::init()->indexQuery($query,false,$order_filed,false,false,$page,$page_size,true);
    }
    /**
     * 获取推荐资源
     * @param string $xd
     * @param string $xk
     * @param string $bb
     * @param string $limit
     */
    public function getRecommendResource($xd='',$xk='',$bb='',$limit=8,$page=1){
        $query = "";

        if($xd){
            $query .= "  node_id:$xd ";
        }
        if($xk){
            if($query){
                $query .= " AND node_id:$xk ";
            }else{
                $query .= " node_id:$xk ";
            }
        }
        if($bb){
            if($query){
                $query .= " AND node_id:$bb ";
            }else{
                $query .= " node_id:$bb ";
            }
        }

        $query .= " NOT (resource_type:8) ";

        return  Helper_Search::init()->indexQuery($query,false,'remark',false,false,$page,$limit,true);

    }

    /**
     * 字节换为大小
     *
     * @param int $bytes
     */
    public function bytesToSize ($bytes)
    {
        if ($bytes > 0) {
            $units = array(
                    0 => 'B',
                    1 => 'KB',
                    2 => 'MB',
                    3 => 'GB'
            );
            $log = log($bytes, 1024);
            $power = (int) $log;
            $size = pow(1024, $log - $power);
            return round($size, 2) . ' ' . $units[$power];
        } else {
            return 0;
        }
    }
    /**
     * 计算时间差
     * @param int $timestamp
     * @return Ambigous <string, unknown>
     */
    public function dateSpan($timestamp) {
        $_lang = array();
        $_lang ['day_before'] = '天前';
        $_lang ['hour_before'] = '小时前';
        $_lang ['minute_before'] = '分钟前';
        $_lang ['seconds_before'] = '秒前';
        $_lang ['now'] = '刚刚';
        $time = (int)$_SERVER['REQUEST_TIME'] - $timestamp;
        if ($time > 24 * 3600) {
            $result = intval ( $time / (24 * 3600) ) . $_lang ['day_before'];
        } elseif ($time > 3600) {
            $result = intval ( $time / 3600 ) . $_lang ['hour_before'];
        } elseif ($time > 60) {
            $result = intval ( $time / 60 ) . $_lang ['minute_before'];
        } elseif ($time > 0) {
            $result = $time . $_lang ['seconds_before'];
        } else {
            $result = $_lang ['now'];
        }
        return $result;
    }

    /**
     * 获取资源评分信息
     * @param int $id
     * @throws Exception
     * @return mixed
     */
    public function getResourceRemarkById($id){
    	$url = HTTP_WENKU.'Interfaces_Resource/getResourceRemarkInfo';
    	$json =  Cola_Com_Http::post($url, array('id'=>$id,'app_key'=>DD_AKEY));
    	$arr =  json_decode($json,1);
    	if($arr['status']!='ok'){
    		throw  new Exception($arr['data']);
    	}
    	return $arr['data'];
    }
    /**
     * 资源评论
     * @param string $user_id
     * @param int $id 资源ID
     * @param int $zs 知识量分
     * @param int $ty 阅读体验分
     * @return mixed
     */
    public function remarkResource($user_id,$id,$zs,$ty){
        $url = HTTP_WENKU.'Interfaces_Resource/remarkResource';
        $json =  Cola_Com_Http::post($url, array('id'=>$id,'user_id'=>$user_id,'zs'=>$zs,'ty'=>$ty,'app_key'=>DD_AKEY));
        $arr =  json_decode($json,1);
        return $arr;
    }
    /**
     * 通过教育关系获取资源关系
     * @param string $xd
     * @param string $xk
     * @param string $bb
     * @param string $nj
     * @throws Exception
     * @return mixed
     */
    public function getResourceRelationByEdu($xd,$xk,$bb,$nj){
    	$url = HTTP_WENKU.'Interfaces_Edu/getResRelation';
    	$json =  Cola_Com_Http::post($url, array(
    	        'xd'=>$xd,
    	        'xk'=>$xk,
    	        'bb'=>$bb,
    	        'nj'=>$nj,
    	        'app_key'=>DD_AKEY));
    	$arr =  json_decode($json,1);
    	if($arr['status']!='ok'){
    		throw  new Exception($json);
    	}
    	return $arr['data'];
    }
    
    /**
     * 取资源的下载地址
     * @param string $id 资源ID
     * @param string $user_id 用户ID
     * @return Ambigous <mixed, boolean>
     */
    public function download($id,$user_id){
        $url = self::WK_URL."/interface/index/app_key/".DD_AKEY."/c/Models_Interface_Resource/m/getResourceUrl?id=$id&user_id=$user_id";
        return $this->getInterface($url);
    }

    
    /**
     * 取接口数据
     * @param string $url
     * @return mixed|boolean
     */
    public  function getInterface($url){
        $json = Cola_Com_Http::get($url);
        $arr = json_decode($json,1);
        if(isset($arr['data'])){
            return $arr['data'];
        }
        return false;
    }



    /**
     * 取文件预览类型
     * @param int $cate_id
     * @param string $ext
     * @return string
     */
    public  function getPerviewType($cate_id,$ext){
        $type = 'isTxt';
        $cate_id = (int)$cate_id;
    	if($cate_id == 4){
    		$type = 'isDown';
    	}elseif($cate_id==1 or $cate_id==2 or $cate_id==3 or $cate_id==7){
    	    //'ppt','pps','pptx','pot','ppsx'
    		if($ext == 'ppt' or $ext == 'pps' or $ext == 'pptx' or $ext == 'pot' or $ext == 'ppsx' ){
    			$type = 'isPpt';
    		}
    	}elseif($cate_id==5 or $cate_id == 6){
    		$type = 'isVideo';
    	}
    	return $type;
    }

   public  function getStar ($num)
    {
        if ($num > 10) {
            $num = 10;
        }
        if ($num) {
            $i = $num / 2;
            $f = (float) number_format($i, 2);
        } else {
            $f = 0;
        }
        if (is_float($f)) {
            if(strpos((string)$f, '.')!==false){
                $int = (int) substr((string) $f, 0, strpos((string) $f, '.'));
            }else{
                $int = $f;
            }
        } else {
            $int = $f;
        }
        $de = $f - $int;
        $html = "";
        // 是否有半颗星
        $isde = false;
        if ($de < 1 and $de >= 0.1) {
            $isde = true;
        }

        for ($i = 0; $i < 5; $i ++) {
            if ($i < $int) {
                $html .= '<i class="icon_tc3"></i>';
            } else {
                if ($isde) {
                    $html .= '<i class="icon_tc4"></i>';
                    $isde = false;
                } else {
                    $html .= '<i class="icon_tc2"></i>';
                }
            }
        }
        return $html;
    }


}