<?php
App::import('Vendor','statement/account');
$conf = array('config'=>'soa_sy24_1q.json');
$AS= new AccountStatement($conf);
foreach($statements as $sInd=>$sObj):
	$AS->data = $sObj;
	$AS->headerInfo();
	$AS->paysched($type);
	$AS->ledger($type);
	$AS->payment_ins();
	$AS->reply_slip($type);
	$ASFile= new AccountStatement($conf);	
	$ASFile->data = $sObj;
	$ASFile->headerInfo();
	$ASFile->paysched($type);
	$ASFile->ledger($type);
	$ASFile->payment_ins();
	$ASFile->reply_slip($type);
	$sno = $sObj['account']['sno'];
	$billMonth = '07-SEP-2024';
	$studName = $sObj['student']['full_name'];
	$fileName = sprintf('%s-%s-%s.pdf',$sno,$billMonth,$studName);
	$fileName = APP.DS.'reports'.DS.$fileName;
	$ASFile->output($fileName,'F');

	if($sInd<count($statements)-1):
		$AS->createSheet();
	endif;
endforeach;
$AS->output();