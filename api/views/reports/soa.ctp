<?php
App::import('Vendor','soa');

//pr($data);exit;

$chunk_data = array_chunk($data,50,true);
$total_page = count($chunk_data);
$i = 1;

$pr= new SOA();

foreach($chunk_data as $dt){
	$pr->ledger($student,$dt,$total_page,$i);
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
}


$pr->output();
?>

