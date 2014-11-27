<?php
/**
 * Wap生命的资源控制器
 */
class Modules_Xl_Controllers_Wap_Resource extends Modules_Xl_Controllers_Base {

    
    public function viewAction() {
       $id = $this->getVar('id');
        
       $res = Models_Resource::init()->getResourceInfoById($id);
       $this->view->res = $res;
      // var_dump($res);
       $this->setLayout($this->getCurrentLayout('wap_index'));
       $this->view->css = array('css/detail.css');
            
       if(isset($res['doc_ext_name']) and $res['doc_ext_name']=='mp4'){
        	$file_path = HTTP_MFS_RESOURCE. $res['file_key'];
            $this->view->file_path = $file_path;
            $this->view->js = array('script/zepto.js');
            $this->tpl('Modules/'.$this->view->ucxk.'/Views/Wap/Resource/mp4.htm');
        	die;
       }elseif(isset($res['doc_pdf_key']) and $res['doc_pdf_key']){
            $file_path = HTTP_MFS_WENKU. $res['doc_pdf_key'];
            $this->view->file_path = $file_path;
            header('Access-Control-Allow-Headers: Range');
            header('Access-Control-Expose-Headers: Accept-Ranges, Content-Encoding, Content-Length');
            $this->view->js = array('script/pdf.js','script/detail.js');
            
            $this->tpl('Modules/'.$this->view->ucxk.'/Views/Wap/Resource/pdf.htm');
            die;
       }
       
       $this->tpl('Modules/'.$this->view->ucxk.'/Views/Wap/Resource/none.htm');
    }
 
    
}