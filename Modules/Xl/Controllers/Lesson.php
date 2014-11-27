<?php

/**
 * 课程夹相关操作控制器
 * @author liujie <ljyf5593@gmail.com>
 */
class Modules_Xl_Controllers_Lesson extends Modules_Xl_Controllers_Base
{

    const ROLE_STUDENT = 1;
    const ROLE_TEACHER = 2;
    const ROLE_PARENT = 3;

    public $layout = '';
    public $xk_name = '';
    public function __construct() {
        parent::__construct();

        // 只需要角色不需要登陆就能查看的页面
        $role_actions = array('indexAction', 'detailAction','ajaxResourcePreviewAction','ajaxTextPreviewAction', 'ajaxLatestUserListAction');
        // 如果当前用户浏览的不是备课夹首页并且用户没有登录
        if (!in_array($this->a, $role_actions) AND empty($this->user_info)) {
            $this->messagePage("/{$this->xk}/index", '您需要登录才可以进行此操作');
        }
        $xk_base = Cola::getConfig('_xk');
        $this->xk_name =  $xk_base[$this->view->xk_code];

        // 增加一个面包屑，参数一连接地址出去学科，参数二连接地址名称
        $this->breadcrumb = Helper_Breadcrumb::getInstance($this->xk);
        $this->breadcrumb->append('lesson', '备课夹');
        $this->view->breadcrumb = $this->breadcrumb;
        $this->layout = $this->getCurrentLayout('index.htm');
    }

    /**
     * 根据$_GET变量中的参数获取备课夹的筛选项(根据版本和年级进行过滤)
     */
    private function getFolderConditions() {
        $extend_conditions = array();
        // 首先筛选科目，只获取当前科目的
        $extend_conditions['subject'] = $this->view->xk_code;

        // 获取当前用户的版本列表,因为该页面只要选角色就可以登录，所以需要先判断用户是否有学段信息
        if (isset($this->user_info['xd'])) {
            $edition_list = Models_Resource::init()->getBbByXdAndXk($this->user_info['xd'], $this->view->xk_code);
            $this->view->edition_list = $edition_list;

            // 获取当前选择版本信息
            $edition = $this->get('edition');

            if ($edition === NULL) {
                // 获取当前用户的版本信息
                $edition = Models_Teacher_TeacherInterface::init()->getTeacherTextbookPublisher($this->user_info['user_id'], $this->view->xk_code);
            }

            // 如果选择的是全部
            if ($edition === '') {
                return $extend_conditions;
            }

            $this->view->edition = $edition;

            // 如果当前选择的有版本则获取该版本下的年级信息
            if ($edition) {
                $extend_conditions['edition'] = $edition;

                $eidtion_id = Models_Resource::init()->getNodeIdByCode($edition, $edition_list);
                $grade_list = Models_Resource::init()->getSubNode($eidtion_id);
                // var_dump($grade_list);
                $this->view->grade_list = $grade_list;

                $grade = $this->get('grade');
                if ($grade) {
                    $extend_conditions['grade'] = $grade;
                    $this->view->grade = $grade;
                }
            }
        }

        return $extend_conditions;
    }

    /**
     * 获取排序信息
     */
    private function getFolderOrder() {
        // 获取排序参数
        $order = $this->get('order', 'new');
        $order_maps = array(
            'new' => 'create_time DESC',
            'hot' => 'view_num DESC',
        );
        if (isset($order_maps[$order])) {
            $order_by = $order_maps[$order];
        } else {
            $order_by = current($order_maps[$order]);
        }
        $this->view->order = $order;
        return $order_by;
    }

    public function testAction() {
       $res = Helper_Search::init()->indexQuery(' resource_type:8 ', false, null, false, false, 1, 12, true);
       foreach ($res as $item) {
           var_dump($item);
       }
    }

    /**
     * 备课夹首页
     */
    public function indexAction() {
        $this->view->page_title = '备课夹--'.$this->xk_name;

        $this->breadcrumb->append('lesson/index', '公共备课夹');

        $order_by = $this->getFolderOrder();

        $page =$this->getVar('page',1);
        $limit = 10;
        $start = max(0, ($page-1)*$limit);

        $folder_model = Models_Lesson_Folder::init();
        // 获取公开的备课夹
        $extend_conditions = $this->getFolderConditions();

        $extend_conditions['subject'] = $this->view->xk_code;

        $result = $folder_model->getPublicFolderList($start, $limit, $extend_conditions, $order_by);

        $this->view->list = $result['rows'];

        $this->view->page = new Cola_Com_Pager($page, $limit, $result['total'], Cola_Model::init()->getPageUrl());

        $this->view->js = array(
            'lesson/les_classDialog.js',
        );

        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * 我的备课夹（备课记录）
     */
    public function recordAction() {
        $this->view->page_title = '备课记录--'.$this->xk_name;

        $this->breadcrumb->append('/lesson/record', '我的备课夹');

        $folder_model = new Models_Lesson_Folder();

        $order_by = $this->getFolderOrder();

        $page =$this->getVar('page',1);
        $limit = 10;
        $start = max(0, ($page-1)*$limit);
        $extend_conditions = $this->getFolderConditions();

        $result = $folder_model->getUserFolderList($this->user_info['user_id'], $start, $limit, $extend_conditions, $order_by);
        $this->view->list = $result['rows'];
        $this->view->page = new Cola_Com_Pager($page, $limit, $result['total'], Cola_Model::init()->getPageUrl());

        $this->view->js = array(
            'lesson/les_classDialog.js',
            'lesson/les_classRecord.js',
        );

        if (!Cola_Request::isAjax()) {
            $this->setLayout($this->layout);
        }
        $this->tpl();
    }

    /**
     * 我的备课夹（知识单元列表）
     */
    public function chapterAction() {
        $this->view->page_title = '课名--'.$this->xk_name;

        $this->breadcrumb->append('/lesson/record', '我的备课夹');
        $edition_list = Models_Resource::init()->getBbByXdAndXk($this->user_info['xd'], $this->view->xk_code);
        $this->view->edition_list = $edition_list;

        // 获取当前选择版本信息
        $edition = $this->get('edition');
        if (!$edition) {
            // 获取当前用户的默认版本信息
            $edition = Models_Teacher_TeacherInterface::init()->getTeacherTextbookPublisher($this->user_info['user_id'], $this->view->xk_code);
            // 如果当前用户设置默认版本，则获取第一个版本作为默认版本
            if (!$edition) {
                $edition = current($edition_list);
            }
        }
        $this->view->edition = $edition;

        $eidtion_id = Models_Resource::init()->getNodeIdByCode($edition, $edition_list);
        $grade_list = Models_Resource::init()->getSubNode($eidtion_id);

        $this->view->grade_list = $grade_list;

        $grade = $this->get('grade', $grade_list[0]['code']);
        if ($grade) {
            $this->view->grade = $grade;
            $node_list = Models_Node::getTopNodeList($this->user_info['xd'], $this->view->xk_code, $edition, $grade);
            if ($node_list) {
                $folder_model = Models_Lesson_Folder::init();
                foreach ($node_list as &$item) {
                    $folderList =  $folder_model->getUserFolderList($this->user_info['user_id'], 0, 1, array(
                        'chapter' => $item['id'],
                        ));
                    if ($folderList['total'] > 0) {
                        $item['folder'] = current($folderList['rows']);
                    }
                }
            }
            // 心理健康教材只有知识节点信息没有知识单元信息
            $this->view->topNodeList = $node_list;
        }

        $this->view->js = array(
            'lesson/les_classChapter.js',
            'lesson/les_classDialog.js',
        );

        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * ajax 获取某个知识单元下面的知识节点列表
     * @todo 在心理健康课程中，这个方法用不上
     */
    public function nodeAction() {
        // 首先获取单元节点值
        $chapter = Cola_Request::param('chapter');
        if ($chapter) {
            $subNodeList = Models_Resource::init()->getSubUnit($chapter);

            // 获取每个子节点的备课状态
            if ($subNodeList) {
                $folder_model = Models_Lesson_Folder::init();
                foreach ($subNodeList as &$item) {
                    $folderList =  $folder_model->getUserFolderList($this->user_info['user_id'], 0, 1, array(
                        'node' => $item['id'],
                        ));
                    if ($folderList['total'] > 0) {
                        $item['folder'] = current($folderList['rows']);
                    }
                }
            }

            $this->view->subNodeList = $subNodeList;
        }
        $this->tpl();
    }

    /**
     * 课程夹详情
     */
    public function detailAction() {
        $folder_id = intval(Cola_Request::param('id'));
        if ($folder_id) {

            $folder_model = new Models_Lesson_Folder();
            $folder_info = $folder_model->load($folder_id);
            if ($folder_info) {
                $this->breadcrumb->append('/lesson/detail/id/'.$folder_id, $folder_info['title']);
                $this->breadcrumb->append('/lesson/detail/id/'.$folder_id, '详情');
                // 查看次数加一
                $folder_model->addViewNum($folder_id);

                $this->view->folder_info = $folder_info;

                // 获取该课程夹下的资源类别
                $resource_model = new Models_Lesson_Resource();
                $this->view->resource_list = $resource_model->getListByFolder($folder_id);

                // 获取当前正在预览的资源，如果有的话
                $resource_id = intval(Cola_Request::param('resource'));
                $this->view->resource_id = $resource_id;

                $this->view->hidden_header = true;
                $this->view->hidden_footer = true;
                $this->view->css = array(
                    'tec-layout.css',
                );
                $this->view->root_js = array(
                    'script/preview.js',
                );
                $this->view->js = array(
                    'lesson/les_classPreview.js',
                );
                $this->setLayout($this->layout);
                $this->tpl();
            } else {
                $this->messagePage('', '课程夹不存在');
            }
        } else {
            $this->messagePage('', '参数错误');
        }
    }

    /**
     * 新建课程夹
     */
    public function addAction() {
        // 需要登录才可以进行此操作
        if (empty($this->user_info)) {
            $this->messagePage('', '您需要登录才可以进行此操作');
        }

        // 只有老师用户才可以创建备课夹
        if ($this->user_info['role_code'] != self::ROLE_TEACHER) {
            $this->messagePage('', '只有教师用户可以进行此操作');
        }

        if (Cola_Request::isPost()) {
            $folder_model = new Models_Lesson_Folder();
            $post_data = $this->post();
            $message = $folder_model->checkAddData($post_data);
            if ($message) {
                $this->messagePage('', current($message));
            } else {
                $default_title = '';
                // 设置备课夹的默认标题
                $chapter_info = Models_Resource::init()->getUnitTitleById($post_data['chapter']);
                if ($chapter_info) {
                    $default_title = $chapter_info['data'].'【备课】';
                }
                $result = $folder_model->insert(array(
                    'user_id' => $this->user_info['user_id'],
                    'subject' => $this->view->xk_code,
                    'stage' => $this->user_info['xd'],
                    'edition' => $post_data['edition'],
                    'grade' => $post_data['grade'],
                    'chapter' => $post_data['chapter'],
                    'node' => $post_data['node'],
                    'title' => $default_title,
                    // 'description' => $post_data['description'],
                    // 'status' => $post_data['status'],
                    'create_time' => $_SERVER['REQUEST_TIME'],
                ));
                if ($result) {
                    $this->echoJson('success', '添加成功', array('id' => $result));
                }
            }
        } else {
            $this->view->loginUser = $this->user_info;
            $this->tpl();
        }
    }

    /**
     * 备课夹复制
     */
    public function copyFolderAction() {
        $folder_id = intval(Cola_Request::param('id'));
        if ($folder_id) {
            $folder_model = new Models_Lesson_Folder();
            $result = $folder_model->copy($this->user_info['user_id'], $folder_id);
            if ($result) {
                $this->messagePage('', '操作成功');
            } else {
                $this->messagePage('', '操作失败');
            }

        } else {
            $this->messagePage('', '参数错误');
        }
    }

    /**
     * 课程夹编辑
     */
    public function editAction() {
        $folder_id = intval(Cola_Request::param('id'));
        if ($folder_id) {
            $folder_model = new Models_Lesson_Folder();
            $folder_info = $folder_model->load($folder_id);
            if ($folder_info) {
                if ($folder_info['user_id'] == $this->user_info['user_id']) {
                    $this->view->folder_info = $folder_info;
                    if (Cola_Request::isPost()) {
                        $post_data = $this->post();
                        $update = array();
                        if (isset($post_data['folder']['title'])) {
                            if ($post_data['folder']['title']) {
                                $update['title'] = $post_data['folder']['title'];
                                $update['status'] = intval(isset($post_data['folder']['status']));
                            } else {
                                $this->messagePage('', '备课夹标题不能为空');
                                exit();
                            }

                        }

                        if (isset($post_data['resource_layout'])) {
                            $update['resource_layout'] = $post_data['resource_layout'];
                        }

                        if (!empty($update)) {
                            $result = $folder_model->update($folder_id, $update);
                            if ($result) {
                                $this->messagePage('', '操作成功');
                            } else {
                                $this->messagePage('', '没有进行任何操作');
                            }
                        }

                    } else {
                        $this->tpl();
                    }
                } else {
                    $this->messagePage('', '你没有权限进行此操作');
                }
            } else {
                $this->messagePage('', '该课程夹不存在');
            }
        } else {
            $this->messagePage('', '参数错误');
        }
    }

    /**
     * 删除课程夹
     */
    public function deleteAction() {
        $folder_id = intval(Cola_Request::param('id'));
        if ($folder_id) {
            $folder_model = new Models_Lesson_Folder();
            $folder_info = $folder_model->load($folder_id);
            if ($folder_info) {
                if ($folder_info['user_id'] == $this->user_info['user_id']) {
                    $folder_model->delete($folder_id);
                    $this->messagePage('', '操作成功');
                } else {
                    $this->messagePage('', '你没有权限进行此操作');
                }
            } else {
                $this->messagePage('', '该课程夹不存在');
            }
        } else {
            $this->messagePage('', '参数错误');
        }
    }

    /**
     * 向备课夹添加资源
     */
    public function addResourcePostAction() {
        $post_data = $this->post();
        if ($post_data) {
            if (isset($post_data['folder_id']) AND $post_data['folder_id']) {
                $folder_model = new Models_Lesson_Folder();
                $foler_info = $folder_model->load($post_data['folder_id']);
                if ($foler_info) {
                    $resource_model = new Models_Lesson_Resource();
                    // 先删除该位置原来的资源
                    $resource_model->db->delete("`folder_id`='{$post_data['folder_id']}' AND `sort`='{$post_data['sort']}'", 'lesson_resource');

                    if (isset($post_data['type'])) {
                        $resource_type = $post_data['type'];
                    } else {
                        $resource_type = Models_Lesson_Resource::$ext_type_maps[$post_data['ext']];
                    }
                    $result = $resource_model->insert(array(
                        'resource_id' => $post_data['id'],
                        'resource_type' => $resource_type,
                        'resource_preview' => $post_data['preview'],
                        'folder_id' => $post_data['folder_id'],
                        'sort' => $post_data['sort'],
                        'title' => isset($post_data['title'])?$post_data['title']:'',
                        'description' => isset($post_data['description'])?$post_data['description']:'',
                        'create_time' => $_SERVER['REQUEST_TIME'],));

                    if ($result) {
                        $this->echoJson('success', '添加资源成功', array(
                            'id' => $result,
                            'iconStyle' => Models_Lesson_Resource::$type_style_maps[$post_data['type']],
                        ));
                    } else {
                        $this->echoJson('error', '操作失败');
                    }
                } else {
                    $this->echoJson('error', '备课夹不存在');
                }
            } else {
                $this->echoJson('error', '参数错误');
            }
        }
    }

    /**
     * 交换备课夹中两个资源的位置
     */
    public function changeResourceAction() {
        $id1 = $this->post('id1');
        $id2 = $this->post('id2');
        if ($id1 AND $id2) {
            $resource_model = new Models_Lesson_Resource();
            $resource_info_1 = $resource_model->load($id1);
            $resource_info_2 = $resource_model->load($id2);
            if ($resource_info_1 AND $resource_info_2) {
                $resource_model->update($id1, array(
                    'sort' => $resource_info_2['sort'],
                ));
                $resource_model->update($id2, array(
                    'sort' => $resource_info_1['sort'],
                ));

                $this->echoJson('success', '操作成功');
            }

            $this->echoJson('error', '资源不存在');
        }

        $this->echoJson('error', '参数错误');
    }

    /**
     * 批量添加资源到备课夹
     */
    public function batchAddResourceAction() {
        $resources = $this->post('resources');
        $folder_id = $this->post('folder_id');
        if ($folder_id) {
            if (is_array($resources) AND count($resources)) {
                $resource_model = new Models_Lesson_Resource();
                // 获取当前备课夹下的资源用到的最大的排序编号，从这个编号开始继续
                $resource = $resource_model->find(array(
                    'where' => "`folder_id`='{$folder_id}'",
                    'order' => "sort DESC",
                    'limit' => 1,
                ));
                if ($resource) {
                    $resource = current($resource);
                    $max_sort = $resource['sort'];
                } else {
                    $max_sort = 0;
                }

                foreach ($resources as $resource_id) {
                    // 获取资源详细信息
                    $resource_info = Models_Resource::init()->getResourceInfoById($resource_id);
                    if ($resource_info) {
                        // var_dump($resource_info);die();
                        $max_sort++;
                        $result = $resource_model->insert(array(
                            'resource_id' => $resource_info['doc_id'],
                            'resource_type' => Models_Lesson_Resource::$ext_type_maps[$resource_info['doc_ext_name']],
                            'resource_preview' => $resource_info['doc_page_key'],
                            'folder_id' => $folder_id,
                            'sort' => $max_sort,
                            'title' => $resource_info['doc_title'],
                            'description' => $resource_info['doc_summery'],
                            'create_time' => $_SERVER['REQUEST_TIME'],));
                    }
                }

                // 重新更新备课夹的资源信息
                $mask = $max_sort%3;
                if ($mask > 0) {
                    $max_sort += 3-$mask;
                }
                Models_Lesson_Folder::init()->update($folder_id, array(
                    'resource_layout' => $max_sort,
                ));
                $this->echoJson('success', '操作成功');

            } else {
                $this->echoJson('error', '请选择要添加的资源');
            }
        } else {
            $this->echoJson('error', '参数错误');
        }
    }

    /**
     * 为课程添加资源
     */
    public function addResourceAction() {
        $this->view->page_title = '备课夹--'.$this->xk_name;

        // 课程夹ID
        $folder_id = intval(Cola_Request::param('id'));
        if ($folder_id) {
            $folder_model = new Models_Lesson_Folder();
            $folder_info = $folder_model->load($folder_id);
            if ($folder_info) {
                $breadcrumb_title = $folder_info['title']?$folder_info['title']:'编辑备课夹';
                $this->breadcrumb->append('/lesson/detail/id/'.$folder_id, $breadcrumb_title);
                $this->view->folder_info = $folder_info;

                // 获取该课程夹下的已有资源列表
                $resource_model = new Models_Lesson_Resource();
                $resource_list = $resource_model->getListByFolder($folder_id);
                $this->view->resource_list = $resource_list;

                $this->view->layoutResource = $folder_model->getLayoutResource($folder_id);

                // 获取我上传的资源
                $uploadResourceList = $resource_model->getUserUploadList($this->user_info['user_id'], $this->view->xk_code);

                if ($uploadResourceList) {
                    $uploadResourceList = array_map(array($resource_model, 'getResourceUserInfo'), $uploadResourceList);
                    $this->view->uploadResourceList = array_filter($uploadResourceList, 'Models_Lesson_Resource::isOriginResource');
                }

                // 获取我收藏的资源
                $favResourceList = Models_Resource::init()->getMyFav($this->user_info['user_id'], 1, $this->view->xk_code);
                if ($favResourceList) {
                    $this->view->favResourceList = array_map(array($resource_model, 'getResourceUserInfo'), $favResourceList['rows']);
                }

                // 通过备课夹课程知识点儿从资源中心获取部分信息
                $query = " node_id:{$folder_info['stage']} AND node_id:{$folder_info['subject']} AND node_id:{$folder_info['edition']} AND node_id:{$folder_info['grade']} AND node_id:{$folder_info['node']}";
                $resources = Helper_Search::init()->indexQuery($query, true, 'on_time', false, false, 0, 10,true);
                $matchResourceList = array_map(array($resource_model, 'getResourceUserInfo'), $resources['data']);
                $this->view->matchResourceList = array_filter($matchResourceList, 'Models_Lesson_Resource::isOriginResource');

                $this->view->root_js = array(
                    'syup/syup.js',
                    'script/zy_resupload.js'
                );

                // 加载JS信息
                $this->view->js = array(
                    'lesson/les_classReady.js',

                );

                $this->setLayout($this->layout);
                $this->tpl();
            } else {
                $this->messagePage('', '备课夹不存在');
            }
        } else {
            $this->messagePage('', '参数错误');
        }
    }

    /**
     * 获取我上传的资源
     */
    public function myResourceAction() {
        $page = $this->get('page', 1);
        $limit = 10;
        // 获取我上传的资源
        $resource_model = new Models_Lesson_Resource();
        $uploadResourceList = $resource_model->getUserUploadList($this->user_info['user_id'], $this->view->xk_code, $page, $limit);
        if ($uploadResourceList) {
            // 获取资源的用户信息
            $resourceList = array_map(array($resource_model, 'getResourceUserInfo'), $uploadResourceList);

            // 过滤掉备课夹类型的资源，并赋值给视图变量
            $this->view->resourceList = array_filter($resourceList, 'Models_Lesson_Resource::isOriginResource');
            $this->tpl('Modules/Xl/Views/Lesson/resourceList.htm');
        }
    }

    /**
     * [favResourceAction description]
     * @return [type] [description]
     */
    public function favResourceAction() {
        $page = $this->get('page', 1);
        $favResourceList = Models_Resource::init()->getMyFav($this->user_info['user_id'], $page, $this->view->xk_code);
        if ($favResourceList) {
            $resource_model = new Models_Lesson_Resource();
            $this->view->resourceList = array_map(array($resource_model, 'getResourceUserInfo'), $favResourceList['rows']);
            $this->tpl('Modules/Xl/Views/Lesson/resourceList.htm');
        }
    }

    /**
     * 编辑课程夹内的资源
     */
    public function editResourceAction() {
        $resource_id = intval(Cola_Request::param('id'));
        if ($resource_id) {

            // 获取该课程资源
            $resource_model = Models_Lesson_Resource::init();
            $resource_info = $resource_model->load($resource_id);
            if ($resource_info) {
                $folder_id = $resource_info['folder_id'];
                $folder_info = Models_Lesson_Folder::init()->load($folder_id);
                if ($folder_info) {
                    if ($folder_info['user_id'] == $this->user_info['user_id']) {
                        if (Cola_Request::isPost()) {
                            $post_data = $this->post();
                            $update = array();
                            if (isset($post_data['sort'])) {
                                $update['sort'] = $post_data['sort'];
                            }
                            if (isset($post_data['title'])) {
                                $update['title'] = $post_data['title'];
                            }
                            if (isset($post_data['description'])) {
                                $update['description'] = $post_data['description'];
                            }

                            $resource_model->update($resource_id, $update);
                            $this->messagePage('', '操作成功');

                        }
                    } else {
                        $this->messagePage('', '你没有权限进行此操作');
                    }
                } else {
                    $this->messagePage('', '该资源文件没有加入备课夹');
                }
            } else {
                $this->messagePage('', '该资源文件不存在');
            }
        } else {
            $this->messagePage('', '参数错误');
        }
    }

    /**
     * 删除课程夹内的资源
     */
    public function delResourceAction() {
        $resource_id = intval(Cola_Request::param('id'));
        if ($resource_id) {
            $resource_model = new Models_Lesson_Resource();
            $resource_info = $resource_model->load($resource_id);
            if ($resource_info) {
                $folder_id = $resource_info['folder_id'];
                $folder_model = new Models_Lesson_Folder();
                $folder_info = $folder_model->load($folder_id);
                if ($folder_info) {

                    // 判断权限信息
                    if ($folder_info['user_id'] == $this->user_info['user_id']) {
                        $resource_model->delete($resource_id);
                        $this->messagePage('', '操作成功');
                    } else {
                        $this->messagePage('', '你没有权限进行此操作');
                    }
                } else {
                    $this->messagePage('', '该资源文件没有加入备课夹');
                }
            } else {
                $this->messagePage('', '资源不存在');
            }
        } else {
            $this->messagePage('', '参数错误');
        }
    }

    /**
     * ajax获取资源相关属性用于备课夹添加资源时预览
     */
    public function ajaxResourcePreviewAction() {
        $resource_id = intval(Cola_Request::param('id'));
        if ($resource_id) {
            // 获取资源相关信息
            $resource_model = Models_Resource::init();
            try {
                $resource_info = $resource_model->getResourceInfoById($resource_id);
                if ($resource_info) {
                    // 更新所有的使用该资源的预览文件
                    Models_Lesson_Resource::init()->db->update(array(
                        'resource_preview' => $resource_info['doc_page_key'],
                    ), "resource_id='{$resource_id}'", 'lesson_resource');

                    $preview_info = $resource_model->getPerviewType($resource_info['cate_id'], $resource_info['doc_ext_name']);
                    $this->echoJson('success', '', array(
                        'info' => array(
                            'id' => $resource_info['doc_id'],
                            'file_key' => $resource_info['file_key'],
                            'swf_key' => $resource_info['doc_swf_key'],
                            'status' => $resource_info['doc_status'],
                            // 'data' => $resource_info,
                        ),
                        'type' => $preview_info,
                    ));
                } else {
                    $this->echoJson('error', '资源被删除或已不存在');
                }
            } catch (Exception $e) {
                $this->echoJson('error', '获取资源失败');
            }
        } else {
            $this->echoJson('error', '参数错误');
        }
    }

    /**
     * ajax获取纯文本预览
     */
    public function ajaxTextPreviewAction() {
        $resource_id = intval(Cola_Request::param('id'));
        if ($resource_id) {
            // 获取资源相关信息
            $resource_info = Models_Lesson_Resource::init()->load($resource_id);
            if ($resource_info) {
                $this->echoJson('success', '', array(
                    'content' => $resource_info['description'],
                ));
            }
        } else {
            $this->echoJson('error', '参数错误');
        }
    }

    /**
     * 获取最近使用过备课夹功能的用户
     */
    public function ajaxLatestUserListAction() {
        $latestUserList = Models_Lesson_Folder::init()->getLatestUserList();
        $this->view->latestUserList = $latestUserList;
        $this->tpl();
    }

    /**
     * 资源搜索
     */
    public function resourceSearchAction() {
        $stage = isset($this->user_info['xd'])?$this->user_info['xd']:Models_Stage::XX;
        $this->view->stage = $stage;

        $res = Models_Resource::init();
        $edition_list = $res->getBbByXdAndXk($stage, $this->view->xk_code);
        $this->view->edition_list = $edition_list;

        //默认版本
        $default_bb = array_shift($edition_list);
        $default_bb_id= $default_bb['id'];
        $bb_code= $default_bb['code'];

        $bb_code = $this->getVar('bb',$bb_code);
        $this->view->edition = $bb_code;

        //默认年级
        $grade = $res->getSubNode($default_bb_id);
        sort($grade);
        $this->view->grade_list = $grade;
        $default_grade = current($grade);
        $grade_code = $this->getVar('nj',$default_grade['code']);
        $this->view->grade = $grade_code;

        //var_dump($this->view->xd, $this->view->xk, $bb_code, $nj_code);
        //默认知识节点
        $unit = Models_Resource::init()->getUnit($stage, $this->view->xk_code, $bb_code, $grade_code);
        $unit = Models_StudyResource_StudyRecordConfig::init()->build_tree($unit);
        $this->view->unit = $unit;

        //知识节点
        $zs_id = $this->getVar('zs');

        //资源类型
        $this->view->resource_type = array(1=>'教案',2=>"课件",3=>'试卷',4=>'素材',5=>'微视频',6=>'公开课');

        //关键字查询条件
        //$key = $this->getVar('key');
        //$query = " * {$key}";

        $query = " node_id:{$stage} AND  node_id:".$this->view->xk_code." AND node_id:{$bb_code} AND node_id:{$grade_code}";

        if($zs_id){
          $query .= " AND node_id:{$zs_id} ";
        }

        //资源类型
        $type = $this->getVar('type',0);
        $this->view->type = $type;
        if((int)$type){
            $query .= "  AND resource_type:$type ";
        }

        //排序条件，默认按时间倒序
        $order = $this->getVar('order',1);
        $this->view->order = $order;
        if($order==1){
            $order_field = 'on_time';
            $asc = false;
        }elseif($order==2){
            $order_field = 'views';
            $asc = false;
        }

        $page = $this->getVar('page',1);
        $page_size = 10;

        //资源数据
        $resources = Helper_Search::init()->indexQuery($query,true,$order_field,$asc,false,$page,$page_size,true);
        $this->view->resources = $resources['data'];

        $itemView = '';
        switch ($type) {
            case 1:
            case 2:
            case 3:
                $list_content = $this->tpl('Modules/Xl/Views/Resource/docList','',1);
                break;
            case 4:
            case 0:
                $list_content = $this->tpl('Modules/Xl/Views/Resource/fileList','',1);
                break;
            case 5:
            case 6:
                $list_content = $this->tpl('Modules/Xl/Views/Resource/videoList','',1);
                break;
        }
        $this->view->list_content = $list_content;

        $url = Cola_Model::init()->getPageUrl();
        $pager = new Cola_Com_Pager($page, $page_size, $resources['count'], $url);
        $page_html = $pager->html();
        $this->view->page_html = $page_html;

        // 获取基础请求URI
        $this->view->base_uri = $_SERVER['PATH_INFO'];
        //var_dump($resources);

        //$this->view->res = $res;

        $this->view->js = array('ziyuan/zy_list.js','common/common.js','common/dialog.js');

        $this->tpl();
    }
}
