<?php
class BillingsController extends AppController {

	var $name = 'Billings';
	function index(){
		$dueDate = date('Y-m-07',time());
		if($_GET['due_date'])
			$dueDate = $_GET['due_date'];
		$billings =$this->Billing->getStudentBillingDetails($dueDate);
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
			$bill['Billing']['paid_amount']=$bill['0']['paid_amount'];

			$status = 'UNPAID';
			if($bill['Billing']['due_amount']==0)
				$status = 'PAID';
			if($bill['Billing']['paid_amount']>0):
				if($bill['Billing']['paid_amount']>=$bill['Billing']['due_amount'])
					$status = 'PAID';
				if($bill['Billing']['paid_amount']<$bill['Billing']['due_amount'])
					$status = 'PARTIAL';
			endif;
			$bill['Billing']['status'] = $status;
			$billings[$bi]=$bill;
		endforeach;
		$this->set('billings',$billings);
	}
}
