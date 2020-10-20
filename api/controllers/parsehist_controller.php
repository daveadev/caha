<?php
class ParsehistController extends AppController {
	var $name = 'Parsehist';
	var $uses = array('Account','Ledger','Student','AccountHistory');
	
	function index(){
		require_once '../vendors/autoload.php';
		$dir = '../files';
		$file = scandir($dir);
		//pr($file[2]); exit();
		$ExcelLedgerFile = $dir.'/'.$file[2];
		$excelReader = PHPExcel_IOFactory::createReaderForFile($ExcelLedgerFile);
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::ZIPARCHIVE);
		
		$LedgerObj = $excelReader->load($ExcelLedgerFile);
		$ledgers = $LedgerObj->getActiveSheet()->toArray(null, true,true,true);


		$highestColumn = $LedgerObj->setActiveSheetIndex(0)->getHighestColumn();
		$highestRow = $LedgerObj->setActiveSheetIndex(0)->getHighestRow();
		
		for($row=2;$row<=792;$row++){
			$students = array();
			$id = $ledgers[$row]['B'];
			array_push($students,$id);
			
			$leds = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.account_id'=>$id)));
			$accts = $this->Account->find('all',array('recursive'=>0,'conditions'=>array('Account.id'=>$id)));
			$acct = $accts[0]['Account'];
			$tui = $acct['assessment_total'];
			$students[$id]=array('total_paid'=>0,'balance'=>0);
			
			$debits = array('ESC','QVR','PUBLIC','Initial Payment','Subsequent Payment');
			foreach($leds as $i=>$led){
				$detail = $led['Ledger']['details'];
				$ledger = $led['Ledger'];
				$payment =  $led['Ledger']['amount'];
				//pr($payment);
				if($ledger['type']!=='+'){
					
					$students[$id]['total_paid'] = $students[$id]['total_paid']+$payment;
					$students[$id]['balance'] = $tui-$students[$id]['total_paid'];
					
					$history = array(
						'account_id'=>$id,
						'transac_date'=>$ledger['transac_date'],
						'total_due'=>$tui,
						'total_paid'=>$students[$id]['total_paid'],
						'balance'=>$students[$id]['balance'],
						'ref_no'=>$ledger['ref_no'],
						'details'=>$ledger['details'],
						'flag'=>$ledger['type'],
						'amount'=>$ledger['amount'],
					);
					$this->AccountHistory->saveAll($history);
					//pr($students);
					//pr($history);
				}
			}
			//exit();
			
		}
		exit();
		
	}
	
}
?>

