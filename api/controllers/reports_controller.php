<?php
class ReportsController extends AppController{
	var $name = 'Reports';
	var $uses = array('Ledger','Account', 'Student');

	// GET srp/test_soa?account_id=LSJXXXXX
	function soa(){
		if(isset($_GET['account_id'])){
			$account_id =  $_GET['account_id'];
			
			//Student's Details
			$student = $this->Student->find('first',array(array('Student.id'=>$account_id)));
			
			//Student's SOA
			$data = $this->Ledger->find('all',array(
				'conditions'=>array('Ledger.account_id'=>$account_id),
				'order'=>'Ledger.transac_date'
			));
			//pr($data);exit;
			
			$this->set(compact('data','student'));
		}else{
			die('No data available.Contact your system administrator.');
		}
	}
	function or(){
		$or_detail = array(
			'ref_no'=>'OR 12234',
			'transac_date'=>'12 OCT 2020',
			'student'=>'Juan Dela Cruz',
			'year_level'=>'Gr. 7',
			'sy'=>'20-21',
			'transac_details'=> array(
				array('item'=>'IP', 'amount'=>'1,000'),
				array('item'=>'PE', 'amount'=>'2,000'),
			),
			'total_paid'=>'3,000.00',
			'cashier'=>'cashier1',
			'verify_sign'=>'1A2khsfdso1sa'
		);
	}
}