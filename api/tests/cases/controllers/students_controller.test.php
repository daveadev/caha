<?php
/* Students Test cases generated on: 2016-02-29 10:20:17 : 1456737617*/
App::import('Controller', 'Students');

class TestStudentsController extends StudentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class StudentsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.student', 'app.educ_level', 'app.year_level');

	function startTest() {
		$this->Students =& new TestStudentsController();
		$this->Students->constructClasses();
	}

	function endTest() {
		unset($this->Students);
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
