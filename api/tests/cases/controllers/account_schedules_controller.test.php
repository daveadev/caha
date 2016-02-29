<?php
/* AccountSchedules Test cases generated on: 2016-02-29 08:23:47 : 1456730627*/
App::import('Controller', 'AccountSchedules');

class TestAccountSchedulesController extends AccountSchedulesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AccountSchedulesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.account_schedule', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.account_history', 'app.ledger', 'app.transaction', 'app.transaction_detail', 'app.transaction_payment', 'app.transaction_type');

	function startTest() {
		$this->AccountSchedules =& new TestAccountSchedulesController();
		$this->AccountSchedules->constructClasses();
	}

	function endTest() {
		unset($this->AccountSchedules);
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
