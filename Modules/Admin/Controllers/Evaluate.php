<?php

/**
 * Description of Evaluate
 * 测评类
 * @author libo
 */
class Modules_Admin_Controllers_Evaluate extends Modules_Admin_Controllers_Base
{

    protected $_evaluate = null;
    protected $_evaluate_subject = null;
    protected static $_subject_config = array(
        'sm' => 'GS0024',
        'xl' => 'GS0025',
    );
    protected static $_evaluate_classify = array(
        'com' => 1, //综合
        'unit' => 2//单元
    );
    protected $_node_inc_config = '';

    public function __construct()
    {
        parent::__construct();
        $this->_evaluate_subject = $this->getVar('xk');
        $this->_node_inc_config = Cola::getConfig('_nj');
    }

    /**
     * 添加综合测评
     */
    public function addComEvaluateAction()
    {
        $node_id = $this->get("node_id");
        //检测当前的node_id 是否是年级id
        $info = Models_Resource::init()->getNodeInfoById($node_id);
        //年级的code
        $grade_code = $this->gradeCodeAction();
        if (empty($info)) {
            echo '无年级信息';
        } else {
            if (!in_array($info['code'], $grade_code)) {
                echo '无年级信息';
            } else {
                //获取学段,学科,版本,年级信息
                $info = $this->nodeInfoAction($node_id);
                $this->view->study_bb = $info['bb'];
                $this->view->study_field = $info['xd'];
                $this->view->study_grade = $info['nj'];
                $this->view->com_evalaute_json = url($this->c, 'addComEvaluateJsonAction');
                $this->view->com_evaluate_to = url($this->c, 'addComEvaluateToAction');
                $this->tpl();
            }
        }
    }

    /**
     * 和易的综合测评
     */
    public function addHeYiComEvalauteAction()
    {
        $node_id = $this->get("node_id");
        //检测当前的node_id 是否是年级id
        $info = Models_Resource::init()->getNodeInfoById($node_id);
        //年级的code
        $grade_code = $this->gradeCodeAction();
        if (empty($info)) {
            echo '无年级信息';
        } else {
            if (!in_array($info['code'], $grade_code)) {
                echo '无年级信息';
            } else {
                //获取学段,学科,版本,年级信息
                $info = $this->nodeInfoAction($node_id);
                $this->view->study_bb = $info['bb'];
                $this->view->study_field = $info['xd'];
                $this->view->study_grade = $info['nj'];
                //添加到
                $this->view->addHeYiEvaluateTo = url($this->c, 'addHeYiEvaluateToAction');
                //测评列表
                $this->view->HeYiEvaluateListJson = url($this->c, 'HeYiEvaluateListJsonAction');
                $this->tpl();
            }
        }
    }

    /**
     * 添加综合测评的ajax
     */
    public function addComEvaluateJsonAction()
    {
        $bb = $this->get('study_bb'); //版本
        $xd = $this->get('study_field'); //学段
        $nj = $this->get('study_grade'); //年级
        $page = $this->get('page');
        $rows = $this->get('rows');
        $evaluate_arr = array();
        //日期的处理
        $date_arr = explode("/", date("m/d/Y", time()));
        $days = $date_arr[1];
        $day = ($days[0] == 0) ? $days[1] : $days;
        $months = $date_arr[0];
        $month = ($months[0] == 0) ? $months[1] : $months;
        //$date = $day . "/" . $month . "/" . end($date_arr);
        $date = $month . "/" . $day . "/" . end($date_arr);
        //获取综合测评
        $evaluate_list = Models_Evaluate_EvaluateApi::init()->getCompositeEvaluateList($xd, $bb, $nj, self::$_subject_config[$this->_evaluate_subject], $page, $rows);
        if ($evaluate_list['status']) {
            if (!empty($evaluate_list['data']['count'])) {
                foreach ($evaluate_list['data']['list'] as $key => $v) {
                    $evaluate_arr[$key]['title'] = $v['paper_title'];
                    $evaluate_arr[$key]['time'] = '<input name="evaluate_end_time" value=' . $date . ' required style="width:200px">';
                    $evaluate_arr[$key]['add'] = '<input type="checkbox" name="add_com_evalaute" value=' . $key . '|' . $v['paper_id'] . '>';
                    $evaluate_arr[$key]['add'] .= "<input type='hidden' name='node_title' value= '{$v['paper_title']}'>";
                }
                $this->view->data = array('rows' => $evaluate_arr, 'total' => $evaluate_list['data']['count']);
            } else {
                $this->view->data = array('rows' => array(), 'total' => 0);
            }
        } else {
            $this->view->data = array('rows' => array(), 'total' => 0);
        }
        $this->tpl();
    }

    /**
     * 综合测评的添加post数据
     */
    public function addComEvaluateToAction()
    {
        $study_bb = $this->post('study_bb'); //版本
        $study_field = $this->post('study_field'); //学段
        $study_grade = $this->post('study_grade'); //年级
        $add_evaluate_id = $this->post('add_com_evalaute'); //测评id
        $evaluate_end_time = $this->post('evaluate_end_time'); //结束时间
        $evaluate_node_title = $this->post('node_title'); //测评标题
        $times = 0;
        if (empty($study_bb) || empty($add_evaluate_id)) {
            $this->flash_page('mm', 0);
        } else {
            $add_evaluate_id_arr = explode(',', $add_evaluate_id);
            $add_evaluate_title_arr = explode(',', $evaluate_node_title);
            $evaluate_end_time_arr = explode(',', $evaluate_end_time);
            foreach ($add_evaluate_id_arr as $key => $ids) {
                $k_id_arr = explode('|', $ids);
                if (!empty($evaluate_end_time_arr[$k_id_arr[0]])) {
                    $date_arr = explode('/', $evaluate_end_time_arr[$k_id_arr[0]]);
                    //判断选择的时间是否小于当前的时间
                    if (strtotime(end($date_arr) . "-" . $date_arr[0] . "-" . $date_arr[1]) + 24 * 3600 - 1 < time()) {
                        $this->flash_page('mm', 0);
                        die;
                    }
                    $times = $evaluate_end_time_arr[$k_id_arr[0]];
                }
                $data = array(
                    'evaluate_id' => $k_id_arr[1],
                    'evaluate_title' => $add_evaluate_title_arr[$k_id_arr[0]],
                    'evaluate_type_id' => self::$_subject_config[$this->_evaluate_subject],
                    'evaluate_user_name' => $_SESSION['admin_user']['user_realname'],
                    'evaluate_user_id' => $_SESSION['admin_user']['user_uid'],
                    'evaluate_classify' => 1,
                    'evaluate_end_time' => $times,
                    'time' => time(),
                    'evaluate_version' => $study_bb,
                    'evaluate_field' => $study_field,
                    'evaluate_grade' => $study_grade,
                    'evaluate_son_id' => 0,
                    'evaluate_type' => 0
                );
                $status = Models_Evaluate_Evaluate::init()->addEvaluate($data);
            }
            // $this->flash_page('mm', $status);
            $arr = array('status' => $status, 'message' => '操作成功', 'success_callback' => "ajax_flash('mm');$('#dlg').dialog('close')");
            $this->abort($arr);
            return false;
        }
    }

    /**
     * 添加单元测试
     */
    public function addUnitEvalauteAction()
    {
        $study_field = $this->get('fields');
        $study_bb = $this->get('bb');
        $study_grade = $this->get('grade');
        $son_id = $this->get('son_id');
        $this->view->study_field = $study_field;
        $this->view->study_bb = $study_bb;
        $this->view->study_grade = $study_grade;
        $this->view->son_id = $son_id;
        $this->view->parameter = "?study_field=" . $study_field . "&study_bb=" . $study_bb . "&study_grade=" . $study_grade . "&son_id=" . $son_id;
        $this->view->unit_evalaute_json = url($this->c, 'addUnitEvalauteJsonAction');
        $this->view->unit_evaluate_to = url($this->c, 'addUnitEvalauteToAction');
        $this->tpl();
    }

    /**
     * 添加单元测试的ajax
     */
    public function addUnitEvalauteJsonAction()
    {
        $xd = $this->get('study_field'); //学段
        $bb = $this->get('study_bb'); //版本
        $nj = $this->get('study_grade'); //年级
        $node = $this->get('son_id'); //知识节点
        $page = $this->get('page');
        $rows = $this->get('rows');
        $unit_evaluate = array();
        //获取测评列表
        $evaluate_list_arr = Models_Evaluate_EvaluateApi::init()->getUnitEvaluateList($xd, $bb, $nj, self::$_subject_config[$this->_evaluate_subject], $node, $page, $rows);
        if ($evaluate_list_arr['status']) {
            if ($evaluate_list_arr['data']['count']) {
                foreach ($evaluate_list_arr['data']['list'] as $key => $v) {
                    $unit_evaluate[$key]['title'] = $v['paper_title'];
                    $unit_evaluate[$key]['add'] = '<input type="checkbox" name="add_unit_evaluate" value=' . $key . "|" . $v['paper_id'] . '>';
                    $unit_evaluate[$key]['add'] .= "<input type='hidden' name='unit_evaluate_title' value='{$v['paper_title']}'>";
                }
                $this->view->unit_data = array('rows' => $unit_evaluate, 'total' => $evaluate_list_arr['data']['count']);
            } else {
                $this->view->unit_data = array('rows' => array(), 'total' => 0);
            }
        } else {
            $this->view->unit_data = array('rows' => array(), 'total' => 0);
        }
        $this->tpl();
    }

    /**
     * 添加单元测评post数据
     */
    public function addUnitEvalauteToAction()
    {
        $xd = $this->post('study_field'); //学段
        $bb = $this->post('study_bb'); //版本
        $nj = $this->post('study_grade'); //年级
        $add_evaluate_id = $this->post('add_unit_evaluate'); //测评id
        $add_evaluate_title = $this->post('unit_evaluate_title');
        $son_id = $this->post('son_id'); //知识节点
        if (empty($xd) || empty($bb) || empty($nj) || empty($add_evaluate_id) || empty($son_id)) {
            $this->flash_page('aa', 0, '添加失败');
        } else {
            $add_evaluate_id_arr = explode(',', $add_evaluate_id);
            $add_evaluate_title_arr = explode(',', $add_evaluate_title);
            foreach ($add_evaluate_id_arr as $id) {
                $k_id_arr = explode("|", $id);
                $data = array(
                    'evaluate_id' => $k_id_arr[1],
                    'evaluate_type_id' => self::$_subject_config[$this->_evaluate_subject],
                    'evaluate_user_name' => $_SESSION['admin_user']['user_realname'],
                    'evaluate_user_id' => $_SESSION['admin_user']['user_uid'],
                    'evaluate_classify' => 2,
                    'evaluate_end_time' => 0,
                    'time' => time(),
                    'evaluate_version' => $bb,
                    'evaluate_field' => $xd,
                    'evaluate_grade' => $nj,
                    'evaluate_son_id' => $son_id,
                    'evaluate_title' => $add_evaluate_title_arr[$k_id_arr[0]],
                    'evaluate_type' => 0
                );
                $status = Models_Evaluate_Evaluate::init()->addEvaluate($data);
            }
            //$this->flash_page('aa', $status);
            $arr = array('status' => $status, 'message' => '操作成功', 'success_callback' => "ajax_flash('aa');$('#dlg').dialog('close')");
            $this->abort($arr);
            return false;
        }
    }

    /**
     * 测评列表
     */
    public function evaluateListAction()
    {
        if (!$this->request()->isAjax()) {
            $layout = $this->getCurrentLayout('common.htm');
            $this->setLayout($layout);
        }
        $this->view->title = '测评列表';
        //配置
        $info = Models_StudyResource_StudyRecordConfig::init()->config();
        //学段
        $this->view->study_field = $info['xd'];
        //学科
        $this->view->xk = $this->_evaluate_subject;
        //综合测评的地址
        $this->view->add_com_evalaute = url($this->c, 'addComEvaluateAction');
        $this->view->com_evalaute = url($this->c, 'comEvalauteAction');
        //和易综合测评地址
        $this->view->add_heyi_com_evalaute = url($this->c, 'addHeYiComEvalauteAction');
        //获取节点
        $this->view->nodeList = url($this->c, 'getNodeListAction');
        //教材的知识单元
        $this->view->cource_list = url($this->c, 'getCourceListAction');
        $this->tpl();
    }

    /**
     * 知识节点列表
     */
    public function getCourceListAction()
    {
        $page = $this->get("page");
        $rows = $this->get("rows");
        $node_id = $this->get("node_id");
        //获取学段,学科,版本,年级信息
        $info = $this->nodeInfoAction($node_id);
        $resource = Models_Resource ::init()->getUnit($info['xd'], self::$_subject_config[$this->_evaluate_subject], $info['bb'], $info['nj']); //假的数据(需要替换) 'GO003-1'
        if (!empty($resource)) {
            foreach ($resource as $key => $v) {
                if ($v['node_fid'] != 0) {
                    $resource[$key]['_parentId'] = $v['node_fid'];
                }
            }
        }
        $this->view->resource = array('total' => count($resource), 'rows' => $resource);
        //添加测评
        $this->view->add_unit_evaluate = url($this->c, 'addUnitEvalauteAction');
        $this->view->parameter = 'fields=' . $info['xd'] . '&grade=' . $info['nj'] . '&bb=' . $info['bb'];
        //测评列表
        $this->view->unit_evaluate_list = url($this->c, 'unitEvaluateCountAction');
        $this->view->xk = $this->_evaluate_subject;
        $this->tpl();
    }

    /**
     * 单元的测评的列表
     */
    public function unitEvaluateCountAction()
    {
        $field = $this->get('fields');
        $bb = $this->get('bb');
        $grade = $this->get('grade');
        $son_id = $this->get('son_id');
        $this->view->parameter = "?field=" . $field . "&bb=" . $bb . "&grade=" . $grade . "&son_id=" . $son_id . "&evaluate_classify=2";
        $this->view->title = '测评列表-统计';
        //ajax
        $this->view->unit_evaluate_list_ajax = url($this->c, 'unitEvaluateListAjaxAction');
        $this->tpl();
    }

    /**
     * 单元测评的ajax
     */
    public function unitEvaluateListAjaxAction()
    {
        $this->view->unit_evaluate_list = $this->publicAction();
        $this->tpl();
    }

    /**
     * 综合测评ajax
     */
    public function comEvalauteAction()
    {
        if (empty($this->_evaluate_subject)) {
            exit('当前没有科目信息');
        }
        $this->view->evaluate_info = $this->publicAction();
        $this->tpl();
    }

    /**
     * 公共部分
     * @return array
     */
    public function publicAction()
    {
        $page = $this->get('page', 1);
        $rows = $this->get('rows', 20);
        $evaluate_classify = $this->get('evaluate_classify'); //分类id
        $interest_evaluate = '';
        if ($this->_evaluate_subject == 'xl' && $evaluate_classify == 1) {
            $interest_evaluate = 3;
        }
        $evaluate_son_id = $this->get('son_id', 0); //知识节点
        $node_id = $this->get("node_id", 0);
        if (empty($node_id)) {
            $bb = $this->get('bb', 0); //版本
            $xd = $this->get('field', 0); //学段
            $nj = $this->get('grade', 0); //年级
        } else {
            //获取学段,学科,版本,年级信息
            $info = $this->nodeInfoAction($node_id);
            $bb = $info['bb'];
            $xd = $info['xd'];
            $nj = $info['nj'];
        }
        if (empty($interest_evaluate)) {
            $info = Models_Evaluate_Evaluate::init()->getEvaluateListByClassify(self:: $_subject_config[$this->_evaluate_subject], $evaluate_classify, $evaluate_son_id, 0, $page, $rows, $xd, $bb, $nj);
        } else {
            $info = Models_Evaluate_Evaluate::init()->getEvaluateListByClassify(self:: $_subject_config[$this->_evaluate_subject], $evaluate_classify . ',' . $interest_evaluate, $evaluate_son_id, 0, $page, $rows, $xd, $bb, $nj);
        }

        if (!$info) {
            $info = array();
        } else {
            foreach ($info['rows'] as $key => $v) {
                //版本,年级,学段的转换
                $s = $this->fieldGradeVersionTransformAction($v['evaluate_field'], $v['evaluate_grade'], $v['evaluate_version']);
                $info['rows'][$key]['evaluate_field'] = $s['evaluate_field'];
                $info['rows'][$key]['evaluate_grade'] = $s['evaluate_grade'];
                $info['rows'][$key]['evaluate_version'] = $s['evaluate_version'];
                //删除
                $del_evaluate = url($this->c, 'delEvaluateAction');
                $info['rows'][$key]['evaluate_operater'] = "<a href='javascript:; ' onclick='javascript:ui_qr(\"{$del_evaluate}?id={$v['id']}&evaluate_classify={$v['evaluate_classify']}\",this);'><span class='icon-table icon-isdel'></span>删除</a>";

                if ($evaluate_classify == 1) {
                    $info['rows'][$key]['evaluate_classify'] = ($v['evaluate_classify'] == 1) ? "综合测评" : "兴趣心理测评";
                    //编辑
                    $edit_evaluate = url($this->c, 'editEvaluateAction');
                    $info['rows'][$key]['evaluate_operater'] .= "<a href='javascript:;' onclick='javascript:ui_dialog(\"{$edit_evaluate}?id={$v['id']}\",this);'><span class='icon-table icon-isedit'></span>编辑</a>";
                    //统计
                    $count = url($this->c, 'comCountAction');
                    $info['rows'][$key]['evaluate_operater'] .= "<a href='javascript:;' onclick='javascript:ui_dialog(\"{$count}?id={$v['id']}\",this,800,550);'><span class='icon-table icon-isedit'></span>查看统计</a>";
                } else {
                    //测评的统计
                    $status = $this->getDifferentEvaluateStatusAction($v['id']);
                    $info['rows'][$key]['not_finish'] = $status['not_finish'];
                    $info['rows'][$key]['finish'] = $status['finish'];
                    $info['rows'][$key]['due_finish'] = $status['due_finish'];
                }
            }
        }
        return $info;
    }

    /**
     * 删除测评
     */
    public function delEvaluateAction()
    {
        $id = $this->get('id');
        $evaluate_classify = $this->get('evaluate_classify');
        $status = Models_Evaluate_Evaluate::init()->deleteEvaluate($id);
        if ($evaluate_classify == 1 || $evaluate_classify == 3) {
            $this->flash_page('mm', $status);
        } else {
            $this->flash_page('unit_resource_list', $status);
        }
    }

    /**
     * 编辑数据
     */
    public function editEvaluateAction()
    {
        $id = $this->getVar('id');
        $info = Models_Evaluate_Evaluate::init()->getEvaluateById($id);
        $this->view->id = $info['id'];
        $this->view->evaluate_end_time = $info['evaluate_end_time'];
        $this->view->editEvaluateTo = url($this->c, "editEvaluateTo");
        $this->tpl();
    }

    /**
     * 编辑综合测评
     */
    public function editEvaluateToAction()
    {
        $id = $this->post('id');
        $evaluate_end_time = $this->post('evaluate_end_time');
        if (strtotime($evaluate_end_time) + 24 * 3600 - 1 < time()) {
            $this->flash_page('mm', 0);
        }
        $status = Models_Evaluate_Evaluate ::init()->editEvaluate($id, $evaluate_end_time);
        // $this->flash_page('mm', $status);
        $arr = array('status' => $status, 'message' => '操作成功', 'success_callback' => "ajax_flash('mm');$('#dlg').dialog('close')");
        $this->abort($arr);
        return false;
    }

    /**
     * 综合测评的统计
     */
    public function comCountAction()
    {
        $id = $this->get("id"); //测评关联id
        //ajax请求
        $this->view->com_count_ajax = url($this->c, "comCountAjaxAction");
        $this->view->id = $id;
        $this->view->title = '测评统计';
        $this->tpl();
    }

    /**
     * 综合测评的ajax请求
     * @return array
     */
    public function comCountAjaxAction()
    {
        $id = $this->get("id"); //测评关联id
        //获取测评的信息
        $evaluate = Models_Evaluate_Evaluate::init()->getEvaluateById($id);
        //获取不同状态的统计数据(1:未完成,2:完成,3:过期)
        $status = $this->getDifferentEvaluateStatusAction($id);
        //学段,年级,版本的转换
        $s = $this->fieldGradeVersionTransformAction($evaluate['evaluate_field'], $evaluate['evaluate_grade'], $evaluate['evaluate_version']);
        $data = array(
            array(
                'evaluate_title' => $evaluate['evaluate_title'],
                'evaluate_field' => $s['evaluate_field'],
                'evaluate_grade' => $s['evaluate_grade'],
                'evaluate_version' => $s['evaluate_version'],
                'not_finish' => $status['not_finish'],
                'finish' => $status['finish'],
                'due_finish' => $status['due_finish']
            )
        );
        $this->view->data = $data;
        $this->tpl();
    }

    //获取不同状态的统计数据(1:未完成,2:完成,3:过期)
    public function getDifferentEvaluateStatusAction($id)
    {
        $not_finish = Models_Evaluate_EvaluateRecord::init()->getEvalauteRecordCountById($id, 1);
        $finish = Models_Evaluate_EvaluateRecord::init()->getEvalauteRecordCountById($id, 2);
        $due_finish = Models_Evaluate_EvaluateRecord::init()->getEvalauteRecordCountById($id, 3);
        return array('not_finish' => $not_finish, 'finish' => $finish, 'due_finish' => $due_finish);
    }

    /**
     * 学段,年级,版本的转换
     * @param  string $field  学段
     * @param  string $grade  年级
     * @param  string $version 版本
     * @return array
     */
    public function fieldGradeVersionTransformAction($field, $grade, $version)
    {
        $study_config = Models_StudyResource_StudyRecordConfig::init()->config();
        return array(
            'evaluate_field' => $study_config['xd']{$field},
            'evaluate_grade' => $study_config['nj']{$field}{$grade},
            'evaluate_version' => $study_config['bb'][$version],
        );
    }

    /**
     * 和易综合测评添加的post
     */
    public function addHeYiEvaluateToAction()
    {
        $xd = $this->post('study_field'); //学段
        $bb = $this->post('study_bb'); //版本
        $nj = $this->post('study_grade'); //年级
        $add_evaluate_id = $this->post('add_heyi_evaluate'); //测评id
        $add_evaluate_title = $this->post('heyi_evaluate_title');
        $evalaute_classify = $this->post('evaluate_types');
        $evaluate_end_time = $this->post('evaluate_end_time'); //结束时间
        $times = 0;
        if (empty($xd) || empty($bb) || empty($nj) || empty($add_evaluate_id)) {
            $this->flash_page('mm', 0);
        } else {
            $add_evaluate_id_arr = explode(',', $add_evaluate_id);
            $add_evaluate_title_arr = explode(',', $add_evaluate_title);
            $evaluate_end_time_arr = explode(',', $evaluate_end_time);
            $evaluate_classify_arr = explode(',', $evalaute_classify);
            //综合测评的兼容模式
            if ($evaluate_classify_arr[$k_id_arr[0]] == 1) {
                $times = empty($evaluate_end_time_arr[$k_id_arr[0]]) ? 0 : $evaluate_end_time_arr[$k_id_arr[0]];
            }
            foreach ($add_evaluate_id_arr as $id) {
                $k_id_arr = explode("|", $id);
                $data = array(
                    'evaluate_id' => $k_id_arr[1],
                    'evaluate_type_id' => self::$_subject_config[$this->_evaluate_subject],
                    'evaluate_user_name' => $_SESSION['admin_user']['user_realname'],
                    'evaluate_user_id' => $_SESSION['admin_user']['user_uid'],
                    'evaluate_classify' => $evaluate_classify_arr[$k_id_arr[0]],
                    'evaluate_end_time' => $times,
                    'time' => time(),
                    'evaluate_version' => $bb,
                    'evaluate_field' => $xd,
                    'evaluate_grade' => $nj,
                    'evaluate_son_id' => 0,
                    'evaluate_title' => $add_evaluate_title_arr[$k_id_arr[0]],
                    'evaluate_type' => 1
                );
                $status = Models_Evaluate_Evaluate::init()->addEvaluate($data);
            }
            // $this->flash_page('mm', $status);
            $arr = array('status' => $status, 'message' => '操作成功', 'success_callback' => "ajax_flash('mm');$('#dlg').dialog('close')");
            $this->abort($arr);
            return false;
        }
    }

    /**
     * 和易综合测评json
     */
    public function HeYiEvaluateListJsonAction()
    {
        $bb = $this->get('study_bb'); //版本
        $xd = $this->get('study_field'); //学段
        $nj = $this->get('study_grade'); //年级
        $page = $this->get('page');
        $rows = $this->get('rows');
        $com_evaluate = array();
        //日期的处理
        $date_arr = explode("/", date("d/m/Y", time()));
        $days = $date_arr[0];
        $day = ($days[0] == 0) ? $days[1] : $days;
        $months = $date_arr[1];
        $month = ($months[0] == 0) ? $months[1] : $months;
        $date = $month . "/" . $day . "/" . end($date_arr);
        //测评的类型
        $sel = '<select name="evaluate_types"><option value=1>综合测评</option><option value=3>趣味测评</option></select>';
        //获取测评列表
        $evaluate_list_arr = Models_Evaluate_EvaluateApi::init()->getHeYiEvaluateList(self::$_subject_config[$this->_evaluate_subject], $page, $rows);
        if ($evaluate_list_arr['total']) {
            foreach ($evaluate_list_arr['data'] as $key => $v) {
                $com_evaluate[$key]['title'] = $v['questionName'];
                $com_evaluate[$key]['types'] = $sel;
                $com_evaluate[$key]['time'] = '<input name="evaluate_end_time" value=' . $date . ' required style="width:200px">';
                $com_evaluate[$key]['add'] = '<input type="checkbox" name="add_heyi_evaluate" value=' . $key . "|" . $v['questionID'] . '>';
                $com_evaluate[$key]['add'] .= "<input type='hidden' name='heyi_evaluate_title' value='{$v['questionName']}'>";
            }
            $this->view->data = array('rows' => $com_evaluate, 'total' => $evaluate_list_arr['total']);
        } else {
            $this->view->data = array('rows' => array(), 'total' => 0);
        }
        $this->tpl();
    }

    /**
     * 获取节点
     *
     */
    public function getNodeListAction()
    {
        $grade_code = $this->gradeCodeAction();
        $id = $this->getVar('id', 0);
        $info = Models_Resource::init()->getSubNode($id);
        $arr = array();
        if (!empty($info)) {
            foreach ($info as $key => $v) {
                if (substr($v['code'], 0, 2) == 'GS') {
                    if ($v['code'] != self::$_subject_config[$this->_evaluate_subject]) {
                        unset($info[$key]);
                    } else {
                        $arr = $v;
                        $arr['text'] = $v['name'];
                        if (!in_array($v['code'], $grade_code)) {
                            $arr['state'] = 'closed';
                        } else {
                            $arr[$key]['state'] = 'open';
                        }
                        $info = array($arr);
                    }
                } else {
                    $info[$key]['text'] = $v['name'];
                    if (!in_array($v['code'], $grade_code)) {
                        $info[$key]['state'] = 'closed';
                    } else {
                        $info[$key]['state'] = 'open';
                    }
                }
            }
        }
        echo json_encode($info);
    }

    /**
     * 年级code
     * @return array
     */
    public function gradeCodeAction()
    {
        $node_arr = array();
        $nj = Models_Resource::init()->getNjArr();
        foreach ($nj as $key => $v) {
            $node_arr[] = $key;
        }
        return $node_arr;
    }

    /**
     * 获取年级,版本
     * @param  int $pid
     * @param  string $str
     * @return string
     */
    public function nodeInfoAction($node_id)
    {
        //判断$node_id 是否是年级id
        $nj_info = Models_Resource::init()->getNodeInfoById($node_id);
        //年级的code
        $grade_code = $this->gradeCodeAction();
        if (!in_array($nj_info['code'], $grade_code)) {
            return array('xd' => NULL, 'xk' => NULL, 'bb' => NULL, 'nj' => NULL);
        } else {
            $bb_info = Models_Resource::init()->getNodeInfoById($nj_info['pid']);
            $bb = $bb_info['code'];
            $xk_info = Models_Resource::init()->getNodeInfoById($bb_info['pid']);
            $xk = $xk_info['code'];
            $xd_info = Models_Resource::init()->getNodeInfoById($xk_info['pid']);
            $xd = $xd_info['code'];
            return array('xd' => $xd, 'xk' => $xk, 'bb' => $bb, 'nj' => $nj_info['code']);
        }
    }

}
