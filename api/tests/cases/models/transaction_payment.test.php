<?php
/* TransactionPayment Test cases generated on: 2016-02-29 08:23:39 : 1456730619*/
App::import('Model', 'TransactionPayment');

class TransactionPaymentTestCase extends CakeTestCase {
	var $fixtures = array('app.transaction_payment', 'app.transaction', 'app.transaction_type');

	function startTest() {
		$this->TransactionPayment =& ClassRegistry::init('TransactionPayment');
	}

	function endTest() {
		unset($this->TransactionPayment);
		ClassRegistry::flush();
	}

}
