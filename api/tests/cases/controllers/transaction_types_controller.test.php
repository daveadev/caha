<?php
/* TransactionTypes Test cases generated on: 2016-03-09 07:14:15 : 1457504055*/
App::import('Controller', 'TransactionTypes');

class TestTransactionTypesController extends TransactionTypesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class TransactionTypesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.transaction_type', 'app.transaction_payment', 'app.transaction', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction_detail');

	function startTest() {
		$this->TransactionTypes =& new TestTransactionTypesController();
		$this->TransactionTypes->constructClasses();
	}

	function endTest() {
		unset($this->TransactionTypes);
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
