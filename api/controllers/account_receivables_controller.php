<?php
class AccountReceivablesController extends AppController {

	var $name = 'AccountReceivables';
	var $uses = array('AccountReceivable','Transaction','Ledger','Reservation');
	
	function index() {
		$to = $_GET['to'];
		$esp = $_GET['esp'];
		
		
		$getFirst = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.transaction_type_id'=>'TUIXN','Ledger.esp'=>$esp),'order'=>'Ledger.transac_date'));
		$from = $getFirst[0]['Ledger']['transac_date'];
		$date_diff=strtotime($to)-strtotime($from);
		$date_diff = round($date_diff / (60 * 60 * 24))+1;
		
		$v_ors = array();
		$o_ors = array();
		$t_ors = array();
		$m_ors = array();
		
		//GET TOTAL TUITIONS
		$tuition_receivables = $this->Ledger->find('all', array('recursive'=>0,'conditions'=>array('Ledger.esp'=>$esp,'Ledger.transaction_type_id'=>'TUIXN')));
		$tuition_Rtotal = 0;
		foreach($tuition_receivables as $t){
			$tuition_Rtotal+=$t['Ledger']['amount'];
		}
		
		//GET TOTAL MODULES RECEIVABLES
		$modules_receivables = $this->Ledger->find('all', array('recursive'=>0,'conditions'=>array('Ledger.esp'=>$esp,'Ledger.transaction_type_id'=>'MODUL','Ledger.type'=>'+','Ledger.ref_no NOT LIKE '=>'%XOR%')));
		$modules_Rtotal = 0;
		foreach($modules_receivables as $m){
			$modules_Rtotal+=$m['Ledger']['amount'];
		}
		
		//GET TOTAL SUBSIDIES
		$subs = array('DSESC','DSPUB');
		$subsidies = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.esp'=>$esp,'Ledger.transaction_type_id'=>$subs,'Ledger.type'=>'-')));
		$total_subsidies = 0;
		foreach($subsidies as $s){
			$total_subsidies+=$s['Ledger']['amount'];
		}
		$data = array('tuition'=>0,'module'=>0,'voucher'=>0);
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
			$collection_data[$new_date]=$data;
		}
		
		//GET ALL CANCELLED OR
		$cancelled = $this->Ledger->find('all',array('recursive'=>0, 'order'=>'Ledger.transac_date',
										'conditions'=>array('Ledger.esp'=>$esp,'Ledger.type'=>'+','Ledger.ref_no LIKE '=>'XOR%')
										));
		$c_ors = array();
		foreach($cancelled as $c){
			$or = $c['Ledger']['ref_no'];
			$or = explode(" ",$or);
			$c_ors[$or[1]] = $c['Ledger']['amount'];
		}
		//pr($c_ors); exit();
		
		
		//GET ALL TUITION PAYMENTS
		$tuitions = array('INIPY','FULLP','SBQPY');
		$tuition_payments = $this->Ledger->find('all',array('recursive'=>0, 'order'=>'Ledger.transac_date',
										'conditions'=>array('Ledger.esp'=>$esp,'Ledger.transaction_type_id'=>$tuitions,'Ledger.type'=>'-','OR'=>array('Ledger.details NOT LIKE '=>'%FAV%','Ledger.details NOT LIKE '=>'%LV%'))
										));
		$t_running = 0;
		foreach($tuition_payments as $t){
			$t_or = $t['Ledger']['ref_no'];
			$t_or = explode(" ",$t_or);
			//CHECK IF OR IS CANCELLED
			if(isset($t_or[1])){
				if(array_key_exists($t_or[1],$c_ors)){
					//pr($t_or[1]);
					continue;
				}
			}
			$t_date = $t['Ledger']['transac_date'];
			$t_running +=$t['Ledger']['amount'];
			if($t['Ledger']['transaction_type_id']=='SBQPY'){
				$vchr = $t['Ledger']['details'];
				if(strpos($vchr,'LV')||strpos($vchr,'FAV')){
					$collection_data[$t_date]['voucher']+=$t['Ledger']['amount'];
				}else{
					$collection_data[$t_date]['tuition']+=$t['Ledger']['amount'];
					$collection_data[$t_date]['t_balance']=$tuition_Rtotal-$t_running;
				}
			}else{
				$collection_data[$t_date]['tuition']+=$t['Ledger']['amount'];
				$collection_data[$t_date]['t_balance']=$tuition_Rtotal-$t_running;
			}
			
		}
		//exit();
		//GET ALL MODULE PAYMENTS
		$module_payments = $this->Ledger->find('all',array('recursive'=>0,'order'=>'Ledger.transac_date','conditions'=>array('Ledger.esp'=>$esp,'Ledger.transaction_type_id'=>'MODUL','Ledger.type'=>'-')));
		$m_balance = $modules_Rtotal;
		$m_running = 0;
		foreach($module_payments as $t){
			$m_or = $t['Ledger']['ref_no'];
			$m_or = explode(" ",$m_or);
			if(array_key_exists($m_or[1],$c_ors))
				continue;
			$m_running += $t['Ledger']['amount'];
			$m_date = $t['Ledger']['transac_date'];
			$collection_data[$m_date]['module']+=$t['Ledger']['amount'];
			$collection_data[$m_date]['m_balance']=$m_balance-$m_running;
			$collection_data[$m_date]['m_balance']=$m_balance-$m_running;
		}
		$cnt = 0;
		$accounts_receivables = array();
		
		
		
		foreach($collection_data as $i=>$c){
			$data = $c;
			$data['date'] = $i;
			$day = strtotime($i);
			$day = date('D', $day);
			if($day!='Sun')
				array_push($accounts_receivables,$data);
		}
		
		
		foreach($accounts_receivables as $i=>$a){
			if(!isset($a['t_balance']))
				$accounts_receivables[$i]['t_balance'] = $accounts_receivables[$i-1]['t_balance'];
			if(!isset($a['m_balance']))
				$accounts_receivables[$i]['m_balance'] = $accounts_receivables[$i-1]['m_balance'];
		}
		
		//GET ALL FINANCIAL ASSISTANCES
		$financial_asst = $this->Ledger->find('all',array('recursive'=>0, 'order'=>'Ledger.transac_date',
										'conditions'=>array('Ledger.esp'=>$esp,'Ledger.transaction_type_id'=>$tuitions,'Ledger.type'=>'-','OR'=>array('Ledger.details LIKE '=>'%FAV%','Ledger.details LIKE '=>'%LV%'))
										));
		
		$total_vouchers = 0;
		foreach($financial_asst as $v){
			$total_vouchers+=$v['Ledger']['amount'];
		}
		$totals = array(
			'Tuitions'=>$tuition_Rtotal-$total_subsidies,
			'Subsidies'=>$total_subsidies,
			'Modules'=>$modules_Rtotal,
			'Receivables'=>$tuition_Rtotal+$modules_Rtotal-$total_vouchers,
			'FinAsstn'=>$total_vouchers
			);
		$final_data = array(array('AccountReceivable'=>array('ARC'=>$accounts_receivables,'totals'=>$totals)));
		//pr($final_data); exit();
		$this->set('accountReceivables',$final_data);
		
	}

}