<?php
class HouseholdMember extends AppModel {
	var $name = 'HouseholdMember';
	var $useDbConfig = 'ser';
	var $belongsTo = array(
		'Household' => array(
			'className' => 'Household',
			'foreignKey' => 'household_id',
			'conditions' => '',
			'fields' => array('id','street','barangay','city','province','email','mobile_number'),
			'order' => ''
		),
		'Guardian' => array(
			'className' => 'Guardian',
			'foreignKey' => 'entity_id',
			'conditions' => '',
			'fields' => array(),
			'order' => ''
		),
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'entity_id',
			'conditions' => '',
			'fields' => array('id','first_name','middle_name','last_name','year_level_id'),
			'order' => ''
		),
	);
}