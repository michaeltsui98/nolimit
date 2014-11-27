<?php
/**
 * 获取知识节点相关方法，（主要用于ajax的调用）
 * @author liujie <ljyf5593@gmail.com>
 */
class Controllers_Node extends Controllers_Base {

    public function __construct() {
        parent::__construct();
        if (empty($this->user_info)) {
            $this->messagePage('/Sign/index/xk/' . $this->xk, '您需要登录才可以进行此操作');
        }
    }

    /**
     * 获取版本信息
     */
    public function editionAction() {
        // 获取学科信息
        $subject = $this->post('subject');

        $editionList = Models_Resource::init()->getBbByXdAndXk($this->user_info['xd'], $subject);
        $this->view->editionList = $editionList;
        // 获取当前用户的默认版本
        $this->view->currentEdition = Models_Teacher_TeacherInterface::init()->getTeacherTextbookPublisher($this->user_info['user_id'], $subject);
        $this->tpl();
    }

    /**
     * 获取版本下拉选项
     */
    public function editionOptionsAction() {
        $subject = $this->post('subject');
        $editionList = Models_Resource::init()->getBbByXdAndXk($this->user_info['xd'], $subject);
        echo $this->echoJson('success', '', $editionList);
    }

    /**
     * 获取年级信息
     */
    public function gradeAction() {
        $id = $this->post('id');
        if ($id) {
            $gradeList = Models_Resource::init()->getSubNode($id);
            $this->view->gradeList = $gradeList;
            $this->tpl();
        }
    }

    /**
     * 获取顶级知识节点
     */
    public function chapterAction() {
        $subject = $this->post('subject');
        $edition = $this->post('edition');
        $grade = $this->post('grade');

        $topNodeList = Models_Node::getTopNodeList($this->user_info['xd'], $subject, $edition, $grade);
        $this->view->topNodeList = $topNodeList;
        $this->tpl();
    }

    /**
     * 获取子知识节点
     */
    public function nodeAction() {
        $id = Cola_Request::param('id');
        $subNodeList = Models_Resource::init()->getSubUnit($id);
        $this->view->subNodeList = $subNodeList;
        $this->tpl();
    }

    /**
     * 获取知识节点的树形结构视图
     */
    public function nodeTreeAction() {
        $stage = $this->user_info['xd'];
        $subject = $this->post('subject');
        $this->view->subject = $subject;

        $edition = $this->post('edition');
        $this->view->edition = $edition;

        $grade = $this->post('grade');
        $this->view->grade = $grade;

        $unit = Models_Resource::init()->getUnit($stage, $subject, $edition, $grade);
        $unit = Models_StudyResource_StudyRecordConfig::init()->build_tree($unit);
        $this->view->unit = $unit;

        $xk_maps = array(
            Models_Subject::XL => 'xl',
            Models_Subject::SM => 'sm',
        );
        $this->view->xk = $xk_maps[$subject];

        $this->tpl();
    }

    /**
     * 根据版本获取年级信息
     */
    public function gradeByEditionAction() {
        $edition = $this->get('edition');
        $grade = Models_Resource::init()->getSubNode($edition);
        $this->echoJson('success', '', $grade);
    }

    /**
     * 年级选项
     */
    public function gradeOptionsAction() {
        $gradeList = Models_Grade::$stage_grade_list[$this->user_info['xd']];
        $this->echoJson('success', '', $gradeList);
    }
}