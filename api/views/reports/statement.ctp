<?php
App::import('Vendor','statement/account');
$conf = array('config'=>'soa_sy23_1q.json');
$AS= new AccountStatement($conf);
foreach($statements as $sInd=>$sObj):
	$AS->data = $sObj;
	$AS->headerInfo();
	$AS->paysched($type);
	$AS->ledger($type);
	$AS->payment_ins();
	if($sInd<count($statements)-1)
		$AS->createSheet();
endforeach;
$AS->output();