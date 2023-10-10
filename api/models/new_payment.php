<?php
class NewPayment extends AppModel {
	var $name = 'NewPayment';
	var $useTable = false;

	function prepareTrnx($bkl_id,$ref_no,$amount,$esp,$account_id,$transac_date ,$cashier){
		$transac_time = date("h:i:s");
		$TObj = array(
			'type'=>'payment',
			'status'=>'fulfilled',
			'booklet_id'=>$bkl_id,
			'ref_no'=>$ref_no,
			'amount'=>$amount,
			'esp'=>$esp,
			'amount'=>$account_id,
			'transac_date'=>$transac_date,
			'transac_time'=>$transac_time,
			'cashier'=>$cashier
		);
	}
}