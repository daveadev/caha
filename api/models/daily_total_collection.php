<?php
class DailyTotalCollection extends AppModel {
	var $name = 'DailyTotalCollection';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	function beforeFind($queryData){
		//pr($queryData); exit();
		
		return $queryData;
	}
}
