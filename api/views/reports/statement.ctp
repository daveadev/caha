<?php
App::import('Vendor','statement/account');
$conf = array('config'=>'soa_sy23_1q2.json');
$AS= new AccountStatement($conf);
foreach($statements as $sInd=>$sObj):
	$AS->data = $sObj;
	$AS->headerInfo();
	$AS->paysched($type);
	$AS->payment_ins();
	$AS->ledger($type);
	
	if($sInd<count($statements)-1)
		$AS->createSheet();
endforeach;
$AS->output();