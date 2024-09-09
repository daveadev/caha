<?php
class BillingsController extends AppController {

	var $name = 'Billings';
	function index(){
		$billings =$this->Billing->getStudentBillingDetails();
		foreach($billings as $bi=>$bill):
			if(!isset($bill['Student']['sno'])):
				continue;
			endif;
			$bill['Billing']['sno']=$bill['Student']['sno'];
			$bill['Billing']['student']=($bill['Student']['print_name']);
			$bill['Billing']['year_level_id']=$bill['Student']['year_level_id'];
			$bill['Billing']['year_level']=$bill['Student']['YearLevel']['name'];
			$bill['Billing']['section_id']=$bill['Student']['section_id'];
			$bill['Billing']['section']=$bill['Student']['Section']['name'];
			$bill['Billing']['billing_no']=$bill['0']['bill_id'];
			$billings[$bi]=$bill;
		endforeach;
		$this->set('billings',$billings);
	}
}
