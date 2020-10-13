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
}