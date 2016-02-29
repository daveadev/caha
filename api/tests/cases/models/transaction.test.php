<?php
/* Transaction Test cases generated on: 2016-02-29 08:23:41 : 1456730621*/
App::import('Model', 'Transaction');

class TransactionTestCase extends CakeTestCase {
	var $fixtures = array('app.transaction', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction_detail', 'app.transaction_payment', 'app.transaction_type');

	function startTest() {
		$this->Transaction =& ClassRegistry::init('Transaction');
	}

	function endTest() {
		unset($this->Transaction);
		ClassRegistry::flush();
	}

}
