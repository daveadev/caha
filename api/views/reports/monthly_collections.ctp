<?php
App::import('Vendor','monthly_collections');

$chunk_data = array_chunk($data['data'][0]['collections'],2,true);
pr($data['data'][0]);exit;
$total_page = count($chunk_data);
$i = 1;

$pr= new MonthlyCollections();

foreach($chunk_data as $dt){
	$pr->hdr($data['data'][0]);
	$pr->data($data['data'][0],$dt,$total_page,$i);
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
}




$pr->output();
?>

