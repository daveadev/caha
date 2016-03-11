<?php
class TransactionType extends AppModel {
	var $name = 'TransactionType';
	var $virtualFields = array(
				'transaction_key'=>'AES_ENCRYPT(AccountTransaction.id, SHA2("My secret passphrase",512))',
				'amount'=>'CASE WHEN AccountTransaction.amount THEN  AccountTransaction.amount ELSE TransactionType.default_amount END'
				);
	var $useDbConfig = 'sfm';
	var $actsAs = array('Containable');
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'TransactionPayment' => array(
			'className' => 'TransactionPayment',
			'foreignKey' => 'transaction_type_id',
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
	var $hasOne = array(
		'AccountTransaction'=> array(
			'className' => 'AccountTransaction',
			'foreignKey' => 'transaction_type_id',
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
	);
	function beforeFind($queryData){
		return $this->sanitizeQuery($queryData);
	}
	function preparePagination($pagination){
		$queryData = $pagination['TransactionType'];
		return array('TransactionType'=>$this->sanitizeQuery($queryData));
	}
	protected function sanitizeQuery($queryData){
		$delimiter = null;
		if(isset($queryData['conditions'][0])){
			foreach($queryData['conditions'] as $i=>$condition){
				foreach($condition as $cond=>$value){
					if(preg_match('/account_id/',$cond)){
						unset($queryData['conditions'][$i][$cond]);
						$delimiter = $value;
					}
				}
			}
		}
		$queryData['contain']=array(
						'AccountTransaction' => array(
							'conditions' => array(
								'AccountTransaction.account_id' => $delimiter
							)
						)
					);
		if(is_array($queryData['conditions'])){
			$conditions= array('OR'=>array(
								array('TransactionType.type'=>'active'),
								array('TransactionType.type'=>'reactive','TransactionType.amount >'=>0)
								)
			);
			array_push($queryData['conditions'],$conditions);
		}
		return $queryData;
	}

}
