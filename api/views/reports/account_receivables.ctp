<?php
App::import('Vendor','account_receivables');

$chunk_data = array_chunk($data['ARC'],38,true);
$total_page = count($chunk_data);
$i = 1;

//pr($chunk_data); exit();
$pr= new AccountReceivables();

foreach($chunk_data as $dt){
	$pr->hdr($data['totals']);
	$pr->data($data,$dt,$total_page,$i);
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
}

$pr->output();
?>

