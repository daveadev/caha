<?php
class SoaCorrection extends AppModel {
	var $name = 'SoaCorrection';

	function log($esp,$type,$statement,$user){
		$logObj = array();
		$logObj['details']=json_encode($statement);
		$hash = md5($logObj['details']);
		$isExist = $this->findByHash($hash);
		
		if($isExist) return $logObj;

		
		$logObj['account_id']=$statement['account']['id'];
		$logObj['username']=$user;
		$logObj['esp']=$esp;
		$logObj['correction']=$type;
		$logObj['hash']=$hash;
		$this->create();
		$this->save($logObj);
		$logObj['id']=$this->id;
		return $logObj;
	}
}