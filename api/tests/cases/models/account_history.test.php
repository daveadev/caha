<?php
/* AccountHistory Test cases generated on: 2016-02-29 08:23:33 : 1456730613*/
App::import('Model', 'AccountHistory');

class AccountHistoryTestCase extends CakeTestCase {
	var $fixtures = array('app.account_history', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_schedule', 'app.ledger', 'app.transaction');

	function startTest() {
		$this->AccountHistory =& ClassRegistry::init('AccountHistory');
	}

	function endTest() {
		unset($this->AccountHistory);
		ClassRegistry::flush();
	}

}
