<?php
class CurrentCollection extends AppModel {
	var $name = 'CurrentCollection';
	var $useTable = 'ledgers';
	
	function beforeFind($queryData){
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				$type = 'CurrentCollection.type';
				$from = 'CurrentCollection.from';
				$to = 'CurrentCollection.to';
				if(isset($cond[$type]))
					unset($cond[$type]);
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
