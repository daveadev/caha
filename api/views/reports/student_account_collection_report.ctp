<?php
App::import('Vendor','collections/student_account_collection_report');

$chunk_data = array_chunk($data['data'][0]['collections'],34,true);
$thdr = $data['data'][0]['columns'];
//pr(isset($data['data'][0]));exit;
$total_page = count($chunk_data);
$i = 1;

$pr= new StudentAccountCollection();
foreach($chunk_data as $k=>$dt){
	$pr->hdr();
	//pr($data['data'][0]);exit;
	if($data['data'][0]['hidden']){
		$pr->hidden_balance($dt,$thdr,$total_page,$i);
	}else{
		$pr->data($dt,$thdr,$total_page,$i);
	}
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
}
$pr->output();
?>

