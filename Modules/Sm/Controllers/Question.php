<?php

/**
 *
 * 问答控制器
 * @author zzw
 * @version 2014-04-22
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */
class Modules_Sm_Controllers_Question extends Modules_Sm_Controllers_Base
{

    //一页显示问题条数
    const NUM = 15;

    private $_mod_question = null;
    private static $_base_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->layout = $this->getCurrentLayout('index.htm');
        $this->_mod_question = new Models_Question_Question();
        self::$_base_data = array(
            'subject' => $this->view->xk_code,
        );
		$this->checkLogin($type = 'get');
    }

    /**
     * 问题中心
     */
    public function indexAction()
    {
        //$this->checkLogin('get');
        //回答榜
        $this->answerList();
        //提问榜
        $this->questionList();
        //热门标签
        $this->hotTag();
        $this->view->js = array('question/common_question.js', 'question/add_question.js');
        $this->setLayout($this->layout);
        $this->view->breadcrumb = Helper_Breadcrumb::getInstance($this->xk)->append('/Question', '问答');
        $this->tpl();
    }

    /**
     * 接收数据
     */
    public function indexAjaxAction()
    {
        //基本参数
        $data = self::$_base_data;
        $num = '15';
        $type = $this->getVar('type');
        in_array($type, array('小学', '初中', '高中')) and $data += array('grade' => $type);
        //关键词
        $key = $this->getVar('key');
        $key and $data += array('q_title' => $key);
        //状态
        $status = $this->getVar('status');
        !in_array($status, array('allAnswer', 'haveAnswer', 'noAnswer')) and $status = 'allAnswer';
        $data+=array('isAnswer' => $status);
        //排序
        $order = $this->getVar('order');
        in_array($order, array('timeOrder', 'hotOrder')) and $data+=array('order' => $order);
        $page = $this->getPage();
        $data+=array(
            'p' => $page,
            'num' => $num,
        );
        $question_data = $this->_mod_question->getAllAskList($data);
        $count = $question_data['count'];
        unset($question_data['count']);
        $res = array('num' => $count, 'data' => $question_data, 'page' => $num);
        Cola_Controller::echoJson('success', 'ok', $res);
    }

    /**
     * 关键词和标签
     */
    public function searchAction()
    {
        //$this->checkLogin($type = 'get');
        //回答榜
        $this->answerList();
        //提问榜
        $this->questionList();
        //热门标签
        $this->hotTag();
        $key = $this->getVar('key');
        $tag = $this->getVar('tag');
        $this->view->key = $key;
        $this->view->tag = $tag;
        $this->view->js = array('question/common_question.js', 'question/tag_question.js');
        $this->setLayout($this->layout);
        $this->view->breadcrumb = Helper_Breadcrumb::getInstance($this->xk)->append('/Question', '问答');
        $this->tpl();
    }

    /**
     * 标签和关键词相关
     */
    public function searchAjaxAction()
    {
        //基本参数
        $data = self::$_base_data;
        $num = self::NUM;
        //取key
        $key = $this->getVar('key');
        $key and $data += array('q_title' => $key);
        //取标签
        $tag = $this->getVar('tag');
        $tag and $data += array('tag' => $tag);
        $page = $this->getPage();
        //其本参数
        $data+=array(
            'p' => $page,
            'num' => $num,
        );
        $question_data = $this->_mod_question->getAllAskList($data);
        $count = $question_data['count'];
        unset($question_data['count']);
        //ajax数据组合
        $res = array(
            'num' => $count,
            'data' => $question_data,
            'page' => $num
        );
        Cola_Controller::echoJson('success', 'ok', $res);
    }

    /**
     * 关键字匹配
     */
    public function suggestAction()
    {
        $num = 9;
        $data = self::$_base_data;
        $key = $this->getVar('key');
        $data+=array(
            'num' => $num,
            'q_key' => $key
        );
        $question_data = $this->_mod_question->getAllAskList($data);
        unset($question_data['count']);
        $res = array();
        foreach ((array) $question_data as $k => $v) {
            $res[] = $v['FAQ_title'];
        }
        echo json_encode($res);
    }

    /**
     * 添加公有的
     */
    public function addPublicQuestionAction()
    {
        $this->checkLogin();
        if (Cola_Request::isPost()) {
            $data = (array) Cola_Request::post('data');
            $help = empty($data['help']) ? 0 : $data['help'];
            //取得基本数据
            $tags = Cola_Request::post('tags');
            $chang_data = $this->_mod_question->changQuestion($data + array('tags' => empty($tags) ? '' : $tags));
            //数据过滤
            $check_data = $this->_mod_question->checkQuestion($chang_data);
            if (!empty($check_data)) {
                Cola_Controller::echoJson($check_data['type'], $check_data['message']);
            }
            $data = array(
                'study_type' => $chang_data['stage'],
                'study_level' => $chang_data['grade'],
                'study_subject' => Models_Subject::$subject_list[$this->view->xk_code],
                'question_title' => $chang_data['question_title'],
                'question_description' => empty($chang_data['question_description']) ? '' : $chang_data['question_description'],
                'tags' => $chang_data['tags'],
                'question_check' => empty($chang_data['question_check']) ? '0' : $chang_data['question_check'],
                'user_id' => $this->user_info['user_id'],
            );
            $res = $this->_mod_question->addBaseQuestion($data);
            if ($res['data']['status'] == '1') {
                //如果有接收对象就发一条消息
                ($help == '1') and $this->send(array('question_title'=>$chang_data['question_title'],'question_id'=>$res['data']['msg']));
                //清除缓存
                $this->clearCache();
                Cola_Controller::echoJson('success', '操作成功');
            } else {
                Cola_Controller::echoJson('error', $res['data']['msg']);
            }
        } else {
            $this->tpl();
        }
    }

    /**
     * 清除缓存
     */
    public function clearCache()
    {
        $post_data = array(
            'subject' => Models_Subject::$subject_list[$this->view->xk_code],
            'isAnswer' => 'allAnswer',
            'order' => 'timeOrder',
            'p' => '1',
            'num' => '15',
        );
        $this->_mod_question->clearCache($post_data);
    }

    /**
     * 发求助消息
     */
    public function send($chang_data)
    {
        //取得当前用户的年级,学段,学科的任课教师
        //Models_Question_Question::teacherOfSomeStudent($user_id = 'q39927970608088180096', $subject = '语文');
        $student_info = Models_Question_Question::teacherOfSomeStudent($this->user_info['user_id'], $this->view->xk_code);
        $targetId = empty($student_info['teacher']) ? '' : $student_info['teacher'];
        $data = array(
            'targetId' => $targetId,
            'title' => "[user={$this->user_info['user_id']}]{$this->user_info['user_realname']}[/user] 向你求助了问题是[question={$chang_data['question_id']}]{$chang_data['question_title']}[/question]",
            'user_id' => $this->user_info['user_id'],
            'user_name' => $this->user_info['user_realname'],
        );
        !empty($targetId) and $this->_mod_question->sendMessage($data);
    }

    /**
     * 取当前学生的学段年级信息
     */
    public function addAjaxAction()
    {
        $user_id = $this->user_info['user_id'];
        $subject = $this->view->xk_code;
        $student_info = Models_Question_Question::teacherOfSomeStudent($user_id, $subject);
        $grade_list = array_flip(Models_Question_Question::$_grade_list);
        $grade = empty($grade_list[$student_info['year']]) ? '' : $grade_list[$student_info['year']];
        $gradeCode = array_flip($this->_mod_question->gradeCode());
        $student_info+=array(
            'xd_code' => $this->user_info['xd'],
            'year_code' => empty($gradeCode[$grade]) ? '' : $gradeCode[$grade],
        );
        echo json_encode($student_info);
    }

    /**
     * 学段取年级
     */
    public function gradeAjaxAction()
    {
        $xd = $this->getVar('xd_code');
        $stage_grade_list = Models_Grade::$stage_grade_list;
        echo json_encode($stage_grade_list[$xd]);
    }

    /**
     * 我的问题我的回答
     */
    public function myAction()
    {
		if (empty($this->user_info)) {
            $this->messagePage("/{$this->xk}/index", '您需要登录才可以查看');
        }
        //回答榜
        $this->answerList();
        //提问榜
        $this->questionList();
        //热门标签
        $this->hotTag();
        $this->view->js = array('question/common_question.js', 'question/my_answer.js');
        $this->view->type = $this->getVar('type');
        $this->setLayout($this->layout);
        $this->view->breadcrumb = Helper_Breadcrumb::getInstance($this->xk)->append('/Question', '问答')->append('/Question/my', '我的问答');
        $this->tpl();
    }

    /**
     * 我的问答相关请求
     */
    public function myAjaxAction()
    {
        $this->checkLogin();
        $page = $this->getPage();
        $type = $this->getVar('type');
        $type = ($type == 'Answer') ? 'Answer' : 'Question';
        $data = self::$_base_data;
        $order = $this->getVar('order');
        $data += array(
            'user_id' => $this->user_info['user_id'],
            'order' => in_array($order, array('timeOrder', 'hotOrder')) ? $order : 'timeOrder'//默认按时间排
        );
        if ($type == 'Answer') {
            $data +=array(
                'page' => $page,
                'count' => self::NUM,
            );
            $answer_data = $this->_mod_question->myAnswer($data);
            foreach ((array) $answer_data['data'] as $k => $v) {
                $answer_data['data'][$k]['in_time'] = date('Y-m-d h:i:s', $v['answer_date']);
                $answer_data['data'][$k]['FAQ_id'] = $v['question_id'];
            }
            $res = array(
                'num' => self::NUM,
                'data' => $answer_data['data']
            );
            $count = $answer_data['count'];
        } else {
            $data +=array(
                'p' => $page,
                'num' => self::NUM,
            );
            $question_data = $this->_mod_question->getAllAskList($data);
            $count = $question_data['count'];
            unset($question_data['count']);
            foreach ((array) $question_data as $k => $v) {
                $question_data[$k]['in_time'] = date('Y-m-d h:i:s', $v['ask_date']);
            }
            $res = array(
                'num' => $count,
                'data' => $question_data,
                'page' => self::NUM,
            );
        }
        Cola_Controller::echoJson('success', 'ok', $res);
    }

    /**
     * 回答榜
     */
    public function answerList()
    {
        $data = self::$_base_data;
        $data+=array(
            'num' => 5
        );
        $answer_data = $this->_mod_question->getAnswerTop($data);
        $this->view->answerList = $this->_mod_question->changeTop($answer_data);
    }

    /**
     * 提问榜
     */
    public function questionList()
    {
        $data = self::$_base_data;
        $data+=array(
            'num' => 5
        );
        $question_data = $this->_mod_question->getQuestionTop($data);
        $this->view->questionList = $this->_mod_question->changeTop($question_data);
    }

    /**
     * 热门标签
     */
    public function hotTag()
    {
        $data = self::$_base_data;
        $data+=array(
            'num' => 3
        );
        $hotTag = $this->_mod_question->getHotTag($data);
        $target_num = array();
        foreach ((array) $hotTag as $k => $v) {
            $target_num[] = $v['target_count'];
        }
        if (empty($target_num)) {
            $sum = 1;
        } else {
            $pos = array_search(max($target_num), $target_num);
            $max = (int) $target_num[$pos];
            $sum = 100 / $max;
        }
        //取得总数
        foreach ($hotTag as $k => $v) {
            $hotTag[$k]['cnum'] = round($v['target_count'] * $sum);
        }
        $this->view->hotTag = $hotTag;
    }

    /**
     * 取得分页
     * @return type
     */
    public function getPage()
    {
        $page = $this->getVar('p');
        $page = empty($page) ? '1' : $page;
        return $page;
    }

    /**
     * 登录检查
     */
    public function checkLogin($type = 'ajax')
    {
        if ($type == 'ajax') {
            empty($this->user_info) and Cola_Controller::echoJson('login', '请登录');
        } else {
            $this->view->user_id = empty($this->user_info['user_id']) ? '' : $this->user_info['user_id'];
        }
    }

}

?>