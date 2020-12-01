<?php
class ParseaccountController extends AppController {
	var $name = 'Parseaccount';
	var $uses = array('Account','Ledger');
	
	function add(){
		pr($this->data); exit();
	/* 	require_once '../vendors/autoload.php';
		$dir = '../files';
		$file = scandir($dir);
		//pr($file[2]); exit();
		$ExcelLedgerFile = $dir.'/'.$file[2];
		$excelReader = PHPExcel_IOFactory::createReaderForFile($ExcelLedgerFile);
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::ZIPARCHIVE);
		
		$LedgerObj = $excelReader->load($ExcelLedgerFile);
		$ledgers = $LedgerObj->getActiveSheet()->toArray(null, true,true,true);


		$highestColumn = $LedgerObj->setActiveSheetIndex(0)->getHighestColumn();
		$highestRow = $LedgerObj->setActiveSheetIndex(0)->getHighestRow(); */
		$account_ids = array();
		for($row=2;$row<=3;$row++){
			$acc_id = $ledgers[$row]['A'];
			if(!in_array($acc_id,$account_ids)){
				array_push($account_ids,$acc_id);
			}
			
		}
		foreach($account_ids as $id){
			
			$leds = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.account_id'=>$id)));
			$payment = 0;
			$data = array();
			foreach($leds as $i=>$led){
				$ledger = $led['Ledger'];
				switch($led['Ledger']['transaction_type_id']){
					case 'TUIXN':
						$data['id'] = $id;
						$data['ref_no'] = $ledger['ref_no'];
						$data['account_type'] = 'student';
						$data['assessment_total'] = $ledger['amount'];
						break;
					case 'DSESC':
						$data['discount_amount'] = $ledger['amount'];
						$data['subsidy_status'] = $ledger['transaction_type_id'];
						break;
					case 'DSQVR':
						$data['discount_amount'] = $ledger['amount'];
						$data['subsidy_status'] = $ledger['transaction_type_id'];
						break;
					case 'DSPUB':
						$data['discount_amount'] = $ledger['amount'];
						$data['subsidy_status'] = $ledger['transaction_type_id'];
						break;
					case 'INIPY':
						$payment += $ledger['amount'];
						break;
					case 'SBQPY':
						$payment += $ledger['amount'];
						break;
				}
			}
			$data['payment_total'] = $payment;
			if(!isset($data['discount_amount'])){
				$data['discount_amount'] = 0;
				$data['subsidy_status'] = 'REGXX';
			}
			$data['outstanding_balance'] = $data['assessment_total'] - ($payment + $data['discount_amount']);
			
			$this->Account->saveAll($data);
		}
		pr('Success');
		exit();
		
	}
	
}
?>
