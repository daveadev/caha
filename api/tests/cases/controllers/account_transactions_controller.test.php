<?php
/* AccountTransactions Test cases generated on: 2016-03-09 06:56:32 : 1457502992*/
App::import('Controller', 'AccountTransactions');

class TestAccountTransactionsController extends AccountTransactionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AccountTransactionsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.account_transaction', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction', 'app.transaction_detail', 'app.transaction_payment', 'app.transaction_type');

	function startTest() {
		$this->AccountTransactions =& new TestAccountTransactionsController();
		$this->AccountTransactions->constructClasses();
	}

	function endTest() {
		unset($this->AccountTransactions);
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
