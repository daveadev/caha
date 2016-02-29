<?php
/* TransactionDetail Test cases generated on: 2016-02-29 08:23:39 : 1456730619*/
App::import('Model', 'TransactionDetail');

class TransactionDetailTestCase extends CakeTestCase {
	var $fixtures = array('app.transaction_detail', 'app.transaction');

	function startTest() {
		$this->TransactionDetail =& ClassRegistry::init('TransactionDetail');
	}

	function endTest() {
		unset($this->TransactionDetail);
		ClassRegistry::flush();
	}

}
