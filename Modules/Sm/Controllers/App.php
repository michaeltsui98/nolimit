<?php
/**
 * App控制器
 */
class Modules_Sm_Controllers_App extends Modules_Sm_Controllers_Base {

 
    public function indexAction() {
        $this->view->page_title = 'App 下载';
        $this->layout = $this->getCurrentLayout('index.htm');
        $this->view->hidden_header = true;
        $this->view->css = array('download.css');
        $this->setLayout($this->layout);
        $this->tpl();
    }

    
}