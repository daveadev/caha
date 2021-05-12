<?php
class Assessment extends AppModel {
	var $name = 'Assessment';
	var $useDbConfig = 'sem';
	
	var $belongsTo = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => array(
				'Student.sno',
				'Student.gender',
				'Student.short_name',
				'Student.full_name',
				'Student.class_name',
				'Student.status',
				'Student.year_level_id',
				'Student.section_id',
				'Student.program_id',
			),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Inquiry' => array(
			'className' => 'Inquiry',
			'foreignKey' => 'id',
			'dependent' => false,
			'conditions' => '',
			'fields' => array(
				'Inquiry.id',
				'Inquiry.gender',
				'Inquiry.short_name',
				'Inquiry.full_name',
				'Inquiry.class_name',
				'Inquiry.year_level_id',
				'Inquiry.program_id'
			),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
}