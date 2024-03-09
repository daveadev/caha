<?php
class StudentAuxField extends AppModel {
	var $name = 'StudentAuxField';
	var $useDbConfig = 'ser';
	var $belongsTo = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	function saveField($sid, $field,$value){
		$cond = array('student_id'=>$sid,'aux_field'=>$field);
		$auxObj = $cond;
		if(is_null($value)) $value ="";
		$auxObj['aux_value']=$value;
		$auxRec = $this->find('first',array('conditions'=>$cond));
		if($auxRec):
			$auxObj['id']=$auxRec['StudentAuxField']['id'];
		else:
			$this->create();
		endif;
		$this->save($auxObj);
	}

	function saveFields($sid,$fields){
		foreach($fields as $field=>$value)
			$this->saveField($sid,$field,$value);
	}
}