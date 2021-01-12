<?php
class CashierCollectionsController extends AppController {

	var $name = 'CashierCollections';
	var $uses = array('CashierCollection','Section','Student','Account','AccountHistory','Transaction');
	
	function index() {
		$this->paginate['CashierCollection']['contain'] = array('Student','Account');
		
		$type = $_GET['type'];
		if(!isset($_GET['cashr'])){
			$start = $_GET['from'];
			$end = $_GET['to'];
			$conds =  array('Transaction.ref_no LIKE'=> $type.'%','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
		}else{
			$date = $_GET['date'];
			$conds =  array('Transaction.ref_no LIKE'=> $type.'%','transac_date'=>$date);
		}
		//pr($start);
		//$conds =  array('AccountHistory.ref_no LIKE'=> $type.'%','flag'=>'-','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
		$collections = $this->paginate();

		$sections = $this->Section->find('all',array('recursive'=>1));
		$total = $this->Transaction->find('all',array('conditions'=>$conds));
		$total_collections = 0;
		foreach($total as $i=>$amount){
			$total_collections += $amount['Transaction']['amount'];
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
			$page = $this->paginate['CashierCollection']['page'];
			$limit = $this->paginate['CashierCollection']['limit'];
			$cnt = $limit!=999999?($page-1)*$limit+1:1;
			//pr($collections); exit();
			foreach($collections as $i=>$col){
				$st = $col['Student'];
				$cl = $col['CashierCollection'];
				$cl['cnt'] =  $cnt;
				$status = $col['Account']['subsidy_status'];
				$status = $status=='REGXX'?'REG':substr($status,-3);
				if(isset($st['full_name'])):
				$cl['received_from'] = $st['class_name'];
				$cl['sno'] = $st['sno'];
				$cl['status'] = $status;
				$yl_ref = $st['year_level_id'];
				$sec_ref = $st['section_id'];
				else:
					$cl['date'] ='-';
					$cl['received_from'] ='-';
					$cl['sno'] ='-';
					$cl['status'] ='-';
				endif;
				if(isset($list[$yl_ref][$sec_ref])):
				$cl['level'] = $list[$yl_ref][$sec_ref]['yl'];
				$cl['section'] = $list[$yl_ref][$sec_ref]['name'];
				else:
					$cl['level'] = '-';
					$cl['section'] = 'CODE:'.$sec_ref;
				endif;
				$cl['particulars'] = $cl['details'];
				$cl['date'] =  date('d M Y',strtotime($cl['transac_date']));
				unset($cl['details']);
				unset($cl['transac_date']);
				unset($cl['transac_time']);
				unset($cl['id']);
				unset($cl['account_id']);

				$collections[$i] = $cl;
				$cnt++;
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