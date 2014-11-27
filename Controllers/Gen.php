<?php
/**
 * 生成单元的Wap 地址:http://jc.dodoedu.com/sm/Wap_Study/unit/node_id/6935
 * 
 * @author liujie <ljyf5593@gmail.com>
 */
class Controllers_Gen extends Controllers_Base {

    
    public function indexAction(){
         $xd = Models_Resource::init()->getSubNode(0);
         $xk = '';
         $bb = '';
         $nj = '';
         $this->view->xd = $xd;
         $this->setLayout('Layout/index');
         $this->tpl();   
    }
    /**
     * 生成单元的Wap 地址
     */
    public function genUnitWapAction(){
        $xd = $this->getVar('xd');
        $xk = $this->getVar('xk');
        $bb = $this->getVar('bb');
        $nj = $this->getVar('nj');
        
       $unit =   Models_Resource::init()->getUnit($xd, $xk, $bb, $nj);
       $url = array();
       $html = '';
       foreach ($unit as $v){
           if($v['have_child']==1){
               $html .= $v['node_title'].'[wap url] => http://jc.dodoedu.com/sm/Wap_Study/unit/node_id/'.$v['id'];
               $html .= "\r\n";
           }
       }
      echo $html;
       
    }
     
}