<?php
class ReceiptsController extends AppController{
	var $name = 'Receipts';
	var $uses = array('MasterConfig','Student','Transaction','Account','Ledger');
	function view($id=null){
		if($id=='cash_or') return $this->cash_or();
		$this->adjust_memo();
	}

	protected function adjust_memo(){
		if(isset($_POST['details'])):
			$details = json_decode($_POST['details'],true);
			$user = $this->Auth->user()['User'];
			$details['cashier']=$user['username'];
			// Transform  SY format
			$details['sy'] = (int)$details['sy'];
			$details['sy'] = sprintf("SY %s-%s",$details['sy'],$details['sy']+1);
			$data = $details;
		else:
			$refNo= $trnDate= $student= $sno= $yearLevel= $section= $syFor="XXX";
			$trnDate=  $totalPaid = $cashier= $verify ="XX";
			$trnxDtls = array(
				array('item'=>'XXX','amount'=>'XXX.XX')
			);
			$data = array(
					'ref_no'=>$refNo,
					'transac_date'=>$trnDate,
					'student'=>$student,
					'sno'=>$sno,
					'year_level'=>$yearLevel,
					'section'=>$section,
					'sy'=>$syFor,
					'transac_details'=> $trnxDtls,
					'total_paid'=>$totalPaid,
					'cashier'=>$cashier,
					'verify_sign'=>'1A2khsfdso1sa'
				);
		endif;

		
		/*
		Array
		(
		    [ref_no] => OR 133329
		    [transac_date] => 21 Aug 2023
		    [student] => Santos, Ysmael Zeth Cyrus Marcellana
		    [sno] => 2023-0085  
		    [year_level] => Gr 7
		    [section] => CHARITY
		    [sy] => 23-24
		    [transac_details] => Array
		        (
		            [0] => Array
		                (
		                    [item] => Subsequent Payment
		                    [amount] => 425.00
		                )

		        )

		    [total_paid] => 425.00
		    [cashier] => admin
		    [verify_sign] => 7c00f733dab1f0c886b14ae5dae7f8ff
		)
		*/
		
		$this->set(compact('data'));
		$this->render('adjust_memo');
	}
	function payment_plan(){
		$details = json_decode($_POST['details'],true);

		$sid = $details['account_id'];
		$this->Student->recursive=-1;
		$stud = $this->Student->findById($sid);
		$details['student']=$stud['Student']['full_name'];
		$details['date_created'] = date('F d, Y',time());
		$this->set(compact('details'));
		$this->render('payment_plan');
		return;
	}

	function cash_ar(){
		$details = json_decode($_POST['details'],true);

		$sid = $details['account_id'];
		$this->Student->recursive=-1;
		$stud = $this->Student->findById($sid);
		$details['student']=$stud['Student']['full_name'];
		$details['transac_date'] = date('d M Y',strtotime($details['transac_date']));
		$user = $this->Auth->user()['User'];
		$details['cashier']=$user['username'];
		$this->set(compact('details'));
		$this->render('cash_ar');
		return;	
	}

	function cash_or(){
		$this->cash_a2o();
	}	

	function cash_a2o(){
		$details = array();
		// Regenerate printout via GET
		if(isset($_GET['series_no'])):
			$seriesNo = $_GET['series_no'];
			$TRNX = $this->Transaction->findByRefNo($seriesNo);
			$LDG = $this->Ledger->findByRefNo($seriesNo);
			if(isset($LDG['Account'])):
				$details['account'] =array('account_id'=>$LDG['Ledger']['account_id']);
			endif;
			$details['account_id']=$TRNX['Transaction']['account_id'];
			$TRNS = $TRNX['Transaction'];
			$details['esp']=$TRNS['esp'];
			$details['series_no']=$TRNS['ref_no'];
			$details['account_type'] = 'student';
			$details['pay_type'] = 'CASH';
			$TDTL = array();
			foreach($TRNX['TransactionDetail'] as $dV){
				$dV['description']=$dV['details'];
				$TDTL[]=$dV;
			}
			$details['details'] = $TDTL;
			$details['pay_amount']=$TRNS['amount'];
			$details['transac_date']=$TRNS['transac_date'];
			$details['cashier']=$TRNS['cashier'];
		elseif(isset($_POST['details'])):
			$details = json_decode($_POST['details'],true);
			if($details['pay_type']=='CHCK'):
				if(isset($details['pay_details'])):
					$checkDetails =  $details['pay_details'];
					$checkDetails .= '  / '.date('Y-m-d',strtotime($details['pay_date']));
					$details['check_details']=$checkDetails;
				endif;
			endif;
			$user = $this->Auth->user()['User'];
			$details['cashier'] = $user['username'];
		endif;
		$sno = $yearLevel = $sectionName = '';
		switch($details['account_type']):
			case 'others':
				$oid = $details['account_id'];
				$this->Account->recursive=-1;
				$other = $this->Account->findById($oid);
				$details['student']=$other['Account']['account_details'];
				$sno=$other['Account']['id'];
				$yearLevel = '-';
				$sectionName = '-';
			break;
			case 'student':
			default:
				$acid = $sid = $details['account_id'];
				$is_new_stud  =substr($sid, 0, 3) === 'LSN';
				if($is_new_stud):
					if(isset($details['account']))
					$sid = $details['account']['account_id'];
				endif;
				$this->Student->recursive=-1;
				$stud = $this->Student->findInfoBySID($sid);
				$details['student']=$stud['Student']['full_name'];
				$sno = $stud['Student']['sno'];
				$yearLevel = $stud['YearLevel']['name'];
				$sectionName = $stud['Section']['name'];

				if($is_new_stud):
					$desc = "New student: $sno";
					$sno = $acid;
					$details['details'][] = array('description'=>$desc,'amount'=>null);
				endif;

			break;
		endswitch;
		
		$details['transac_date'] = date('d M Y',strtotime($details['transac_date']));
		
		

		
		$trnxDtls  =array();
		foreach($details['details'] as $dtl){
			$item = $dtl['description'];
			if($dtl['amount'])
				$amount= number_format($dtl['amount'],2,'.',',');
			else
				$amount ="";
			$trnxDtls[] =  array('item'=>$item,'amount'=>$amount);
		}
		
		$syAlias = (int)substr($details['esp'], 2);
		$syAlias = "SY $syAlias - ".($syAlias+1);
		$or_details = array(
			'ref_no'=>$details['series_no'],
			'transac_date'=>$details['transac_date'],
			'student'=>$details['student'],
			'sno'=>$sno,
			'year_level'=>$yearLevel,
			'section'=>$sectionName,
			'sy'=>$syAlias,
			'transac_details'=> $trnxDtls,
			'total_paid'=>$details['pay_amount'],
			'cashier'=>$details['cashier']
		);

		if($details['pay_type']=='CHCK'):
			$or_details['check_details'] = $details['check_details'];
		endif;
		$this->set(compact('details','or_details'));
		$this->render('cash_a2o');
		return;	
	}
}