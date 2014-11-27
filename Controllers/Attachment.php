<?php

/**
 * 公用附件上传以及浏览入口，（主要用于js调用）
 * @author liujie <ljyf5593@gmail.com>
 */
class Controllers_Attachment extends Controllers_Base
{

    public function __construct() {
        parent::__construct();
        $login_actions = array('flashAction', 'uploadAction', 'deleteAction', 'listAction');
        if (empty($this->user_info) AND in_array($this->a, $login_actions)) {
            if (Cola_Request::isAjax()) {
                echo json_encode(array('type' => 'login', 'data' => array(), 'message' => '您需要登录才可以进行此操作',));
            } else {
                $this->messagePage('/Sign/index/xk=' . $this->xk, '您需要登录才可以进行此操作');
            }
        }
    }

    public function indexAction() {
        $this->tpl();
    }

    /**
     * 文件上传
     */
    public function uploadAction() {
        if (isset($_FILES['file'])) {

            /**
             * 该文件需要保存的文件夹
             * 建议按业务分文件夹，如果这个值为空，则保存到根目录
             * @var string
             */
            $dir = $this->getVar('dir');
            $fileinfo = Models_Attachment::init()->upload($_FILES['file'], $this->user_info['user_id'], $dir);
            echo json_encode($fileinfo);
        }
    }

    /**
     * flash上传的demo
     * @return [type] [description]
     */
    public function flashAction(){
        $this->tpl();
    }

    public function flashUploadAction() {
        $json = array(
            'state' => 'error',
            'message' => '操作失败',
            'data' => array(),
        );
        // original: "2673"

        // 文件夹为空
        $dir = '';

        // 普通上传
        if (count($_FILES) > 0){
            foreach($_FILES as $value){
                $fileinfo = Models_Attachment::init()->upload($value, $this->user_info['user_id'], $dir);

                if (isset($fileinfo['file_path'])) {
                    $json['state'] = 'SUCCESS';
                    $json['title'] = $fileinfo['file_name'];
                    $json['file_path'] = $fileinfo['file_path'];
                } else {
                    $json['state'] = 'ERROR';
                    $json['message'] = '操作失败';
                }
            }
        }

        // 流文件上传(对应demo3示例的方法)
        if(isset($_SERVER['HTTP_FILENAME']))
        {
            $filename = $_SERVER['HTTP_FILENAME'];
            $filename = urldecode($filename);
            $file = file_get_contents('php://input');
            $fileinfo = Models_Attachment::init()->streamUpload($filename, $file, $this->user_info['user_id']);
            if ($fileinfo) {
                $json['state'] = 'SUCCESS';
                $json['title'] = $fileinfo['file_name'];
                $json['file_path'] = $fileinfo['file_path'];
            }
        }

        if (isset($json['file_path'])) {
            $json['url'] = Models_Attachment::init()->getBaseUrl().$json['file_path'];
        }

        echo json_encode($json);
    }

    /**
     * 文件下载
     */
    public function downloadAction() {
        $file_path = $this->get('file_path');
        $file_name = $this->get('file_name');
        Models_Attachment::init()->download($file_path, $file_name);
    }

    /**
     * 文件删除
     */
    public function deleteAction() {
        $id = Cola_Request::param('id');
        $file_model = Models_Attachment::init();
        $file_info = $file_model->load($id);
        if ($file_info) {
            if ($file_info['user_id'] == $this->user_info['user_id']) {
                Models_Attachment::init()->delete($id);
            } else {
                $this->messagePage('', '你没有权限进行此操作');
            }
        } else {
            $this->messagePage('', '文件不存在');
        }
    }

    /**
     * 根据文件路径删除文件
     */
    public function deleteByPathAction() {
        $path = $this->get('path');
        $file_model = Models_Attachment::init();
        Models_Attachment::init()->deleteByPath($path);
    }

    /**
     * 图片文件预览
     */
    public function viewAction() {
        $file_path = $this->get('file_path');
        $base_url = Models_Attachment::init()->getBaseUrl();
        header('location:' . $base_url . $file_path);
    }

    /**
     * 当前用户上传的附件列表
     */
    public function listAction() {
        $limit = 10;
        $page = Cola_Request::param('p');
        $start = max(($page - 1)*$limit, 0);

        $extend_conditions = array();
        $list = Models_Attachment::init()->getUserFileList($this->user_info['user_id'], $start, $limit, $extend_conditions);
        $json = array('type' => 'success', 'message' => '',);
        $base_url = Models_Attachment::init()->getBaseUrl();
        foreach ($list['rows'] as &$item) {
            $item['url'] = $base_url.$item['file_path'];
        }
        $json['data'] = $list;
        $this->renderJsonpData($json);
    }
}
