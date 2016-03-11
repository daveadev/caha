<?php
/* TransactionType Test cases generated on: 2016-03-09 07:14:00 : 1457504040*/
App::import('Model', 'TransactionType');

class TransactionTypeTestCase extends CakeTestCase {
	var $fixtures = array('app.transaction_type', 'app.transaction_payment', 'app.transaction', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction_detail');

	function startTest() {
		$this->TransactionType =& ClassRegistry::init('TransactionType');
	}

	function endTest() {
		unset($this->TransactionType);
		ClassRegistry::flush();
	}

}
