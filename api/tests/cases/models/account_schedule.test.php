<?php
/* AccountSchedule Test cases generated on: 2016-02-29 08:23:33 : 1456730613*/
App::import('Model', 'AccountSchedule');

class AccountScheduleTestCase extends CakeTestCase {
	var $fixtures = array('app.account_schedule', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.ledger', 'app.transaction');

	function startTest() {
		$this->AccountSchedule =& ClassRegistry::init('AccountSchedule');
	}

	function endTest() {
		unset($this->AccountSchedule);
		ClassRegistry::flush();
	}

}
