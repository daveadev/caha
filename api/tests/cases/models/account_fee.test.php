<?php
/* AccountFee Test cases generated on: 2016-02-29 08:23:32 : 1456730612*/
App::import('Model', 'AccountFee');

class AccountFeeTestCase extends CakeTestCase {
	var $fixtures = array('app.account_fee', 'app.account', 'app.account_adjustment', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction', 'app.fee');

	function startTest() {
		$this->AccountFee =& ClassRegistry::init('AccountFee');
	}

	function endTest() {
		unset($this->AccountFee);
		ClassRegistry::flush();
	}

}
