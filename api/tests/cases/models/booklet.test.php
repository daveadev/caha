<?php
/* Booklet Test cases generated on: 2016-02-29 08:23:35 : 1456730615*/
App::import('Model', 'Booklet');

class BookletTestCase extends CakeTestCase {
	var $fixtures = array('app.booklet');

	function startTest() {
		$this->Booklet =& ClassRegistry::init('Booklet');
	}

	function endTest() {
		unset($this->Booklet);
		ClassRegistry::flush();
	}

}
