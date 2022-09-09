<?php
require('vendors/fpdf17/formsheet.php');
class SOA extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 13;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	//negative ()
	function SOA(){
		$this->showLines = !true;
		$this->FPDF(SOA::$_orient, SOA::$_unit,array(SOA::$_width,SOA::$_height));
		$this->createSheet();
	}
	
	function ledger($stud,$data,$total_page,$page){
		//pr($stud);exit();
		$esp = $stud['Student']['esp'];
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.5,
			'width'=> 7.5,
			'height'=> 12.5,
			'cols'=> 38,
			'rows'=> 62,	
		);
		$this->section($metrics);
		//$this->DrawImage(9,-1,0.8,0.8,__DIR__ ."/logo.png");
	
		$y=1;
		$this->GRID['font_size']=9;
		$this->centerText(0,$y++,'Lake Shore Educational Institution',$metrics['cols'],'b');
		$this->centerText(0,$y++,'Student Account',$metrics['cols'],'b');
		
		$y=4;
		//SNO
		$this->leftText(0,$y,'Student No.:','','');
		$this->leftText(5,$y++,$stud['Student']['sno'],'','');
		//STUDENT NAME
		$fullname  = $stud['Student']['last_name'].', '.$stud['Student']['first_name'].' '.$stud['Student']['middle_name'];
		$this->leftText(0,$y,'Student Name:','','');
		$this->leftText(5,$y++,utf8_decode($fullname),'','');
		//YEAR/SECTION
		$this->leftText(0,$y,'Year/Section:','','');
		$this->leftText(5,$y++,$stud['YearLevel']['description'].' / '.$stud['Section']['description'],'','');
		
		$y=5;
		//DATE
		$this->leftText(28,$y,'Date:','','');
		$this->leftText(30,$y++,date("d M Y"),'','');
		//TIME
		$this->leftText(28,$y,'Time:','','');
		$this->leftText(30,$y++,date("h:i:s a"),'','');
		//SY
		$this->leftText(28,$y,'SY:','','');
		$this->leftText(30,$y++,$esp . ' - '.($esp+1),'','');

		$y++;
		$this->leftText(1,$y,'TUITION','','b');
		$y++;
		$this->centerText(0,$y,'Date',5,'b');
		$this->leftText(6.1,$y,'Ref No.','','b');
		$this->leftText(11.1,$y,'Descriptions','','b');
		$this->rightText(28,$y,'Charges','','b');
		$this->rightText(33,$y,'Payments','','b');
		$this->rightText(38,$y,'Balance','','b');

		$y++;
		$balance = 0;
		//pr($data); exit();
		unset($data['Student']);
		unset($data['YearLevel']);
		unset($data['Section']);
		foreach($data as $d){
			if($d['Ledger']['transaction_type_id']!='MODUL'){
				$time = strtotime($d['Ledger']['transac_date']);

				$newformat = date('d M Y',$time);

				$this->centerText(0,$y,$newformat,5,'');
				$this->leftText(6.1,$y,$d['Ledger']['ref_no'],'','');
				$this->leftText(11,$y,$d['Ledger']['details'],'','');
				
				if($d['Ledger']['type'] == '+'){
					$this->rightText(28,$y,number_format($d['Ledger']['amount'],2),'','');
					$balance+=$d['Ledger']['amount'];
				}else{
					//$this->rightText(33,$y,'('.number_format(abs($d['Ledger']['amount']),2).')','','');
					$this->rightText(33,$y,number_format($d['Ledger']['amount'],2),'','');
					$balance-=$d['Ledger']['amount'];
				}
				if ($balance < 0){
					$this->rightText(38,$y,'('.number_format(abs($balance),2).')','','');
				}else{
					$this->rightText(38,$y,number_format($balance,2),'','');
				}
				$y++;
			}
		}
		$y+=2;
		$isModule = 0;
		foreach($data as $d){
			if($d['Ledger']['transaction_type_id']=='MODUL')
				$isModule = 1;
			
		}
		if($isModule)
			$this->leftText(1,$y,'MODULE','','b');
		$y++;
		$module_balance =0;
		foreach($data as $d){
			if($d['Ledger']['transaction_type_id']=='MODUL'){
				$time = strtotime($d['Ledger']['transac_date']);

				$newformat = date('d M Y',$time);

				$this->centerText(0,$y,$newformat,5,'');
				$this->leftText(6.1,$y,$d['Ledger']['ref_no'],'','');
				$this->leftText(11,$y,$d['Ledger']['details'],'','');
				
				if($d['Ledger']['type'] == '+'){
					$this->rightText(28,$y,number_format($d['Ledger']['amount'],2),'','');
					$module_balance+=$d['Ledger']['amount'];
				}else{
					//$this->rightText(33,$y,'('.number_format(abs($d['Ledger']['amount']),2).')','','');
					$this->rightText(33,$y,number_format($d['Ledger']['amount'],2),'','');
					$module_balance-=$d['Ledger']['amount'];
				}
				if ($module_balance < 0){
					$this->rightText(38,$y,'('.number_format(abs($balance),2).')','','');
				}else{
					$this->rightText(38,$y,number_format($module_balance,2),'','');
				}
				$y++;
			}
		}
		
		$this->GRID['font_size']=8;
		$this->centerText(0,61,'Page '.$page.' of '.$total_page,38,'');
	}
	
	function payment_sched($data){
		//pr($data); exit();
		$metrics =array(
			'base_x'=> 0.5,
			'base_y'=> 0.5,
			'width'=> 7.5,
			'height'=> 12.5,
			'cols'=> 38,
			'rows'=> 62,	
		);
		$this->section($metrics);
		$this->GRID['font_size']=7;

		//PAYMENT SCHED
		$y=40;
		$totaldue=0;
		$this->leftText(1,$y++,'PAYMENT SCHEDULE','','b');
		$this->rightText(10,40.25,'Amount Due',3,'');
		$this->rightText(14,40.25,'Balance',3,'');
		$this->rightText(18,40.25,'Date paid',3,'');
		$total_bal = 0;
		foreach($data as $d){
			$this->leftText(1,$y,date("M d, Y", strtotime($d['due_date'])),'','');
			
			if($d['due_amount']){ 
				$this->rightText(10,$y,number_format($d['due_amount'],2),3,'');
				$balance = $d['due_amount']-$d['paid_amount'];
				$this->rightText(14,$y,number_format($d['due_amount']-$d['paid_amount'],2),3,'');
				$total_bal+=$balance;
				if($d['status']=='PAID')
					$this->rightText(18,$y,date("M d, Y", strtotime($d['paid_date'])),3,'');
				
			}
			
			else $this->rightText(10,$y,'--',3,'');
			$totaldue+=$d['due_amount'];
			$y++;
		}
	
		$this->drawLine($y-0.6,'h',array(1,21));
		$this->rightText(10,$y,number_format($totaldue,2),3,'b');
		$this->rightText(14,$y,number_format($total_bal,2),3,'b');
	}
	
}
?>	