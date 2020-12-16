<?php
App::import('Vendor','collections/student_account_collection_report');

$chunk_data = array_chunk($data['data'][0]['collections'],34,true);
$thdr = $data['data'][0]['columns'];
//pr($data['data'][0]['columns']);exit;
$total_page = count($chunk_data);
$i = 1;

$pr= new StudentAccountCollection();
foreach($chunk_data as $k=>$dt){
	$pr->hdr();
	if(!$data['data'][0]['hidden']){
		$pr->data($dt,$thdr,$total_page,$i);
	}else{
		//$pr->hidden_balance($dt,$thdr,$total_page,$i);
	}
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
}
$pr->output();
?>

