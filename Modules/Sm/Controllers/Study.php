<?php

/**
 * Description of StudyResource
 *  心理在线学习模块
 * @author libo
 */
class Modules_Sm_Controllers_Study extends Modules_Sm_Controllers_Base
{

    public $layout = '';
    protected $_site = '教材支撑平台';
    protected $study_config = '';
    protected $default_version = '';
    protected $_study_record = '';
    protected $_resource = '';
    protected $_grade = NULL;
    protected $_c = NULL;
    protected $_qustion_type = NULL; //问题的类型
    protected $_answer_type = NULL; //回答问题的类型
    protected $_jc_bb_list = NULL; //版本列表
    protected $_config = NULL;
    protected $_defalut_xd = 'xd001';
    protected $_default_bb = 'v11';

    public function __construct()
    {
        parent::__construct();
        $this->study_config = Models_StudyResource_StudyRecordConfig::init();
        if (!empty($this->user_info)) {
            //获取年级
            $this->_grade = $this->study_config->getCurClassSchoolByUserId('grade_code');
            // if (empty($this->_grade)) {
            //     $this->messagePage('/' . $this->view->xk . '/index', '没有年级信息');
            // }
        }
        $this->_config = $this->study_config->config();
        $this->layout = $this->getCurrentLayout('index.htm');
        $this->default_version = Models_StudyResource_StuDefaultVersion::init();
        $this->_study_record = Models_StudyResource_StudyRecord::init();
        $this->_resource = Models_StudyResource_StudyResource::init();
        $this->_c = explode("_", $this->c);
        $this->_qustion_type = $this->view->xk . "_node_id";
        $this->_answer_type = $this->view->xk . "_node_id";
        $this->_jc_bb_list = Models_Resource::init()->getBbByXdAndXk(empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $this->view->xk_code);
    }

    /**
     * 学习中心首页
     */
    public function indexAction()
    {
        $this->user_infoAction();
        //获取用户选择的教材版本
        $default_version = $this->getStudyResourceVersionAction('index');
        if (empty($default_version)) {
            $this->view->page_title = '学习中心';
            $this->view->user_realname = $this->user_info['user_realname'];
            $this->view->user_id = $this->user_info['user_id'];
            //默认的教材版本
            $this->view->default_version = $this->_jc_bb_list[0];
            //年级
            $this->view->grade = $this->_grade;
            //js
            $this->view->js = array(
                "xuexi/stu_learning.js"
            );
            // $this->view->root = array(//要修改
            //      "script/dialog.js"
            //     );
            $this->setLayout($this->layout);
            $this->tpl();
        } else {
            //跳转到学习单元列表页面去
            $this->redirect("/" . $this->view->xk . "/Study/studyDetail");
        }
    }

    /**
     * 学习单元列表页面
     */
    public function studyDetailAction()
    {
        if (!empty($this->user_info)) {
            //获取用户选择的教材版本
            $default_version = $this->getStudyResourceVersionAction();
            //版本列表
            $this->view->bb_list = $this->_jc_bb_list;
            $this->view->bb = $default_version;
        } else {
            $this->view->bb_list = Models_Resource::init()->getBbByXdAndXk($this->_defalut_xd, $this->view->xk_code);
            $this->view->bb = $this->_default_bb;
        }
        $this->setLayout($this->layout);

        $this->view->page_title = '学习教材';
        //js
        $this->view->js = array(
            "xuexi/stu_detail.js",
            "evaluate/eva_list.js"
        );
        $this->tpl();
    }

    /**
     * ajax加载
     */
    public function studyDetailMoreAjaxAction()
    {
        $page_config = $this->study_config->pageConfig();
        $version = $this->post('bb');
        $grade_code = $this->post('grade');
        //获取知识节点的学习资源(电子教材和微视频)
        $info = Models_Resource::init()->getUnit(empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $this->view->xk_code, $version, $grade_code);
        $node_tree = $this->study_config->build_tree($info); //知识树
        $node = $this->study_config->getNodePage($node_tree, $page_config['start'], $page_config['limit']);
        // $configs = $this->study_config->config();
        if (!empty($node)) {
            foreach ($node as $key => $v) {
                if (isset($v['child'])) {
                    foreach ($v['child'] as $k => $value) {
                        //判断是否有学习资源
                        $study_type_id_arr = $this->hasStudyResourceAction($value['id']);
                        $html = NULL;
                        if (!empty($study_type_id_arr)) {
                            foreach ($study_type_id_arr as $type) {
                                //获取资源类型的信息
                                $study_type_info = Models_StudyResource_StudyType::init()->getInfoById($type['study_resource_type_id']);
                                $code = ($study_type_info['type_id'] == 100) ? 'evaluate_task' : 'code' . $study_type_info['id'];
                                $html .= '<a  class="a-mulO f-ml10" href="javascript:;" title=""  class="f-ml10 f-mr10" study_value_code = "' . $code . '" name="study_resource_type" study_value=' . $study_type_info['id'] . '>' . $study_type_info['classify_name'] . '</a>';
                            }
                            $v['child'][$k]['study_resource'] = $html;
                            //获取第一个学习资源
                            $study_resource = $this->getFirstStudyResourceAction($value['id'], $version, $grade_code);
                            //获取资源类型的信息
                            $study_type_info = Models_StudyResource_StudyType::init()->getInfoById($study_resource[0]['study_resource_type_id']);
                            if ($study_type_info['type_id'] != 100) {//100:测评的类型id
                                $v['child'][$k]['url'] = "/" . $this->view->xk . "/Study/learin/" . $study_resource[0]['study_resource_id'];
                            }
                        }
                    }
                    $node[$key]['child'] = $v['child'];
                } else {
                    //判断是否有学习资源(电子教材,微视频,测评作业)
                    $study_type_id_arr = $this->hasStudyResourceAction($v['id']);
                    $str = NULL;
                    if (!empty($study_type_id_arr)) {
                        foreach ($study_type_id_arr as $type) {
                            //获取资源类型的信息
                            $study_type_info = Models_StudyResource_StudyType::init()->getInfoById($type['study_resource_type_id']);
                            $code = ($study_type_info['type_id'] == 100) ? 'evaluate_task' : 'code' . $study_type_info['id'];
                            $str .= '<a  class="a-mulO f-ml10" href="javascript:;" title=""  class="f-ml10 f-mr10" study_value_code = "' . $code . '" name="study_resource_type" study_value=' . $study_type_info['id'] . '>' . $study_type_info['classify_name'] . '</a>';
                        }
                        $v['study_resource'] = $str;
                        //获取第一个学习资源(如果只有测评时间的话就没有跳转)
                        $study_resource = $this->getFirstStudyResourceAction($v['id'], $version, $grade_code);
                        //获取资源类型的信息
                        $study_type_info = Models_StudyResource_StudyType::init()->getInfoById($study_resource[0]['study_resource_type_id']);
                        if ($study_type_info['type_id'] != 100) {//100:测评的类型id
                            $v['url'] = "/" . $this->view->xk . "/Study/learin/" . $study_resource[0]['study_resource_id'];
                        }
                    }
                    $node[$key] = $v;
                }
            }
            $this->view->start = $page_config['start'];
            $this->view->node = $node;
            $this->view->node_count = count($node);
            echo json_encode(array('data' => $this->tpl('Modules/' . $this->view->ucxk . '/Views/Study/studyDetailMoreAjax.htm', NULL, TRUE)));
        } else {
            echo json_encode(array('data' => NULL));
        }
    }

    /**
     * 判断是否有学习资源(电子教材,微视频)
     * @param int $node_id 知识节点id
     */
    public function hasStudyResourceAction($node_id)
    {
        return $this->_resource->getStudyResourceType($node_id, $this->view->xk_code);
    }

    /**
     * 获取第一个学习资源
     * @param int $node_id 知识节点id
     * @param string $bb 版本
     * @param string $grade 年级
     */
    public function getFirstStudyResourceAction($node_id, $bb, $grade)
    {
        //获取年级的主键id
        $grade_info = Models_Resource::init()->getNodeInfoByXdXkBbNj(empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $this->view->xk_code, $bb, $grade);
        return $this->_resource->getFirstOneEvaluate($node_id, $this->view->xk_code, $grade_info['id']);
    }

    /**
     * 学习的历史记录
     */
    public function studyHistoryAction()
    {
        //检测是否有版本
        $this->getStudyResourceVersionAction();
        //获取用户选择的教材版本
        $default_version = $this->getStudyResourceVersionAction();
        //版本列表
        $this->view->bb_list = $this->_jc_bb_list;
        $this->view->bb = $default_version;
        $this->setLayout($this->layout);
        // //年级列表
        // $this->view->grade_list = $this->_config['nj_code'];
        // $this->view->default_grade = $this->_grade_code;
        //js
        $this->view->js = array(
            "xuexi/stu_history.js"
        );
        $this->tpl();
    }

    /**
     * 学习的历史记录加载的数据
     */
    public function studyHistoryMoreAjaxAction()
    {
        $page_config = $this->study_config->pageConfig();
        $default_version = $this->post("bb");
        $grade_code = $this->post('grade');
        $study_history = $this->_study_record->getStudyRecordList($this->user_info['user_id'], $this->view->xk_code, empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $default_version, $grade_code, $page_config['start'], $page_config['limit']);
        if (!empty($study_history)) {
            foreach ($study_history as $key => $v) {
                $info = Models_Resource::init()->getUnit(empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $this->view->xk_code, $default_version, $grade_code);
                if (!empty($info)) {
                    //$study_history[$key]['parent_node_title'] = $this->study_config->getNodeTitleByNodeId($info, $v['study_resource_grade_node']);//这个地方要修改
                    $study_history[$key]['son_node_title'] = $this->study_config->getNodeTitleByNodeId($info, $v['study_grade_node_node']);
                }
            }
            $this->view->history = $study_history;
            echo json_encode(array('limit' => $page_config['limit'], 'every_page' => count($study_history), 'data' => $this->tpl('Modules/' . $this->view->ucxk . '/Views/Study/studyHistoryMoreAjax.htm', NULL, TRUE)));
        } else {
            echo json_encode(array('limit' => $page_config['limit'], 'every_page' => 0, 'data' => NULL));
        }
    }

    /**
     * 通过知识结构的方式获取历史记录
     */
    public function studyHistoryMoreAjaxByNodeAction()
    {
        $page_config = $this->study_config->pageConfig();
        //获取用户选择的教材版本
        $default_version = $this->post("bb");
        $grade = $this->post("grade");
        //知识单元节点
        $info = Models_Resource::init()->getUnit(empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $this->view->xk_code, $default_version, $grade);
        $node_tree = $this->study_config->build_tree($info); //节点树
        $node = $this->study_config->getNodePage($node_tree, $page_config['start'], $page_config['limit']);
        if (!empty($node)) {
            foreach ($node as $key => $value) {
                if (isset($value['child'])) {
                    foreach ($value['child'] as $k => $v) {
                        $info = $this->_study_record->getStudyResourceCount($this->user_info['user_id'], $this->view->xk_code, empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $default_version, $grade, $v['id']);
                        $value['child'][$k]['pic'] = $info['pic'];
                        $value['child'][$k]['descript'] = $info['descript'];
                    }
                    $node[$key] = $value;
                } else {
                    $info = $this->_study_record->getStudyResourceCount($this->user_info['user_id'], $this->view->xk_code, empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $default_version, $grade, $value['id']);
                    $node[$key]['pic'] = $info['pic'];
                    $node[$key]['descript'] = $info['descript'];
                }
            }
            $this->view->start = $page_config['start'];
            $this->view->node = $node;
            echo json_encode(array('data' => $this->tpl("Modules/" . $this->view->ucxk . "/Views/Study/studyHistoryMoreAjaxByNode.htm", NULL, TRUE)));
        } else {
            echo json_encode(array('data' => NULL));
        }
    }

    /**
     * 在线学习
     */
    public function learinAction()
    {
        //资源id
        $resource_id = $this->getVar('resource_id');
        if (empty($resource_id)) {
            $this->messagePage('/' . $this->view->xk . '/Study/studyDetail', '当前资源找不到了');
        }
        //获取资源相关的信息
        $resource_info = $this->_resource->getStudyResourceByStudyResourceId($resource_id);
        //判断当前用户是否有这条学习资源
        //$count = $this->_resource->userIssetResource($resource_id, $this->view->xk_code, $this->user_info['xd'], $resource_info[0]['study_resource_grade']);
        // if (empty($count)) {
        //      $this->messagePage('/' . $this->view->xk . '/Study/studyDetail', '当前资源找不到了');
        // }
        //获取资源
        $source = Models_Resource::init()->getResourceInfoById($resource_id);
        if (!$source) {
            $this->messagePage('/' . $this->view->xk . '/Study/studyDetail', "当前资源找不到了");
        }
        //检测是否有电子教材,微视频和测评
        $study_type = $this->_resource->getStudyResourceType($resource_info[0]['study_grade_node_node'], $this->view->xk_code);
        $configs = $this->study_config->config();
        $html = NULL;
        if (!empty($study_type)) {
            foreach ($study_type as $k => $v) {
                //获取资源类型的信息
                $study_type_info = Models_StudyResource_StudyType::init()->getInfoById($v['study_resource_type_id']);
                $code = ($study_type_info['type_id'] == 100) ? 'evaluate_task' : 'code' . $study_type_info['id'];
                $html .= '<a href="javascript:;" title=""  class="f-ml10 f-mr10" study_value_code = "' . $code . '" name="study_resource_type" study_value=' . $study_type_info['id'] . '>' . $study_type_info['classify_name'] . '</a>|';
            }
            $html = rtrim($html, '|');
        }
        $this->view->study_type = $html;
        //课程标题
        $node_model = new Models_Resource();
        $node_title = $node_model->getUnitTitleById($resource_info[0]['study_grade_node_node']);
        //获取上级的节点
        $node_info = $node_model->getUnitInfoById($resource_info[0]['study_grade_node_node'], 'node_fid');
        $this->view->node_title = $node_title['data']; //知识节点
        if (!empty($node_info['data']['node_fid'])) {
            $unit_title_arr = $node_model->getUnitTitleById($node_info['data']['node_fid']);
            $unit_title = $unit_title_arr['data'];
        } else {
            $unit_title = $node_title['data'];
        }
        $this->view->unit_title = $unit_title;
        //获取知识节点的描述
        $node_descript = Models_StudyResource_NodeDiscripe::init()->getDescriptData($resource_info[0]['study_grade_node_node']);
        $this->view->node_descript = (empty($node_descript)) ? '' : $node_descript[0]['son_node_descript'];
        //获取资源的类型
        $source_type = Models_Resource::init()->getPerviewType($source['cate_id'], $source['doc_ext_name']);
        //计算当前知识节点的上一个和下一个节点
        $this->view->prev_next_node = $this->prevAndNextResourceAction($resource_info[0]['study_resource_version'], $resource_info[0]['study_grade_node_node'], $resource_info[0]['study_resource_grade']);
        //问答
        $this->view->question_list = $this->questionAndAnswerAction($resource_info[0]['study_grade_node_node']);
        //登录的用户写学习记录到记录表里面去
        if (!empty($this->user_info)) {
            $this->loginAddStudyRecordAction($source_type, $resource_info);
            //推荐后请求过来的地址
            $url = DODOJC . "/" . $this->view->xk . "/Study/learin/" . $resource_id;
            $this->view->request_url = DOMAIN_NAME . "/share/index?title=" . urlencode($node_title['data']) . "&url=" . urlencode($url) . "&desc=" . urlencode($node_descript[0]['son_node_descript']) . "&is_obj=1&obj=" . urlencode("教材") . "&site=" . urlencode($this->_site);
        }
        //知识节点id
        $this->view->study_resource_node_node = $resource_info[0]['study_grade_node_node'];
        $this->view->resource_id = $resource_id;
        $this->view->user_id = $this->user_info['user_id'];
        $this->view->source = $source;
        $this->view->perview_type = $source_type;
        $this->view->page_title = '在线学习';
        $this->view->grade = $resource_info[0]['study_resource_grade'];
        $this->view->add_question_url = "/" . $this->view->xk . '/Study/addQuestion';
        $this->view->add_answer_url = "/" . $this->view->xk . '/Study/addAnswer';
        $this->setLayout($this->layout);
        $this->view->hidden_header = true;
        $this->view->hidden_footer = true;
        //js
        $this->view->js = array(
            'xuexi/stu_preview.js',
            'xuexi/stu_online.js',
            'evaluate/eva_list.js'
        );

        //root_js
        $this->view->root_js = array(
            'script/preview.js'
        );
        $this->tpl();
    }

    /**
     * 登录用户添加学习记录
     * @param  string $source_type 学习类型
     * @param  string $resource_info 资源的信息
     * @return
     */
    public function loginAddStudyRecordAction($source_type, $resource_info)
    {
        if ($source_type == 'isVideo') {
            // 获取当前学习资源的历史记录
            $study_record = $this->_study_record->getResourceRecordById($resource_info[0]['resource_id'], $this->user_info['user_id'], $this->view->xk_code);
            $this->view->study_record_status = empty($study_record) ? 0 : $study_record[0]['study_record_status'];
            $this->view->study_record_time = empty($study_record) ? 0 : $study_record[0]['study_record_time'];
            //关联id
            $this->view->id = $resource_info[0]['resource_id'];
        } else {
            //直接往学习记录表里面添加数据
            $this->addStudyRecordAction($resource_info[0]['resource_id'], $source_type);
        }
    }

    /**
     * 上一个节点和下一个节点的处理
     * @param   string $resource_version 教材版本
     * @param   int    $node_id           节点id
     * @param   string  $grade 教材年级
     * @return  array
     */
    public function prevAndNextResourceAction($resource_version, $node_id, $grade)
    {
        $info = Models_Resource::init()->getUnit(empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $this->view->xk_code, $resource_version, $grade);
        $node = $this->study_config->build_tree($info);
        $pre_next_node_id = $this->study_config->getNodeId($node, $node_id);
        $prev_node_title = '';
        $prev_url = '';
        $next_node_title = '';
        $next_url = '';
        //上一个知识节点,获取第一个学习资源
        if (!empty($pre_next_node_id['prev_id'])) {
            $study_resource = $this->getFirstStudyResourceAction($pre_next_node_id['prev_id'], $resource_version, $grade);
            //获取资源类型的信息
            $study_type_info = !empty($study_resource) ? Models_StudyResource_StudyType::init()->getInfoById($study_resource[0]['study_resource_type_id']) : NULL;
            if (!empty($study_type_info) && $study_type_info['type_id'] != 100) {//100:测评类型
                $prev_node_title_info = Models_Resource::init()->getUnitTitleById($pre_next_node_id['prev_id']);
                $prev_node_title = $prev_node_title_info['data'];
                $prev_url = "/" . $this->view->xk . "/Study/learin/" . $study_resource[0]['study_resource_id'];
            }
        }
        //下一个知识节点,获取第一个学习资源
        if (!empty($pre_next_node_id['next_id'])) {
            $study_resource = $this->getFirstStudyResourceAction($pre_next_node_id['next_id'], $resource_version, $grade);
            //获取资源类型的相关信息
            $study_type_info = !empty($study_resource) ? Models_StudyResource_StudyType::init()->getInfoById($study_resource[0]['study_resource_type_id']) : NULL;
            if (!empty($study_type_info) && $study_type_info['type_id'] != 100) {//100:测评类型
                $next_node_title_info = Models_Resource::init()->getUnitTitleById($pre_next_node_id['next_id']);
                $next_node_title = $next_node_title_info['data'];
                $next_url = "/" . $this->view->xk . "/Study/learin/" . $study_resource[0]['study_resource_id'];
            }
        }

        return array(
            'prev_node_title' => $prev_node_title,
            'prev_url' => $prev_url,
            'next_node_title' => $next_node_title,
            'next_url' => $next_url
        );
    }

    /**
     * 问答
     * @param  $node_id 节点id
     * @return array
     */
    public function questionAndAnswerAction($node_id)
    {
        $page_config = $this->study_config->pageConfig(); //分页的配置
        $model_question = new Models_Question_Interfaces();
        $question_arr = $model_question->getQuestion(array('app_obj_id' => (int) $node_id, 'p' => (int) $page_config['p'], 'num' => (int) $page_config['limit'], 'subject' => $this->view->xk_code, 'app_obj_type' => $this->_qustion_type));
        $question_count = $question_arr['count'];
        unset($question_arr['count']);
        return array('count' => $question_count, 'data' => $question_arr, 'num' => $page_config['limit']);
    }

    /**
     * 添加记录
     * @param int $id 主键id
     */
    public function addStudyRecordAction($id, $source_type)
    {
        if ($source_type != 'isDown' && $source_type != 'isVideo') {
            //直接往学习记录表里面写状态
            $data = array(
                'study_record_user_id' => $this->user_info['user_id'],
                'resource_id' => $id,
                'study_record_status' => 2,
                'study_record_time' => 0,
                'study_resource_subject_type' => $this->view->xk_code,
                'time' => time(),
                'study_score' => 0
            );
            $this->_study_record->addStudyRecord($data);
        }
    }

    /**
     * 学习资源的记录ajax
     */
    public function getStudyRecordAction()
    {
        $study_record_status = $this->get('study_record_status');
        $study_record_time = $this->get('study_record_time');
        if ($study_record_time == 'undefined') {
            return false;
        }
        $id = $this->get('id');
        $data = array(
            'study_record_user_id' => $this->user_info['user_id'],
            'resource_id' => $id,
            'study_record_status' => $study_record_status,
            'study_record_time' => $study_record_time,
            'study_resource_subject_type' => $this->view->xk_code,
            'time' => time(),
            'study_score' => 0
        );
        $status = $this->_study_record->addStudyRecord($data, 'isVideo');
        echo json_encode(array('type' => "success", 'status' => $status));
    }

    /**
     * 添加默认的教材版本
     */
    public function addDefaultVersionAjaxAction()
    {
        $default_version = (string) $this->post('default_version');
        if (empty($default_version) || empty($this->user_info['user_id'])) {
            echo json_encode(array('type' => 'error', 'message' => '未选择版本'));
        }
        $data = array(
            'sel_user_id' => $this->user_info['user_id'],
            'sel_study_version' => $default_version,
            'study_type_id' => $this->view->xk_code,
            'time' => time()
        );

        $status = $this->default_version->addDefultVersion($data);
        if ($status) {
            echo json_encode(array('type' => 'success', 'message' => '添加默认版本成功', 'data' => '/' . $this->view->ucxk . '/Study/studyDetail/'));
        } else {
            echo json_encode(array('type' => 'error', 'message' => '设置失败或选择了相同的版本'));
        }
    }

    /**
     * 获取知识节点不同类型的学习资源
     */
    public function getDifferentResourceTypeAction()
    {
        $config = $this->study_config->config();
        $node_id = $this->post('node_id'); //知识节点
        $study_resource_type_id = $this->post('study_resource_type_id'); //资源类型关联id
        //获取资源类型的信息
        $study_type_info = Models_StudyResource_StudyType::init()->getInfoById($study_resource_type_id);
        $info = $this->_resource->getDifferentResourceTypeList($node_id, $this->view->xk_code, $study_resource_type_id);
        $data = array();
        if (!empty($info)) {
            foreach ($info as $key => $v) {
                if ($study_type_info['type'] != 100) {// 100:测评作业,5:微视频
                    $data[$key]['title'] = $v['study_resource_title'];
                    $data[$key]['url'] = '/' . $this->view->xk . '/Study/learin/' . $v['study_resource_id'];
                    $data[$key]['size'] = ($study_type_info['type'] == 5) ? $this->study_config->time($v['study_resource_size']) : 0;
                } else {
                    $data[$key]['title'] = $v['study_resource_title'];
                    $data[$key]['id'] = $v['resource_id'];
                    $data[$key]['evaluate_id'] = $v['study_resource_id'];
                    $data[$key]['type'] = $config['_evaluate_type']['study_evaluate'];
                    $data[$key]['url'] = '/' . $this->view->xk . '/Evaluate/doEvaluate';
                }
            }
        }
        echo json_encode($data);
    }

    /**
     * 获取版本的ajax
     */
    public function getJcVersionAjaxAction()
    {
        //获取学科的版本
        $data = array();
        $bb_list = Models_Resource::init()->getBbByXdAndXk(empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $this->view->xk_code);
        foreach ($bb_list as $key => $v) {
            $data[$v['code']] = $v['name'];
        }
        echo json_encode($data);
    }

    /**
     * 公共的学生的基本信息情况
     */
    public function publicStuInfoAction($param = array())
    {
        //获取学科的版本
        $data = array();
        $bb_list = Models_Resource::init()->getBbByXdAndXk(empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], $this->view->xk_code);
        foreach ($bb_list as $key => $v) {
            $data[$v['code']] = $v['name'];
        }
        $this->view->jc = json_encode($data);
        //学生中心需要可以设置教材版本
        $default_version = $this->getStudyResourceVersionAction('index');
        if (!empty($default_version)) {
            $this->view->default_version_descript = $data[$default_version];
        }
        $this->view->default_version = $default_version;
        //修改版本请求的地址
        $this->view->url = "/" . $this->view->xk . "/Student/ajaxProcessTextbookPublisher";
        $this->view->param = $param;
        $this->view->info = $this->study_config->publicStuInfo($this->user_info, $this->view->xk_code);
        $this->tpl('Modules/' . $this->view->ucxk . '/Views/Study/publicStuInfo.htm');
    }

    /**
     * 公共左侧
     */
    public function leftPublicAction()
    {
        //课程资源推荐
        if (end($this->_c) == 'Study') {
            $this->view->url = '/' . $this->view->xk . '/Study/studyHistory';
            $this->view->title = '学习记录';
        } else {
            $this->view->url = '/' . $this->view->xk . '/Evaluate/evaluateHistory';
            $this->view->title = '测评记录';
        }
        $this->tpl('Modules/' . $this->view->ucxk . '/Views/Study/leftPublic.htm');
    }

    /**
     * 获取版本
     * @return bool or string
     */
    public function getStudyResourceVersionAction($param = NULL)
    {
        $version = $this->default_version->getDefaultVersion($this->user_info['user_id'], $this->view->xk_code);
        if (!empty($param)) {
            return empty($version) ? NULL : $version[0]['sel_study_version'];
        } else {
            if (empty($version)) {
                $this->redirect("/" . $this->view->xk . "/Study/index");
            } else {
                return $version[0]['sel_study_version'];
            }
        }
    }

    /**
     * 添加问题
     */
    public function addQuestionAction()
    {
        if (!isset($this->user_info) || empty($this->user_info)) {
            Cola_Controller::echoJson('login', '请登录');
        } else {
            $node_id = $this->post("node_id");
            $grade = $this->post("grade");
            $question_title = trim($this->post('question_title'));
            $model_question = new Models_Question_Interfaces();
            $baseData = array(
                'Section' => empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], //学段
                'Grade' => $grade, //年级
                'Subject' => $this->view->xk_code, //科目
                'app_obj_id' => $node_id,
            );
            $data = array(
                'question_title' => $question_title,
                'app_obj_type' => $this->_qustion_type,
                'user_id' => $this->user_info['user_id'],
                'num' => 10
            );
            $model_question = new Models_Question_Interfaces();
            $res = $model_question->baseData($baseData)->addQuestion($data);
            if ($res['data']['status'] == '1') {
                Cola_Controller::echoJson('success', '操作成功', array('question_id' => $res['data']['msg'], 'question_title' => $question_title));
            } else {
                Cola_Controller::echoJson('error', $res['data']['msg']);
            }
        }
    }

    /**
     * 添加回答
     */
    public function addAnswerAction()
    {
        if (!isset($this->user_info) || empty($this->user_info)) {
            Cola_Controller::echoJson('login', '请登录');
        } else {
            $question_id = $this->post('question_id');
            $answer_content = $this->post('answer_content');
            $data = array(
                'uid' => $this->user_info['user_id'],
                'answer_content' => $answer_content,
                'question_id' => $question_id
            );
            $model_question = new Models_Question_Interfaces();
            $res = $model_question->addAnswer($data);
            if ($res['data']['status'] == '1') {
                Cola_Controller::echoJson('success', '操作成功', array('answer_content' => $answer_content));
            } else {
                Cola_Controller::echoJson('error', $res['data']['msg']);
            }
        }
    }

    /**
     * 问题列表加载更多
     * @return
     */
    public function getQuestionListAction()
    {
        $node_id = $this->post("node_id");
        $page_config = $this->study_config->pageConfig(); //分页的配置
        $model_question = new Models_Question_Interfaces();
        $question_arr = $model_question->getQuestion(array('app_obj_id' => (int) $node_id, 'p' => (int) $page_config['p'], 'num' => (int) $page_config['limit'], 'subject' => $this->view->xk_code, 'app_obj_type' => $this->_qustion_type));
        unset($question_arr['count']);
        Cola_Controller::echoJson('success', '操作成功', array('answer_list' => $question_arr));
    }

    /**
     * 问题回答加载更多
     * @return
     */
    public function getAnswerListAction()
    {
        $question_id = $this->post('question_id');
        $page_config = $this->study_config->pageConfig(); //分页的配置
        $model_question = new Models_Question_Interfaces();
        $answer_arr = $model_question->getAnswerList(array('qid' => (int) $question_id, 'p' => (int) $page_config['p'], 'num' => (int) 3));
        Cola_Controller::echoJson('success', '操作成功', array('answer_list' => $answer_arr));
    }

    /**
     * 检测是否登录
     */
    public function user_infoAction()
    {
        if (!isset($this->user_info) || empty($this->user_info)) {
            $this->redirect('/' . $this->view->xk . '/Study/studyDetail');
        }
    }

    /**
     * 版本获取年级
     * @return
     */
    public function getNjByBbAction()
    {
        if (!empty($this->user_info)) {
            $xd = substr(empty($this->user_info['xd']) ? $this->_defalut_xd : $this->user_info['xd'], -1);
        } else {
            $xd = substr($this->_defalut_xd, -1);
        }
        $bb = $this->post("bb");
        $info = Models_StudyResource_StudyRecordConfig::init()->getJcNj($xd, $this->view->xk_code, $bb);
        if (empty($info)) {
            echo json_encode(array());
        } else {
            echo json_encode($info);
        }
    }

    /**
     * 未登录公共左侧
     *
     */
    public function uLoginLeftPublicAction()
    {
        //推荐的资源
        $recommend_study_resource = Models_StudyResource_StudyResource::init()->recommendStudyResource($this->view->xk_code, 5);
        $this->view->recommend_study_resource = $recommend_study_resource;
        $study_user = array();
        $DDClient = new Models_DDClient();
        //学习的用户
        $study_resource_user = Models_StudyResource_StudyRecord::init()->getPartUserStudy($this->view->xk_code, 5);
        if (!empty($study_resource_user)) {
            foreach ($study_resource_user as $k => $v) {
                //获取用户所在的学校
                $info = Models_StudyResource_StudyRecordConfig::init()->getCurClassSchoolByUserId(NULL, $v['study_record_user_id']);
                //获取用户的头像
                if (!empty($v['study_record_user_id'])) {
                    $user_info = $DDClient->viewUserInfo($v['study_record_user_id']);
                    $study_user[$k]['user_id'] = $v['study_record_user_id'];
                    $study_user[$k]['school_id'] = empty($info) ? NULL : $info['school_id'];
                    $study_user[$k]['school_name'] = empty($info) ? NULL : $info['school_name'];
                    $study_user[$k]['icon'] = $user_info['icon'];
                    $study_user[$k]['user_name'] = $user_info['real_name'];
                }
            }
        }
        $this->view->study_user = $study_user;
        $this->tpl('Modules/' . $this->view->ucxk . '/Views/Study/uLoginLeftPublic.htm');
    }

}
