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
	protected static $tui = 0;
	protected static $mod = 0;
	protected static $old = 0;
	protected static $vou = 0;
	protected static $oth = 0;
	
	function DailyCollections(){
		$this->showLines = !true;
		$this->FPDF(DailyCollections::$_orient, DailyCollections::$_unit,array(DailyCollections::$_width,DailyCollections::$_height));
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
		$this->leftText(0,$y++,'Daily Collection Summary Report','','');
		$this->leftText(0,$y++,'School Year: 2020 - 2021','','');
		//pr($hdr[0]['date']);exit;
	
		$lastcollectionsdata = end($hdr);
		$fromdate = date('M d, Y',strtotime($hdr['BreakDowns'][0]['date']));
		$last_date = end($hdr['BreakDowns']);
		$todate = date('M d, Y',strtotime($last_date['date']));
		if($todate == $fromdate){
			$this->leftText(0,$y++,'Collections for '.$fromdate,'','');
		}else{
			$this->leftText(0,$y++,'Collections from '.$fromdate.' to '.$todate ,'','');
		}
	}
	
	function data($hdr,$data,$total_page,$page){
		//pr($hdr); exit();
		//pr($data);
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
		$dvr = '---------------------------------------------------';
		$fromdate = date('M d, Y',strtotime($hdr['BreakDowns'][0]['date']));
		$last_date = end($hdr['BreakDowns']);
		$todate = date('M d, Y',strtotime($last_date['date']));
		if($page==1){
			$this->leftText(8,$y,'Collections Forwarded as of '.$fromdate,'','');
			$this->rightText(17,$y,number_format($hdr['Forwarded'],2),'','');
		}
		$y++;
		
		$this->drawLine(.8,'h');
		$this->drawLine(2.2,'h');
		
		$y=1;
		$this->centerText(15,$y+=.4,'Receivable',2,'b');
		$y=2;
		$this->centerText(0,$y,'Date',2,'b');
		$this->centerText(1.5,$y,'Day',2,'b');
		$this->leftText(3,$y,'Old Accounts','','b');
		$this->centerText(5,$y,'Tutions',2,'b');
		$this->centerText(7,$y,'Modules',2,'b');
		$this->centerText(9,$y,'Vouchers',2,'b');
		$this->centerText(11,$y,'Others',2,'b');
		$this->centerText(13,$y,'Total',2,'b');
		$this->centerText(15,$y++,'Balance',2,'b');
		$totalcollectionperpage = 0;
		
		
		$this->GRID['font_size']=8;
		$newpage=true;
		
		foreach($data as $d){
			$totalcollectionperpage+= $d['total'];
			
			$this->centerText(0,$y,date('d M Y',strtotime($d['date'])),2,'');
			$this->centerText(1.5,$y,$d['day'],2,'');
			$this->rightText(5,$y,number_format($d['old_account'],2),'','');
			$this->rightText(5,$y,number_format($d['tuition'],2),2,'');
			$this->rightText(7,$y,number_format($d['module'],2),2,'');
			$this->rightText(9,$y,number_format($d['voucher'],2),2,'');
			$this->rightText(11,$y,number_format($d['other'],2),2,'');
			$this->rightText(13,$y,number_format($d['total'],2),2,'');
			$this->rightText(15,$y,number_format($d['running_balance'],2),2,'');
			DailyCollections::$tui+=$d['tuition'];
			DailyCollections::$mod+=$d['module'];
			DailyCollections::$old+=$d['old_account'];
			DailyCollections::$vou+=$d['voucher'];
			DailyCollections::$oth+=$d['other'];
			
			$this->SetTextColor(185,185,185);
			$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr,'','');
			$this->SetTextColor(0,0,0);
			DailyCollections::$grand_total+=$d['total'];
			if($newpage){
				$newpage=false;
				$fromdate = date('d M Y',strtotime($d['date']));
			}
			$todate = date('d M Y',strtotime($d['date']));
			$y++;
		}
		if($todate == $fromdate){
			$this->rightText(13,$y,'Total Collection for '.$fromdate,'','');
		}else{
			$this->rightText(13,$y,'Total Collection from '.$fromdate.' to '.$todate,'','');
		}
		
		$this->rightText(15,$y++,number_format($totalcollectionperpage,2),'','');
		if($page == $total_page && $total_page != 1){
			$this->rightText(13,$y,'Grand Total','','b');
			$this->rightText(15,$y,number_format(DailyCollections::$grand_total,2),'','b');
			
			$this->SetTextColor(185,185,185);
			$this->leftText(0,$y+0.4,$dvr.$dvr.$dvr.$dvr,'','');
			$this->SetTextColor(0,0,0);
			$this->GRID['font_size']=9;
			$this->leftText(0,40,'Breakdown:','','b');
			$this->GRID['font_size']=8;
			$this->leftText(0,41,'Tuitions:','','');
			$this->rightText(3.5,41,number_format(DailyCollections::$tui,2),'','b');
			$this->leftText(0,42,'Old Accounts:','','');
			$this->rightText(3.5,42,number_format(DailyCollections::$old,2),'','b');
			$this->leftText(6,41,'Modules:','','');
			$this->rightText(9.5,41,number_format(DailyCollections::$mod,2),'','b');
			$this->leftText(6,42,'Vouchers:','','');
			$this->rightText(9.5,42,number_format(DailyCollections::$vou,2),'','b');
			$this->leftText(0,43,'Others:','','');
			$this->rightText(3.5,43,number_format(DailyCollections::$oth,2),'','b');
		}
		
		
		//FOOTER DETAILS
		$this->GRID['font_size']=8;
		$this->leftText(0,48,'Printed by: '.'Cashier 1','','');
		$this->centerText(0,48,'Date & Time Printed: '. date("M d, Y h:i:s A"),17,'');
		$this->rightText(16.9,48,'Page '.$page.' of '.$total_page,'','');
	}
}
?>
	