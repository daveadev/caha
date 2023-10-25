<?php
class AccountSchedule extends AppModel {
	var $name = 'AccountSchedule';
	//var $useDbConfig = 'sfm';
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

	function updateSchedule($schedule){
		$scheduleAdj = array();
		foreach($schedule as $sched){
			if(isset($sched['is_total'])) 
				continue;
			if($sched['due_date']=='Old Account')
				continue;
			
			$schedObj = array();
			$schedObj['id'] =$sched['id'];
			$due_amount = floatval(str_replace(",", "", $sched['due_amount']));

			$paid_amount = floatval(str_replace(",", "", $sched['paid_amount']));
			$schedObj['due_amount'] =$due_amount;
			$schedObj['paid_amount'] =$paid_amount;
			$schedObj['status'] =$sched['status'];
			$scheduleAdj[] = $schedObj;
		}
		$this->saveAll($scheduleAdj);
	}
}
