<?php
class CollectionsController extends AppController {

	var $name = 'Collections';
	var $uses = array('Ledger','AccountSchedule','Account');
	
	function index() {
		
		$type = $_GET['type'];
		$from = $_GET['from'];
		$to = $_GET['to'];
		
		$date_diff=strtotime($to)-strtotime($from);
		$date_diff = round($date_diff / (60 * 60 * 24))+1;
		$cut_off = '2021-06-01';
		//pr($cut_off); exit();
		
		$esp = $_GET['esp'];
		$rcvd = array('INIPY','SBQPY','FULLP','RSRVE','ADVPT');
		$total = 'TUIXN';
		$date_range = array('transac_date <='=>$to,'transac_date >='=>$from);
		$cond_collect = array('type'=>'-','transaction_type_id'=>$rcvd,'esp'=>$esp,'AND'=>array('transac_date <'=>date('Y-m-d',strtotime($from)),'transac_date >='=>date('Y-m-d',strtotime($cut_off))));
		//pr($cond_collect); exit();
		$cond_reverse = array('type'=>'+','transaction_type_id'=>'RRVRS','esp'=>$esp,'transac_date <'=>date('Y-m-d',strtotime($from)));
		$cond_res = array('type'=>'-','transaction_type_id'=>'RSRVE','esp'=>$esp,'transac_date >='=>date('Y-m-d',strtotime($from)));
		
		$cond_total = array('type'=>'+','transaction_type_id'=>$total,'esp'=>$esp);
		$collect_range = array('type'=>'-','transaction_type_id'=>$rcvd,'esp'=>$esp,'and'=>$date_range);
		$group = array('Ledger.ref_no','Ledger.transaction_type_id');
		
		//$cond_collect['account_id'] = 'LSJ88955';
		//$collect_range['account_id']= 'LSJ88955';
		$order = array('Ledger.transac_date'=>'ASC');
		
		
		
		$projected = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>$collect_range,'group'=>$group));
		$collections = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>$cond_collect));
		$total = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>$cond_total));
		
		
		
		//pr($projected);
		//pr($collections);
		//exit();
		$total_rcvbl = 0;
		$collection_forwarded = 0;
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
			$collection_forwarded += $amount;
			
		}
		$beginning_balance = $total_rcvbl-($collection_forwarded+$total_subs);
		if($type=='daily'){
			$date = explode('-',$from);
			$day = $date[2];
			$month = $date[1];
			$max_day = 30;
			$ext = array(1,3,5,7,8,10,12);
			for($i=0;$i<$date_diff;$i++){
				if(in_array($month,$ext))
					$max_day = 31;
				else
					$max_day = 30;
				if($month==2)
					$max_day=28;
				if($day>$max_day){
					$day=1;
					$month++;
					if($month>12)
						$month = 1;
				}
				if(strlen($day)==1)
					$day='0'.$day;
				if(strlen($month)==1)
					$month='0'.$month;
				$new_date = $date[0].'-'.$month.'-'.$day;
				$day++;
				$collection_data[$new_date]=0;
			}
		}else{
			$from = explode('-',$from);
			$to = explode('-',$to);
			$mo = $from[1]-1;
			do{
				$mo++;
				if($mo>12){
					$mo = $mo-12;
					$from[0] = $from[0]+1;
				}
				switch($mo){
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
				$data = $month.$from[0];
				$collection_data[$data]=0;
				
			}
			while($mo!=$to[1]);
		}
		
		//pr($projected); exit();
		foreach($projected as $i=>$t){
			$amount = $t['Ledger']['amount'];
			$led = $t['Ledger'];

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
				//pr($mo);exit();
			}
		}
		
		$collection_range = array();
		$running_balance = $beginning_balance;
		$running_collection = $collection_forwarded;
		$total_collected = 0;
		$items = count($collection_data);
		$item = 0;
		foreach($collection_data as $i=>$data){
			$running_balance -= $data;
			$running_collection += $data;
			$total_collected += $data;
			if($type=='month')
				$coll = array('month'=>$i,'details'=>'Cash','collection'=>$data,'t_collection'=>$running_collection,'r_balance'=>$running_balance);
			else
				$coll = array('date'=>$i,'day'=>date('D', strtotime($i)),'description'=>'Cash','collection'=>$data,'t_collection'=>$running_collection,'r_balance'=>$running_balance);
			if($type=='month'&&++$item==$items&&$to[1]==date('m')){
				$last_date = date_create($projected[count($projected)-1]['Ledger']['transac_date']);
				$last_date = date_format($last_date,'d M Y');
				$coll['details'] = 'Cash until '.$last_date;
			}
			array_push($collection_range,$coll);
			
		}
		
		
		$annual_collections = array(
			'total_receivables'=>$total_rcvbl,
			'total_subsidies'=>$total_subs,
			'collection_forwarded'=>$collection_forwarded,
			'receivable_balance'=>$beginning_balance,
			'ending_balance'=>$running_balance,
			'coverage_collected'=>$total_collected,
			'total_collected'=>$running_collection,
			'collections'=>$collection_range

		);
		//exit();
		$annual_collections = array(array('Collection'=>$annual_collections));
		$this->set('collections',$annual_collections);
	}

}