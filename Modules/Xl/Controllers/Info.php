<?php
/**
 * 资讯控制器
 */
class Modules_Xl_Controllers_Info extends Modules_Xl_Controllers_Base {

    public $layout = '';

    public function __construct() {
        parent::__construct();

        $this->layout = $this->getCurrentLayout('index.htm');

        $this->breadcrumb = Helper_Breadcrumb::getInstance($this->xk);
        $this->breadcrumb->append('/info', '资讯');
        //$this->breadcrumb->append('javascript:;', '教师');

    }

    public function indexAction() {
        $this->view->page_title = '资讯中心';

        $this->view->breadcrumb =  $this->breadcrumb->render();

        $informationModel = new Modules_Xl_Models_Information();
        $this->view->siteId = $informationModel->getSiteId();
        // 获取资讯小站基础信息
        $siteInfo = $informationModel->cached('getSiteInfo', array(), 3600);

        $this->view->siteInfo = $siteInfo;
        // 获取小站的所有博客组件
        $blogElementList = $informationModel->cached('getBlogElementList', array(), 3600);
        $this->view->blogElementList = $blogElementList;

        // 获取全部资讯
        $page = Cola_Request::param('p');
        $limit = 10;
        $start = max(0, ($page-1)*$limit);

        // 这里之所以拆分为两个方法，因为有id和没有id获取的方式是不一样的
        $eid = $this->get('eid');
        $this->view->eid = $eid;
        if ($eid) {
            if (isset($blogElementList[$eid]['element_name'])) {
                $this->breadcrumb->append('/info/index?eid='.$eid, $blogElementList[$eid]['element_name']);
            }

            $blogList = $informationModel->cached('getBlogListByElement', array($eid, $start, $limit), 3600);
        } else {
            $blogList = $informationModel->cached('getBlogList', array($start, $limit), 3600);
            $blogList['count'] = $blogList['num'];
        }

        $this->view->blogList = $blogList;
        $this->view->page = new Cola_Com_Pager($page, $limit, $blogList['count'], "/{$this->xk}/Info/index/p/%page%?".http_build_query($this->get()));

        $this->view->js = array(
            'info/info_home.js',
        );

        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * 资讯搜索
     */
    public function searchAction() {
        $keyword = $this->get('key');
        if ($keyword) {
           $this->view->breadcrumb =  $this->breadcrumb->append('/info/search', '资讯搜索')->render();
            $informationModel = new Modules_Xl_Models_Information();
            $this->view->siteId = $informationModel->getSiteId();
            // 获取资讯小站基础信息
            $siteInfo = $informationModel->getSiteInfo();
            $this->view->siteInfo = $siteInfo;
            // 获取小站的所有博客组件
            $blogElementList = $informationModel->getBlogElementList();
            $this->view->blogElementList = $blogElementList;

            $page = Cola_Request::param('p');
            $limit = 10;
            $start = max(0, ($page - 1)*$limit);
            $blogList = $informationModel->blogSearch($keyword, $start, $limit);
            $this->view->blogList = $blogList;
            $this->view->page = new Cola_Com_Pager($page, $limit, $blogList['count'], "/{$this->xk}/Info/search/p/%page%?".http_build_query($this->get()));

            $this->view->js = array(
                'info/info_home.js',
            );

            $this->setLayout($this->layout);
            $this->tpl();
        }
    }

    /**
     * ajax获取榜单数据
     */
    public function ajaxTopAction() {
        $informationModel = new Modules_Xl_Models_Information();
        $this->view->siteId = $informationModel->getSiteId();

        $order_maps = array('new', 'hot');
        $order = $this->get('order');
        if (!in_array($order, $order_maps)) {
            $order = 'new';
        }
        $blogList = $informationModel->getBlogList(0, 10, $order);
        $this->view->blogList = $blogList;
        $this->tpl();
    }

    /**
     * 搜索建议
     */
    public function suggestAction() {
        $keyword = $this->get('key');
        if ($keyword) {
            // 功能预留
        }
    }
}