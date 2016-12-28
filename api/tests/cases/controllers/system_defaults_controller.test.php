<?php
/* SystemDefaults Test cases generated on: 2016-02-29 10:25:45 : 1456737945*/
App::import('Controller', 'SystemDefaults');

class TestSystemDefaultsController extends SystemDefaultsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SystemDefaultsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.system_default');

	function startTest() {
		$this->SystemDefaults =& new TestSystemDefaultsController();
		$this->SystemDefaults->constructClasses();
	}

	function endTest() {
		unset($this->SystemDefaults);
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
