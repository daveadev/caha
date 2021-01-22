<?php
class Transaction extends AppModel {
	var $name = 'Transaction';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Booklet' => array(
			'className' => 'Booklet',
			'foreignKey' => 'booklet_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	var $hasMany = array(
		'TransactionDetail' => array(
			'className' => 'TransactionDetail',
			'foreignKey' => 'transaction_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'TransactionPayment' => array(
			'className' => 'TransactionPayment',
			'foreignKey' => 'transaction_id',
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
