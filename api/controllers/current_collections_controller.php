<?php
class CurrentCollectionsController extends AppController {

	var $name = 'CurrentCollections';
	var $uses = array('CurrentCollection','Ledger','DailyTotalCollection');
	
	function index() {
		
		$type = $_GET['type'];
		$from = $_GET['from'];
		$to = $_GET['to'];
		$esp = $_GET['esp'];
		
		$date_diff=strtotime($to)-strtotime($from);
		$date_diff = round($date_diff / (60 * 60 * 24))+1;
		$getFirst = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.transaction_type_id'=>'TUIXN','Ledger.esp'=>$esp),'order'=>'Ledger.transac_date'));
		$cut_off = $getFirst[0]['Ledger']['transac_date'];
		$rcvd = array('INIPY','SBQPY','FULLP','RSRVE','ADVPT');
		$cond_collect = array('type'=>'-','transaction_type_id'=>$rcvd,'esp'=>$esp,'AND'=>array('transac_date <'=>date('Y-m-d',strtotime($from)),'transac_date >='=>date('Y-m-d',strtotime($cut_off))));
		
		
		//GET FORWARDED AMOUNT BEFORE THE START DATE
		$forwarded = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>$cond_collect));
		$total_forwarded = 0;
		foreach($forwarded as $f){
			$total_forwarded+=$f['Ledger']['amount'];
		}
		
		//GET TOTAL RECEIVABLES
		$receivables = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.esp'=>$esp,'Ledger.transaction_type_id'=>'TUIXN')));
		$total_receivables = 0;
		foreach($receivables as $r){
			$total_receivables+=$r['Ledger']['amount'];
		}
		
		//GET TOTAL SUBSIDIES
		$subsidies = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.esp'=>$esp,'Ledger.transaction_type_id'=>array('DSESC','DSPUB'))));
		$total_subsidies = 0;
		foreach($subsidies as $s){
			$total_subsidies+=$s['Ledger']['amount'];
		}
		
		//GET BREAKDOWNS
		$breakdowns = $this->DailyTotalCollection->find('all',array('recursive'=>0,
																	'conditions'=>
																		array('AND'=>array('DailyTotalCollection.date >= '=>date('Y-m-d',strtotime($from)),
																							'DailyTotalCollection.date <= '=>date('Y-m-d',strtotime($to)))),
																	'order'=>'DailyTotalCollection.date'));
		//pr($breakdowns); exit();
		$total_collection = 0;
		foreach($breakdowns as $i=>$b){
			$b = $b['DailyTotalCollection'];
			$b['day'] = date('D', strtotime($b['date']));
			$total_collection+=$b['total'];
			$breakdowns[$i] = $b;
		}
		$daily_collections = array(
			'TotalReceivables'=>$total_receivables,
			'TotalSubsidies'=>$total_subsidies,
			'NetReceivables'=>$total_receivables-$total_subsidies,
			'Forwarded'=>$total_forwarded,
			'Collection'=>$total_collection,
			'BreakDowns'=>$breakdowns
			);
		$this->set('currentCollections',array(array('CurrentCollection'=>$daily_collections)));
		/* pr($breakdowns);
		pr($total_receivables); 
		pr($total_subsidies); 
		exit(); */
		
	}

}