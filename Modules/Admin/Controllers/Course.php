<?php

/**
 * 课程统计模块控制器
 * @author ？
 */
class Modules_Admin_Controllers_Course extends Modules_Admin_Controllers_Base
{

    protected $_course = null;

    public function __construct()
    {
        parent::__construct();

        $this->_course = new Models_Course_Course();
    }

    /**
     * 课程开课率统计
     */
    public function teachAction()
    {
        $this->view->title = '开课率统计';
        $this->view->stat_ajax = url($this->c, "teachStatAction");
        $this->tpl();
    }

    /**
     * 开课率统计结果
     */
    public function teachStatAction()
    {
        $data = $this->_course->statForTeachByCity(XK);
        $stat['rows'] = $data;
        $this->view->stat = $stat;
        $this->tpl();
    }

    /**
     * 课程评价
     */
    public function evaluateAction()
    {
        $this->view->title = '评价统计';
        $this->view->stat_ajax = url($this->c, "evaluateStatAction");
        $this->tpl();
    }

    /**
     * 评价统计结果 
     */
    public function evaluateStatAction()
    {
        $data = $this->_course->statForAppraiseByCity(XK);
        $stat['rows'] = $data;
        $this->view->stat = $stat;
        $this->tpl();
    }

    /**
     * 使用情况统计
     */
    public function situationAction()
    {
        $this->view->title = '使用情况统计';
        $this->view->stat_ajax = url($this->c, "situationStatAction");
        $this->tpl();
    }

    /**
     * 使用情况统计结果
     */
    public function situationStatAction()
    {
        $data = $this->_course->statForCourseCountByCity(XK);
        $stat['rows'] = $data;
        $this->view->stat = $stat;
        $this->tpl();
    }

}
