<?php

class Modules_Sm_Controllers_Base extends Controllers_Base {
	
	public $layout = '';
	
	public function __construct(){
		parent::__construct();
		$this->xk = 'sm';
		$this->view->xk = $this->xk;
		$this->view->ucxk = ucfirst($this->xk);
		$this->view->xk_code = 'GS0024';
	}
	 
}

?>