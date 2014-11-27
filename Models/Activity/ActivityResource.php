<?php
/**
 * 活动资源管理模型
 * @author sizzflair87430@gmail.com
 */
class Models_Activity_ActivityResource extends Cola_Model
{

    protected $_db = '_db';
    protected $_table = 'activity_resourse';
    protected $_pk = 'activity_resourse_id';

    public function __construct()
    {

    }


    /**
     * 获取活动列表
     * @param $inActivityId
     * @return array
     */
    public function getActivityResourceList($inActivityId,$inResourceType)
    {
        return $this->find(array('fileds' => '*',
            'where' => "activity_resourse_pid={$inActivityId}  and  activity_resourse_type={$inResourceType}  ", 'order' => 'activity_resourse_date asc'));
    }


}
