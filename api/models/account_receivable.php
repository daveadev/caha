<?php
class AccountReceivable extends AppModel {
	var $name = 'AccountReceivable';
	var $useTable = 'ledgers';
	
	function beforeFind($queryData){
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				$from = 'AccountReceivable.from';
				$to = 'AccountReceivable.to';
				if(isset($cond[$from]))
					unset($cond[$from]);
				if(isset($cond[$to]))
					unset($cond[$to]);
				//pr($cond); exit();
				$conds[$i]=$cond;
			}
			
			$queryData['conditions']=$conds;
		}
		
		return $queryData;
	}
	
}
