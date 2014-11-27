<?php
/**
 * 知识节点模型工具类
 *
 * @author Liu.Jie <ljyf5593@gmail.com>
 *
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */

class Models_Node {

    public static function getNodeSelect($stage, $subject, $edition, $grade) {
        $html = '';
        $nodeList = Models_Resource::init()->getUnit($stage, $subject, $edition, $grade);
        if ($nodeList) {
            $html .= '<select name="node">';
            foreach ($nodeList as $item) {
                $html .= $item['option'];
            }
            $html .= '</select>';
        }
        return $html;
    }

    /**
     * 获取顶级节点
     * @param $stage
     * @param $subject
     * @param $edition
     * @param $grade
     */
    public static function getTopNodeList($stage, $subject, $edition, $grade){
        $nodeList = Models_Resource::init()->getUnit($stage, $subject, $edition, $grade);
        $topNodeList = array();
        if($nodeList){
            $topNodeList = array_filter($nodeList, 'self::isTopNode');
        }
        return $topNodeList;
    }

    private static function isTopNode(array $node) {
        return $node['node_fid'] == '0';
    }

    /**
     * 获取节点信息
     * @param  [type] $nodeId [description]
     * @return [type]         [description]
     */
    public static function getNodeInfoById($nodeId) {

    }
} 