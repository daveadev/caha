<?php
class MasterModule extends AppModel {
	var $name = 'MasterModule';
	var $order = 'sequence';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'UserGrant' => array(
			'className' => 'UserGrant',
			'foreignKey' => 'master_module_id',
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
