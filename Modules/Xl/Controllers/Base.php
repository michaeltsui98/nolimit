<?php

class Modules_Xl_Controllers_Base extends Controllers_Base {
	
	public $layout = '';
	
	public function __construct(){
	    parent::__construct();
		$this->xk = 'xl';
		$this->view->xk = $this->xk;
		$this->view->ucxk = ucfirst($this->xk);
		$this->view->xk_code = 'GS0025';
		 
	}

}
