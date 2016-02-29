<?php
/* AccountFees Test cases generated on: 2016-02-29 08:23:47 : 1456730627*/
App::import('Controller', 'AccountFees');

class TestAccountFeesController extends AccountFeesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AccountFeesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.account_fee', 'app.account', 'app.account_adjustment', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction', 'app.transaction_detail', 'app.transaction_payment', 'app.transaction_type', 'app.fee');

	function startTest() {
		$this->AccountFees =& new TestAccountFeesController();
		$this->AccountFees->constructClasses();
	}

	function endTest() {
		unset($this->AccountFees);
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
