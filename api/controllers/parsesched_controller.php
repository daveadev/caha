<?php
class ParseschedController extends AppController {
	var $name = 'Parsesched';
	var $uses = array('AccountSchedule','Ledger','Student');
	
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
				if($detail=='Initial Payment'||$detail=='Subsequesnt Payment')
					$total_payments +=$a['Ledger']['amount'];
			}
			
			//pr($discount);
			//exit();
			$ESC_HS = array(
				array('UPONNROL',2030, '2020-09-01'),
				array('SEP2020',625, '2020-09-15'),
				array('OCT2020',625, '2020-10-15'),
				array('NOV2020',625, '2020-11-15'),
				array('DEC2020',625, '2020-12-15'),
				array('JAN2021',625, '2021-01-15'),
				array('FEB2021',625, '2021-02-15'),
				array('MAR2021',625, '2021-03-15'),
				array('APR2021',625, '2021-04-01')
			);
			$REG_HS = array(
				array('UPONNROL',2030, '2020-09-01'),
				array('SEP2020',1750, '2020-09-15'),
				array('OCT2020',1750, '2020-10-15'),
				array('NOV2020',1750, '2020-11-15'),
				array('DEC2020',1750, '2021-12-15'),
				array('JAN2021',1750, '2021-01-15'),
				array('FEB2021',1750, '2021-02-15'),
				array('MAR2021',1750, '2021-03-15'),
				array('APR2021',1750, '2021-04-01')
			);
			$REG_SH =array(
				array('UPONNROL',3885, '2020-09-01'),
				array('SEP2020',2000, '2020-09-15'),
				array('OCT2020',2000, '2020-10-15'),
				array('NOV2020',2000, '2020-11-15'),
				array('DEC2020',3885, '2020-12-15'),
				array('JAN2021',2000, '2021-01-15'),
				array('FEB2021',2000, '2021-02-15'),
				array('MAR2021',2000, '2021-03-15'),
				array('APR2021',2000, '2021-04-01')
			);
			$ESC_SH = array(
				array('UPONNROL',3885, '2020-09-01'),
				array('DEC2020',3885, '2020-12-15')
			);
			$PUB_SH = array(
				array('UPONNROL',2135, '2020-09-15'),
				array('DEC2020',2135, '2020-12-01')
			);
			
			if($dept=='HS'&&$discount=='ESC')
				$sched=$ESC_HS;
			if($dept=='HS'&&$discount=='')
				$sched=$REG_HS;
			if($dept=='SH'&&($discount=='ESC'||$discount=='QVR'))
				$sched=$ESC_SH;
			if($dept=='SH'&&$discount=='')
				$sched=$REG_HS;
			if($dept=='SH'&&$discount=='PUB')
				$sched=$PUB_SH;
			
			$schedule = array();
			foreach($sched as $i=>$sc){
				$order = $i+1;
				if($total_payments>=$sc[1]){
					$payment = $sc[1];
					$total_payments -= $sc[1]; 
					$status = 'PAID';
				}else{
					$payment = $total_payments;
					$total_payments = 0;
					$status = 'NONE';
				}
				$data['account_id'] = $id;
				$data['bill_month'] = $sc[0];
				$data['due_amount'] = $sc[1];
				$data['paid_amount'] = $payment;
				$data['status'] = $status;
				$data['order'] = $order;
				array_push($schedule,$data);
			}
			$this->AccountSchedule->saveAll($schedule);
			//pr($schedule);
			//exit();
		}
		pr('Success');
		exit();
		
	}
	
}
?>
