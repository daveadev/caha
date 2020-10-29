<?php
class CashierCollectionsController extends AppController {

	var $name = 'CashierCollections';
	var $uses = array('CashierCollection','Section','Student');
	
	function index() {
		$this->paginate['CashierCollection']['contain'] = array('Student');
		$collections = $this->paginate();
		$sections = $this->Section->find('all',array('recursive'=>1));
		$list = array();
		foreach($sections as $i=>$sec){
			$section = $sec['Section'];
			$yl = $sec['YearLevel'];
			$grade = $yl['id'];
			if(!isset($list[$grade]))
				$list[$grade] = array();
			$data = array('id'=>$section['id'],'name'=>$section['name'],'yl'=>$yl['name']);
			$sec_id = $section['id'];
			if(!isset($list[$grade][$sec_id]))
				$list[$grade][$sec_id] = $data;
		}
		//pr($list);exit();
		if($this->isAPIRequest()){
			foreach($collections as $i=>$col){
				$st = $col['Student'];
				$cl = $col['CashierCollection'];
				$cl['name'] = $st['full_name'];
				$cl['sno'] = $st['sno'];
				$yl_ref = $st['year_level_id'];
				$sec_ref = $st['section_id'];
				$cl['year_level'] = $list[$yl_ref][$sec_ref]['yl'];
				$cl['section'] = $list[$yl_ref][$sec_ref]['name'];
				$collections[$i]['CashierCollection'] = $cl;
			}
		}
		//pr($collections);
		//exit();
		$this->set('cashierCollections', $collections);
	}

}