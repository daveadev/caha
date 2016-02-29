<?php
/* TransactionType Test cases generated on: 2016-02-29 08:23:40 : 1456730620*/
App::import('Model', 'TransactionType');

class TransactionTypeTestCase extends CakeTestCase {
	var $fixtures = array('app.transaction_type', 'app.transaction_payment', 'app.transaction');

	function startTest() {
		$this->TransactionType =& ClassRegistry::init('TransactionType');
	}

	function endTest() {
		unset($this->TransactionType);
		ClassRegistry::flush();
	}

}
