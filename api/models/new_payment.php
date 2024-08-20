<?php
class NewPayment extends AppModel {
	var $name = 'NewPayment';
	var $useTable = false;

	function prepareTrnx($trnxObj,$TRNX,$LDGR=null){
		// Check if ref no already used
		$tCond = array(
					'Transaction.ref_no'=>$trnxObj['ref_no']
					//'Transaction.amount'=>$trnxObj['pay_amount']
					);
		$is_transacted = $TRNX->find('first',array('conditions'=>$tCond));

		
		if($is_transacted):
				$TObj = array('is_valid'=>false,'ref_no'=>$trnxObj['ref_no']);
				return $TObj;
			
		endif;
		
		// Prepare Transaction object
		$transac_time = date("h:i:s");
		$TObj = array(
			'type'=>'payment',
			'status'=>'fulfilled',
			'booklet_id'=>$trnxObj['booklet_id'],
			'ref_no'=>$trnxObj['ref_no'],
			'amount'=>$trnxObj['amount'],
			'esp'=>$trnxObj['esp'],
			'account_id'=>$trnxObj['account_id'],
			'transac_date'=>$trnxObj['transac_date'],
			'transac_time'=>$transac_time,
			'cashier'=>$trnxObj['cashier']
		);

		// Prepate TransactionDetails object
		$TDtl = array();
		if(isset($trnxObj['details'])):
			$details = $trnxObj['details'];
			foreach($details as $d):
				$dtl = array(
					'transaction_type_id'=>$d['id'],
					'details'=>$d['description'],
					'amount'=>$d['amount']
				);
				$TDtl[]=$dtl;
			endforeach;
		endif;

		// Prepate TransactionPayments object
		$TPay = array();
		switch($trnxObj['pay_type']){
			case 'CASH':
				$pay = array(
					'payment_method_id'=>'CASH',
					'details'=>'Cash',
					'amount'=>$trnxObj['amount']
				);
				$TPay[]=$pay;
			break;
			case 'CHCK':
				$validOn = date('Y-m-d',strtotime($trnxObj['pay_date']));
				$pay = array(
					'payment_method_id'=>'CHCK',
					'details'=>$trnxObj['pay_details'],
					'valid_on'=>$validOn,
					'amount'=>$trnxObj['amount']
				);
				$TPay[]=$pay;
			break;
		}
		

		// Build complete Transaction object
		$TObj = array(
				'Transaction'=>$TObj,
				'TransactionDetail'=>$TDtl,
				'TransactionPayment'=>$TPay,
			);

		$TObj['is_valid'] = true;
		
		return $TObj;
	}
}