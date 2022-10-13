<?php
App::import('Vendor','collections/cashier_daily_collections');

$chunk_data = array_chunk($data['data'][0]['collections'],28,true);
//pr($chunk_data);exit;
$total_page = count($chunk_data);
$i = 1;

$pr= new CashierDailyCollections();

foreach($chunk_data as $dt){
	//pr($data['breakdowns']); exit();
	$pr->hdr($data['data'][0]);
	$pr->data($data['data'][0],$dt,$total_page,$i,$data['breakdowns'][0]);
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
}


$pr->output();
?>

