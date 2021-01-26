<?php
require('vendors/fpdf17/formsheet.php');
class IncomeStatement extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 11;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	protected static $grand_total = 0;
	
	function IncomeStatement(){
		$this->showLines = !true;
		$this->FPDF(IncomeStatement::$_orient, IncomeStatement::$_unit,array(IncomeStatement::$_width,IncomeStatement::$_height));
		$this->createSheet();
	}
	
	function hdr(){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.75,
			'base_y'=> 0.25,
			'width'=> 7,
			'height'=> 0.8,
			'cols'=> 38,
			'rows'=> 4,	
		);
		$this->section($metrics);
		$amount = '99,999.99';
		$y=0;
		$this->GRID['font_size']=12;
		$this->centerText(0,$y++,'INCOME STATEMENT ',38,'b');
		$this->GRID['font_size']=9;
		$this->centerText(0,$y,'FOR THE PERIOD AUGUST 5 TO DECEMBER 31, 2020',38,'i');
		
		$y+=1.5;
		$this->GRID['font_size']=10;
		$this->centerText(0,$y,'INCOME',38,'b');
		
		$y+=1.5;
		$this->GRID['font_size']=9;
		
		$this->leftText(0,$y,'Income from tuition','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(0,$y,'Less: deffered Income','','');
		$this->rightText(30,$y,$amount,'','');
		$this->drawLine($y+0.3,'h',array(22,16));
		$y++;
		$this->leftText(0,$y,'Income for the period','','');
		$this->rightText(32,$y,'P','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Interest income from bank deposits','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Miscellaneous income','','');
		$this->rightText(38,$y,$amount,'','');
		$this->drawLine($y+0.3,'h');
		
		$y++;
		$this->leftText(0,$y,'TOTAL','','b');
		$this->rightText(32,$y,'P','','b');
		$this->rightText(38,$y,$amount,'','b');
		
		$y+=1.5;
		$this->GRID['font_size']=10;
		$this->centerText(0,$y,'EXPENSES',38,'b');
		
		$y+=1.5;
		$this->GRID['font_size']=9;
		$this->leftText(0,$y,'Income for the period','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'13th Month Pay','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Salaries & Wages','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'SSS Premium Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'HDMF Premium Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'PHIC Premium Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Training Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Teachers Uniform','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Light & Water Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Security Services','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Office Supplies Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Computer Supplies Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Repair & Maintenance','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Sanitation Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Meal Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Taxes & License','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Transportation & Travelling','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Representation','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Professional Fee','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Honorarium','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Demolition Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Miscellaneous Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Interest Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Foundation Day Expense','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Telephone & Internet','','');
		$this->rightText(38,$y,$amount,'','');
		$this->drawLine($y+0.3,'h');
		$y++;
		$this->leftText(0,$y,'TOTAL','','b');
		$this->rightText(32,$y,'P','','b');
		$this->rightText(38,$y,$amount,'','b');
		$this->drawLine($y+0.3,'h',array(31,7));
		
		$y+=3;
		$this->drawLine($y-0.9,'h',array(31,7));
		$this->leftText(0,$y,'NET INCOME/LOSS','','b');
		$this->rightText(32,$y,'P','','b');
		$this->rightText(38,$y,$amount,'','b');
		$this->drawLine($y+0.3,'h',array(31,7));
		$this->drawLine($y+0.5,'h',array(31,7));
		
	


		$this->GRID['font_size']=7;
		$this->leftText(-1,53,'Date & Time Printed: '.date("M d,Y h:i:s A"),'','');
	}
}
?>
	