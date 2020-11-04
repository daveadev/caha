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
			'base_x'=> 0.5,
			'base_y'=> 0.25,
			'width'=> 10,
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
			'base_x'=> 0.5,
			'base_y'=> 1.2,
			'width'=> 10,
			'height'=> 7,
			'cols'=> 36,
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
		$this->leftText(4,$y,'Received From','','b');
		$this->leftText(11,$y,'Level','','b');
		$this->leftText(13,$y,'Section','','b');
		$this->centerText(16,$y,'Status',2,'b');
		$this->leftText(18,$y,'Particular','','b');
		$this->centerText(21,$y,'O.R No.',3,'b');
		$this->centerText(24,$y,'Amount',3,'b');
		$this->centerText(27,$y,'Total Due',3,'b');
		$this->centerText(30,$y,'Total Paid',3,'b');
		$this->centerText(33,$y++,'Total Balance',3,'b');
		$dvr = '---------------------------------------------';
		$this->GRID['font_size']=8;
		$amount = 0;
		$total_due = 0;
		$total_paid = 0;
		$balance = 0;
		foreach($data as $d){
			$amount+= $d['amount'];
			$total_due+= $d['total_due'];
			$total_paid+= $d['total_paid'];
			$balance+= $d['balance'];
			$this->centerText(0,$y,$d['cnt'],1,'');
			$this->leftText(1,$y,$d['sno'],'','');
			$this->leftText(4,$y,$d['received_from'],'','');
			$this->leftText(11,$y,$d['level'],'','');
			$this->leftText(13,$y,$d['section'],'','');
			$this->centerText(16,$y,$d['status'],2,'');
			$this->leftText(18,$y,$d['particulars'],'','');
			$this->centerText(21,$y,$d['ref_no'],3,'');
			$this->rightText(26.9,$y,number_format($d['amount'],2),'','');
			$this->rightText(29.9,$y,number_format($d['total_due'],2),'','');
			$this->rightText(32.9,$y,number_format($d['total_paid'],2),'','');
			$this->rightText(35.9,$y,number_format($d['balance'],2),'','');
			$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr.$dvr.$dvr,'','');
			//CashierDailyCollections::$grand_total+=$d['total_paid'];
			CashierDailyCollections::$total_amount+=$d['amount'];
			CashierDailyCollections::$total_due+=$d['total_due'];
			CashierDailyCollections::$total_paid+=$d['total_paid'];
			CashierDailyCollections::$total_balance+=$d['balance'];
			

			
			$y++;
		}
		$this->rightText(23.9,$y,'Sub Total ','','');
		$this->rightText(26.9,$y,number_format($amount,2),'','');
		$this->rightText(29.9,$y,number_format($total_due,2),'','');
		$this->rightText(32.9,$y,number_format($total_paid,2),'','');
		$this->rightText(35.9,$y++,number_format($balance,2),'','');

		
		
		if($page == $total_page){
			$this->rightText(23.9,$y,'Grand Total','','b');
			$this->rightText(26.9,$y,number_format(CashierDailyCollections::$total_amount,2),'','');
			$this->rightText(29.9,$y,number_format(CashierDailyCollections::$total_due,2),'','');
			$this->rightText(32.9,$y,number_format(CashierDailyCollections::$total_paid,2),'','');
			$this->rightText(35.9,$y,number_format(CashierDailyCollections::$total_balance,2),'','');
			//$this->rightText(32.9,$y,number_format(CashierDailyCollections::$grand_total,2),'','b');
		}
		//FOOTER DETAILS
		$this->GRID['font_size']=8;
		$this->leftText(0,36,'Printed by: '.'Cashier 1','','');
		$this->centerText(0,36,'Date & Time Printed: '. date("M d, Y h:i:s A"),35,'');
		$this->rightText(36,36,'Page '.$page.' of '.$total_page,'','');
	
	}
}
?>
	