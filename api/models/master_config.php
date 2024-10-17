<?php
class MasterConfig extends AppModel {
	var $name = 'MasterConfig';
	var $cacheExpires = '+30 day';
	var $usePaginationCache = false;

	function getInfo($field=null){
		$conf = array();
		$conf['fields'] = array('sys_key','sys_value');
		if($field)
			$conf['conditions'] = array('sys_key'=>$field);
		$info = $this->find('list',$conf);
		return $info;
	}
}
