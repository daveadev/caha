<?php
class StudentAccountCollectionsController extends AppController {

	var $name = 'StudentAccountCollections';
	var $uses = array('StudentAccountCollection','Account','Section');

	function index(){
		//$this->StudentAccountCollection->recursive = 0;
		//$this->paginate['StudentAccountCollection']['contain'] = array('Account.id');
		
		//pr($this->paginate); exit();
		$Accounts = $this->paginate();
		
		//pr($Accounts); exit();
		if(isset($_GET['account_id']))
			$Accounts = $this->paginate('Account',array('id'=>$_GET['account_id']));
		//echo count($Accounts); exit();
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
		$collections =  array();
		
		if($this->isAPIRequest()){
			$col = $this->Account->find('all',array('recursive'=>0));
			$total = 0;
			foreach($col as $payment){
				$total+=$payment['Account']['payment_total'];
			}
			//pr($collections);
			foreach($Accounts as $i=>$account){
				//pr($account); exit();
				if(!isset($account['AccountSchedule'][0])){
					continue;
					//pr($account); exit();
				}
				
				
				$cnt=$i+1;
				if(isset($_GET['page'])&&$_GET['page']!==1)
					$cnt=(($_GET['page']-1)*100)+$i+1;
				//pr($_GET['page']);
				$st = $account['Student'];
				
				if(isset($_GET['account_id']))
					$acc = $account['Account'];
				else
					$acc = $account['StudentAccountCollection'];
				// Build your data here
				//pr($account); exit();
				$accountObj =  array();
				$yl_ref = $st['year_level_id'];
				$sec_ref = $st['section_id'];
				$accountObj['cnt'] = $cnt;
				$accountObj['account_id'] = $acc['id'];
				$accountObj['name'] = $st['full_name'];
				
				$accountObj['year_level'] = $list[$yl_ref][$sec_ref]['yl'];
				$accountObj['section'] = $list[$yl_ref][$sec_ref]['name'];
				$accountObj['total_fees'] = $acc['assessment_total'];
				if(!isset($acc['discount_amount']))
					$acc['discount_amount']=0;
				$accountObj['subsidy'] = $acc['discount_amount'];
				$accountObj['fee_dues'] = $acc['assessment_total']-$acc['discount_amount'];
				$accountObj['hasRes']=false;
				$accountObj['payments'] = array();
				$payment = $accountObj['subsidy'];
				foreach($account['AccountSchedule'] as $sched){
					$payment += $sched['paid_amount'];
					$schedObj['bill_month'] = $sched['bill_month'];
					$schedObj['payment'] = $sched['paid_amount'];
					$schedObj['balance'] = $accountObj['total_fees']-$payment;
					array_push($accountObj['payments'],$schedObj);
				}
				$accountObj['advances']=0;
				foreach($account['Ledger'] as $led){
					if($led['transaction_type_id']=='RSRVE')
						$accountObj['hasRes']=true;
					if($led['transaction_type_id']=='ADVTP')
						$accountObj['advances']+=$led['amount'];
				}
				array_push($collections, $accountObj);
			}
			//$collections['total_collected'] = $total;
		}
		//pr($collections);exit();
		$student_account_collections  =  array('total_collected'=>$total,'collections'=>$collections);
		$student_account_collections =  array(
							array('StudentAccountCollection'=>$student_account_collections)
						);
		// Important to render the correct data
		//$student_account_collections =  array('studentAccountCollections'=>$student_account_collections);
		//pr($student_account_collections); exit();
		$this->set('studentAccountCollections',$student_account_collections);
		
	}

}