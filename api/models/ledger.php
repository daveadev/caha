<?php
class Ledger extends AppModel {
	var $name = 'Ledger';
	//var $useDbConfig = 'sfm';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	function beforeFind($queryData){
		//pr($queryData); exit();
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				if(!is_array($cond))
					break;
				$keys =  array_keys($cond);
				$search = 'Ledger.rect';
				
				if(in_array($search,$keys)){
					$cond['Ledger.ref_no LIKE']='OR %';
					unset($cond[$search]);
				}
				$conds[$i]=$cond;
				//pr($conds);
			}
			
			$queryData['conditions']=$conds;
		}
		
		return $queryData;
	}
}
