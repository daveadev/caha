<?php
App::import('Vendor','statement/reminder');
$conf = array('config'=>'soa_sy23_1q.json');
$AS= new AccountReminder($conf);
foreach($statements as $sInd=>$sObj):
	$AS->data = $sObj;
	$AS->headerInfo();
	$AS->reminder();
	
	if($sInd<count($statements)-1)
		$AS->createSheet();
endforeach;
$AS->output();