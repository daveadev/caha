<?php
class AccountTransaction extends AppModel {
	var $name = 'AccountTransaction';
	var $useDbConfig = 'sfm';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'TransactionType' => array(
			'className' => 'TransactionType',
			'foreignKey' => 'transaction_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
