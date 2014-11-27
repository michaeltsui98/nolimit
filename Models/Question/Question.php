<?php

/**
 *
 * 问答方面的接口
 * @author zzw
 * @version 2014-04-22
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */
class Models_Question_Question extends Cola_Model {

    private static $_question = null;
    public static $_study = array();
    public static $_qcache = true; //true已经开启缓存
    public static $_grade_list = array(
        '小学一年级' => '一年级',
        '小学一年级上' => '一年级',
        '小学二年级' => '二年级',
        '小学三年级' => '三年级',
        '小学四年级' => '四年级',
        '小学五年级' => '五年级',
        '小学六年级' => '六年级',
        '初中一年级' => '初一',
        '初中二年级' => '初二',
        '初中三年级' => '初三',
        '高中一年级' => '高一',
        '高中二年级' => '高二',
        '高中三年级' => '高三',
    );

    /**
     * 接口初始化
     * @return type
     */
    public static function init() {
        if (self::$_question === null) {
            self::$_question = new Models_Question_BaseQuestion();
        }
        return self::$_question;
    }

    /**
     * 课程数据源
     * @param type $id
     * @return
     */
    public static function baseData($data) {
        foreach ((array) $data as $k => $v) {
            self::$_study[$k] = $v;
        }
        return new self();
    }

    /**
     * 根据课程取科目
     * @param type $id
     * @return type
     */
    public static function getSubjectByCourse() {
        return Models_Subject::$subject_list[self::$_study['Subject']];
    }

    /**
     * 根据课程取年级
     * @param type $id
     * @return type
     */
    public static function getGradeByCourse() {
        return self::$_grade_list[Models_Grade::$stage_grade_list[self::$_study['Section']][self::$_study['Grade']]];
    }

    /**
     * 根据课程取单元
     * @param type $id
     * @return type
     */
    public static function getUnitByCourse() {
        return self::$_study['Unit'];
    }

    /**
     * 根据课程取版本
     * @param type $id
     * @return type
     */
    public static function getEditionByCourse() {
        return self::$_study['Edition'];
    }

    /**
     * 根据课程取学段
     * @param type $id
     * @return type
     */
    public static function getSectionByCourse() {
        return Models_Stage::$stage_list[self::$_study['Section']];
    }

    /**
     * 添加问题[主要针对外部添加接口]
     * @param type $data
     * @return type
     */
    public function addQuestion($data) {
        //添加附加元素
        $base_data = array(
            'study_level' => self::getGradeByCourse(), //年级
            'study_subject' => self::getSubjectByCourse(), //科目
            'study_type' => self::getSectionByCourse(), //学段
            'app_obj_id' => self::$_study['app_obj_id'],
            'tags' => self::getGradeByCourse() . ',' . self::getSubjectByCourse() . ',' . self::getSectionByCourse(), //年级，学段，课程为标签
            'question_check' => '0', //是否匿名
        );
        $data = array_merge($base_data, $data);
        //清除缓存
        $this->getCacheData($data);
        return $this->addBaseQuestion($data);
    }

    /**
     * 添加问题[公共提问]
     * @param type $data
     * @return type
     */
    public static function addBaseQuestion($data) {
        return self::init()->addQuestion($data);
    }

    /**
     * 添加回答
     * @param type $data
     * @return type
     */
    public static function addAnswer($data) {
        return self::init()->addAnswer($data);
    }

    /**
     * 编辑问题
     * @param type $data
     * @return type
     */
    public static function editQuestion($data) {
        return self::init()->editQuestion($data);
    }

    /**
     * 取得问题列表
     */
    public function getAllAskList($data) {
        return $this->inCache(__FUNCTION__, $data, $time = 60);
    }

    /**
     * 我回答的问题
     * @param type $data
     * @return type
     */
    public function myAnswer($data) {
        return $this->inCache(__FUNCTION__, $data, $time = 60);
    }

    /**
     * 问题榜
     * @param type $data
     */
    public function getQuestionTop($data) {
        return $this->inCache(__FUNCTION__, $data, $time = 60);
    }

    /**
     * 回答榜
     * @param type $data
     */
    public function getAnswerTop($data) {
        return $this->inCache(__FUNCTION__, $data, $time = 60);
    }

    /**
     * 热门标签
     * @param type $data
     */
    public function getHotTag($data) {
        return $this->inCache(__FUNCTION__, $data, $time = 60);
    }

    /**
     * 问答的回答数据列表
     * @param type $data
     * @return type
     */
    public function getAnswerList($data) {
        return $this->inCache(__FUNCTION__, $data, $time = 60);
    }

    /**
     * 取用户信息
     * @param type $uid
     */
    public function viewUserInfo($uid) {
        return $this->inCache(__FUNCTION__, $uid, $time = 120);
    }

    /**
     * 取得问题数
     * @param type $data
     * @return type
     */
    public function getAllAskCount($data) {
        return $this->inCache(__FUNCTION__, $data, $time = 120);
    }

    /*
     * 数据检查
     */

    public function checkQuestion($data) {
        if (empty($data)) {
            return array('type' => 'error', 'message' => '没有提交数据');
        }
        //数据项检查
        if (empty($data['question_title'])) {
            return array('type' => 'error', 'message' => '标题不能为空');
        }
        if (empty($data['tags'])) {
            return array('type' => 'error', 'message' => '你没有输入标签或标签名字太短');
        }
        if (empty($data['stage'])) {
            return array('type' => 'error', 'message' => '学段不能为空');
        }
        if (empty($data['grade'])) {
            return array('type' => 'error', 'message' => '年级不能为空');
        }
    }

    /**
     * 获取基本数据
     */
    public function changQuestion($data) {
        $res = array();
        isset($data['question_title']) and $res['question_title'] = $data['question_title'];
        isset($data['question_description']) and $res['question_description'] = $data['question_description'];
        !empty($data['question_check']) and $res['question_check'] = $data['question_check'];
        !empty($data['tags']) and $res['tags'] = $data['tags'];
        !empty($data['stage']) and $res['stage'] = Models_Stage::$stage_list[$data['stage']]; //学段
        !empty($data['grade']) and $res['grade'] = self::$_grade_list[Models_Grade::$stage_grade_list[$data['stage']][$data['grade']]]; //年级
        return $res;
    }

    /**
     * 发消息
     */
    public function sendMessage($data) {
        $HeaderArray = array('personal');
        foreach ($HeaderArray as $v) {
            $head[] = array('target' => $v, 'targetId' => $data['targetId']);
        }
        $data = array(
            'head' => $head,
            'title' => $data['title'],
            'content' => '问题',
            'sender_id' => $data['user_id'],
            'sender_name' => $data['user_name'],
            'scope' => 'question',
        );
        return self::init()->putMessage($data);
    }

    /**
     * 根学生ID及科目取
     * $user_id = 'q39927970608088180096';
     * $subject = '语文';
     */
    public function teacherOfSomeStudent($user_id, $subject) {
        $key = __CLASS__ . "_teacherOfSomeStudent_{$user_id}_{$subject}";
        $res = $this->cache->get($key);
        if (empty($res) || self::$_qcache == false) {
            //转换
            $model = new Models_Course_Interface();
            //转换科目
            $subject = Models_Subject::$subject_list[$subject];
            $res = $model->teacherOfSomeStudent($user_id, $subject);
            if (empty($res)) {
                return array();
            }
            $teacher = $res['teacher'];
            $data = array(
                'xd' => $res['xd'],
                'year' => $res['nj'],
                'teacher' => empty($teacher) ? '' : current($teacher),
            );
            $this->cache->set($key, $data, '60');
        }
        return $data;
    }

    /**
     * 数据转换
     * @param type $data
     * @return type
     */
    public function changeTop($data) {
        foreach ($data as $k => $v) {
            $user_info = $this->viewUserInfo($v['user_id']);
            $data[$k]+=$user_info;
        }
        return$data;
    }

    /**
     * 年级和代码对应表
     * @return type
     */
    public function gradeCode() {
        $key = __CLASS__ . "_gradeCode";
        $res = $this->cache->get($key);
        if (empty($res) || self::$_qcache == false) {
            $stage_grade_list = Models_Grade::$stage_grade_list;
            foreach ((array) $stage_grade_list as $k => $v) {
                foreach ((array) $v as $k1 => $v1) {
                    $res[$k1] = $v1;
                }
            }
            $this->cache->set($key, $res, '60');
        }
        return $res;
    }

    /**
     * 函数缓存公共方法
     * @param type $key
     * @param type $function
     * @param type $time
     */
    public function inCache($function, $data, $time) {
        if ($function != 'viewUserInfo' and !empty($data['subject'])) {
            $data['subject'] = Models_Subject::$subject_list[$data['subject']];
        }
        //$res=$this->init()->cached($function, array($data),$time);
        $key = __CLASS__ . "_{$function}_" . serialize($data);
        $res = $this->cache->get($key);
        if (empty($res) || self::$_qcache == false) {
            $res = call_user_func_array(array($this->init(), $function), array($data));
            $this->cache->set($key, $res, $time);
        }
        return $res;
    }

    /**
     * 按条件清缓存主要针对提问
     * @param type $data
     */
    public function clearCache($data) {
        $key = __CLASS__ . "_getAllAskList_" . serialize($data);
        $this->cache->delete($key);
    }

    /**
     * 针对接口提问的缓存处理
     * @param type $data
     */
    public function getCacheData($data) {
        $res = array(
            'app_obj_id' => (int) $data['app_obj_id'],
            'p' => (int) '1',
            'num' => (int) $data['num'],
            'subject' => $data['study_subject'],
            'app_obj_type' => $data['app_obj_type'],
        );
        $this->clearCache($res);
    }

    /**
     * 读问答信息
     * @param type $data
     */
    public function getQuestionListByQuestionIds($data) {
        return self::init()->getQuestionListByQuestionIds($data);
    }
	/**
	*读取一条回答信息
	*@param type $data  =array('id'=>);
	*/
    public function getanswerdetail($data)
    {
        return self::init()->getanswerdetail($data);
    }
	/**
     * 问答的回答数据列表 [mobile]
	 *@param type $data  =array('id'=>askId,'start','count','sort'=[1,2,3]);
     */
    public function getAnswerListMobile($data)
    {
        return self::init()->getAnswerListMobile($data);
    }

}

?>