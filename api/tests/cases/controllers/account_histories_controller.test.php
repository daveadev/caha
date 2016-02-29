<?php
/* AccountHistories Test cases generated on: 2016-02-29 08:23:47 : 1456730627*/
App::import('Controller', 'AccountHistories');

class TestAccountHistoriesController extends AccountHistoriesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AccountHistoriesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.account_history', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_schedule', 'app.ledger', 'app.transaction', 'app.transaction_detail', 'app.transaction_payment', 'app.transaction_type');

	function startTest() {
		$this->AccountHistories =& new TestAccountHistoriesController();
		$this->AccountHistories->constructClasses();
	}

	function endTest() {
		unset($this->AccountHistories);
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
