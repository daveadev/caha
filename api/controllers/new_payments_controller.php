<?php
class NewPaymentsController extends AppController {

	var $name = 'NewPayments';
	var $uses = array('NewPayment','Transaction');

	function add(){
		pr($this->data);exit;
		$cashier = $this->Auth->user()['User']['username'];

		$this->NewPayment->prepareTrnx($bkl_id,$ref_no,$amount,$esp,$account_id,$transac_date ,$cashier);
	}

}