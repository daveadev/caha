<?php
/* Fee Test cases generated on: 2016-02-29 08:23:36 : 1456730616*/
App::import('Model', 'Fee');

class FeeTestCase extends CakeTestCase {
	var $fixtures = array('app.fee', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction');

	function startTest() {
		$this->Fee =& ClassRegistry::init('Fee');
	}

	function endTest() {
		unset($this->Fee);
		ClassRegistry::flush();
	}

}
