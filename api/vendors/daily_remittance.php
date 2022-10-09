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
	
	function booklet($booklet,$doctype){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.25,
			'base_y'=> 1.2,
			'width'=> 4,
			'height'=> 6,
			'cols'=> 14,
			'rows'=> 30,	
		);
		$this->section($metrics);
		$y=1;
		$this->GRID['font_size']=9;
		
		$this->drawBox(0,0,14,2);
		$this->drawLine(1,'h',array(3,8));
		$this->drawLine(2,'h');
		$this->drawLine(3,'v',array(0,2));
		$this->drawLine(7,'v',array(1,1));
		$this->drawLine(11,'v',array(0,2));
		//$this->drawMultipleLines(2,29,1,'h');
		$this->leftText(0,-0.3,'Doc Type: '.$doctype,'','');
		$y = 1.2;
		$this->leftText(0.2,$y,'Booklet No.','','');
		
		$this->centerText(3,$y-0.5,'SERIES',8,'');
		$this->centerText(3,$y+0.5,'From',4,'');
		$this->centerText(7,$y+0.5,'To',4,'');
		$this->centerText(11,$y,'Amount',3,'');
		
		//data
		
		$this->GRID['font_size']=8;
		$y=2.7;
		foreach($booklet as $d){
			$this->leftText(0.2,$y,$d['booklet_no'],'','');
			$this->centerText(3,$y,$d['series_start'],4,'');
			$this->centerText(7,$y,$d['series_end'],4,'');
			$this->rightText(13.9,$y,number_format($d['amount'],2),'','');
			$y++;
		}
		
		$y = 27;
		$this->GRID['font_size']=7;
		$this->leftText(0,$y,'Date & Time Printed: '.date("M d,Y h:i:s A"),'','');
	}
	
	function cash_breakdown($breakdown){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 4.5,
			'base_y'=> 1.2,
			'width'=> 2.5,
			'height'=> 2.8,
			'cols'=> 9,
			'rows'=> 15,	
		);
		$this->section($metrics);
		$y=1;
		$this->GRID['font_size']=9;
		
		$this->drawBox(0,0,9,15);
		$this->drawLine(4,'v',array(0,16));
		$this->drawLine(6,'v',array(0,16));
		$this->drawLine(9,'v',array(15,1));
		$this->drawLine(16,'h',array(4,5));
		$this->drawMultipleLines(2,14,1,'h');
		$this->leftText(0.2,-0.3,'CASH BREAKDOWN','','');
		$y = 1.2;
		$this->centerText(0,$y,'Denomination',4,'');
		$this->centerText(4,$y,'Qty',2,'');
		$this->centerText(6,$y,'Amount',3,'');
		
		//data
		$this->GRID['font_size']=8;
		$y=2.7;
		$total = 0;
		foreach($breakdown as $d){
			$this->centerText(0,$y,$d['denomination'],4,'');
			$this->centerText(4,$y,$d['quantity'],2,'');
			$this->rightText(8.9,$y,number_format($d['amount'],2),'','');
			$total+=$d['amount'];
			$y++;
			
		}
		
		$y = 15.8;
		$this->rightText(5.8,$y,'Total','','b');
		$this->rightText(8.9,$y,number_format($total,2),'','b');
		
		$y=24.8;
		$this->leftText(0,$y,'Prepared:','','');
		$this->drawLine($y+0.2,'h',array(3,5));
		$this->centerText(3,$y,'',5,'');
		$this->centerText(3,$y+1,'Cashier',5,'');
		$y+=4;
		$this->leftText(0,$y,'Received:','','');
		$this->drawLine($y+0.2,'h',array(3,5));
		$this->centerText(3,$y,'Signature Over Printed Name',5,'');
		$this->centerText(3,$y+1,'Finance',5,'');
	}
	
	function non_cash_breakdown($breakdown){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 7.2,
			'base_y'=> 1.2,
			'width'=> 3.5,
			'height'=> 2.8,
			'cols'=> 12,
			'rows'=> 15,	
		);
		$this->section($metrics);
		$y=1;
		$this->GRID['font_size']=9;
		
		$this->drawBox(0,0,12,18);
		$this->drawLine(3,'v',array(0,17));
		$this->drawLine(6,'v',array(0,18));
		$this->drawLine(9,'v',array(0,18));
		$this->drawLine(15,'v',array(15,1));
		$this->drawLine(18,'h',array(6,6));
		$this->drawMultipleLines(2,17,1,'h');
		$this->leftText(0.2,-0.3,'NON-CASH BREAKDOWN','','');
		$y = 1.2;
		$this->centerText(0,$y,'OR No.',3,'');
		$this->centerText(3,$y,'Check Date',3,'');
		$this->centerText(6,$y,'Details',3,'');
		$this->centerText(9,$y,'amount',3,'');
		
		//data
		$y=2.7;
		$total = 0;
		$this->GRID['font_size']=8;
		foreach($breakdown as $d){
			$this->leftText(0.2,$y,$d['OR'],'','');
			if(isset($d['check_date']))
				$this->centerText(3,$y,date('M d, Y',strtotime($d['check_date'])),3,'');
			$this->centerText(6,$y,$d['bank_details'],3,'');
			$this->rightText(11.9,$y,number_format($d['amount'],2),'','');
			$total+=$d['amount'];
			$y++;
			
		}
		
		$y = 17.8;
		$this->rightText(8.8,$y,'Total','','b');
		$this->rightText(11.9,$y,number_format($total,2),'','b');
		
		$y=24.8;
		$this->leftText(0,$y,'Prepared:','','');
		$this->drawLine($y+0.2,'h',array(3,5));
		$this->centerText(3,$y,'',5,'');
		$this->centerText(3,$y+1,'Cashier',5,'');
		$y+=4;
		$this->leftText(0,$y,'Received:','','');
		$this->drawLine($y+0.2,'h',array(3,5));
		$this->centerText(3,$y,'Signature Over Printed Name',5,'');
		$this->centerText(3,$y+1,'Finance',5,'');
	}
}
?>
	