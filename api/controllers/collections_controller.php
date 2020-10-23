<?php
class CollectionsController extends AppController {

	var $name = 'Collections';
	var $uses = array('Ledger','AccountSchedule','Account');
	
	function index() {
		
		$type = $_GET['type'];
		$from = $_GET['from'];
		$to = $_GET['to'];
		
		$esp = $_GET['esp'];
		$rcvd = array('INIPY','SBQPY');
		$total = 'TUIXN';
		$cond_collect = array('type'=>'-','transaction_type_id'=>$rcvd,'esp'=>$esp,'and'=>array('transac_date <='=>$to,'transac_date >='=>$from));
		$cond_total = array('type'=>'+','transaction_type_id'=>$total,'esp'=>$esp);
		$collections = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>$cond_collect));
		$total = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>$cond_total));
		
		//pr($cond_collect);
		//pr($collections); exit();
		
		$total_rcvbl = 0;
		$total_collections = 0;
		$total_subs = 0;
		foreach($total as $i=>$t){
			$amount = $t['Account']['assessment_total'];
			$total_rcvbl += $amount;
		}
		foreach($total as $i=>$t){
			$amount = $t['Account']['discount_amount'];
			$total_subs += $amount;
		}
		
		$collection_data = array();
		foreach($collections as $i=>$t){
			$amount = $t['Ledger']['amount'];
			$led = $t['Ledger'];
			$total_collections += $amount;
			if($type=='month'){
				$date = explode('-',$led['transac_date']);
				switch($date[1]){
					case '01': $month = 'Jan '; break;
					case '02': $month = 'Feb '; break;
					case '03': $month = 'Mar '; break;
					case '04': $month = 'Apr '; break;
					case '05': $month = 'May '; break;
					case '06': $month = 'Jun '; break;
					case '07': $month = 'Jul '; break;
					case '08': $month = 'Aug '; break;
					case '09': $month = 'Sep '; break;
					case '10': $month = 'Oct '; break;
					case '11': $month = 'Nov '; break;
					case '12': $month = 'Dec '; break;
				}
				$mo = $month.$date[0];
				if(!isset($collection_data[$mo]))
					$collection_data[$mo] = 0;
				$collection_data[$mo] += $amount;
			}else{
				$mo = $led['transac_date'];
				if(!isset($collection_data[$mo]))
					$collection_data[$mo] = 0;
				$collection_data[$mo] += $amount;
			}
		}
		//pr($collection_data); exit();
		$monthly_collections = array();
		foreach($collection_data as $i=>$data){
			if($type=='month')
				$coll = array('month'=>$i,'details'=>'cash','collection'=>$data,'balance'=>$total_rcvbl-($total_subs+$data));
			else
				$coll = array('date'=>$i,'day'=>date('D', strtotime($i)),'description'=>'cash','collection'=>$data,'balance'=>$total_rcvbl-($total_subs+$data));
			array_push($monthly_collections,$coll);
			
		}
		//exit();
		
		$annual_collections = array(
			'total_receivables'=>$total_rcvbl,
			'total_subsidies'=>$total_subs,
			'collection_forwarded'=>$total_collections,
			'net_receivables'=>$total_rcvbl-($total_subs+$total_collections),
			'monthly_collections'=>$monthly_collections
		);
		//exit();
		$annual_collections = array(array('Collection'=>$annual_collections));
		$this->set('collections',$annual_collections);
	}

}