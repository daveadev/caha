<?php
App::import('Vendor','daily_remittance');

//$chunk_data = array_chunk($data['data'][0]['collections'],34,true);
//pr($data);exit;
//$total_page = count($chunk_data);
//$i = 1;

$pr= new DailyRemittance();
$pr->hdr();
$pr->series();
$pr->cash_breakdown();
/* foreach($chunk_data as $k=>$dt){
	
	$pr->data($dt,$total_page,$i);
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
} */
$pr->output();
?>

