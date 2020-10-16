<?php
class ReportsController extends AppController{
	var $name = 'Reports';
	var $uses = array('Ledger','Account', 'Student','Section');

	// GET srp/test_soa?account_id=LSJXXXXX
	function soa(){
		if(isset($_GET['account_id'])){
			$account_id =  $_GET['account_id'];
			
			//Student's Details
			$this->Student->bindModel(array('belongsTo' => array('Section')));
			$student = $this->Student->find('first',array(array('Student.id'=>$account_id)));
			
			//Student's SOA
			$data = $this->Ledger->find('all',array(
				'conditions'=>array('Ledger.account_id'=>$account_id),
				'order'=>array('Ledger.transac_date','Ledger.id')
			));
			//pr($student);exit;
			
			$this->set(compact('data','student'));
		}else{
			die('No data available.Contact your system administrator.');
		}
	}
	function receipt(){
		$data = array(
			'ref_no'=>'12234',
			'transac_date'=>'12 OCT 2020',
			'student'=>'Juan Dela Cruz',
			'sno'=>'S082420',
			'year_level'=>'Gr. 7',
			'section'=>'Mt. Makiling',
			'sy'=>'20-21',
			'transac_details'=> array(
				array('item'=>'Initial Payment', 'amount'=>'1000'),
				array('item'=>'Subsequent Payment', 'amount'=>'2000'),
			),
			'total_paid'=>'3,000.00',
			'cashier'=>'Cashier Sophia',
			'verify_sign'=>'1A2khsfdso1sa'
		);
		$this->set(compact('data'));
	}
}