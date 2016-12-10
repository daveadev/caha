<?php
/* AccountTransaction Test cases generated on: 2016-03-09 06:56:18 : 1457502978*/
App::import('Model', 'AccountTransaction');

class AccountTransactionTestCase extends CakeTestCase {
	var $fixtures = array('app.account_transaction', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction', 'app.transaction_detail', 'app.transaction_payment', 'app.transaction_type');

	function startTest() {
		$this->AccountTransaction =& ClassRegistry::init('AccountTransaction');
	}

	function endTest() {
		unset($this->AccountTransaction);
		ClassRegistry::flush();
	}

}
