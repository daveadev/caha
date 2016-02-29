<?php
/* Ledgers Test cases generated on: 2016-02-29 08:23:48 : 1456730628*/
App::import('Controller', 'Ledgers');

class TestLedgersController extends LedgersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class LedgersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.ledger', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.transaction', 'app.transaction_detail', 'app.transaction_payment', 'app.transaction_type');

	function startTest() {
		$this->Ledgers =& new TestLedgersController();
		$this->Ledgers->constructClasses();
	}

	function endTest() {
		unset($this->Ledgers);
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
