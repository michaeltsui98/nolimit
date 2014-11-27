<?php

/**
 * Description of OnlineStudy
 * 推荐的在线学习(所有人都可以看)
 * @author libo
 */
class Modules_Xl_Controllers_OnlineStudy extends Modules_Xl_Controllers_Base
{

    protected $_qustion_type = NULL;//问题的类型
    protected $_answer_type = NULL;//回答问题的类型
    public $layout = '';
    protected $study_config = '';
    protected $_default_bb = 'v11';
    protected $_defalut_xd = 'xd001';
    public function __construct()
    {
        parent::__construct();
        $this->layout = $this->getCurrentLayout('index.htm');
        $this->study_config = Models_StudyResource_StudyRecordConfig::init();
    }
     
     
    /**
     * 学习展示页
     */
    public function indexAction()
    {
        //资源id
        $resource_id = $this->getVar('resource_id');
        if (empty($resource_id)) {
            $this->messagePage('/'.$this->view->xk.'/index', '当前资源找不到了');
        }

        //判断当前用户是否有这条学习资源
        $count = Models_StudyResource_StudyResource::init()->countByResourceId($resource_id);
        
        if (empty($count)) {
            $this->messagePage('/'.$this->view->xk.'/index', '当前资源找不到了');
        }
        //获取资源
        $source = Models_Resource::init()->getResourceInfoById($resource_id);
        if (!$source) {
            $this->messagePage('/'.$this->view->xk.'/index', "当前资源找不到了");
        }
        //获取资源相关的信息
        $resource_info = Models_StudyResource_StudyResource::init()->getStudyResourceByStudyResourceId($resource_id);
        //课程标题
        $node_model = new Models_Resource();
        $node_title = $node_model->getUnitTitleById($resource_info[0]['study_grade_node_node']);
        $this->view->node_title = $node_title['data'];
        //获取知识节点的描述
        $node_descript = Models_StudyResource_NodeDiscripe::init()->getDescriptData($resource_info[0]['study_grade_node_node']);
        $this->view->node_descript = (empty($node_descript)) ? '' : $node_descript[0]['son_node_descript'];
        //获取资源的类型
        $source_type = Models_Resource::init()->getPerviewType($source['cate_id'], $source['doc_ext_name']);

        //问答
        $this->view->question_list = $this->questionAndAnswerAction($resource_info[0]['study_grade_node_node']);
        //知识节点id
        $this->view->study_resource_node_node = $resource_info[0]['study_grade_node_node'];
        $this->view->resource_id = $resource_id;
        $this->view->user_id = isset($_SESSION['user']) ? $_SESSION['user']['user_id'] : NULL;
        $this->view->source = $source;
        $this->view->perview_type = $source_type;
        $this->view->grade = $resource_info[0]['study_resource_grade'];
        $this->view->add_question_url = "/".$this->view->xk .'/Study/addQuestion';
        $this->view->add_answer_url = "/".$this->view->xk .'/Study/addAnswer';
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
     * 问答
     * @param  $node_id 节点id
     * @return array
    */
    public function  questionAndAnswerAction($node_id)
    {
        $page_config = Models_StudyResource_StudyRecordConfig::init()->pageConfig();
        $model_question = new Models_Question_Interfaces();
        $question_arr = $model_question->getQuestion(array('app_obj_id' => (int)$node_id, 'p' =>(int)$page_config['p'], 'num' => (int)$page_config['limit'], 'subject' => $this->view->xk_code, 'app_obj_type' => $this->_qustion_type));
        $question_count = $question_arr['count'];
        unset($question_arr['count']);
        return  array('count' => $question_count, 'data' => $question_arr,'num' =>$page_config['limit']);
   }
   
   
}
