<?php
/**
 * 资讯相关模型
 */
class Models_Information extends Cola_Model {

    protected $_config;

    private $_DDClient = null;

    public function __construct() {
        $this->_DDClient = new Models_DDClient();
        $this->_config = Cola::getConfig('_infomation');
    }

    /**
     * 提取内容中的图片
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    public static function getImage($content) {
        $pic = '';
        $str = "/src\=[\"\'].*([^\>\s]{25,255})\.(jpg|gif|png|jpeg)/Ui";
        if (preg_match($str, $content, $matches)) {
            $pic = substr($matches[1], strrpos($matches[1], '/') + 1) . "." . $matches[2];
            return HTTP_MFS_IMG.$pic;
        }

        return DODOJC.'/Modules/Xl/static/images/news_empty.gif';
    }

    /**
     * 获取小站基础信息
     */
    public function getSiteInfo() {
        $data['site_id'] = $this->site_id;
        return $this->_execute('/site/getsiteinfo', $data);
    }

    /**
     * 获取某一个博客组件下的博客列表
     */
    public function getBlogListByElement($eid, $start = 0, $limit = 10) {
        $data['element_id'] = $eid;
        $data['element_type'] = 'Blog';
        $data['start'] = $start;
        $data['limit'] = $limit;
        return $this->_execute('/site/getelementdatalist', $data);
    }

    /**
     * 获取关联的小站ID
     */
    public function getSiteId() {
        return $this->site_id;
    }

    /**
     * 获取小站内的博客数据列表
     * @param  int    $start 分页开始
     * @param  int    $limit 每页数量
     * @param  string $order 排序
     * @return array        返回博客列表
     */
    public function getBlogList($start = 0, $limit = 10, $order = 'new') {
        $data['site_id'] = $this->site_id;
        $data['start'] = $start;
        $data['limit'] = $limit;
        $data['order'] = $order;
        return $this->_execute('/site/getbloglistbysite', $data);
    }

    /**
     * 获取博客详情
     * @param  [type] $article_id [description]
     * @return [type]             [description]
     */
    public function getBlogDetail($article_id) {
        $data['element_type'] = 'Blog';
        $data['id'] = $article_id;
        return $this->_execute('/site/getelementdatadetail', $data);
    }

    /**
     * 小站内的博客数据搜索
     * @param  string $keyword 待搜索的关键字
     * @param  int $start   分页开始
     * @param  int $limit   每页显示的数量
     * @return array          返回搜索的结果（博客列表）
     */
    public function blogSearch($keyword, $start = 0, $limit = 10) {
        return $this->_execute('/site/sitesearch', array(
            'site_id' => $this->site_id,
            'type' => 'blog',
            'keyword' => $keyword,
            'start' => $start,
            'limit' => $limit,
        ));
    }

    /**
     * 获取小站内的博客组件列表
     * @return array 返回博客组件列表
     */
    public function getBlogElementList() {
        $data['site_id'] = $this->site_id;
        $data['element_type'] = 'Blog';
        $result = $this->_execute('/site/getsiteelementlistbytype', $data);
        if ($result) {
            $blogElementList = array();
            foreach ($result as $item) {
                $blogElementList[$item['element_id']] = $item;
            }
            return $blogElementList;
        }
    }


    /**
     * 最终的执行操作
     * @param string $uri 请求的相对地址
     * @param array $data 请求的参数
     * @param bool $refresh_token 当token过期后是否重新刷新token再取一次
     */
    protected function _execute($uri, $data, $refresh_token = true) {
        $request_url = DOMAIN_NAME . '/DDApi'.$uri;
        $dd = new Models_DDClient;
        $query =  $dd->getDataByApi($uri, $data);
        return $query['data'];
 
    }
}