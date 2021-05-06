<?php
require('vendors/fpdf17/formsheet.php');
class CashierDailyCollections extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 11;
	protected static $_unit = 'in';
	protected static $_orient = 'L';	
	protected static $curr_page = 1;
	protected static $page_count;
	protected static $grand_total = 0;
	protected static $total_amount = 0;
	protected static $total_due = 0;
	protected static $total_paid = 0;
	protected static $total_balance = 0;
	
	function CashierDailyCollections(){
		$this->showLines = !true;
		$this->FPDF(CashierDailyCollections::$_orient, CashierDailyCollections::$_unit,array(CashierDailyCollections::$_width,CashierDailyCollections::$_height));
		$this->createSheet();
	}
	
	function hdr($hdr){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.25,
			'base_y'=> 0.25,
			'width'=> 10.5,
			'height'=> 0.8,
			'cols'=> 38,
			'rows'=> 4,	
		);
		$this->section($metrics);
		$this->GRID['font_size']=9;
		$y=1;
		$this->leftText(0,$y++,'Lake Shore Educational Institution','','');
		$this->leftText(0,$y++,'Cashier Daily Collection Report','','');
		$this->leftText(0,$y++,'School Year: 2020 - 2021','','');
		//$this->leftText(0,$y++,'Date: '.date('d M Y',strtotime($hdr['date'])),'','');
	}
	
	function data($hdr,$data,$total_page,$page){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.25,
			'base_y'=> 1.2,
			'width'=> 10.5,
			'height'=> 7,
			'cols'=> 28,
			'rows'=> 35,	
		);
		$this->section($metrics);
		$y=0;
		$this->drawLine($y++,'h');
		$this->drawLine($y++,'h');
		$y=0.7;
		$this->GRID['font_size']=8;
		$this->centerText(0,$y,'#',1,'b');
		$this->leftText(1,$y,'Student No.','','b');
		$this->leftText(3.5,$y,'Received From','','b');
		$this->leftText(10,$y,'Level','','b');
		$this->leftText(11.5,$y,'Section','','b');
		$this->leftText(14,$y,'Status','','b');
		$this->centerText(15.5,$y,'Date',2,'b');
		$this->leftText(18,$y,'Particular','','b');
		$this->centerText(22,$y,'O.R No.',3,'b');
		$this->centerText(25,$y,'Amount',3,'b');
		$this->centerText(28,$y,'Total Due',3,'b');
		$this->centerText(31,$y,'Total Paid',3,'b');
		$this->centerText(34,$y++,'Total Balance',3,'b');
		$dvr = '----------------------------------------------------------------------------------------------------------------------------------------------';
		$this->GRID['font_size']=8;
		$amount = 0;
		$total_due = 0;
		$total_paid = 0;
		$balance = 0;
		
		foreach($data as $d){
			//pr($d); 
			$amount+= $d['amount'];
			$total_due+= $d['total_due'];
			$total_paid+= $d['total_paid'];
			$balance+= $d['balance'];
			$this->centerText(0,$y,$d['cnt'],1,'');
			$this->leftText(1,$y,$d['sno'],'','');
			$this->leftText(3.5,$y,$d['received_from'],'','');
			$this->leftText(10,$y,$d['level'],'','');
			$this->leftText(11.5,$y,$d['section'],'','');
			$this->leftText(14,$y,$d['status'],'','');
			$this->centerText(15.5,$y,$d['date'],2,'');
			$this->leftText(18,$y,$d['particulars'],'','');
			$this->centerText(22,$y,$d['ref_no'],3,'');
			$this->rightText(27.9,$y,number_format($d['amount'],2),'','');
			//$this->rightText(30.9,$y,number_format($d['total_due'],2),'','');
			//$this->rightText(33.9,$y,number_format($d['total_paid'],2),'','');
			//$this->rightText(36.9,$y,number_format($d['balance'],2),'','');
			$this->SetTextColor(185,185,185);
			$this->leftText(0,$y+0.4,$dvr.$dvr,'','');
			$this->SetTextColor(0,0,0);
			//CashierDailyCollections::$grand_total+=$d['total_paid'];
			CashierDailyCollections::$total_amount+=$d['amount'];
			//CashierDailyCollections::$total_due+=$d['total_due'];
			//CashierDailyCollections::$total_paid+=$d['total_paid'];
			//CashierDailyCollections::$total_balance+=$d['balance'];
			

			
			$y++;
		}
		$this->rightText(24.9,$y,'Sub Total ','','');
		$this->rightText(27.9,$y,number_format($amount,2),'','');
		//$this->rightText(30.9,$y,number_format($total_due,2),'','');
		//$this->rightText(33.9,$y,number_format($total_paid,2),'','');
		//$this->rightText(36.9,$y,number_format($balance,2),'','');
		$y++;
		
		
		if($page == $total_page){
			$this->rightText(24.9,$y,'Grand Total','','b');
			$this->rightText(27.9,$y,number_format(CashierDailyCollections::$total_amount,2),'','');
			//$this->rightText(30.9,$y,number_format(CashierDailyCollections::$total_due,2),'','');
			//$this->rightText(33.9,$y,number_format(CashierDailyCollections::$total_paid,2),'','');
			//$this->rightText(36.9,$y,number_format(CashierDailyCollections::$total_balance,2),'','');
			//$this->rightText(32.9,$y,number_format(CashierDailyCollections::$grand_total,2),'','b');
		}
		//FOOTER DETAILS
		$this->GRID['font_size']=8;
		$this->leftText(0,36,'Printed by: '.'Cashier 1','','');
		$this->centerText(0,36,'Date & Time Printed: '. date("M d, Y h:i:s A"),28,'');
		$this->rightText(28,36,'Page '.$page.' of '.$total_page,'','');
	
	}
}
?>
	