<?php
/* Fees Test cases generated on: 2016-02-29 08:23:48 : 1456730628*/
App::import('Controller', 'Fees');

class TestFeesController extends FeesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FeesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.fee', 'app.account', 'app.account_adjustment', 'app.account_fee', 'app.account_history', 'app.account_schedule', 'app.ledger', 'app.transaction', 'app.transaction_detail', 'app.transaction_payment', 'app.transaction_type');

	function startTest() {
		$this->Fees =& new TestFeesController();
		$this->Fees->constructClasses();
	}

	function endTest() {
		unset($this->Fees);
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
