<?php
class BillingsController extends AppController {

	var $name = 'Billings';
	function index(){
		$billings =$this->Billing->getStudentBillingDetails();
		foreach($billings as $bi=>$bill):
			if(!isset($bill['Student']['sno'])):
				continue;
			endif;
			foreach($bill['Student'] as $fld=>$val):
				if(gettype($val)=='array') continue;
				if(gettype($val)=='string')
					$val = trim(ucwords(strtolower($val)));
				$bill['Billing'][$fld]=$val;
			endforeach;
			$bill['Billing']['student']=$bill['Billing']['print_name'];
			$bill['Billing']['year_level']=$bill['Student']['YearLevel']['name'];
			$bill['Billing']['section_id']=$bill['Student']['section_id'];
			$bill['Billing']['section']=$bill['Student']['Section']['name'];
			$bill['Billing']['billing_no']=$bill['Billing']['bill_id'];
			$billings[$bi]=$bill;
		endforeach;
		$this->set('billings',$billings);
	}
}
