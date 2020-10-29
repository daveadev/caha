<?php
App::import('Vendor','cashier_daily_collections');

$chunk_data = array_chunk($data[0]['collections'],5,true);
//pr($chunk_data);exit;
$total_page = count($chunk_data);
$i = 1;

$pr= new CashierDailyCollections();

foreach($chunk_data as $dt){
	$pr->hdr($data[0]);
	$pr->data($data[0],$dt,$total_page,$i);
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
}


$pr->output();
?>

