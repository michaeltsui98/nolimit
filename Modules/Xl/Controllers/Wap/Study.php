<?php

/**
 * Wap生命的学习控制器
 */
class Modules_Xl_Controllers_Wap_Study extends Modules_Xl_Controllers_Base
{
    /**
     * 学习单元(心理的不用这个方法)
     */
//    public function unitAction()
//    {
//        //$node_id = $this->getVar('node_id');
//        $node_id = 6929;
//        //获取节点的信息
//        $node_info = Models_Resource::init()->getUnitInfoById($node_id);
//        $node_title = $node_info['data']['node_title'];
//        //取子节点列表
//        $unit_node_list = Models_Resource::init()->getSubUnit($node_id);
//        $this->view->node_title = $node_title;
//        $this->view->unit_node_list = $unit_node_list;
//        $this->tpl();
//    }

    /**
     * 单元下对应的资源列表
     */
    public function resourceListAction()
    {
        $node_id = $this->getVar('id', 6729);
        // $node_id = 6729;
        $arr = array();
        $study_resource_type = Models_StudyResource_StudyResource::init()->getStudyResourceType($node_id, $this->view->xk_code);
        if (!empty($study_resource_type)) {
            foreach ($study_resource_type as $key => $study_type) {
                //获取资源类型的信息
                $study_type_info = Models_StudyResource_StudyType::init()->getInfoById($study_type['study_resource_type_id']);
                if ($study_type_info['type_id'] == 100) {
                    //测评的类型为100,要过滤掉
                    unset($study_resource_type[$key]);
                } else {
                    $study_resource_type[$key]['study_resource_type_name'] = $study_type_info['classify_name'];
                }
            }
        }
        if (!empty($study_resource_type)) {
            foreach ($study_resource_type as $key => $type) {
                //获取资源列表
                $info = Models_StudyResource_StudyResource::init()->getDifferentResourceTypeList($node_id, $this->view->xk_code, $type['study_resource_type_id']);
                $arr[$type['study_resource_type_name']]['info'] = $info;
            }
        }
        $this->setLayout($this->getCurrentLayout('wap_index'));
        $this->view->css = array('css/info_list.css', 'css/refresh.css');
        $this->view->arr = $arr;
        $this->tpl();
    }

}
