<?php

/**
 * Description of EvaluateApi
 *
 * @author libo
 */
class Models_Evaluate_EvaluateApi extends Cola_Model
{

    protected $_key = 'adf4ftsdaewrw4sdf4945kkmhg';
    // protected $_url = 'http://172.16.3.21/app/syxue/1/';
     protected $_url = 'http://dev-xue.dodoedu.com/';
     protected $_heyi_url = 'http://heyi.dodoedu.com/remote/test/getList.do';
    /**
     *  获取综合测评列表
     * @param  string $xd 学段
     * @param  string $bb    版本
     * @param  string $nj 年级
     * @param  string $xk    学科
     * @param  int $page
     * @param  int $rows
     * @return  array
     */
    public function getCompositeEvaluateList($xd, $bb, $nj, $xk, $page, $rows)
    {
        $url = $this->_url . "api-getTestList";
        $param = array(
            'key' => $this->_key,
            'xd' => $xd,
            'bb' => $bb,
            'nj' => $nj,
            'xk' => $xk,
            'page' => $page,
            'rows' => $rows
        );
        return json_decode(Cola_Com_Http::post($url, $param), true);
    }

    /**
     * 获取单元知识节点的测评列表
     * @param  string $xd   学段
     * @param  string $bb   版本
     * @param  string $nj   年级
     * @param  string $xk   学科
     * @param  int $node    知识节点
     * @param  int $page
     * @param  int $rows
     * @return array
     */
    public function getUnitEvaluateList($xd, $bb, $nj, $xk, $node, $page, $rows)
    {
        $url = $this->_url . 'api-getNodeTestList';
        $param = array(
            'key' => $this->_key,
            'xd' => $xd,
            'bb' => $bb,
            'nj' => $nj,
            'xk' => $xk,
            'node' => $node,
            'page' => $page,
            'rows' => $rows
        );
        return json_decode(Cola_Com_Http::post($url, $param), true);
    }
    
    /**
     * 合易测评列表
     * @return array
     */
    public function getHeYiEvaluateList($xk,$page,$rows)
    {
        $key = $this->getCacheKey(__FUNCTION__,array($xk));
        $data  = $this->cache->get($key);
        if(!$data){
             $list =  json_decode(Cola_Com_Http::get($this->_heyi_url),true);
             if($list['status'] && !empty($list['totalCount'])){
                $this->cache->set($key,$list,5*60); 
             }
            $data = $list; 
        }
        $arr = array();
        if(!empty($data['totalCount'])){
            foreach($data['data'] as $key =>$v){
                if($key >= $page && $key <= ((intval($page) + intval($rows)) -1)){
                    $arr[] =$v;
                }
            }
        }
        return array('data' =>$arr,'total' =>$data['totalCount']);
    }
}
