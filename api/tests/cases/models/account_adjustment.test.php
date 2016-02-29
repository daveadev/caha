<?php
/* AccountAdjustment Test cases generated on: 2016-02-29 08:23:31 : 1456730611*/
App::import('Model', 'AccountAdjustment');

class AccountAdjustmentTestCase extends CakeTestCase {
	var $fixtures = array('app.account_adjustment', 'app.account', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction');

	function startTest() {
		$this->AccountAdjustment =& ClassRegistry::init('AccountAdjustment');
	}

	function endTest() {
		unset($this->AccountAdjustment);
		ClassRegistry::flush();
	}

}
