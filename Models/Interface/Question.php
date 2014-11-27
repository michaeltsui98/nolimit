<?php
/**
 * 教材问答接口类
 * @author michaeltsui98@qq.com
 */
class Models_Interface_Question extends Models_Interface_Base {

    public static $xd = array(
    	'xd001'=>'小学',
    	'xd002'=>'初中',
    	'xd003'=>'高中'
    );
    
    /**
     * 取问答列表
     * @param string $xd 学段编码  xd001
     * @param string $xk 学科code GS0024 
     * @param string $order 排序方式     'timeOrder', 'hotOrder' (可空,默认按时间倒序)
     * @param string $q_title 关键字搜索  (可空)
     * @param int $page 
     * @param int $limit 
     * @return array 
     * @example http://dev-jc.dodoedu.com/interface/index/app_key/b8c8320d9c6d35c0b7dc412a44549bbb/c/Models_Interface_Question/m/getQuestionList?xd=xd001&xk=GS0024&order=&q_title=&page=1&limit=15
     */
    public function getQuestionList($xd,$xk,$order,$q_title,$page,$limit){
 
        $data = array('subject'=>$xk);
        if(isset(self::$xd[$xd])){
            $data['grade'] = self::$xd[$xd];
        }
        if($q_title){
            $data['q_title'] = $q_title;
        }
        $data['isAnswer'] = 'allAnswer';
        $data['order'] = $order?$order:'timeOrder';
        $data['p'] = $page;
        $data['num'] = $limit;
        $data['get_tag'] = 1;
        $model =  new Models_Question_Question();
        $rows =   $model->getAllAskList($data);
        $count =  $rows['count'];
        unset($rows['count']);
        //var_dump($rows);die;
        $tmp = array();
          foreach ((array) $rows as $key => $value) {
            $tmp[] = array(
                    'id' => $value['id'],
                    'user_id' => $value['user_id'],
                    'user_name' => $value['user_name'],
                    'user_avatar' => $value['user_image'],
                    'title' => $value['FAQ_title'],
                    'content' => $value['FAQ_explain'],
                    'time' => $value['FAQ_time'],
                    'answers_count' =>strip_tags($value['FAQ_answersCount'],'<img>'),
                    'tag' => is_array($value['tag'])?array_values($value['tag']):''
            );
        }  
        //var_dump($rows);die;
        $d = array('rows'=>$tmp,'count'=>$count);
        //var_dump($d);die;
        return $d;
    	
    }
      /**
       * 取问题的回答列表 
       * @param int $id  问题 的ID
       * @param int $start 开始的条数
       * @param int $count  总数
       * @param int $sort  排序
       * @return Ambigous <mixed, array>
       */
    public function getAnswerList($qid,$p,$num){
        $model =  new Models_Question_Question();
         $data=array(
                'qid'=>$qid,
                'p'=>$p,
                'num'=>$num
        );
        $data = $model->getAnswerList($data);
        //var_dump($data);die;
        $count = $data['count'];
        unset($data['count']);
        foreach ($data as $k=>$v){
            $data[$k]['answer_content'] = strip_tags($v['answer_content']);
        }
       // var_dump($data);die;
        return array('rows'=>$data,'count'=>$count);
    } 
    /**
     * 取Question 详细
     * @param int $id
     * @return array 
     */
    public function getQuestionInfo($id){
        $model =  new Models_Question_Question();
        //return  $model->get($data);
        $data  = array('question_id'=>$id);
        $res =   $model->getQuestionListByQuestionIds($data);
        if(isset($res['data'])){
        	return $res['data'];
        }else{
        	return false;
        }
    }
    /**
     * 添加提问
     * @param string $user_id 用户ID
     * @param string $xd  学科Code xd001
     * @param string $xk  学科CODE  GS0024
     * @param string $nj  年级CODE  GO003
     * @param string $title  提问标题
     * @param string $desc  提问内容
     * @param string $tags  标签
     * @param int $is_anymor  1为匿名提问,0为非匿名提交 
     * @param int $is_help  1发信息给老师求助，2不发
     * @return multitype:unknown Ambigous <string> |Ambigous <type, mixed, array>
     */        
    public function addQuestion($user_id,$xd,$xk,$nj,$title,$desc,$tags,$is_anymor=0,$is_help=0){
        $model =  new Models_Question_Question();
        $grade_list = $model::$_grade_list;
        $nj_list = Cola::getConfig('_nj');
        $nj_name = $grade_list[$nj_list[$nj]];
        $data = array(
                'study_type' => self::$xd[$xd],
                'study_level' => $nj_name,
                'study_subject' => Models_Subject::$subject_list[$xk],
                'question_title' => $title,
                'question_description' => $desc,
                'tags' => (string)$tags,
                'question_check' => $is_anymor,
                'user_id' => $user_id,
                'help'=>(int)$is_help
               
        );
        
      //  return $data;
        
        //数据过滤
       /*  $check_data = $model->checkQuestion($data);
        if (!empty($check_data)) {
            return array('error'=>$check_data['type'],'message'=>$check_data['message']);
        } */
      
        return $model->addBaseQuestion($data);
    }
    /**
     * 添加回答
     * @param int $question_id  问答ID
     * @param string $user_id  用户ID
     * @param string $answer_content 回答内容
     * @return Ambigous <type, mixed, array>
     */
    public function addAnswer($question_id,$user_id,$answer_content){
        $data = array();
        $data['uid']= $user_id;
        
        $result = (array)Models_Attachment::init()->uploadToDodo('question', $question_id);
        $imgs = "";
        foreach ($result as $key => $value) {
            $imgs .= '<img src="' . $value['path'] . '" />';
        }
        
        $data['answer_content']= $answer_content.$imgs;
        $data['question_id']= $question_id;
       
        //return $data;
        $model =  new Models_Question_Question();
        $res = $model->addAnswer($data);
        return $res;
    }
    /**
     * 请回答详细
     * @param string   $id answer_id
     * @return Ambigous <mixed, array>
     */
    public function getAnswerDetail($id) {
        $data = array();
        $data['id'] = $id;
        /* $model =  new Models_Question_Question();
        return $model->getanswerdetail($data); */
        
        $url = 'question/answerdetail';
        return Models_DDClient::init()->getDataByApi($url, $data);
    }
    /**
     * 根据输入生成标签
     * @param string $keyword  输入内容
     * @return Ambigous <mixed, array>
     */
    public function getTagSuggest($keyword){
        $api = 'tag/getSuggest';
        $data = array();
        $data['keyword'] = $keyword;
        $model =  new Models_Question_BaseQuestion();
        return $model->getDataByApi($api, $data);
    }
    /**
     * 取内容的推荐标签分词
     * @param string $content 文档内容
     * @param int $limit  取几个词
     * @return Ambigous <mixed, array>
     */
    public function getTokenize($content,$limit){
        $api = 'tag/getTokenize';
        $data = array();
        $data['content'] = $content;
        $data['limit'] = $limit;
        $model =  new Models_Question_BaseQuestion();
        return $model->getDataByApi($api, $data);
    }
    
    
}