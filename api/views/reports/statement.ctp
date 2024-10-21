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

	if($createFile):
		$ASFile= new AccountStatement($conf);	
		$ASFile->data = $sObj;
		$ASFile->headerInfo();
		$ASFile->paysched($type);
		$ASFile->ledger($type);
		$ASFile->payment_ins();
		$ASFile->reply_slip($type);
		$sno = $sObj['account']['sno'];
		$billMonth = strtoupper(str_replace(' ','-',$sObj['account']['due_now']['date']));
		$studName = strtoupper($sObj['student']['full_name']);
		$fileName = sprintf('%s-%s-%s.pdf',$sno,$billMonth,$studName);
		$fileName = APP.DS.'reports'.DS.$fileName;
		$ASFile->output($fileName,'F');
	endif;

	if($sInd<count($statements)-1):
		$AS->createSheet();
	endif;
endforeach;
$AS->output();