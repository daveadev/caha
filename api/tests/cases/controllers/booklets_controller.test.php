<?php
/* Booklets Test cases generated on: 2016-02-29 08:23:48 : 1456730628*/
App::import('Controller', 'Booklets');

class TestBookletsController extends BookletsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class BookletsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.booklet');

	function startTest() {
		$this->Booklets =& new TestBookletsController();
		$this->Booklets->constructClasses();
	}

	function endTest() {
		unset($this->Booklets);
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
