<?php
class CurrentCollectionsController extends AppController {

	var $name = 'Collections';
	var $uses = array('Ledger','AccountSchedule','Account');
	
	function index() {
		
		$type = $_GET['type'];
		$from = $_GET['from'];
		$to = $_GET['to'];
		$esp = $_GET['esp'];
		
		$date_diff=strtotime($to)-strtotime($from);
		$date_diff = round($date_diff / (60 * 60 * 24))+1;
		$getFirst = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.transaction_type_id'=>'TUIXN','Ledger.esp'=>$esp),'order'=>'Ledger.transac_date'));
		//pr($getFirst); exit();
		$cut_off = $getFirst[0]['Ledger']['transac_date'];
		
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
		
		//GET TOTAL VOUCHERS
		$vouchers = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.esp'=>$esp,'OR'=>array('Ledger.details LIKE '=>'%LV%','Ledger.details LIKE'=>'%FAV%'))));
		$total_vouchers = 0;
		foreach($vouchers as $v){
			$total_vouchers+=$s['Ledger']['amount'];
		}
		pr($total_vouchers); 
		pr($total_receivables); 
		pr($total_subsidies); 
		exit();
		
	}

}