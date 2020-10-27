<?php
require('vendors/fpdf17/formsheet.php');
class DailyCollections extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 11;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	protected static $grand_total = 0;
	
	function DailyCollections(){
		$this->showLines = !true;
		$this->FPDF(DailyCollections::$_orient, DailyCollections::$_unit,array(DailyCollections::$_width,DailyCollections::$_height));
		$this->createSheet();
	}
	
	function hdr($data){
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
		$this->leftText(0,$y++,'Daily Collection Summary Report','','');
		$this->leftText(0,$y++,'School Year: 2020 - 2021','','');
		$this->leftText(0,$y++,'Month of: August 2020','','');
		
		$y=1;
		$this->rightText(33,$y,'Total Receivables:','','');
		$this->rightText(38,$y++,number_format($data['total_receivables'],2),'','');
		
		$this->rightText(33,$y,'Total Subsidies:','','');
		$this->rightText(38,$y++,number_format($data['total_subsidies'],2),'','');
		
		$this->rightText(33,$y,'Collection Forwarded:','','');
		$this->rightText(38,$y++,number_format($data['collection_forwarded'],2),'','');
		
		$this->rightText(33,$y,'Beginning Balance:','','');
		$this->rightText(38,$y++,number_format($data['beginning_balance'],2),'','');
	
	
	
	}
	
	function data($data,$total_page,$page){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 1.2,
			'width'=> 7.5,
			'height'=> 9.4,
			'cols'=> 15,
			'rows'=> 47,	
		);
		$this->section($metrics);
		
		$y=0;
		$this->drawLine($y++,'h');
		$this->drawLine($y++,'h');
	
	
	
		$y=0.8;
		$this->GRID['font_size']=9;
		$this->centerText(0,$y,'Date',2,'b');
		$this->centerText(2,$y,'Day',2,'b');
		$this->leftText(4.2,$y,'Description','','b');
		$this->centerText(11,$y,'Collection',2,'b');
		$this->centerText(13,$y++,'Balance',2,'b');
		
		$totalcollectionperpage = 0;
		$dvr = '---------------------------------------------';
		//;
		
		//pr($data[0]['date']);
		$newpage=true;
		foreach($data as $d){
			$totalcollectionperpage+= $d['collection'];
			$this->centerText(0,$y,date('d M Y',strtotime($d['date'])),2,'');
			$this->centerText(2,$y,$d['day'],2,'');
			$this->leftText(4.2,$y,ucfirst($d['description']),'','');
			$this->rightText(12.9,$y,number_format($d['collection'],2),'','');
			$this->rightText(14.9,$y,number_format($d['balance'],2),'','');
			$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr,'','');
			
			DailyCollections::$grand_total+=$d['collection'];
		
			if($newpage){
				$newpage=false;
				$fromdate = date('M d',strtotime($d['date']));
			}
			$todate = date('M d',strtotime($d['date']));
			$y++;
		}
		$this->rightText(11,$y,'Total Collection for '.$fromdate.' - '.$todate,'','');
		$this->rightText(12.9,$y++,number_format($totalcollectionperpage,2),'','');
		if($page == $total_page){
			$this->rightText(11,$y,'Grand Total','','b');
			$this->rightText(12.9,$y,number_format(DailyCollections::$grand_total,2),'','b');
		}
		
		
		
		$this->GRID['font_size']=8;
		$this->leftText(0,48,'Printed by: '.'Cashier 1','','');
		$this->centerText(0,48,'Date & Time Printed: '. date("M d, Y h:i:s A"),15,'');
		$this->rightText(14.9,48,'Page '.$page.' of '.$total_page,'','');
	
	}
	
}
?>
	