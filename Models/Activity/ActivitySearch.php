<?php
/**
 * 活动搜索
 * @example       
 * $data = array('id'=>'1','activity_info_title'=>'test');
 * //添加索引
 * $res = Models_Activity_ActivitySearch::init()->search()->addIndex($data);
 * //查询索引
 * $res = Models_Activity_ActivitySearch::init()->search()->queryIndex('1');
 * @author Michaeltsui98@qq.com 2014-05-18
 */
class Models_Activity_ActivitySearch extends Cola_Model
{

    protected $_config_name = '_activity_search';
    /**
     * @var Helper_Search
     */
    public $search  = '';


    function search(){
        $this->search = Helper_Search::inits(Cola::getConfig($this->_config_name));
        return $this;
    }

    /**
     * 添加搜索,(学段，学科，版本，年级)=>node_id 用逗号分开，放一个字段里 
     * @return array
     */
    public function addIndex($data)
    {
         return  $this->search->addIndex($data);   
    }

    public function editIndex($id,$data){
        return $this->search->updateIndex($id, $data);
    }
    public function delIndex($id){
        return $this->search->delIndex($id);
    }

    public function queryIndex($id=null,$title=null,$desc=null,$type=null,
            $xd=null,$xk=null,$bb=null,$nj=null,$class=null,$status=null,$area=null,$updater_id=null,$page=1,$page_size=20){
        $query = " * ";
        if($id){
            $query .= " id:$id ";
        }

        if($title){
            $query .= " AND activity_info_title:$title ";
        }
        if($desc){
            $query .= " AND activity_info_description:$desc ";
        }
        if($type){
            $query .= " AND activity_info_type:$type ";
        }
        if($class){
            $query .= " AND activity_info_class:$class ";
        }
        if($status){
            $query .= " AND activity_info_status:$status ";
        }
        if($area){
            $query .= " AND activity_info_area:$area ";
        }
        if($updater_id){
            $query .= " AND activity_info_updater_id:$updater_id ";
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

        return  $this->search->indexQuery($query,true,'activity_info_release_date',false,false,$page,$page_size,true);
    }



}
