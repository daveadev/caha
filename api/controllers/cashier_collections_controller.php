<?php
class CashierCollectionsController extends AppController {

	var $name = 'CashierCollections';
	var $uses = array('CashierCollection','Section','Student','Account','AccountHistory','Transaction','TransactionDetail','Booklet');
	
	function index() {/* 
		if($_GET['type']!=='OR')
			$this->CashierCollection->$useTable = 'transactions'; */
		$this->paginate['CashierCollection']['contain'] = array('Student','Account','Booklet');
		
		
		$type = $_GET['type'];
		$typ=$type;
		if($type=='A2O')
			$typ='OR';
		//pr($type);
		if(!isset($_GET['cashr'])){
			$start = $_GET['from'];
			$end = $_GET['to'];
			$conds =  array('CashierCollection.ref_no LIKE'=> $typ.'%','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
			if($type!=='OR'){
				$this->paginate['CashierCollection']['contain'] = array('Student','Account','TransactionDetail','Booklet');
				//$conds =  array('Transaction.ref_no LIKE'=> $typ.'%','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
			}
		}else{
			$date = $_GET['date'];
			$conds =  array('CashierCollection.ref_no LIKE'=> $typ.'%','transac_date'=>$date);
			if($type!=='OR'){
				$this->paginate['CashierCollection']['contain'] = array('Student','Account','TransactionDetail','Booklet');
				//$conds =  array('Transaction.ref_no LIKE'=> $typ.'%','transac_date'=>$date);
			}
		}
		//pr($this->paginate()); exit();
		//$conds =  array('AccountHistory.ref_no LIKE'=> $type.'%','flag'=>'-','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
		$collections = $this->paginate();

		$sections = $this->Section->find('all',array('recursive'=>1));
		$total = $this->AccountHistory->find('all',array('conditions'=>$conds));
		if($type!=='OR')
			$total = $this->Transaction->find('all',array('conditions'=>$conds));
		$total_collections = 0;
		foreach($total as $i=>$amount){
			$total_collections += $amount['AccountHistory']['amount'];
		}
		
		
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
			$booklets = array();
			foreach($collections as $i=>$col){
				//pr($col);
				$st = $col['Student'];
				$cl = $col['CashierCollection'];
				//pr($cl); exit();
				$acct = $col['Account'];
				$book = $col['Booklet'];
				$booknum = $book['booklet_number'];
				if(!isset($booklets[$booknum])){
					$booklets[$booknum] = array(
						'booklet_no'=>$booknum,
						'ref_nos'=>array(),
						'amount'=>$cl['amount'],
					);
					$ref = explode(" ",$cl['ref_no']);
					$ref = $ref[1];
					$booklets[$booknum]['ref_nos'][0]=$ref;
				}else{
					$ref = explode(" ",$cl['ref_no']);
					$ref = $ref[1];
					array_push($booklets[$booknum]['ref_nos'],$ref);
					$booklets[$booknum]['amount'] += $cl['amount'];
				}
				//pr($booklets);
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
				$cl['total_due'] = $cl['total_paid'];
				$cl['total_paid'] = $cl['total_due'];
				$cl['balance'] = $cl['balance'];
				$collections[$i] = $cl;
				$cnt++;
			}
			foreach($booklets as $i=>$b){
				$b['series_start'] = min($b['ref_nos']);
				$b['series_end'] = max($b['ref_nos']);
				$booklets[$i] = $b;
			}
			//pr($booklets);
		}
		//exit();
		$collections = array('collections'=>$collections,'total'=>$total_collections,'booklets'=>$booklets);
		$cashierCollections = array(array('CashierCollection'=>$collections));
		//pr($collections); exit();
		//pr($collections);
		//exit();
		$this->set('cashierCollections', $cashierCollections);
	}

}