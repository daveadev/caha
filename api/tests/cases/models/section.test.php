<?php
/* Section Test cases generated on: 2024-07-28 22:58:36 : 1722178716*/
App::import('Model', 'Section');

class SectionTestCase extends CakeTestCase {
	var $fixtures = array('app.section', 'app.department', 'app.program', 'app.subject', 'app.year_level', 'app.student', 'app.account', 'app.inquiry', 'app.cashier_collection', 'app.booklet', 'app.cashier', 'app.account_history', 'app.transaction_detail', 'app.transaction', 'app.transaction_payment', 'app.transaction_type', 'app.account_schedule', 'app.assessment_paysched', 'app.assessment', 'app.assessment_fee', 'app.assessment_subject', 'app.account_adjustment', 'app.account_fee', 'app.fee', 'app.ledger', 'app.account_transaction', 'app.classlist_block', 'app.classlist_irregular');

	function startTest() {
		$this->Section =& ClassRegistry::init('Section');
	}

	function endTest() {
		unset($this->Section);
		ClassRegistry::flush();
	}

}
