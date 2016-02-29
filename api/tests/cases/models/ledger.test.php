<?php
/* Ledger Test cases generated on: 2016-02-29 08:23:37 : 1456730617*/
App::import('Model', 'Ledger');

class LedgerTestCase extends CakeTestCase {
	var $fixtures = array('app.ledger', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.transaction');

	function startTest() {
		$this->Ledger =& ClassRegistry::init('Ledger');
	}

	function endTest() {
		unset($this->Ledger);
		ClassRegistry::flush();
	}

}
