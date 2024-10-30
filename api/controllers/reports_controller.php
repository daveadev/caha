<?php
class ReportsController extends AppController{
	var $name = 'Reports';
	var $uses = array('Ledger','Account', 'Student','Section','Transaction','TransactionType',
						'MasterConfig','ClasslistBlock','Assessment','AccountSchedule','PaymentPlan');

	// GET srp/test_soa?account_id=LSJXXXXX

	function view($module){
		if($module=='statement'){
			$this->statement();
		}
	}
	function soa($format="old"){
		//pr($_GET); exit();
		$billMonth = 'current';
		if(isset($_GET['format']))
			$format = $_GET['format'];
		if(isset($_GET['bill_month']))
			$billMonth = $_GET['bill_month'];
		if(isset($_GET['account_id'])){
			$account_id =  $_GET['account_id'];

			if(!isset($_GET['sy'])):
				$cond  =array('ClasslistBlock.student_id'=>$account_id);
				$flds = array('esp','status');
				$ord =array('esp'=>'desc');
				$clHist = $this->ClasslistBlock->find('list',array('conditions'=>$cond,'fields'=>$flds,'order'=>$ord));
				$esp = floor(array_keys($clHist)[0]);
			else:
				$esp = $_GET['sy'];
			endif;

			if($format=='new'):
				
				return $this->statement($account_id,$esp,'current',true,$billMonth);
			endif;
			//Student's Details
			$this->Student->bindModel(array('belongsTo' => array('Section')));
			$this->Student->recursive=1;
			$conditions = array(array('Student.id'=>$account_id));
			$student = $this->Student->find('first',compact('conditions'));
			$config = $this->MasterConfig->find('all',array());
			$esp = $config[5]['MasterConfig']['sys_value'];
			$mod_esp = $config[10]['MasterConfig']['sys_value'];
			if(isset($_GET['sy']))
				$esp = $_GET['sy'];
			if(in_array($student['Student']['year_level_id'],array('GY','GZ')))
				$esp_check = $esp+1+.10;
			else
				$esp_check = $esp+1;
			/*
			$classlist =  $this->ClasslistBlock->find('all',array('recursive'=>-1,'conditions'=>array('ClasslistBlock.student_id'=>$account_id,'ClasslistBlock.esp'=>$esp_check)));
			*/
			
			/*
			if(isset($classlist[0])&&$mod_esp)
				$esp++;
			*/
			$data = $this->Ledger->find('all',array(
				'conditions'=>array('Ledger.account_id'=>$account_id,'Ledger.esp'=>$esp),
				'order'=>array('Ledger.transac_date','Ledger.ref_no','Ledger.id')
			));
			$acct = $this->Account->find('all',array('recursive'=>1,'conditions'=>array('Account.id'=>$account_id)));
			$sched = $acct[0]['AccountSchedule'];
			//pr($sched); exit();
			$student['Student']['esp'] = $esp;
			$this->set(compact('data','student','sched'));
		}else{

			
			$sec_id=$_GET['section_id'];
			$sy=$_GET['sy'];
			if($_GET['dept']=='SH'){
				if($_GET['sem']==45)
					$esp=.3;
				else
					$esp=.1;
				$sy = intval($sy)+$esp;
			}
			$ids = $this->ClasslistBlock->getIds($sec_id,$sy);
			$batch = array();

			if($format=='new'):
				return $this->statement(null,$sy,'current',true,$billMonth);
			endif;
			foreach($ids as $i=>$id){
				$this->Student->bindModel(array('belongsTo' => array('Section')));
				$this->Student->recursive=1;
				$conditions = array(array('Student.id'=>$id));
				$student = $this->Student->find('first',compact('conditions'));
				$esp = $this->MasterConfig->find('all',array('recursive'=>-1,'conditions'=>array('MasterConfig.sys_key'=>'ACTIVE_SY')));
				$esp = $esp[0]['MasterConfig']['sys_value'];
				$data = $this->Ledger->find('all',array(
					'conditions'=>array('Ledger.account_id'=>$id,'Ledger.esp'=>$esp),
					'order'=>array('Ledger.transac_date','Ledger.ref_no','Ledger.id')
				));
				$sched = $this->Account->find('all',array('recursive'=>1,'conditions'=>array('Account.id'=>$id)));
				$sched = $sched[0]['AccountSchedule'];
				$order = array_column($sched, 'order');
				array_multisort($order, SORT_ASC, $sched);
				//pr($sched); exit();
				$student['Student']['esp'] = $esp;
				$data['Student'] = $student['Student'];
				$data['YearLevel'] = $student['YearLevel'];
				$data['Section'] = $student['Section'];
				$data['Sched'] = $sched;
				$batch[$i]=$data;
			}
			$this->set(compact('batch'));
		}

	}
	function statement($account_id=null,$sy=null,$type='old',$render=true,$billMonth ='current'){
		$ids = array($account_id);
		if(isset($_GET['account_id'])):
			$account_id = $_GET['account_id'];
			if(isset($_GET['sy']))
				$sy = $_GET['sy'];
			if(isset($_GET['type']))
				$type = $_GET['type'];
			$ids = explode(',', $account_id);
		endif;

		if(isset($_GET['section_id']) && isset($_GET['sy'])):
			$sec_id=$_GET['section_id'];
			$sy=$_GET['sy'];
			if($_GET['dept']=='SH'){
				if($_GET['sem']==45)
					$esp=.3;
				else
					$esp=.1;
				$sy = intval($sy)+$esp;
			}
			$ids = $this->ClasslistBlock->getIds($sec_id,$sy);
		endif;
		
		if(isset($_GET['bill_month']))
			$billMonth = $_GET['bill_month'];
	
		$statements=array();
		foreach($ids as $accId):
			$is_new_stud  =substr($accId, 0, 3) === 'LSN';
			if($is_new_stud):
				App::import('Model','Assessment');
				$ASM = new Assessment();
				$esp = $sy+0.25;
				$AObj = $ASM->getDetails($account_id,$esp);
				$STO = array();
				$STO['account'] = $AObj['Assessment'];
				$STO['student'] = $AObj['Inquiry'];
				$schedule = $this->PaymentPlan->buildSchedule($AObj['AssessmentPaysched']);
				$STO['paysched_current'] = $schedule;
				$STO['ledger_current'] = array();
				$STO['account']['school_year'] = sprintf("%s -  %s",$sy,$sy+1);
				$names = array();
				$names[] = $STO['student']['first_name'];
				$names[] = $STO['student']['middle_name'][0];
				$names[] = $STO['student']['last_name'];
				$STO['account']['name']=implode($names," ");
				$STO['student']['section']=$AObj['Section']['name'];
				$STO['student']['sno']=$STO['account']['id'];
				$STO['account']['sno']=$STO['account']['id'];
				
				
			else:

				$STO = $this->PaymentPlan->getDetails($accId ,$sy,$type,$billMonth);
				$isValid = $this->Account->review($STO,$type);
				$ammendSOA = false;
				if(!$isValid):
					App::import('Model','SoaCorrection');
					$SOAC = new SoaCorrection();
					$user = $this->Auth->user()['User']['username'];
					$SOAC->logDetails($sy,$type,$STO,$user);
					if($ammendSOA):
						$ammend = $this->Account->ammend($STO,$type);
						if($ammend['corrected']):
							$SOAC->logDetails($sy,$type.'_correction',$STO,$user);
						endif;
					endif;
				endif;
				

			endif;

			$PS =  $STO['paysched_'.$type];
			$PSLen =  count($PS);
			if($PSLen):
				$dueNowObj =  $PS[$PSLen-1];
				if(isset($dueNowObj['due_now'])):
					App::import('Model','Billing');
					$Billing =  new Billing();
					$account =  $STO['account'];
					$account['due_now'] =  $dueNowObj['due_now'];
					$Billing->checkAccount($account,$STO);
					$STO['account'] =  $account;
				endif;
			endif;

			
			$statements[] =  $STO;
		endforeach;
		if(!$render)
			return $statements;
		$createFile = true;
		$this->set(compact('statements','type','createFile'));
		$this->render('statement');
		

	}
	function billings($bill_no){
		App::import('Model','Billing');
		$Billing =  new Billing();
		$BObj = $Billing->findById($bill_no);
		$statement = json_decode($BObj['Billing']['statement'],true);
		if($statement)
			$statement['account']['billing_no'] = $BObj['Billing']['id'];

		$statements = [$statement];
		if(!$statement):
			$account_id=$BObj['Account']['id'];
			$sy=$BObj['Billing']['sy'];
			$type='current';
			$render=false;
			$billMonth = 'current';
			$statements = $this->statement($account_id,$sy,$type,$render,$billMonth);

		endif;
		
		$type='current';
		$createFile = false;
		$this->set(compact('statements','type','createFile'));
		$this->render('statement');
	}
	function daily_collections(){
		$data = $_POST['Collections'];
		//test data
		//$data = file_get_contents(APP."json/daily_collection.json");
		//end test data
		$data = json_decode($data,true);
		$this->set(compact('data'));
	}
	
	function account_receivables(){
		//pr($_POST['AccountReceivable']); exit();
		$data = $_POST['AccountReceivable'];
		$data = json_decode($data,true);
		$this->set(compact('data'));
	}
	
	function receipt(){
		//$trnxId = 2536;//FOR SAMPLE OR DATAA
		$trnxId = $_POST['TransactionId'];//1
		$trnx = $this->Transaction->findById($trnxId);
		//pr($trnx); exit();
		$refNo = $trnx['Transaction']['ref_no'];
		$totalPaid = $trnx['Transaction']['amount'];
		$totalPaid =  number_format($totalPaid,2,'.',',');

		$esp = $trnx['Transaction']['esp'];
		
		$trnDate = $trnx['Transaction']['transac_date'];
		$trnDate =  date('d M Y',strtotime($trnDate));
		
		$acctId =  $trnx['Account']['id'];
		$this->Account->recursive =0;
		$account = $this->Account->findById($acctId);
		//pr($account);
		if(isset($account['Student']['id'])){
			$s =  $account['Student'];
			$student = $s['last_name']. ', '.$s['first_name']. ' '.$s['middle_name'];
			$sno =  $account['Student']['sno'];
			$sectionId  =  $account['Student']['section_id'];
			$sectObj = $this->Section->findById($sectionId);
			$yearLevel = $sectObj['YearLevel']['name'];
			$section = $sectObj['Section']['name'];
		}
		if(isset($account['Inquiry']['id'])){
			$student =  $account['Inquiry']['class_name'];
			$sno =  $account['Inquiry']['id'];
			$yearLvId  =  $account['Inquiry']['year_level_id'];
			$yrlvObj = $this->Section->YearLevel->findById($yearLvId);
			$yearLevel = $yrlvObj['YearLevel']['name'];
			$section = ' ';
		}
		$trnxTypes = $this->TransactionType->find('list');
		$trnxDtls = array();
		//pr($account);
		// TODO: Add to master_config flag MOD_ESP to advance SY
		$modESP = 0;
		foreach($trnx['TransactionDetail'] as $dtl){
			//pr($dtl);
			$item = $trnxTypes[$dtl['transaction_type_id']];
			if($dtl['transaction_type_id']=='OTHRS')
				$item = $dtl['details'];
			$amount= number_format($dtl['amount'],2,'.',',');
			$dtlObj = array('item'=>$item,'amount'=>$amount);
			if($dtl['transaction_type_id']=='RSRVE'):
				$esp = $esp+$modESP;
				
			endif;
			//pr($dtlObj); exit();
			array_push($trnxDtls,$dtlObj);
		}
		
		//pr($data); exit();
		$syShort = (int)substr($esp, 2,2);
		$syFor = $syShort.'-'.($syShort+1);
		$cashier = $trnx['Transaction']['cashier'];
		//pr($student);
		if(isset($student)){
			$data = array(
				'ref_no'=>$refNo,
				'transac_date'=>$trnDate,
				'student'=>$student,
				'sno'=>$sno,
				'year_level'=>$yearLevel,
				'section'=>$section,
				'sy'=>$syFor,
				'transac_details'=> $trnxDtls,
				'total_paid'=>$totalPaid,
				'cashier'=>$cashier,
				'verify_sign'=>'1A2khsfdso1sa'
			);
		}else{
			$data = array(
				'ref_no'=>$refNo,
				'transac_date'=>$trnDate,
				'student'=>$account['Account']['account_details'],
				'sno'=>'',
				'year_level'=>'',
				'section'=>'',
				'sy'=>$syFor,
				'transac_details'=> $trnxDtls,
				'total_paid'=>$totalPaid,
				'cashier'=>$cashier,
				'verify_sign'=>'1A2khsfdso1sa'
			);
		}
		$data['verify_sign'] = md5(json_encode($data));
		foreach($trnx['TransactionPayment'] as $payment){
			if($payment['payment_method_id']=='CHCK')
				$data['check_details'] = $payment['details'] .' / '. date("d-M-Y", strtotime($payment['valid_on']));
			if($payment['payment_method_id']=='VCHR')
				$data['check_details'] = 'Voucher No.: ' . $payment['details'];
		}
		//pr($data); exit();
		$this->set(compact('data'));
	}
	
	function monthly_collections(){
		$data = $_POST['Collections'];
		//test data
		//$data = file_get_contents(APP."json/monthly_collection.json");
		//end test data
		
		$data = json_decode($data,true);
		$this->set(compact('data'));
	}
	
	function cashier_daily_collections(){
		//use test data if Cashier is not set
		if(!isset($_POST['Cashier'])){
			$data = file_get_contents(APP."json/cashier_collection.json");
		}else{
			$data = $_POST['Cashier'];
		}
		$flds = array('sys_key','sys_value');
		$config = $this->MasterConfig->find('list',array('fields'=>$flds));
		//pr($data); exit();
		
		$data = json_decode($data,true);
		//$data = $data['data'];
		//$data  = array();
		$this->set(compact('data','config'));
	}
	
	function student_account_collection_report(){
		if(!isset($_POST['student'])){
			$data = file_get_contents(APP."json/student_account_collection.json");
		}else{
			$data = $_POST['student'];	
		}
		
		$data = json_decode($data,true);
		//$data = $data['data'];
		//pr($data); exit;
		$this->set(compact('data'));
	}
	
	function acknowledgement_receipt(){
		$data = array();
		$this->set(compact('data'));
	}
	
	function daily_remittance(){
		$data = json_decode($_POST['Cashier'],true);
		
		
		//pr(json_decode($_POST['Cashier'],true)); exit();
		$this->set(compact('data'));
	}

	function balance_sheet(){
		$data = array();
		$this->set(compact('data'));
	}
	
	function income_statement(){
		/*$expenses = array(
			array(
				'name'=>'13th Month Pay'
				'amount'=>'999,999.00'
			),array(
				'name'=>'Salaries & Wages'
				'amount'=>'999,999.00'
			),array(
				'name'=>'SSS Premium Expense'
				'amount'=>'999,999.00'
			),array(
				'name'=>'HDMF Premium Expense'
				'amount'=>'999,999.00'
			),array(
				'name'=>'PHIC Premium Expense'
				'amount'=>'999,999.00'
			),array(
				'name'=>'Training Expense'
				'amount'=>'999,999.00'
			),array(
				'name'=>'Teachers Uniform'
				'amount'=>'999,999.00'
			),array(
				'name'=>'Light & Water Expense'
				'amount'=>'999,999.00'
			),array(
				'name'=>'Security Services'
				'amount'=>'999,999.00'
			),array(
				'name'=>'Office Supplies Expense'
				'amount'=>'999,999.00'
			),array(
				'name'=>'Computer Supplies Expense'
				'amount'=>'999,999.00'
			),array(
				'name'=>'Repair & Maintenance'
				'amount'=>'999,999.00'
			),array(
				'name'=>''
				'amount'=>'999,999.00'
			)
			
			
			Sanitation Expense
			Meal Expense
			Taxes & License
			Transportation & Travelling
			Representation
			Professional Fee
		Honorarium
		Demolition Expense
		Miscellaneous Expense
		Interest Expense
		Foundation Day Expense
		Telephone & Internet

		*/
		
		
		
		
		
		
		$data = array();

		$this->set(compact('data'));
	}
}