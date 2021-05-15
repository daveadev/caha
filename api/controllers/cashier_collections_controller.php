<?php
class CashierCollectionsController extends AppController {

	var $name = 'CashierCollections';
	var $uses = array('CashierCollection','Section','Student','Account','AccountHistory','Transaction','TransactionDetail','Booklet','TransactionPayment');
	
	function index() {
		
		$this->paginate['CashierCollection']['contain'] = array('Student','Account','Booklet','AccountHistory','TransactionDetail','Inquiry','TransactionPayment');
		//pr($this->paginate()); exit;
		
		
		$type = $_GET['type'];
		$typ=$type;
		if($type=='A2O')
			$typ='OR';
		//pr($type);
		if(!isset($_GET['cashr'])){
			$start = $_GET['from'];
			$end = $_GET['to'];
			$conds =  array('Transaction.ref_no LIKE'=> '%'.$typ.'%','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
			if($type!=='OR'){
				$this->paginate['CashierCollection']['contain'] = array('Student','Account','TransactionDetail','Booklet');
				//$conds =  array('Transaction.ref_no LIKE'=> $typ.'%','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
			}
		}else{
			$date = $_GET['date'];
			$conds =  array('Transaction.ref_no LIKE'=> '%'.$typ.'%','transac_date'=>$date);
			if($type!=='OR'){
				$this->paginate['CashierCollection']['contain'] = array('Student','Account','TransactionDetail','Booklet');
				//$conds =  array('Transaction.ref_no LIKE'=> $typ.'%','transac_date'=>$date);
			}
		}
		//pr($this->paginate()); exit();
		//$conds =  array('AccountHistory.ref_no LIKE'=> $type.'%','flag'=>'-','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
		$collections = $this->paginate();
		
		$sections = $this->Section->find('all',array('recursive'=>1));

		// Get running total from AccountHistory if OR otherwise use Transaction
		/* switch($type){
			case 'AR':
				$total = $this->AccountHistory->find('all',array('conditions'=>$conds));
			default:
				$total = $this->Transaction->find('all',array('conditions'=>$conds));
			break;
		}
		
		$total_collections = 0;
		foreach($total as $i=>$amount){
			$total_collections += $amount['Transaction']['amount'];
			if($type=='AR')
				$total_collections += $amount['AccountHistory']['amount'];
		} */
		
		
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
				//pr($col); 
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
				
				if($acct['account_type']=='student'){
					$cl['received_from'] = $st['class_name'];
					$cl['sno'] = $st['sno'];
					$cl['status'] = $status;
					$yl_ref = $st['year_level_id'];
					$sec_ref = $st['section_id'];
				}else{
					$cl['date'] ='-';
					$cl['received_from'] ='-';
					$cl['sno'] =$acct['id'];
					$cl['status'] ='-';
					
				}
				if(isset($yl_ref)){
					if(isset($list[$yl_ref][$sec_ref])):
						$cl['level'] = $list[$yl_ref][$sec_ref]['yl'];
						$cl['section'] = $list[$yl_ref][$sec_ref]['name'];
					else:
						$cl['level'] = '-';
						$cl['section'] = 'CODE:'.$sec_ref;
					endif;
				}
				
				if(isset($col['TransactionDetail'][0]))
					$cl['particulars'] = $col['TransactionDetail'][0]['details'];
				else
					$cl['particulars'] = $cl['details'];
				if(isset($col['TransactionPayment'][0])){
					if($col['TransactionPayment'][0]['payment_method_id']!=='CASH'){
						$cl['payment'] = $col['TransactionPayment'][0]['details'];
						$cl['check_date'] = $col['TransactionPayment'][0]['valid_on'];
					}
				}
				$cl['date'] =  date('d M Y',strtotime($cl['transac_date']));
				unset($cl['details']);
				unset($cl['transac_date']);
				unset($cl['transac_time']);
				unset($cl['id']);
				unset($cl['account_id']);
				if(isset($col['AccountHistory']['id'])){
					$cl['total_due'] = $col['AccountHistory']['total_due'];
					$cl['total_paid'] = $col['AccountHistory']['total_paid'];
					$cl['balance'] = $col['AccountHistory']['balance'];
				}else{
					$cl['total_due'] = 'N/A';
					$cl['total_paid'] = 'N/A';
					$cl['balance'] = 'N/A';
					$cl['level'] = 'N/A';
					$cl['section'] = 'N/A';
					
					if($acct['account_type']=='inquiry'){
						$cl['status']='New';
						$cl['received_from'] = $col['Inquiry']['full_name'];
					}
					if($acct['account_type']=='others'){
						$cl['status']='Others';
						$cl['received_from'] = $acct['account_details'];
					}
					if($acct['account_type']=='student')
						$cl['received_from'] = $st['full_name'];
				}
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
		$collections = array('collections'=>$collections,'booklets'=>$booklets);
		$cashierCollections = array(array('CashierCollection'=>$collections));
		//pr($collections); exit();
		//pr($collections);
		//exit();
		$this->set('cashierCollections', $cashierCollections);
	}

}