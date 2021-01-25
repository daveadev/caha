<?php
require('vendors/fpdf17/formsheet.php');
class BalanceSheet extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 11;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	protected static $grand_total = 0;
	
	function BalanceSheet(){
		$this->showLines = !true;
		$this->FPDF(BalanceSheet::$_orient, BalanceSheet::$_unit,array(BalanceSheet::$_width,BalanceSheet::$_height));
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
		$amount = '999,999.00';
		$y=0;
		$this->GRID['font_size']=12;
		$this->centerText(0,$y++,'BALANCE SHEET',38,'b');
		$this->GRID['font_size']=9;
		$this->centerText(0,$y,'As of December 31,2020',38,'');
		
		$y+=1.5;
		$this->GRID['font_size']=10;
		$this->centerText(0,$y,'ASSETS',38,'b');
		
		$y+=1.5;
		$this->GRID['font_size']=9;
		$this->leftText(0,$y,'Cash on hand','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(0,$y++,'Cash on bank','','');
		$this->leftText(0,$y,'PSB SA No. 054112000729','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'Chinabank SA no. 618202004753','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'Landbank SA no. 2381-0616-74','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'Landbank Sa no. 2381-0551-43','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'PNB CA no. 2451-70005174','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'AUB CA no. 101-01-006413','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'Petty Cash Fund	80,603.19','','');
		$this->rightText(30,$y,$amount,'','');
		$this->rightText(38,$y,$amount,'','');
		$this->drawLine($y+0.3,'h',array(23,15));
		
		
		$y+=1.5;
		$this->leftText(0,$y,'Receivable from enrollees','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Receivable from ESC','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Office machines & equipment','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Furnitures & fixtures','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Computer equipment','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Office improvements','','');
		$this->rightText(38,$y,$amount,'','');
		$this->drawLine($y+0.3,'h',array(0,38));
		$y++;
		$this->leftText(0,$y,'TOTAL ASSETS','','b');
		$this->rightText(32,$y,'P','','b');
		$this->rightText(38,$y,$amount,'','b');
		$this->drawLine($y+0.3,'h',array(31,7));
		$this->drawLine($y+0.5,'h',array(31,7));


		//LIABILITIES
		$y+=1.5;
		$this->GRID['font_size']=10;
		$this->centerText(0,$y,'Liabilities & Stockholders Equity',38,'b');
		$y+=1.5;
		$this->centerText(0,$y,'LIABILITIES',38,'b');
		
		$y+=1.5;
		$this->leftText(0,$y,'Beginnig Cash','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'SSS premiums payable','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Philhealth premiums payable','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Pagibig premiums payable','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'HDMF Employee Loans Payable','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Withholding tax payable','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y,'Deferred income','','');
		$this->rightText(38,$y,$amount,'','');
		$this->drawLine($y+0.3,'h',array(0,38));
		$y++;
		$this->leftText(0,$y,'TOTAL','','b');
		$this->rightText(32,$y,'P','','b');
		$this->rightText(38,$y,$amount,'','b');
		$this->drawLine($y+0.3,'h',array(31,7));

	
	
		
	
		//STOCKHOLDERS\' EQUITY
		$y+=1.5;
		$this->GRID['font_size']=10;
		$this->centerText(0,$y,'STOCKHOLDERS\' EQUITY',38,'b');
		$y+=1.5;
		$this->leftText(0,$y,'Net income from operations','','');
		$this->rightText(38,$y++,$amount,'','');
		$this->leftText(0,$y++,'Prior year Expenses','','');
		$this->leftText(2,$y,'Telephone & Internet','','');
		$this->rightText(30,$y++,$amount,'','');
		
		$this->leftText(2,$y,'Security & Services','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'Electricity & Water','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'Financial Assistance','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'Loyalty Pay','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'Income Tax Payable','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'Professional Fee','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'PHIC Premium Payable','','');
		$this->rightText(30,$y++,$amount,'','');
		$this->leftText(2,$y,'RA Adelan Hardware','','');
		$this->rightText(30,$y,$amount,'','');
		$this->rightText(38,$y,$amount,'','');
		$this->drawLine($y+0.3,'h',array(31,7));
		$y++;
		$this->leftText(0,$y,'TOTAL','','b');
		$this->rightText(32,$y,'P','','b');
		$this->rightText(38,$y,$amount,'','b');
		$this->drawLine($y+0.3,'h',array(31,7));
		
		$y+=1.5;
		$this->leftText(0,$y,'TOTAL LIABILITIES & STOCKHOLDERS\' EQUITY','','b');
		$this->rightText(32,$y,'P','','b');
		$this->rightText(38,$y,$amount,'','b');
		$this->drawLine($y+0.3,'h',array(31,7));
		$this->drawLine($y+0.5,'h',array(31,7));

		
		
	
		
	}
	
}
?>
	