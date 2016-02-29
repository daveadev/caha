<?php
/* PaymentMethod Test cases generated on: 2016-02-29 08:23:38 : 1456730618*/
App::import('Model', 'PaymentMethod');

class PaymentMethodTestCase extends CakeTestCase {
	var $fixtures = array('app.payment_method');

	function startTest() {
		$this->PaymentMethod =& ClassRegistry::init('PaymentMethod');
	}

	function endTest() {
		unset($this->PaymentMethod);
		ClassRegistry::flush();
	}

}
