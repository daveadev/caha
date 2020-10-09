<?php
class YearLevel extends AppModel {
	var $name = 'YearLevel';
	var $useDbConfig = 'ser';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $order = 'YearLevel.order';
	

	var $hasMany = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'year_level_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
