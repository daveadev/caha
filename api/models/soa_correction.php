<?php
class SoaCorrection extends AppModel {
	var $name = 'SoaCorrection';

	function log($esp,$type,$statement,$user){
		$logObj = array();
		$logObj['account_id']=$statement['account']['id'];
		$logObj['username']=$user;
		$logObj['esp']=$esp;
		$logObj['correction']=$type;
		$logObj['details']=json_encode($statement);
		$logObj['hash']=md5($logObj['details']);
		$this->create();
		$this->save($logObj);
		$logObj['id']=$this->id;
		return $logObj;
	}
}