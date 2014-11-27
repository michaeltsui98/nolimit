<?php

/**
 * Description of NodeDiscripe
 * 知识节点描述类
 * @author libo
 */
class Models_StudyResource_NodeDiscripe extends Cola_Model
{

    protected $_table = 'node_descript';

    public function __construct()
    {

    }

    /**
     * 添加知识节点的描述
     * @param  array $data
     */
    public function addNodeDescript($data)
    {
        if (empty($data)) {
            return false;
        }
        //判断是否存在此条记录
        $count = $this->isOrNotHasDescript($data);
        if (empty($count)) {
            return $this->insert($data, $this->_table);
        } else {
            return $this->updateDescript($data);
        }
    }

    /**
     * 判断当前知识节点是否有描述
     * @param array $data
     * @return int
     */
    public function isOrNotHasDescript($data)
    {
        return $this->count(" `son_node` = {$data['son_node']}", $this->_table);
    }

    /**
     *
     * @param array $data
     * @return int
     */
    public function updateDescript($data)
    {
        $sql = "update {$this->_table} set `son_node_descript` = '{$data['son_node_descript']}' where `son_node` = {$data['son_node']}";
        return $this->sql($sql);
    }

    /**
     * 获取知识节点的描述
     * @param int $son_node
     */
    public function getDescriptData($son_node)
    {
        return $this->sql("select `son_node_descript` from {$this->_table} where `son_node` = $son_node");
    }

}
