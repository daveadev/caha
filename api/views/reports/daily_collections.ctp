<?php
App::import('Vendor','collections/daily_collections');

$chunk_data = array_chunk($data['data'][0]['BreakDowns'],38,true);
$total_page = count($chunk_data);
$i = 1;

$pr= new DailyCollections();

foreach($chunk_data as $dt){
	$pr->hdr($data['data'][0]);
	$pr->data($data['data'][0],$dt,$total_page,$i);
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
}




$pr->output();
?>

