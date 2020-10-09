<?php
class UserGrant extends AppModel {
	var $name = 'UserGrant';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'UserType' => array(
			'className' => 'UserType',
			'foreignKey' => 'user_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		/*'MasterModule' => array(
			'className' => 'MasterModule',
			'foreignKey' => 'master_module_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)*/
	);
}
