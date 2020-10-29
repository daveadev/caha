<?php
require('vendors/fpdf17/formsheet.php');
class MonthlyCollections extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 11;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	protected static $grand_total = 0;
	
	function MonthlyCollections(){
		$this->showLines = !true;
		$this->FPDF(MonthlyCollections::$_orient, MonthlyCollections::$_unit,array(MonthlyCollections::$_width,MonthlyCollections::$_height));
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
		$this->leftText(0,$y++,'Monthly Collection Summary Report','','');
		$this->leftText(0,$y++,'School Year: 2020 - 2021','','');
		
		$lastcollectionsdata = end($hdr['collections']);
		$frommonth =$hdr['collections'][0]['month'];
		$tomonth = $lastcollectionsdata['month'];
		//pr($tomonth);exit;
		$this->leftText(0,$y++,'Month of: '.$frommonth.' - '.$tomonth ,'','');
	}
	
	function data($hdr,$data,$total_page,$page){
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
		$this->centerText(0,$y,'Month',2,'b');
		$this->leftText(3.2,$y,'Description','','b');
		$this->centerText(11,$y,'Collection',2,'b');
		$this->centerText(13,$y++,'Balance',2,'b');
		$totalcollectionperpage = 0;
		$dvr = '---------------------------------------------';
		if($page == 1){
			//TOTAL RECEIVABLES
			$this->leftText(3.2,$y,'Total Receivable','','');
			$this->rightText(14.9,$y,number_format($hdr['total_receivables'],2),'','');
			$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr,'','');
			$y++;
			//LESS SUBSIDIES & DISCOUNT
			$this->leftText(3.2,$y,'Less Subsidies & Discount','','');
			$this->rightText(15,$y,'('.number_format($hdr['total_subsidies'],2).')','','');
			$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr,'','');
			$y++;
			//NET RECEIVABLES
			$netrecievable = $hdr['total_receivables']-$hdr['total_subsidies'];
			$this->leftText(3.2,$y,'Net Receivable','','');
			$this->rightText(14.9,$y,number_format($netrecievable,2),'','');
			$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr,'','');
			$y++;
			//COLLECTION FORWARDED
			$this->leftText(3.2,$y,'Forwarded Collection','','');
			$this->rightText(12.9,$y,number_format($hdr['collection_forwarded'],2),'','');
			$this->rightText(14.9,$y,number_format($hdr['beginning_balance'],2),'','');
			$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr,'','');
			$y++;
		}
		$newpage=true;
		foreach($data as $d){
			$totalcollectionperpage+= $d['collection'];
			$this->centerText(0,$y,date('M Y',strtotime($d['month'])),2,'');
			$this->leftText(3.2,$y,ucfirst($d['details']),'','');
			$this->rightText(12.9,$y,number_format($d['collection'],2),'','');
			$this->rightText(14.9,$y,number_format($d['balance'],2),'','');
			$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr,'','');
			MonthlyCollections::$grand_total+=$d['collection'];
			if($newpage){
				$newpage=false;
				$fromdate = date('M d',strtotime($d['month']));
			}
			$todate = date('M d',strtotime($d['month']));
			$y++;
		}
		$this->rightText(11,$y,'Total Collection for '.$fromdate.' - '.$todate,'','');
		$this->rightText(12.9,$y++,number_format($totalcollectionperpage,2),'','');
		if($page == $total_page){
			$this->rightText(11,$y,'Grand Total','','b');
			$this->rightText(12.9,$y,number_format(MonthlyCollections::$grand_total,2),'','b');
		}
		//FOOTER DETAILS
		$this->GRID['font_size']=8;
		$this->leftText(0,48,'Printed by: '.'Cashier 1','','');
		$this->centerText(0,48,'Date & Time Printed: '. date("M d, Y h:i:s A"),15,'');
		$this->rightText(14.9,48,'Page '.$page.' of '.$total_page,'','');
	}
}
?>
	