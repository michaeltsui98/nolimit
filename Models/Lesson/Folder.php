<?php

/**
 * 课程夹相关操作类
 * @author liujie <ljyf5593@gmail.com>
 */
class Models_Lesson_Folder extends Cola_Model
{

    protected $_table = "lesson_folder";

    // 备课夹在资源中心索引数据库的类型ID
    const SEARCH_TYPE = 8;

    // 备课夹在资源中心索引数据库中的ID前缀
    const SEARCH_PREFIX = 'folder_';

     // 公开备课夹
    const STATUS_PUBLIC = 1;
     // 不公开
    const STATUS_UNPUBLIC = 0;


    public static $status_list = array(
        self::STATUS_PUBLIC => '公开',
        self::STATUS_UNPUBLIC => '不公开',
    );

    // 原创备课夹
    const ORIGINAL = 1;

    /**
     * 课程夹状态下拉选项
     * @param  string $selected   [description]
     * @param  string $name       [description]
     * @param  array  $attributes [description]
     * @return [type]             [description]
     */
    public static function statusSelect($selected = '', $name = 'status', array $attributes = array()){
    	return Helper_HTML::select($name, self::$status_list, $selected, $attributes);
    }

    /**
     * 获取用户的课程夹列表
     *
     *  // 获取我的全部课程夹
     *  Models_Lesson_Folder::init()->getUserFolderList('l2323', 0, -1)
     *
     *  // 分页获取第三页，每页10个
     *  Models_Lesson_Folder::init()->getUserFolderList('l2323', 20, 10)
     *
     *  // 获取我的小学二年级、生命安全的全部课程夹
     *  Models_Lesson_Folder::init()->getUserFolderList(
     *      'l2323',
     *      0,
     *      -1,
     *      array(
     *          'grade' => Models_Grade::XX2,
     *          'subject' => Models_Subject::SM
     *  ))
     *
     * @param  string $user_id 用户id
     * @param int $start 分页开始项
     * @param int $limit 每页显示的数量
     * @param array $extend_conditions 扩展筛选条件
     * @return array          课程夹列表数组
     */
    public function getUserFolderList($user_id, $start = 0, $limit = 10, array $extend_conditions = array(), $order_by = "`create_time` DESC") {
        $where_sql = $this->buildUserFolderSQL($user_id, $extend_conditions);
        $rows = $this->find(array(
            'where' => $where_sql,
            'start' => $start,
            'limit' => $limit,
            'order' => $order_by,
        ));
        $result = array(
            'total' => $this->count($where_sql),
            'rows' => $rows,
        );

        $result['rows'] = array_map(array($this, 'getNodeInfo'), $result['rows']);
        return $result;
    }

    /**
     * 获取节点信息 (主要用于获取节点的标题用于显示)
     * @param  array $item 节点信息
     * @return array       [description]
     */
    private function getNodeInfo($item) {

        $k = Cola_Model::init()->getCacheKey(__FUNCTION__,func_get_args());
        $data = Cola_Model::init()->cache()->get($k);
        if($data){
            return $data;
        }
        if (isset($item['chapter']) AND $item['chapter']) {
            $item['chapter_info'] = Models_Resource::init()->getUnitTitleById($item['chapter']);
        }

        if (isset($item['node']) AND $item['node']) {
            $item['node_info'] = Models_Resource::init()->getUnitTitleById($item['node']);
        }

        if($item){
            Cola_Model::init()->cache()->set($k,$item,3600*24*30);
        }

        return $item;
    }

    /**
     * 获取用户的备课夹数量
     *
     * // 获取用户 12323 的心理健康学科的备课夹数量
     * Models_Lesson_Folder::init()->getUserFolderCount('l2323', array('subject' => Models_Subject::XL)
     *
     * @param  [type] $user_id           [description]
     * @param  array  $extend_conditions 附加筛选条件
     * @return [type]                    [description]
     */
    public function getUserFolderCount($user_id, array $extend_conditions = array()) {
        $where_sql = $this->buildUserFolderSQL($user_id, $extend_conditions);
        return $this->count($where_sql);
    }

    /**
     * 构造和当前用户相关的备课夹SQL语句条件
     * @param  [type] $user_id           [description]
     * @param  array  $extend_conditions [description]
     * @return [type]                    [description]
     */
    private function buildUserFolderSQL($user_id, array $extend_conditions = array()) {
        $where_sql = "`user_id`='{$user_id}'";
        if ($extend_conditions) {
            foreach ($extend_conditions as $key => $value) {
                $where_sql .= " AND `{$key}`='{$value}'";
            }
        }
        return $where_sql;
    }

    /**
     * 获取公开的课程夹
     * @param int $start 分页开始项
     * @param int $limit 每页显示的数量
     * @param array $extend_conditions 扩展筛选条件
     * @return array          课程夹列表数组                    [description]
     */
    public function getPublicFolderList($start = 0, $limit = 10, array $extend_conditions = array(), $order_by){

        $where_sql = "`status`='".self::STATUS_PUBLIC."'";
        if ($extend_conditions) {
            foreach ($extend_conditions as $key => $value) {
                $where_sql .= " AND `{$key}`='{$value}'";
            }
        }
        $rows = $this->find(array(
            'where' => $where_sql,
            'start' => $start,
            'limit' => $limit,
            'order' => $order_by,
        ));

        if ($rows) {
            $rows = array_map(array($this, 'getUserInfo'), $rows);
        }
        $result = array(
            'total' => $this->count($where_sql),
            'rows' => $rows,
        );



        return $result;
    }

    /**
     * 获取备课夹的所属用户信息
     * @param  array  $data 数据库中的一条基础的备课夹信息
     * @return array  包含了用户信息的备课夹数据
     */
    private function getUserInfo(array $data){
        $cache_key = 'user_info_'.$data['user_id'];
        $user_info = $this->cache->get($cache_key);
        if (!$user_info) {
            $DDClient = new Models_DDClient();
            $user_info = $DDClient->viewUserInfo($data['user_id']);
            $this->cache->set($cache_key, $user_info);
        }
        $data['user'] = $user_info;
        return $data;
    }

    /**
     * 备课夹增加查看次数
     * @param int $folder_id  备课夹ID
     * @param integer $num    增加的次数默认每次增加一次
     */
    public function addViewNum($folder_id, $num = 1){
        return $this->query("UPDATE `lesson_folder` SET `view_num`=`view_num`+{$num} WHERE `{$this->_pk}`='{$folder_id}'");
    }

    /**
     * 获取最新的使用过备课夹的用户列表
     * @param  integer $start [description]
     * @param  integer $limit [description]
     * @return [type]         [description]
     */
    public function getLatestUserList($start = 0, $limit = 10) {
        $sql = "SELECT DISTINCT `user_id` FROM `lesson_folder` ORDER BY `create_time` DESC LIMIT {$start}, {$limit}";

        $result = $this->db->sql($sql);

        $result = array_map(array($this, 'getUserInfo'), $result);
        return $result;
    }

    /**
     * 备课夹复制
     * @param  string $user_id   复制的用户
     * @param  int $folder_id 待复制的备课夹
     * @return int 复制后的备课夹ID
     */
    public function copy($user_id, $folder_id) {
        // 获取待复制的备课夹
        $folder_info = $this->load($folder_id);
        if ($folder_info) {
            unset($folder_info['id']);
            $folder_info['user_id'] = $user_id;
            $folder_info['original'] = intval(!self::ORIGINAL);
            $new_folder_id = $this->insert($folder_info);

            if ($new_folder_id) {
                // 获取原备课夹下的资源并复制一份到新备课夹
                $resource_model = new Models_Lesson_Resource();
                $resource_list = $resource_model->getListByFolder($folder_id);
                if ($resource_list) {
                    foreach ($resource_list as $resource) {
                        unset($resource['id']);
                        $resource['folder_id'] = $new_folder_id;
                        $resource_model->insert($resource);
                    }
                }
                return $new_folder_id;
            }
        }

        return FALSE;
    }

    /**
     * 二维数组布局的方式获取资源并更新到对应的列表
     */
    public function getLayoutResource($folder_id, $row = 3) {
        $default_layout = array(
            0 => '教学目标',
            1 => '教案设计',
            2 => '教学视频',
            3 => '教学总结',
            4 => '学习测评',
        );
        $folder_info = $this->load($folder_id);
        $resource_list = Models_Lesson_Resource::init()->getListByFolder($folder_id);
        $sort_resource = array();
        foreach ($resource_list as $resource) {
            $sort_resource[$resource['sort']] = $resource;
        }

        $layout_resource = array();
        for ($i = 0; $i < $folder_info['resource_layout']; $i++) {
            $line = $i/$row;
            $layout_resource[$line][$i] = '';
            if (isset($default_layout[$i])) {
                $layout_resource[$line][$i] = $default_layout[$i];
            }
            if (isset($sort_resource[$i])) {
                $layout_resource[$line][$i] = $sort_resource[$i];
            }
        }

        // var_dump($layout_resource);
        return $layout_resource;
    }

    public function getPreviewResourceHtml($folder_id, $limit = 5) {
        $resource_list = Models_Lesson_Resource::init()->getListByFolder($folder_id, $limit);
        $html = '';
        if ($resource_list) {
            foreach ($resource_list as $item) {
                if ($item['resource_type'] == '0') { // 自定义文本资源
                    $preview = <<<HTML
                    <span style="position: relative;display: inline-block;width: 124px;height: 120px;overflow: hidden;text-align: center;background-color: #FFF;border: 1px solid #C6C6C6;box-shadow: 0 1px 2px #E4E4E4;">
                        <div style="position:relative;left:-425px;top:-30px;height:120px;width:984px;_zoom:0.12;transform: scale(0.12);-moz-transform: scale(0.12);-webkit-transform: scale(0.12);">{$item['description']}</div>
                    </span>
HTML;
                } else {
                    $preview_url = HTTP_MFS_WENKU.$item['resource_preview'];
                    $preview = <<<HTML
                    <a href="/xl/lesson/detail/id/{$folder_id}/resource/{$item['id']}" title="{$item['title']}" target="_blank" class="pic">
                        <span><img width="120" src="{$preview_url}" alt=""></span>
                    </a>
HTML;
                }

                $icon = Models_Lesson_Resource::$type_ico_maps[$item['resource_type']];
                $html .= <<<HTML
                <div class="courseItem f-fl">
                    {$preview}
                    <p class="name f-fs14">
                        <a href="/xl/lesson/detail/id/{$folder_id}/resource/{$item['id']}" title="{$item['title']}" target="">
                        <i class="{$icon} f-mr5" title="word文档"></i>{$item['title']}
                        </a>
                    </p>
                </div>
HTML;
            }
        }

        return $html;
    }

    /**
     * 重载插入方法，在插入数据的时候需要写全文索引
     * @param  [type] $data  [description]
     * @param  [type] $table [description]
     * @return [type]        [description]
     */
    public function insert($data, $table = null){
    	$insert_id = parent::insert($data, $table);
    	// 如果备课夹类型为公开则添加全文索引
    	if (isset($data['status']) AND $data['status'] == self::STATUS_PUBLIC) {
    		$search_update = $this->filterSearchField($data);
    		if ($search_update) {
    			$search_update['id'] = self::SEARCH_PREFIX.$insert_id;
    			Helper_Search::init()->addIndex($search_update);
    		}
    	}

    	return $insert_id;
    }

    /**
     * 重载数据更新方法，在更新备课夹的时候同时更新全文索引
     * @param  [type] $id   [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function update($id, $data){
    	$folder_status = self::STATUS_UNPUBLIC;
    	/**
    	 * 如果不是更新备课夹的状态，则需要从数据库获取备课夹的状态，以判断是否需要操作索引
    	 */
    	if ( !isset($data['status'])) {
    		$folder_info = $this->load($id);
    		$folder_status = $folder_info['status'];
    	} else {
    		$folder_status = $data['status'];
    	}

    	/**
    	 * 如果设置了将备课夹设置为私有，则删除全文索引
    	 * 这里不用考虑之前没有设置为公开的情况，因为之前没有设置为公开，就不会进入全文索引，
    	 * 没有进入全文索引，调用删除索引的方法也不会出错
    	 */
    	if ($folder_status == self::STATUS_UNPUBLIC) {
    		Helper_Search::init()->delIndex(self::SEARCH_PREFIX.$id);

    	/**
		 * 更新全文索引
		 */
    	} else {
            // 如果要更新全文索引，则需要加载所有的节点信息用于写入
            if (!isset($folder_info)) {
                $folder_info = $this->load($id);
            }
    		$search_update = $this->filterSearchField($folder_info + $data);
    		if ($search_update) {
                // 删除原来的全文索引
                Helper_Search::init()->delIndex(self::SEARCH_PREFIX.$id);
                // 添加新的全文索引
                $search_update['id'] = self::SEARCH_PREFIX.$id;
    			Helper_Search::init()->addIndex($search_update);
    		}
    	}

    	return parent::update($id, $data);
    }

    /**
     * 将当前数据库字段转换为加入全文索引的字段
     * @param  array  $data 待转换的数据库数据
     * @return array        转换后的可以加入全文检索的数据
     */
    private function filterSearchField(array $data){
    	$search_update = array();
    	if(isset($data['title'])){
    		$search_update['title'] = $data['title'];
    	}

    	if(isset($data['description'])){
    		$search_update['summery'] = $data['description'];
    	}

        if (isset($data['stage']) AND isset($data['subject']) AND isset($data['edition']) AND $data['node']) {
            // 写入节点信息  学段，学科，版本，年级,知识节点
            $search_update['node_id'] = "{$data['stage']},{$data['subject']},{$data['edition']},{$data['grade']},{$data['node']}";

            // 写入知识节点名称
            $node_name = Models_Stage::$stage_list[$data['stage']];
            $node_name .= "+".Models_Subject::$subject_list[$data['subject']];
            $node_name .= "+".Models_Edition::$edition_list[$data['edition']];
            $node_name .= "+".Models_Grade::$stage_grade_list[$data['stage']][$data['grade']];
            if ($data['node']) {
                $node_info = Models_Resource::init()->getUnitTitleById($data['node']);
                if ($node_info) {
                    $node_name .= "+".$node_info['data'];
                }
            }
            $search_update['node_name'] = $node_name;
        }

        // 获取用户信息
        $data = $this->getUserInfo($data);
        $search_update['user_id'] = $data['user_id'];
        $search_update['user_name'] = $data['user']['real_name'];
        $search_update['on_time'] = $data['create_time'];

    	$search_update['resource_type'] = self::SEARCH_TYPE;
    	return $search_update;
    }

    /**
     * 重载删除方法，当删除课程夹时需要先删除该课程夹下的课程资源
     * @param  [type] $id  [description]
     * @param  [type] $col [description]
     * @return bool
     */
    public function delete($id, $col = null) {
    	// 删除该课程夹下的所有资源
        $resource_model = new Models_Lesson_Resource();
        $resource_model->delete($id, 'folder_id');

        // 删除全文索引
        Helper_Search::init()->delIndex(self::SEARCH_PREFIX.$id);

        return parent::delete($id, $col);
    }

    /**
     * 判断该备课夹是否可以删除，
     * 因为备课夹当关联到课程活动后，如果改课程活动完成，则不能再编辑和删除该备课夹
     * @return bool
     */
    public function isEdit(array $folder_info) {
        $course_model = new Models_Course_Course();
        return $course_model->lessonFolderCheck($folder_info['subject'], $folder_info['id']);
    }

    /**
     * 检测待添加到数据中的数据的有效性
     * @param  array  $data 待加入的数据
     * @return array  如果检测数据合法则返回空数组，否则返回各个字段的错误信息
     */
    public function checkAddData(array $data) {
        $message = array();

        if (!isset($data['edition']) OR $data['edition'] == '') {
            $message['edition'] = '请选择正确的版本';
        }

        if (!isset($data['grade']) OR $data['grade'] == '') {
            $message['grade'] = '年级不能为空';
        }

        if (!isset($data['chapter']) OR $data['chapter'] == '') {
            $message['chapter'] = '章节不能为空';
        }

        /* if (!isset($data['node']) OR $data['node'] == '') {
            $message['node'] = '课名不能为空';
        } */

        // if (!isset($data['title']) OR $data['title'] == '') {
        //     $message['title'] = '标题不能为空';
        // }

        // if (!isset($data['description']) OR $data['description'] == '') {
        //     $message['description'] = '描述信息不能为空';
        // }

        return $message;
    }
}
