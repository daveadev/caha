<?php
require('vendors/fpdf17/formsheet.php');
class SOA extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 13;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	
	function SOA(){
		$this->showLines = !true;
		$this->FPDF(SOA::$_orient, SOA::$_unit,array(SOA::$_width,SOA::$_height));
		$this->createSheet();
	}
	
	function ledger($stud,$data){
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
		//$this->DrawImage(0.5,0.5,0.8,0.8,__DIR__ ."/deped.png");
	
		$y=1;
		$this->GRID['font_size']=9;
		$this->centerText(0,$y++,'Lake Shore Educational Institution',$metrics['cols'],'');
		$this->centerText(0,$y++,'Student Account',$metrics['cols'],'');
		
		$y++;
		$fullname  = $stud['Student']['last_name'].', '.$stud['Student']['first_name'].' '.$stud['Student']['middle_name'];
		$this->leftText(0,$y,'Student No.:     '.$stud['Student']['sno'],'','');
		$this->leftText(28,$y++,'Date:     '.date("F d,Y"),'','');
		$this->leftText(0,$y,'Student Name:     '.$fullname,'','');
		$this->leftText(28,$y++,'Time:     '.date("h:i:s a"),'','');
		
		$y++;
		$this->leftText(0,$y++,'SY: 2020 - 2021','','');

		$y++;
		$this->centerText(1,$y,'Date',5,'');
		$this->centerText(6,$y,'Ref No.',5,'');
		$this->centerText(11,$y,'Descriptions',12,'');
		$this->rightText(28,$y,'Charges','','');
		$this->rightText(33,$y,'Payments','','');
		$this->rightText(38,$y,'Balance','','');

		$y++;
		$balance = 0;
		foreach($data as $d){
			$this->centerText(1,$y,$d['Ledger']['transac_date'],5,'');
			$this->centerText(6,$y,$d['Ledger']['ref_no'],5,'');
			$this->centerText(11,$y,$d['Ledger']['details'],12,'');
			
			if($d['Ledger']['type'] == '+'){
				$this->rightText(28,$y,number_format($d['Ledger']['amount'],2),'','');
				$balance+=$d['Ledger']['amount'];
			}else{
				$this->rightText(33,$y,number_format($d['Ledger']['amount'],2),'','');
				$balance-=$d['Ledger']['amount'];
			}
			$this->rightText(38,$y,number_format($balance,2),'','');
			$y++;
		}
		
	}
	
}
?>
	