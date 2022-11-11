<?php
require('vendors/fpdf17/formsheet.php');
class AccountReceivables extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 11;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	protected static $grand_total = 0;
	
	function AccountReceivables(){
		$this->showLines = !true;
		$this->FPDF(AccountReceivables::$_orient, AccountReceivables::$_unit,array(AccountReceivables::$_width,AccountReceivables::$_height));
		$this->createSheet();
	}
	
	function hdr($hdr){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.25,
			'width'=> 7.5,
			'height'=> 0.8,
			'cols'=> 38,
			'rows'=> 4,	
		);
		$this->section($metrics);
		$this->GRID['font_size']=9;
		$y=1;
		$this->leftText(0,$y++,'Lake Shore Educational Institution','','');
		$this->leftText(0,$y++,'Accounts Receivables','','');
		$this->leftText(0,$y++,'School Year: 2022 - 2023','','');
		
		
		
	}
	
	function data($hdr,$data,$total_page,$page){
		//pr($hdr); exit();
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 1.2,
			'width'=> 7.5,
			'height'=> 9.4,
			'cols'=> 17,
			'rows'=> 47,	
		);
		$this->section($metrics);
		$y=0;
		$this->GRID['font_size']=9;
		if($page==1){
			$this->rightText(16,$y,'P '.number_format($hdr['totals']['Tuitions'],2),'','');
			$this->rightText(13,$y++,'Tuition and Misc Fees: ','','');
			$this->rightText(16,$y,'P '.number_format($hdr['totals']['Subsidies'],2),'','');
			$this->rightText(13,$y++,'PEAC Subsides: ','','');
			$this->rightText(16,$y,'P '.number_format($hdr['totals']['Modules'],2),'','');
			$this->rightText(13,$y++,'Modules: ','','');
			$this->rightText(16,$y,'P '.number_format($hdr['totals']['FinAsstn'],2),'','');
			$this->rightText(13,$y++,'Financial Assistance: ','','');
			$this->rightText(16,$y,'P '.number_format($hdr['totals']['Receivables'],2),'','');
			$this->rightText(13,$y++,'Total Receivables: ','','');
		}
		$dvr = '--------------------------------------------------';
		$this->drawLine($y++,'h');
		$this->centerText(1,$y-.2,'Date','','');
		$this->centerText(3,$y-.2,'Tuition Payments','','');
		$this->centerText(6,$y-.2,'Tuition Balance','','');
		$this->centerText(9,$y-.2,'Module Payments','','');
		$this->centerText(12,$y-.2,'Module Balance','','');
		$this->centerText(15,$y-.2,'Total Balance','','');
		$this->drawLine($y+=.2,'h');
		
		$this->GRID['font_size']=8;
		if($page==1){
			$y=7;
		}else{
			$y=2;
		}
		//pr($data); exit();
		$this->leftText(0,$y,'Beginning Balance','','');
		$this->rightText(16,$y,number_format($hdr['totals']['Receivables'],2),'','');
		$this->SetTextColor(185,185,185);
		$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr,'','');
		$this->SetTextColor(0,0,0);
		$y++;
		foreach($data as $d){
			$this->centerText(0,$y,date('d M Y',strtotime($d['date'])),2,'');
			$this->rightText(2,$y,number_format($d['tuition']),2,'');
			$this->rightText(5,$y,number_format($d['t_balance']),2,'');
			$this->rightText(8,$y,number_format($d['module']),2,'');
			$this->rightText(11,$y,number_format($d['m_balance']),2,'');
			$this->rightText(14,$y,number_format($d['total_balance']),2,'');
			$this->SetTextColor(185,185,185);
			$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr,'','');
			$this->SetTextColor(0,0,0);
			$y++;
		}
		
		//FOOTER DETAILS
		$this->GRID['font_size']=8;
		$this->leftText(0,48,'Printed by: '.'Cashier 1','','');
		$this->centerText(0,48,'Date & Time Printed: '. date("M d, Y h:i:s A"),17,'');
		$this->rightText(16.9,48,'Page '.$page.' of '.$total_page,'','');
	}
}
?>
	