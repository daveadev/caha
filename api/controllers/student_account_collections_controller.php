<?php
class StudentAccountCollectionsController extends AppController {

	var $name = 'StudentAccountCollections';
	var $uses = array('Account','Section');

	function index(){
		$order =  array(); // Sort by level then last name G7- G10
		$recrusive= 2; // to get the Account Schedule data
		$Accounts = $this->paginate();
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
		foreach($Accounts as $i=>$account){
				$st = $account['Student'];
				$acc = $account['Account'];
				// Build your data here
				$accountObj =  array();
				$yl_ref = $st['year_level_id'];
				$sec_ref = $st['section_id'];
				$accountObj['cnt'] = $i+1;
				$accountObj['account_id'] = $acc['id'];
				$accountObj['name'] = $st['full_name'];
				$accountObj['year_level'] = $list[$yl_ref][$sec_ref]['yl'];
				$accountObj['section'] = $list[$yl_ref][$sec_ref]['name'];
				$accountObj['total_fees'] = $acc['assessment_total'];
				$accountObj['subsidy'] = $acc['discount_amount'];
				$accountObj['fee_dues'] = $acc['assessment_total']-$acc['discount_amount'];
				$accountObj['payments'] = array();
				foreach($account['AccountSchedule'] as $sched){
					$schedObj['bill_month'] = $sched['bill_month'];
					$schedObj['payment'] = $sched['paid_amount'];
					$schedObj['balance'] = $sched['due_amount'];
					array_push($accountObj['payments'],$schedObj);
				}
				array_push($collections, $accountObj);
		}
		//pr($collections);exit();
		$student_account_collections  =  array('collections'=>$collections);
		$student_account_collections =  array(
							array('StudentAccountCollection'=>$student_account_collections)
						);
		// Important to render the correct data
		//$student_account_collections =  array('studentAccountCollections'=>$student_account_collections);
		//pr($student_account_collections); exit();
		$this->set('studentAccountCollections',$student_account_collections);
		
	}

}