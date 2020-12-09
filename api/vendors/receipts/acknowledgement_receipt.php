<?php
require('vendors/fpdf17/formsheet.php');
class AcknowledgementReceipt extends Formsheet{
	//20cm x 10.7cm
	protected static $_width = 7.87402;
	protected static $_height = 4.212598;
	protected static $_unit = 'in';
	protected static $_orient = 'L';	
	protected static $curr_page = 1;
	protected static $page_count;
	
	function AcknowledgementReceipt(){
		$this->showLines = true;
		$this->FPDF(AcknowledgementReceipt::$_orient, AcknowledgementReceipt::$_unit,array(AcknowledgementReceipt::$_width,AcknowledgementReceipt::$_height));
		$this->createSheet();
	}
	
	function template(){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 1,
			'base_y'=> 0.25,
			'width'=> 5.87402,
			'height'=> 3.712598,
			'cols'=> 22,
			'rows'=> 17,	
		);
		$this->section($metrics);
		//$this->DrawImage(0,0,7.87402,6.181102,__DIR__ ."/../images/arr.png");
		$this->DrawImage(1,0,0.8,0.8,__DIR__ ."/../images/logo.png");
		$y=2;
		$this->GRID['font_size']=14;
		$this->centerText(1,$y++,'Lake Shore Educational Institution, Inc.',22,'b');
		$this->GRID['font_size']=10;
		$this->centerText(1,$y++,utf8_decode ('Canlalay, Biñan, Laguna'),22,'b');
		$y+=1;
		$this->GRID['font_size']=11;
		$this->leftText(0,$y,'CASH ACKNOWLEDGEMENT RECEIPT','','b');
		
		$this->GRID['font_size']=10;
		$y+=1;
		$this->drawLine($y+0.2,'h',array(16.5,5.5));
		$this->leftText(15,$y,'Date','','');
		$y+=1.5;
	
		$this->drawLine($y+0.2,'h',array(5.5,16.5));
		$this->leftText(2,$y++,'Receive from','','');
		
		$this->drawLine($y+0.2,'h',array(4,18));
		$this->leftText(0,$y++,'and address at','','');
		
		$this->drawLine($y+0.2,'h',array(0,22));
		$this->leftText(0,$y++,'','','');
		
		$this->drawLine($y+0.2,'h',array(3,19));
		$this->leftText(0,$y++,'the sum of','','');
		
		$this->drawLine($y+0.2,'h',array(0,22));
		$this->leftText(0,$y++,'','','');
		
		$this->drawLine($y+0.2,'h',array(6,16));
		$this->leftText(0,$y,'In partial/full payment for','','');
	
		$y+=2;
		$this->drawLine($y+0.2,'h',array(13,9));
		$this->leftText(12,$y++,'By:','','');
		$this->centerText(13,$y++,'Cashier / Authorized Representative',9,'');
	}
	
	
	
}
?>
	