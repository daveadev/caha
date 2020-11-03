<?php
class CashierCollectionsController extends AppController {

	var $name = 'CashierCollections';
	var $uses = array('CashierCollection','Section','Student','Account','AccountHistory');
	
	function index() {
		$this->paginate['CashierCollection']['contain'] = array('Student','Account');
		$start = $_GET['from'];
		$end = $_GET['to'];
		//pr($start);
		$conds = array('details LIKE'=>'% Payment','flag'=>'-','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
		$collections = $this->paginate('CashierCollection',array('CashierCollection.transac_date'=>'asc'),array('CashierCollection.transac_date'=>'asc'));

		$sections = $this->Section->find('all',array('recursive'=>1));
		$total = $this->AccountHistory->find('all',array('conditions'=>$conds));
		$total_collections = 0;
		foreach($total as $i=>$amount){
			$total_collections += $amount['AccountHistory']['amount'];
		}
		//pr($total_collections);
		//exit();
		
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
				//pr($col); exit();
				$st = $col['Student'];
				$cl = $col['CashierCollection'];
				$cl['name'] = $st['full_name'];
				$cl['sno'] = $st['sno'];
				$cl['status'] = $col['Account']['subsidy_status'];
				$yl_ref = $st['year_level_id'];
				$sec_ref = $st['section_id'];
				$cl['year_level'] = $list[$yl_ref][$sec_ref]['yl'];
				$cl['section'] = $list[$yl_ref][$sec_ref]['name'];
				$collections[$i] = $cl;
			}
		}
		$collections = array('collections'=>$collections,'total'=>$total_collections);
		$cashierCollections = array(array('CashierCollection'=>$collections));
		//pr($collections); exit();
		//pr($collections);
		//exit();
		$this->set('cashierCollections', $cashierCollections);
	}

}