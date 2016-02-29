<?php
/* PaymentMethods Test cases generated on: 2016-02-29 08:23:48 : 1456730628*/
App::import('Controller', 'PaymentMethods');

class TestPaymentMethodsController extends PaymentMethodsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PaymentMethodsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.payment_method');

	function startTest() {
		$this->PaymentMethods =& new TestPaymentMethodsController();
		$this->PaymentMethods->constructClasses();
	}

	function endTest() {
		unset($this->PaymentMethods);
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
