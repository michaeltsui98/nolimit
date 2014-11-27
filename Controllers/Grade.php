<?php
class Controllers_Grade extends Cola_Controller {
	/**
	 * 通过学段获取json格式的年级列表
	 */
	public function getGradeJsonAction(){
		$stage = $this->get('stage');
		$json = array(
			'type' => 'success',
			'message' => '',
			'data' => array(),
		);
		$json['data'] = Models_Grade::$stage_grade_list[$stage];
		echo json_encode($json);
	}
}