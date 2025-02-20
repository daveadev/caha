<?php
class CashierCollectionsController extends AppController {

	var $name = 'CashierCollections';
	var $uses = array('CashierCollection','Section','Student','Account','AccountHistory','Transaction','TransactionDetail','Booklet','TransactionPayment','Ledger');
	
	function index() {
		//pr($this->paginate()); exit();
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
			$conds =  array('Transaction.type LIKE'=> '%'.$typ.'%','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
			if($type!=='OR'){
				//$this->paginate['CashierCollection']['contain'] = array('Student','Account','TransactionDetail','Booklet');
				//$conds =  array('Transaction.ref_no LIKE'=> $typ.'%','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
			}
			$cancelled = $this->Ledger->find('all',
						array('recursive'=>1,
							'conditions'=>array(
								'and'=>array('Ledger.transac_date <='=>$end,
											'Ledger.transac_date >='=>$start),
									'Ledger.ref_no LIKE'=> 'X'.$typ.'%')));
		}else{
			$date = $_GET['date'];
			$conds =  array('transac_date'=>$date);
			if($type=='OR'):
				$conds['Transaction.ref_no REGEXP'] = '^#|^OR[[:space:]]*[0-9]+|^SI[[:space:]]*[0-9]+';
			else:
				$conds['Transaction.ref_no LIKE'] = $typ.'%';
			endif;
				
			if($type!=='OR'){
				$this->paginate['CashierCollection']['contain'] = array('Student','Account','TransactionDetail','Booklet');
			}
			$cancelled = $this->Ledger->find('all',
						array('recursive'=>1,
							'conditions'=>array(
									'Ledger.transac_date'=>$date,
									//'Transaction.status'=>'cancelled',
									'Ledger.ref_no LIKE'=> 'X'.$typ.'%')));
		}
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
		
	
		if($this->isAPIRequest()){
			$page = $this->paginate['CashierCollection']['page'];
			$limit = $this->paginate['CashierCollection']['limit'];
			$cnt = $limit!=999999?($page-1)*$limit+1:1;
			$booklets = array();
			$old_accounts = 0;
			$vouchers = 0;
			$tuitions = 0;
			$modules = 0;
			$total = 0;
			$v_ors = array();
			$o_ors = array();
			$t_ors = array();
			$m_ors = array();
			$school_years = array();
			
			foreach($collections as $i=>$col){
				$vchr=null;
				$sy_col = floor($col['CashierCollection']['esp']);
				$amt_col = $col['CashierCollection']['amount'];
				if(!isset($school_years[$sy_col]))
					$school_years[$sy_col] = 0;
				$school_years[$sy_col] +=$amt_col;

				$_REFNO =$col['CashierCollection']['ref_no'];
				$refno = $this->parseRefNo($_REFNO);
				//pr($refno); exit();
				switch($col['TransactionDetail'][0]['transaction_type_id']){
					case 'OLDAC':  case 'EXTPY':
						$old_accounts+=$amt_col;
						array_push($o_ors,$refno[1]);
						break;
					case 'INIPY':
						$tuitions+=$amt_col;
						array_push($t_ors,$refno[1]);
						break;
					case 'FULLP': 
						$tuitions+=$amt_col;
						array_push($t_ors,$refno[1]);
						break;
					case 'MODUL': 
						$modules+=$amt_col;
						array_push($m_ors,$refno[1]);
						break;
				}
				if(isset($col['TransactionPayment'][0]['details']))
					$vchr = $col['TransactionPayment'][0]['details'];
				if($col['TransactionDetail'][0]['transaction_type_id']=='SBQPY'){
					if(strpos('LV',$vchr)||strpos('FAV',$vchr)){
						$vouchers+=$col['CashierCollection']['amount'];
						array_push($v_ors,$refno[2]);
					}else{
						$tuitions+=$col['CashierCollection']['amount'];
						array_push($t_ors,$refno[1]);
					}
				}
				$st = $col['Student'];
				$inq = isset($col['Inquiry'])?$col['Inquiry']:array();
				$cl = $col['CashierCollection'];
				$acct = $col['Account'];
				$book = $col['Booklet'];
				$booknum = $book['booklet_number'];
				$ref = $this->parseRefNo($cl['ref_no']);
				if(!isset($booklets[$booknum])){
					$booklets[$booknum] = array(
						'booklet_no'=>$booknum,
						'ref_nos'=>array(),
						'amount'=>$cl['amount'],
					);
					$ref = $ref[1];
					$booklets[$booknum]['ref_nos'][0]=$ref;
				}else{
					$ref = $ref[1];
					array_push($booklets[$booknum]['ref_nos'],$ref);
					$booklets[$booknum]['amount'] += $cl['amount'];
				}
				
				$cl['cnt'] =  $cnt;
				$status = $col['Account']['subsidy_status'];
				$status = $status=='REGXX'?'REG':substr($status,-3);
				
				if($acct['account_type']=='student'){
					if(isset($st['class_name']))
						$cl['received_from'] = $st['class_name'];
					else
						$cl['received_from'] = $st['last_name'].', '.$st['first_name'];
						
					$cl['sno'] = $st['sno'];
					$cl['status'] = $status;
					$yl_ref = $st['year_level_id'];
					$sec_ref = $st['section_id'];
				}else if($acct['account_type']=='inquiry'){
					$cl['date'] ='-';
					$cl['received_from'] =$inq['full_name'];
					$cl['sno'] =$acct['id'];
					$cl['status'] ='New';
					$cl['section'] = '-';
					$cl['level'] ='Incoming '.$inq['year_level_id'];
				}else{
					$cl['date'] ='-';
					$cl['received_from'] =$acct['account_details'];
					$cl['sno'] =$acct['id'];
					$cl['status'] ='-';
					
				}
				//pr($col);
				if(isset($yl_ref)){
					if(isset($list[$yl_ref][$sec_ref])):
						$cl['level'] = $list[$yl_ref][$sec_ref]['yl'];
						$cl['section'] = $list[$yl_ref][$sec_ref]['name'];
					else:
						$cl['level'] = '-';
						$cl['section'] = 'CODE:'.$sec_ref;
					endif;
				}
				$cl['particulars'] = '';
				if(isset($col['TransactionDetail'][0]))
					$cl['particulars'] = $col['TransactionDetail'][0]['details'];
				
				else if(isset($cl['details']))
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
					if($acct['account_type']!='student'){
						$cl['level'] = 'N/A';
						$cl['section'] = 'N/A';
					}
					if($acct['account_type']=='inquiry'||isset($col['Inquiry']['full_name'])){
						$cl['status']='New';
						$cl['received_from'] = $col['Inquiry']['full_name'];
						
					}
					if($acct['account_type']=='others'){
						$cl['status']='Others';
						$cl['received_from'] = $acct['account_details'];
					}
					if($acct['account_type']=='student')
						$cl['received_from'] = $st['full_name'];
					if($acct['account_type']=='enrolled'&&isset($col['Inquiry']))
						{$cl['received_from'] = $col['Inquiry']['full_name']; $cl['status']='New';}
				}
				$collections[$i] = $cl;
				$cnt++;
				
			}
			foreach($booklets as $i=>$b){
				$b['series_start'] = min($b['ref_nos']);
				$b['series_end'] = max($b['ref_nos']);
				$total+=$b['amount'];
				$booklets[$i] = $b;
			}
			
		}
		
		
		foreach($cancelled as $c){
			$c_or = explode(" ",$c['Ledger']['ref_no']);
			$deduct = $c['Ledger']['amount'];
			if(in_array($c_or[1],$t_ors))
				$tuitions-=$deduct;
			if(in_array($c_or[1],$v_ors))
				$vouchers-=$deduct;
			if(in_array($c_or[1],$m_ors))
				$modules-=$deduct;
			if(in_array($c_or[1],$o_ors))
				$old_accounts-=$deduct;
		} 
		$others = $total-($old_accounts+$tuitions+$modules+$vouchers);
		$sy_coll =array();
		foreach($school_years as $syKey=>$syAmount):
			$label = $syKey.' - '.($syKey+1);
			$sy_coll[] = array('label'=>$label,'amount'=>$syAmount);
		endforeach;
		$sy_coll = array_reverse($sy_coll);
		$collections = array('collections'=>$collections,
							'booklets'=>$booklets,
							'vouchers'=>$vouchers,
							'tuitions'=>$tuitions,
							'modules'=>$modules,
							'old_accounts'=>$old_accounts,
							'others'=>$others,
							'school_years'=>$school_years,
							'sy_coll'=>$sy_coll,

						);
		
		
		$cashierCollections = array(array('CashierCollection'=>$collections));
		
		$this->set('cashierCollections', $cashierCollections);
	}

	protected function parseRefNo($_REFNO, $type='OR'){
		$refno = array();
		preg_match('/\d+/', $_REFNO, $refno);
		array_unshift($refno, $type);
		return $refno;
	}

}