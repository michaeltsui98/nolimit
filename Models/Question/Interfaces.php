<?php

/**
 *
 * 问答提供给外部的接口
 * @author zzw
 * @version 2014-04-22
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */
class Models_Question_Interfaces extends Cola_Model
{

    public static $_question = null;

    /**
     * 接口初始化
     * @return string
     */
    public static function init()
    {
        if (self::$_question === null) {
            self::$_question = new Models_Question_Question();
        }
        return self::$_question;
    }

    /**
     * 取得当前课的问题
     * @param type $data
     */
    public function getQuestion($data)
    {
        //默认取5条
        //!empty($data['p']) and $data+=array('p' => $data['p']);
        return self::init()->getAllAskList($data);
    }

    /**
     * 取得我提出的问题总数
     * @param type $user_id
     */
    public static function getQuestionCountByUser($user_id, $subject)
    {
        $data = array(
            'user_id' => $user_id,
            'num' => 1,
            'subject' => $subject
        );
        $question_data = self::init()->getAllAskCount($data);
        return $question_data['data'];
    }

    /**
     * 取得我回答的问题总数
     * @param type $user_id
     */
    public static function getAnswerCountByUser($user_id, $subject)
    {
        $data = array(
            'user_id' => $user_id,
            'count' => 1,
            'subject' => $subject,
            'page'=>'1',
        );
        $answer_data = self::init()->myAnswer($data);
        return empty($answer_data['count']) ? 0 : $answer_data['count'];
    }

    /**
     * 课程数据源
     * @param type $id
     * @return
     */
    public static function baseData($data)
    {
        self::init()->baseData($data);
        return self::$_question;
    }

    /**
     * 添加问题[根据课程提问]
     * @param type $data
     * @return type
     */
    public function addQuestion($data)
    {
        return self::init()->addQuestion($data);
    }

    /**
     * 添加回答
     * @param type $data
     * @return type
     */
    public function addAnswer($data)
    {
        return self::init()->addAnswer($data);
    }

    /**
     * 回答列表
     * @param type $data $data=array('uid','answer_content','question_id');
     * @return type
     */
    public function getAnswerList($data)
    {
        return self::init()->getAnswerList($data);
    }

    /**
     * 取得随机的几个推荐问题
     * @param string $data $data  =array('type'=>'','subject'=>'')
     * @return type
     */
    public function getRecommend($data)
    {
        $res = array(
            'type' => 'xl_node_id'
        );
        $data+=$res;
        return self::init()->getAllAskList($data);
    }

    /**
     * 取得相关的问题总数
     * @param type $data
     */
    public function getAllAskCount($data)
    {
        return self::init()->getAllAskCount($data);
    }

}
