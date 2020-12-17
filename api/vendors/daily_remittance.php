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
	
	function hdr(){
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
		$this->centerText(0,$y++,'Date:',38,'');
	}
	
	function series(){
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
		for($i=1;$i<10;$i++){
			$this->leftText(0.2,$y,'Booklet No.'. $i,'','');
			$this->centerText(3,$y,'xx',4,'');
			$this->centerText(7,$y,'xxx',4,'');
			$this->centerText(11,$y,'xxxx',3,'');
			$y++;
		}
		
		
		$y = 36;
		$this->GRID['font_size']=7;
		$this->leftText(0,$y,'Date & Time Printed:','','');
	}
	
	function cash_breakdown(){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 6.7,
			'base_y'=> 1.2,
			'width'=> 3.8,
			'height'=> 4,
			'cols'=> 9,
			'rows'=> 20,	
		);
		$this->section($metrics);
		$y=1;
		$this->GRID['font_size']=9;
		
		$this->drawBox(0,0,9,20);
		$this->drawLine(4,'v',array(0,21));
		$this->drawLine(6,'v',array(0,21));
		$this->drawLine(9,'v',array(20,1));
		$this->drawLine(21,'h',array(4,5));
		$this->drawMultipleLines(2,19,1,'h');
		$this->centerText(0,-0.3,'CASH BREAKDOWN',9,'');
		$y = 1.2;
		$this->centerText(0,$y,'Denomination',4,'');
		$this->centerText(4,$y,'Qty',2,'');
		$this->centerText(6,$y,'Amount',3,'');
		
		//data
		$y=2.7;
		for($i=1;$i<10;$i++){
			$this->leftText(0.2,$y,'Denomination '.$i,'','');
			$this->centerText(4,$y,'xx',2,'');
			$this->centerText(6,$y,'xxx',3,'');
			$y++;
		}
		
	
		$y = 20.8;
		$this->rightText(5.8,$y,'Total','','');
		$this->leftText(6.2,$y,'xx','','');
		
		$y=24.8;
		$this->leftText(0,$y,'Prepared:','','');
		$this->drawLine($y+0.2,'h',array(3,5));
		$this->centerText(3,$y,'xx',5,'');
		$this->centerText(3,$y+1,'Cashier',5,'');
		$y+=4;
		$this->leftText(0,$y,'Recived:','','');
		$this->drawLine($y+0.2,'h',array(3,5));
		$this->centerText(3,$y,'xx',5,'');
		$this->centerText(3,$y+1,'Signature Over Printed Name',5,'');
	}
	
	






}
?>
	