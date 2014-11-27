<?php

/**
 *
 * 问答基本接口数据类
 * @author zzw
 * @version 2014-04-22
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */
class Models_Question_BaseQuestion extends Models_DDClient
{

    private $_client = null;

    public function __construct()
    {
        $this->_client == null && $this->_client = new Models_DDClient();
        //Models_Oauth::init()->clear();
    }

    /**
     * 添加问题
     * @param type $data
     * @return type
     */
    public function addQuestion($data)
    {
        $url = 'question/addquestion';
        return $this->_client->getDataByApi($url, $data + array('app_id' => DD_APPID), false);
    }

    /**
     * 编辑问题
     */
    public function editQuestion($data)
    {
        $url = 'question/editquestion';
        return $this->_client->getDataByApi($url, $data);
    }

    /**
     * 添加回答
     * @param type $data
     * @return type
     */
    public function addAnswer($data)
    {
        $url = 'question/answerpost';
        return $this->_client->getDataByApi($url, $data + array('isJson' => 0), false);
    }

    /**
     * 取得问题列表
     */
    public function getAllAskList($data)
    {
        $url = 'question/getallasklist';
        $bug = $_GET['bug'];
        $debug = empty($bug) ? false : true;
        return $this->_client->getDataByApi($url, $data + array('isJson' => 0), $debug);
    }

    /**
     * 我回答的问题
     * @param type $data
     * @return type
     */
    public function myAnswer($data)
    {
        $url = 'question/getanswerbyuserid';
        return $this->_client->getDataByApi($url, $data + array('study_subject' => $data['subject']), false);
    }

    /**
     * 问答的回答数据列表
     */
    public function getAnswerList($data)
    {
        $base_data = array(
            'isJson' => 0,
            'study_subject' => $data['subject']
        );
        $data = array_merge($base_data, $data);
        $url = 'question/getanswerList';
        return $this->_client->getDataByApi($url, $data, false);
    }

    /**
     * 发消息
     */
    public function putMessage($array)
    {
        $url = "message/pushmessagebycustom";
        $data = array(
            'head' => json_encode($array['head']),
            'title' => $array['title'],
            'content' => $array['content'],
            'sender_id' => $array['sender_id'],
            'sender_name' => $array['sender_name'],
            'scope' => $array['scope'],
            'area' => "app",
            'send_unit_id' => DD_APPID
        );
        return $data = $this->_client->getDataByApi($url, $data, false);
    }

    /**
     * 问题榜
     * @param type $data
     * @return type
     */
    public function getQuestionTop($data)
    {
        $url = 'question/getquestiontop';
        return $this->_client->getDataByApi($url, $data, false);
    }

    /**
     * 回答榜
     * @param type $data
     * @return type
     */
    public function getAnswerTop($data)
    {
        $url = 'question/getanswertop';
        return $this->_client->getDataByApi($url, $data, false);
    }

    /**
     * 热门标签
     * @param type $data
     * @return type
     */
    public function getHotTag($data)
    {
        $url = 'question/gethottag';
        return $this->_client->getDataByApi($url, $data, false);
    }

    /**
     * 取得问题总数
     * @param type $data
     */
    public function getAllAskCount($data)
    {
        $url = 'question/getallaskcount';
        return $this->_client->getDataByApi($url, $data,false);
    }
	/**
     * 读问答列表信息
     * @param type $data  =array('question_id'=>);
     */
    public function getQuestionListByQuestionIds($data)
    {
        $url = 'question/getquestionlistbyquestionids';
        return $this->_client->getDataByApi($url, $data,false);
    }

    /**
	*读取一条回答信息
	*@param type
	*/
    public function getanswerdetail($data)
    {
        $url = 'mobile/answerdetail';
        return $this->_client->getDataByApi($url, $data,false);
    }
    /**
     * 问答的回答数据列表 [mobile]
     */
    public function getAnswerListMobile($data)
    {
        $url = 'mobile/answerList';
        return $this->_client->getDataByApi($url, $data, false);
    }
}
?>