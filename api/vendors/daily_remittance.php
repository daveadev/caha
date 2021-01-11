<?php
require('vendors/fpdf17/formsheet.php');
class DailyRemittance extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 11;
	protected static $_unit = 'in';
	protected static $_orient = 'L';	
	protected static $curr_page = 1;
	protected static $page_count;
	protected static $ctr = 1.8;
	protected static $grand_total = 0;
	
	function DailyRemittance(){
		$this->showLines = !true;
		$this->FPDF(DailyRemittance::$_orient, DailyRemittance::$_unit,array(DailyRemittance::$_width,DailyRemittance::$_height));
		$this->createSheet();
	}
	
	function hdr($date){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.25,
			'width'=> 10,
			'height'=> 0.7,
			'cols'=> 38,
			'rows'=> 4,	
		);
		$this->section($metrics);
		
		$y=1;
		$this->DrawImage(11,-0.7,0.7,0.7,__DIR__."/images/logo.png");
		$this->GRID['font_size']=11;
		$this->centerText(0,$y++,'Lake Shore Educational Institution',38,'');
		$this->GRID['font_size']=9;
		$this->centerText(0,$y++,'Daily Remittance Report',38,'');
		$date = strtotime($date);
		$this->centerText(0,$y++,'Date: '. date('M d, Y',$date),38,'');
	}
	
	function booklet($booklet){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 1.2,
			'width'=> 6,
			'height'=> 6,
			'cols'=> 14,
			'rows'=> 30,	
		);
		$this->section($metrics);
		$y=1;
		$this->GRID['font_size']=9;
		
		$this->drawBox(0,0,14,30);
		$this->drawLine(1,'h',array(3,8));
		$this->drawLine(3,'v');
		$this->drawLine(7,'v',array(1,29));
		$this->drawLine(11,'v');
		$this->drawMultipleLines(2,29,1,'h');
		$this->leftText(0,-0.3,'Doc Type: OR','','');
		$y = 1.2;
		$this->leftText(0.2,$y,'Booklet No.','','');
		
		$this->centerText(3,$y-0.5,'SERIES',8,'');
		$this->centerText(3,$y+0.5,'From',4,'');
		$this->centerText(7,$y+0.5,'To',4,'');
		$this->centerText(11,$y,'Amount',3,'');
		
		//data
		$y=2.7;
		foreach($booklet as $d){
			$this->leftText(0.2,$y,$d['booklet_no'],'','');
			$this->centerText(3,$y,$d['series_start'],4,'');
			$this->centerText(7,$y,$d['series_end'],4,'');
			$this->rightText(13.9,$y,number_format($d['amount'],2),'','');
			$y++;
		}
		
		$y = 36;
		$this->GRID['font_size']=7;
		$this->leftText(0,$y,'Date & Time Printed: '.date("M d,Y h:i:s A"),'','');
	}
	
	function cash_breakdown($breakdown){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 6.7,
			'base_y'=> 1.2,
			'width'=> 3.8,
			'height'=> 2.8,
			'cols'=> 9,
			'rows'=> 14,	
		);
		$this->section($metrics);
		$y=1;
		$this->GRID['font_size']=9;
		
		$this->drawBox(0,0,9,14);
		$this->drawLine(4,'v',array(0,15));
		$this->drawLine(6,'v',array(0,15));
		$this->drawLine(9,'v',array(14,1));
		$this->drawLine(15,'h',array(4,5));
		$this->drawMultipleLines(2,13,1,'h');
		$this->centerText(0,-0.3,'CASH BREAKDOWN',9,'');
		$y = 1.2;
		$this->centerText(0,$y,'Denomination',4,'');
		$this->centerText(4,$y,'Qty',2,'');
		$this->centerText(6,$y,'Amount',3,'');
		
		//data
		$y=2.7;
		$total = 0;
		foreach($breakdown as $d){
			$this->leftText(0.2,$y,$d['denomination'],'','');
			$this->centerText(4,$y,number_format($d['quantity'],2),2,'');
			$this->rightText(8.9,$y,number_format($d['amount'],2),'','');
			$total+=$d['amount'];
			$y++;
			
		}
		
		$y = 14.8;
		$this->rightText(5.8,$y,'Total','','');
		$this->rightText(8.9,$y,number_format($total,2),'','');
		
		$y=24.8;
		$this->leftText(0,$y,'Prepared:','','');
		$this->drawLine($y+0.2,'h',array(3,5));
		$this->centerText(3,$y,'Accounting',5,'');
		$this->centerText(3,$y+1,'Cashier',5,'');
		$y+=4;
		$this->leftText(0,$y,'Received:','','');
		$this->drawLine($y+0.2,'h',array(3,5));
		$this->centerText(3,$y,'Finance',5,'');
		$this->centerText(3,$y+1,'Signature Over Printed Name',5,'');
	}
}
?>
	