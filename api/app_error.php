<?php
class AppError extends ErrorHandler {
	var $CODES = array(
			202=>'No results found for %s',
			403=>'Invalid Endpoint',
			402=>'Data not set',
			404=>'Empty Record',
	);
	function invalidEndpoint($params){
		$code = 403;
		$message = $this->CODES[$code];
		$this->fetchError($code,$message);
	}
	function dataNotSet($params){
		$code = 402;
		$message = $this->CODES[$code];
		$this->fetchError($code,$message);
	}
	function emptyRecord($params){
		$code = 404;
		$message = $this->CODES[$code];
		$this->fetchError($code,$message);
	}
	function noResults($params){
		$code =202;
		$message = sprintf($this->CODES[$code],$params['keyword']);
		$this->fetchError($code,$message);
	}
	protected function fetchError($code,$message){
		$this->controller->header('HTTP/1.1  '.$code.' '.$message);
		$this->controller->set(compact('code','message'));
		$this->controller->layout='json/default';
		$this->_outputMessage('json/default');
	}
	
}
?>