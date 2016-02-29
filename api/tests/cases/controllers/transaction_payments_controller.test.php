<?php
/* TransactionPayments Test cases generated on: 2016-02-29 08:23:49 : 1456730629*/
App::import('Controller', 'TransactionPayments');

class TestTransactionPaymentsController extends TransactionPaymentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class TransactionPaymentsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.transaction_payment', 'app.transaction', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction_detail', 'app.transaction_type');

	function startTest() {
		$this->TransactionPayments =& new TestTransactionPaymentsController();
		$this->TransactionPayments->constructClasses();
	}

	function endTest() {
		unset($this->TransactionPayments);
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
