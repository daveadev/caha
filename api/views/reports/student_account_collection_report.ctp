<?php
App::import('Vendor','collections/student_account_collection_report');

$chunk_data = array_chunk($data['data'][0]['collections'],34,true);
pr($data);exit;
$total_page = count($chunk_data);
$i = 1;

$pr= new StudentAccountCollection();
foreach($chunk_data as $k=>$dt){
	$pr->hdr();
	$pr->data($dt,$total_page,$i);
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
}
$pr->output();
?>

