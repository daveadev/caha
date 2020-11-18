<?php
App::import('Vendor','collections/student_account_collection_report');

$chunk_data = array_chunk($data['data'][0]['collections'],34,true);
//pr($chunk_data);exit;
$total_page = count($chunk_data);
$i = 1;

$pr= new StudentAccountCollection();

foreach($chunk_data as $k=>$dt){
	$col_page = 1;

	$pr->hdr();
	$pr->data($dt,$total_page,$i);
	
	//$payments = array_chunk($dt[0]['payments'],4,true);
	$first_page =true;
	
	foreach($dt as $d){
			
		if($col_page==1){
			$payments = array_chunk($d['payments'],4,true);
			$col_page++;
		}else{
			$payments = array_chunk($d['payments'],14,true);
		}
	
		foreach($payments as $p){
			$pr->first_page_payment($p);
		}
		
		
		//pr($d['payments']);exit;
	}
	if(count($chunk_data) != ($i++)){
		$pr->createSheet();
	}
}


$pr->output();
?>

