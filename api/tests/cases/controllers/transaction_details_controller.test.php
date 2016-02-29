<?php
/* TransactionDetails Test cases generated on: 2016-02-29 08:23:49 : 1456730629*/
App::import('Controller', 'TransactionDetails');

class TestTransactionDetailsController extends TransactionDetailsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class TransactionDetailsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.transaction_detail', 'app.transaction', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction_payment', 'app.transaction_type');

	function startTest() {
		$this->TransactionDetails =& new TestTransactionDetailsController();
		$this->TransactionDetails->constructClasses();
	}

	function endTest() {
		unset($this->TransactionDetails);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
