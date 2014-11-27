<?php

/**
 * Description of StudyRecordConfig
 *  资源类型配置
 * @author libo
 */
class Models_StudyResource_StudyRecordConfig extends Cola_Model
{

    protected $_video = 5;

    protected $_e_textbook = 9;

    public $circle;

    public function __construct()
    {
        $this->circle or $this->circle = Cola_Com_WebServices::factory(Cola::getconfig('_webServicesCircle'));
    }

    public static function config()
    {
        return array(
            'xd' => array(
                'xd001' => '小学',
                'xd002' => '初中',
                'xd003' => '高中'
            ),
            'xd_code' =>array(
                 1 =>'xd001',
                 2 =>'xd002',
                 3 =>'xd003'
                ),
            'xk' => array(
                'GS0024' => '生命与安全',
                'GS0025' => '心理健康'
            ),
            'xk_code' =>array(
                 'GS0024' => 'xl',
                'GS0025' => 'sm'
                ),
            'bb' => array(
                'v11' => '鄂科版',
                'v01' => '人教版',
                'v02' => '鄂教版',
            ),
            'nj' => array(
                'xd001' => array(
                    'GO003' => '一年级',
                    'GO004' => '二年级',
                    'GO005' => '三年级',
                    'GO006' => '四年级',
                    'GO007' => '五年级',
                    'GO008' => '六年级',
                ),
                'xd002' => array(
                    'GO009' => '初中一年级',
                    'GO0010' => '初中二年级',
                    'GO0011' => '初中三年级',
                ),
                'xd003' => array(
                    'GO0012' => '高中一年级',
                    'GO0013' => '高中二年级',
                    'GO0014' => '高中三年级',
                )
            ),
            'nj_code' =>array(
                '一年级' =>'GO003',
                '二年级' =>'GO004',
                '三年级' =>'GO005',
                '四年级' => 'GO006',
                '五年级' => 'GO007',
                '六年级' =>'GO008',
                '初中一年级' =>'GO009',
                '初中二年级' =>'GO0010',
                '初中三年级' =>'GO0011',
                '高中一年级'=>'GO0012',
                '高中二年级'=>'GO0013',
                '高中三年级'=>'GO0014'
            ),
            // '_resourceType' => array(
            //     1 =>'教案',
            //     2 =>"课件",
            //     3 =>'试卷',
            //     4 =>'素材',
            //     5 => '微视频',
            //     6 =>'公开课',
            //     8=>'备课夹',
            //     7 =>'习题',
            //     9 => '电子教材',
            //     100 =>'测评作业'
            // ),
            '_resourceTypeCode' =>array(
                // 5 =>'video',
                // 9 =>'e_book',
                100 => 'evaluate_task'
                ),
             //测评的种类
             '_evaluate_type' =>array(
                 'study_evaluate' =>1, //学习资源里面的测评
                 'evaluate'=>2  //评测
                ),
             '_evaluate_classify' =>array(
                     'com' => 1, //综合测评
                    'unit' => 2,//单元测评,
                    'interest' =>3
                ),
             '_evaluate_status' =>array(
                     'not_do'=>0,
                     'do' => 1,
                     'finish'=>2,
                     'end' => 3
                ),
             '_apply_type' =>array(
                    1 =>'内部',
                    2 =>'第三方'
                ),
             '_study_big_type' =>array(
                1 =>"学习资源",
                2 =>"测评",
              ),
             '_study_evaluate' =>array(
                100 =>'测评作业'
              )
        );
    }

    /**
     * 根据入学年获取所在的年级
     */
    public function newGradeByNewTime($gradeName)
    {
        $date = date("Y-m", time());
        $arr = explode("-", $date);
        $gradeName = $arr[0] - $gradeName;
        $month = $arr[1];
        if ($month < 10) {
            $month = $month[1];
        }
        if ($gradeName == 0) {
            $gradeName = 1;
        } else if ($gradeName > 0 && $month >= 9) {
            $gradeName+=1;
        }
        return $gradeName;
    }

    /**
     * 年级的封装
     */
    public function getGrade($type, $grade_name)
    {
        $grade = $this->newGradeByNewTime($grade_name);
        if ($type == 1) {
            switch ($grade) {
                case 1: $grades = "一年级";
                    break;
                case 2: $grades = "二年级";
                    break;
                case 3: $grades = "三年级";
                    break;
                case 4: $grades = "四年级";
                    break;
                case 5: $grades = "五年级";
                    break;
                case 6: $grades = "六年级";
                    break;
            }
        } else if ($type == 2) {
            switch ($grade) {
                case 1: $grades = "初中一年级";
                    break;
                case 2: $grades = "初中二年级";
                    break;
                case 3: $grades = "初中三年级";
                    break;
            }
        } else if ($type == 3) {
            switch ($grade) {
                case 1: $grades = "高中一年级";
                    break;
                case 2: $grades = "高中二年级";
                    break;
                case 3: $grades = "高中三年级";
                    break;
            }
        }
        return $grades;
    }
    
    /**
     * 学段的映射
     */
    public function fieldMap($field)
    {
        $arr = array(1 => 'xd001', 2 => 'xd002', 3 => 'xd003');
        return $arr[$field];
    }

    /**
     * 递归计算节点树
     * @param array $node_arr 节点数据
     * @param int $id
     * @param int $start
     * @param int $limit
     * return array
     */
    public function build_tree($node_arr, $id = 0)
    {
        if (empty($node_arr)) {
            return array();
        }
        $childs = array();
        foreach ($node_arr as $k => $v) {
            if ($v['node_fid'] == $id) {
                $childs[] = $v;
            }
        }
        if (empty($childs)) {
            return array();
        }
        foreach ($childs as $k => $v) {
            $rescurTree = $this->build_tree($node_arr, $v['id']);
            if (null != $rescurTree) {
                $childs[$k]['child'] = $rescurTree;
            }
        }
        return $childs;
    }

    /**
     *  节点数的分页处理
     * @param array $node_tree 节点数
     * @param int $start
     * @param int $limit
     */
    public function getNodePage($node_tree, $start, $limit)
    {
        if (empty($node_tree)) {
            return false;
        }
        $tree_arr = array();
        foreach ($node_tree as $k => $tree) {
            if ($k >= $start && $k <= $limit + $start - 1) {
                $tree_arr[$k] = $tree;
            }
        }
        return $tree_arr;
    }

    /**
     * 获取节点对应的名称
     * @param int $node_id 知识节点id
     * @param array 知识结构树
     */
    public function getNodeTitleByNodeId($info, $node_id)
    {
        if (!empty($info)) {
            foreach ($info as $key => $v) {
                if ($node_id == $v['id']) {
                    return $info[$key]['node_title'];
                }
            }
        }
        return NULL;
    }

    /**
     * 分页配置
     */
    public function pageConfig()
    {
        $p = Cola_Request::post('p', 1);
        $limit = 10;
        $start = ($p - 1) * $limit;
        return array('p' => $p, 'start' => $start, 'limit' => $limit);
    }

    /**
     * 获取单元节点的下一个和上一个节点id
     * @param array $info 树状结构的数据
     * @param int  $son_node_id 子节点
     */
    public function getNodeId($info, $son_node_id)
    {
        foreach ($info as $key => $v) {
            if (isset($v['child'])) {
                foreach ($v['child'] as $k => $value) {
                    if ($value['id'] == $son_node_id) {
                        $prev_id = isset($v['child'][$k - 1]['id']) ? $v['child'][$k - 1]['id'] : 0;
                        $next_id = isset($v['child'][$k + 1]['id']) ? $v['child'][$k + 1]['id'] : 0;
                        return array('prev_id' => $prev_id, 'next_id' => $next_id);
                    }
                }
            }else{
                   if($v['id'] == $son_node_id){
                    $prev_id = isset($info[$key - 1]['id']) ? $info[$key - 1]['id'] : 0;
                    $next_id = isset($info[$key + 1]['id']) ? $info[$key + 1]['id'] : 0;
                    return array('prev_id' => $prev_id, 'next_id' => $next_id);
                }
            }
        }
        return array('prev_id' => 0, 'next_id' => 0);
    }

    /**
     * 时间的转换
     * @param int $time 毫秒
     */
    public function time($time)
    {
        if ($time < 60) {
            return "00:" . ($time < 10 ? "0" . $time : $time);
        } else if ($time < 60 * 60) {
            $minute = floor($time / 60);
            return $minute . ":" . (($time - $minute * 60) < 10 ? '0' . ($time - $minute * 60) : ($time - $minute * 60));
        } else {
            $hour = floor($time / 60 * 60);
            $minute = floor(($time - $hour * 60 * 60) / 60);
            $second = $time - $hour * 60 * 60 - $minute * 60;
            return $hour . ":" . ($minute < 10 ? "0" . $minute : $minute) . ":" . ($second < 10 ? "0" . $second : $second);
        }
    }

    /**
     * 日期的转换
     * @param string $date
     */
    public function dateConfig($date)
    {
        switch ($date) {
            case 'week':
                $start_time = strtotime(date("Y-m-d", time())) - 6 * 24 * 3600;
                $end_time = strtotime(date("Y-m-d", time())) + 24 * 3600;
                break;
            case 'month':
                $start_time = strtotime(date("Y-m-d", time())) - 29 * 24 * 3600;
                $end_time = strtotime(date("Y-m-d", time())) + 24 * 3600;
                break;
        }
        return array('start_time' => $start_time, 'end_time' => $end_time);
    }

    /**
     *  日期转成时间戳
     * @param string $date 14/5/2014
     */
    public function dateToTime($date)
    {
        if (empty($date)) {
            return $date;
        }
        $date_arr = explode("/", $date);
        return strtotime($date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1]) + 24 * 3600;
    }

    /*
     * 获取学校班级等信息
     */

    public function getCurClassSchoolByUserId($param = NULL,$user_id = NULL)
    {
        $user_ids = empty($user_id) ? $_SESSION['user']['user_id'] : $user_id;
        $info = Models_Circle::init()->getCurClassSchoolByUserId($user_ids);
        //获取班级列表
        if (!empty($info['class_id'])) {
            $info['grade_code'] = $this->getGrade($info['xd'], $info['grade_id']);
            $client = new Models_DDClient();
            $data = $client->getDataByApi('class/classBaseInfo ', array('class_id' => intval($info['class_id']), 'fields' => 'class_name'), false);
            if ($data['msg'] == 'OK' && !empty($data['data'])) {
                $info['class_name'] = $data['data'][0];
            }
        }  
        return empty($param) ? $info : (isset($info[$param]) ? $info[$param] : NULL);
    }

    /**
     *  公共左侧信息
     * @param array $user_info 登录用户的信息
     * @param type $xk 学科
     */
    public function publicStuInfo($user_info, $xk)
    {
        $key = $this->getCacheKey(__FUNCTION__,array($user_info,$xk));
        $data  = $this->cache->get($key);
        if(!$data){
        $config = $this->config();
        $user_id_info = '';
        $user_id_info = isset($user_info['stu_user_id']) ? $user_info['stu_user_id'] : $user_info['user_id'];
        //学生信息
        $user_id = $user_info['user_id'];
        $user_realname = $user_info['user_realname'];
        //图像
        $image = $user_info['icon'];
        //等级
        $user_level = $user_info['user_level'];
        //学校和班级信息
        $school_class_info = $this->getCurClassSchoolByUserId('',$user_id_info);
        //学习的微视频的统计
        $study_video = Models_StudyResource_StudyRecord::init()->getDiffStudyRecordCount($user_id_info, $xk, $this->_video);
        //学习的电子教材的统计
        $study_e_textbook = Models_StudyResource_StudyRecord::init()->getDiffStudyRecordCount($user_id_info, $xk, $this->_e_textbook);
        //测评的统计
        $evaluate_count = Models_Evaluate_EvaluateRecord ::init()->getEvalauteRecordCount($user_id_info, $xk);
        //课程活动的统计
        $course_count = Models_Course_Interface::init()->couserStatForStudentByCurrentWeek($user_id_info, $config['xk_code'][$xk]);
        //提出问题的统计及回答问题的统计
        $info = new Models_Question_Interfaces();
        $question_count = $info->getQuestionCountByUser($user_id_info, $xk);
        $answer_count = $info->getAnswerCountByUser($user_id_info, $xk);
         $data =  array(
            'user_id' => $user_id,
            'user_realname' => $user_realname,
            'image' => $image,
            'user_level' => $user_level,
            'school_class_info' => $school_class_info,
            'study_video' => $study_video,
            'study_e_textbook' => $study_e_textbook,
            'evaluate_count' => $evaluate_count,
            'question_count' => $question_count,
            'answer_count' => $answer_count,
            'user_id_info' =>$user_id_info,
            'course_count' =>$course_count
        );
        $this->cache->set($key,$data,5*60); 
    }
       return $data;
    }
    

    /**
     * 通过家长id获取孩子id
     * @return 
     */
   public function getChildrenByParentId($user_id)
   {
      
        $child_id = null;
        $key = $this->getCacheKey(__FUNCTION__,array($user_id));
        $child = $this->cache->get($key);
        if(!$child){
           $info = $this->circle->getChildrenIdByParentId($user_id);
             if($info[0]['User_Id']){
                $child_id = end($info[0]['User_Id']);
                $this->cache->set($key,$child_id,3600*3600);
            }
            $child = $child_id;  
        }
        return $child;
   }

  
  public function object2array($obj)
      {
        $arr = is_object($obj) ? get_object_vars($obj) : $obj;
        if (!empty($arr)) {
            foreach ($arr as $key => $val) {
                $val = (is_array($val) || is_object($val)) ? $this->object2array($val) : $val;
                $arr[$key] = $val;
            }
            return $arr;
        } else {
            return NULL;
        }
    }
  

  /**
   * 获取教材的年级
   * @param  int  $id 学段id
   * @param  string $xk_code 学科code
   * @param  $bb 版本
   */
  public function getJcNj($id,$xk_code,$bb)
  {
   $xd_info = Models_Resource::init()->getSubNode(intval($id));
   $xd_key = '';
   $bb_key = '';
   $nj = array();
   if(!empty($xd_info)){
      foreach($xd_info as $v){
        if($v['code'] == $xk_code){
           $xd_key = $v['id'];
        }
      }
   }
    if(!empty($xd_key)){
       $bb_info = Models_Resource::init()->getSubNode(intval($xd_key));
       if(!empty($bb_info)){
        foreach($bb_info as $bb_v){
            if($bb_v['code'] == $bb){
                $bb_key = $bb_v['id'];
            }
         }
       }
    }
    if(!empty($bb_key)){
      $nj = Models_Resource::init()->getSubNode(intval($bb_key));
    }
    return $nj;
  }
 
  /**
   * 
   * 资源列表
   */
  public function getResourceType()
  {
     return  Models_Resource::init()->getResourceType();
  }
  
  /**
   * 用户的年级转成对应教材的年级
   * @param  string $user_nj 学生年级
   * @return string
   */
  public function userNjToJcNj($user_nj)
  {
     //高中学生的年级
     $arr = array('GO0012','GO0013','GO0014');
     if(in_array($user_nj,$arr)){
       return 'GO0027';
     }else{
      return $user_nj;
     }
  }

}
