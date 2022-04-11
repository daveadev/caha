<?php
App::import('Vendor','soa');

$pr= new SOA();
if(!isset($batch)){
	$chunk_data = array_chunk($data,50,true);
	$total_page = count($chunk_data);
	$i = 1;


	foreach($chunk_data as $dt){
		//pr($chunk_data); exit();
		$pr->ledger($student,$dt,$total_page,$i);
		if(count($chunk_data) != ($i++)){
			$pr->createSheet();
		}
	}


	$pr->output();
}else{
	foreach($batch as $index=>$item){
		//pr($index);
		$total_page = count($batch);

		$pr->ledger($item,$item,$total_page,$index+1);
		if($index+1!=count($batch))
			$pr->createSheet();
		
	}
	$pr->output();
	//pr($batch); exit();
}
?>

