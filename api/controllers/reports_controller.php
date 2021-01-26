<?php
class ReportsController extends AppController{
	var $name = 'Reports';
	var $uses = array('Ledger','Account', 'Student','Section','Transaction','TransactionType');

	// GET srp/test_soa?account_id=LSJXXXXX
	function soa(){
		if(isset($_GET['account_id'])){
			$account_id =  $_GET['account_id'];
			
			//Student's Details
			$this->Student->bindModel(array('belongsTo' => array('Section')));
			$this->Student->recursive=1;
			$conditions = array(array('Student.id'=>$account_id));
			$student = $this->Student->find('first',compact('conditions'));
			
			//Student's SOA
			$data = $this->Ledger->find('all',array(
				'conditions'=>array('Ledger.account_id'=>$account_id),
				'order'=>array('Ledger.transac_date','Ledger.id')
			));
			//pr($student);exit;
			$this->set(compact('data','student'));
		}else{
			die('No data available.Contact your system administrator.');
		}
	}
	
	function daily_collections(){
		$data = $_POST['Collections'];
		//test data
		//$data = file_get_contents(APP."json/daily_collection.json");
		//end test data
		$data = json_decode($data,true);
		$this->set(compact('data'));
	}
	
	function receipt(){
		//$trnxId = 1;//FOR SAMPLE OR DATAA
		$trnxId = $_POST['TransactionId'];//1
		$trnx = $this->Transaction->findById($trnxId);
		
		$refNo = $trnx['Transaction']['ref_no'];
		$totalPaid = $trnx['Transaction']['amount'];
		$totalPaid =  number_format($totalPaid,2,'.',',');

		$esp = $trnx['Transaction']['esp'];
		$syShort = (int)substr($esp, 2,2);
		$syFor = $syShort.'-'.($syShort+1);
		//pr($trnx); exit();
		$trnDate = $trnx['Transaction']['transac_date'];
		$trnDate =  date('d M Y',strtotime($trnDate));
		
		$acctId =  $trnx['Account']['id'];
		$this->Account->recursive =0;
		$account = $this->Account->findById($acctId);
		$student =  $account['Student']['class_name'];
		$sno =  $account['Student']['sno'];
		$sectionId  =  $account['Student']['section_id'];
		$sectObj = $this->Section->findById($sectionId);
		$yearLevel = $sectObj['YearLevel']['name'];
		$section = $sectObj['Section']['name'];

		$trnxTypes = $this->TransactionType->find('list');
		
		$trnxDtls = array();

		foreach($trnx['TransactionDetail'] as $dtl){
			$item = $trnxTypes[$dtl['transaction_type_id']];
			$amount= number_format($dtl['amount'],2,'.',',');
			$dtlObj = array('item'=>$item,'amount'=>$amount);
			array_push($trnxDtls,$dtlObj);
		}
		$cashier = $trnx['Transaction']['cashier'];

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
		$data['verify_sign'] = md5(json_encode($data));
		
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
		
		
		$data = json_decode($data,true);
		//$data = $data['data'];
		//$data  = array();
		$this->set(compact('data'));
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