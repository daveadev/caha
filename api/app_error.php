<?php
App::import('Core','Api.ApiAppError');
class AppError extends ApiAppError {
	public function __construct($controller = null, $method = null) {
		$this->CODES['406'] ='Duplicate Ref No %s, already used.';
        parent::__construct($controller, $method);
    }
	function duplicateRefNo($params){
		$code = 406;
		$message = sprintf($this->CODES[$code],$params['ref_no']);
		$this->fetchError($code,$message);
	}
}
?>