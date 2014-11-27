<?php
/**
 * 心理健康咨询资讯相关模型
 */
class Modules_Xl_Models_Information extends Models_Information {

    public function __construct() {
        parent::__construct();
        $this->site_id = $this->_config['xl']['site'];
    }
}