<?php
/* Account Test cases generated on: 2016-02-29 08:23:34 : 1456730614*/
App::import('Model', 'Account');

class AccountTestCase extends CakeTestCase {
	var $fixtures = array('app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction');

	function startTest() {
		$this->Account =& ClassRegistry::init('Account');
	}

	function endTest() {
		unset($this->Account);
		ClassRegistry::flush();
	}

}
