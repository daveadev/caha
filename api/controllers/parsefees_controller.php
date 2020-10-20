<?php
class ParsefeesController extends AppController {
	var $name = 'Parsefees';
	var $uses = array('AccountFee','Ledger','Student');
	
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
		
		
		$FEES = array(
			array('TUI',11510,15000,0,0,16),
			array('REG',200,200,0.029542,0.044248,15),
			array('CMP',1550,1550,0.228951,0.34292,14),
			array('TLE',300,0,0,0.066372,13,),
			array('SCI',300,500,0.073855,0.066372,12),
			array('SID',200,200,0.029542,0.044248,11),
			array('GUI',170,170,0.025111,0.037611,10),
			array('DEV',500,500,0.073855,0.110619,9),
			array('ENR',500,1500,0.221566,0.110619,8),
			array('LIB',190,300,0.044313,0.042035,7),
			array('ATH',200,200,0.029542,0.044248,6),
			array('MED',200,200,0.029542,0.044248,5),
			array('PUB',100,300,0.044313,0.022124,4),
			array('INS',50,50,0.007386,0.011062,3),
			array('SHB',60,100,0.014771,0.013274,2),
			array('MAT',0,1000,0.14771,0,1),
		);
		
		$SH_MSC = 6770;
		$HS_MSC = 4520;
		
		for($row=2;$row<=792;$row++){
			$total_payments = 0;
			$id = $ledgers[$row]['B'];
			$dept = $ledgers[$row]['C'];
			$discount = '';
			$leds = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.account_id'=>$id)));
			$transacs = array();
			foreach($leds as $i=>$a){
				$detail = $a['Ledger']['details'];
				if($detail=='ESC'||$detail=='QVR'||$detail=='PUBLIC')
					$discount = $detail;
				
				array_push($transacs,$a['Ledger']['details']);
				if($detail=='Initial Payment'||$detail=='Subsequent Payment'||$detail=='ESC'||$detail=='QVR'||$detail=='PUBLIC')
					$total_payments +=$a['Ledger']['amount'];
			}
			
			$account_fees = array();
			
			$tots = 0;
			if($dept=='SH'){
				if($total_payments>$SH_MSC){
					$tuition = $total_payments-$SH_MSC;
					foreach($FEES as $i=>$fee){
						$order = $i+1;
						$data['account_id'] = $id;
						$data['fee_id'] = $fee[0];
						$data['due_amount'] = $fee[2];
						$data['paid_amount'] = $fee[2];
						$data['percentage'] = 0;
						$data['order'] = $order;
						if($fee[0]=='TUI')
							$data['paid_amount'] = $tuition;
						array_push($account_fees,$data);
						$tots += $data['paid_amount'];
					}
				}else{
					$tuition = 0;
					foreach($FEES as $i=>$fee){
						$order = $i+1;
						$data['account_id'] = $id;
						$data['fee_id'] = $fee[0];
						$data['due_amount'] = $fee[2];
						$data['paid_amount'] = $total_payments*$fee[3];
						$data['percentage'] = $fee[3];
						$data['order'] = $order;
						if($fee[0]=='TUI')
							$data['paid_amount'] = $tuition;
						array_push($account_fees,$data);
						$tots += $data['paid_amount'];
					}
				}
			}else{
				if($total_payments>$HS_MSC){
					$tuition = $total_payments-$HS_MSC;
					foreach($FEES as $i=>$fee){
						$order = $i+1;
						$data['account_id'] = $id;
						$data['fee_id'] = $fee[0];
						$data['due_amount'] = $fee[1];
						$data['paid_amount'] = $fee[1];
						$data['percentage'] = 0;
						$data['order'] = $order;
						if($fee[0]=='TUI')
							$data['paid_amount'] = $tuition;
						array_push($account_fees,$data);
						$tots += $data['paid_amount'];
					}
				}else{
					$tuition = 0;
					foreach($FEES as $i=>$fee){
						$order = $i+1;
						$data['account_id'] = $id;
						$data['fee_id'] = $fee[0];;
						$data['due_amount'] = $fee[1];
						$data['paid_amount'] = $total_payments*$fee[4];
						$data['percentage'] = $fee[4];
						$data['order'] = $order;
						if($fee[0]=='TUI')
							$data['paid_amount'] = $tuition;
						array_push($account_fees,$data);
						$tots += $data['paid_amount'];
					}
				}
			}
			$this->AccountFee->saveAll($account_fees);
		}
		pr('Success');
		exit();
		//
		/* pr($account_fees);
		pr($total_payments);
		pr($tots);
		exit(); */
	}
}

?>