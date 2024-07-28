<?php
/* YearLevels Test cases generated on: 2024-07-28 22:52:52 : 1722178372*/
App::import('Controller', 'YearLevels');

class TestYearLevelsController extends YearLevelsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class YearLevelsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.year_level', 'app.student', 'app.section', 'app.program', 'app.account', 'app.inquiry', 'app.cashier_collection', 'app.booklet', 'app.cashier', 'app.account_history', 'app.transaction_detail', 'app.transaction', 'app.transaction_payment', 'app.transaction_type', 'app.account_schedule', 'app.assessment_paysched', 'app.assessment', 'app.assessment_fee', 'app.assessment_subject', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.ledger', 'app.account_transaction');

	function startTest() {
		$this->YearLevels =& new TestYearLevelsController();
		$this->YearLevels->constructClasses();
	}

	function endTest() {
		unset($this->YearLevels);
		ClassRegistry::flush();
	}

}
