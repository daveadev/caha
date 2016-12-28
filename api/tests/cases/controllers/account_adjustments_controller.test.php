<?php
/* AccountAdjustments Test cases generated on: 2016-02-29 08:23:47 : 1456730627*/
App::import('Controller', 'AccountAdjustments');

class TestAccountAdjustmentsController extends AccountAdjustmentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AccountAdjustmentsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.account_adjustment', 'app.account', 'app.account_fee', 'app.fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction', 'app.transaction_detail', 'app.transaction_payment', 'app.transaction_type');

	function startTest() {
		$this->AccountAdjustments =& new TestAccountAdjustmentsController();
		$this->AccountAdjustments->constructClasses();
	}

	function endTest() {
		unset($this->AccountAdjustments);
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
