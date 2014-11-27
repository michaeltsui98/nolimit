<?php
/**
 * Wap生命的资讯控制器
 */
class Modules_Sm_Controllers_Wap_Info extends Modules_Sm_Controllers_Base {

    /**
     * 资讯列表页
     */
    public function listAction() {
      $informationModel = new Modules_Sm_Models_Information();
      $blogElementList = $informationModel->cached('getBlogElementList',array());

      // 获取全部资讯
      $page = Cola_Request::param('p');
      $limit = 10;
      $start = max(0, ($page-1)*$limit);
      $blogList = $informationModel->cached('getBlogList', array($start, $limit), 3600);
      $blogList['count'] = $blogList['num'];

      $this->view->blogElementList = $blogElementList;
      $this->view->blogList = $blogList;

      if (Cola_Request::isAjax()) {
        $this->tpl('Modules/Sm/Views/Wap/Info/Ajax/list.htm');
        exit();
      } else {
        $this->setLayout($this->getCurrentLayout('wap_index'));
        $this->view->css = array('css/info_list.css', 'css/refresh.css');
        $this->view->js = array('/script/zepto.js', '/script/dqm.js', '/script/iscroll.js', '/script/dqm.refresh.js', '/script/$iscroll.js', '/script/info/info_list.js');
        $this->tpl();
      }
    }

    /**
     * 资讯详情页
     */
    public function DetailAction() {
      $id = Cola_Request::param('id');

      $informationModel = new Modules_Sm_Models_Information();
      $blogDetail = $informationModel->getBlogDetail($id);
      if ($blogDetail) {
        $this->view->blogDetail = $blogDetail;
        // 获取用户详情
        $DDClientModels = new Models_DDClient();
        $userInfo = $DDClientModels->searchUserInfo($blogDetail['user_id']);
        $this->view->userInfo = $userInfo;
        $this->setLayout($this->getCurrentLayout('wap_index'));
        $this->view->css = array('css/detail.css');
        $this->tpl();
      }
    }
}
