<?php
require('vendors/fpdf17/formsheet.php');
class StudentAccountCollection extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 13;
	protected static $_unit = 'in';
	protected static $_orient = 'L';	
	protected static $curr_page = 1;
	protected static $page_count;
	protected static $ctr = 1.8;
	protected static $grand_total = 0;
	
	function StudentAccountCollection(){
		$this->showLines = !true;
		$this->FPDF(StudentAccountCollection::$_orient, StudentAccountCollection::$_unit,array(StudentAccountCollection::$_width,StudentAccountCollection::$_height));
		$this->createSheet();
	}
	
	function hdr(){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.2,
			'width'=> 7.5,
			'height'=> 0.7,
			'cols'=> 38,
			'rows'=> 4,	
		);
		$this->section($metrics);
		$this->GRID['font_size']=9;
		$y=1;
		$this->leftText(0,$y++,'Lake Shore Educational Institution','','');
		$this->leftText(0,$y++,'Student Account Collection','','');
		$this->leftText(0,$y++,'School Year: 2020 - 2021','','');
		$this->leftText(0,$y++,'As of '.date("M d,Y h:i:s A"),'','');
	}
	
	function data($data,$total_page,$page){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 1,
			'width'=> 12,
			'height'=> 7.2,
			'cols'=> 48,
			'rows'=> 36,	
		);
		$this->section($metrics);
		$y=1;
		$this->drawBox(0,0,48,36);
		$this->drawMultipleLines(2,35,1,'h');
		$this->drawLine(1,'h',array(24,24));
		$this->drawLine(8,'v');
		$this->drawLine(10,'v');
		$this->drawLine(15,'v');
		$x=15;
		$xntrvl=3;
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$y=1.2;
		$this->GRID['font_size']=8;
		$this->centerText(1,$y,'Name',6,'b');
		$this->centerText(8,$y,'Year',2,'b');
		$this->centerText(10,$y,'Section',5,'b');
		$x=15;
		$xntrvl=3;
		$this->centerText($x,$y,'Total Fees',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Subsidy',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Fee Due',$xntrvl,'b');
		$y=1.8;
		$this->centerText($x+=$xntrvl,$y,'IP',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		
		//pr($data);exit;
		$y=2.8;
		foreach($data as $hdrk=>$d){
			$x=29.9;
			$this->leftText(0.2,$y,$d['name'],'','');
			$this->centerText(8,$y,$d['year_level'],2,'');
			$this->centerText(10,$y,$d['section'],5,'');
			$this->rightText(17.9,$y,number_format($d['total_fees'],2),'','');
			$this->rightText(20.9,$y,number_format($d['subsidy'],2),'','');
			$this->rightText(23.9,$y,number_format($d['fee_dues'],2),'','');	
			$x=26.9;
			$hdrx = 24;
			foreach($d['payments'] as $k=>$p){
				if($k<4){
					if($hdrk<1){//HEADER
						$this->centerText($hdrx,0.8,$p['bill_month'],6,'b');	
						$hdrx+=6;
					}
					//$this->rightText($x,$y,$p['bill_month'],'','');
					$this->rightText($x,$y,number_format($p['payment'],2).' '.$k,'','');
					$this->rightText($x+3,$y,number_format($p['balance'],2),'','');
					$x+=6;
				}
			}
			$y++;
		}
		$this->rightText(47.9,36.8,'Page '.$page.'-0 of '.$total_page,'','');
		
		//2nd Col Page
		$this->createSheet();
		$this->drawBox(0,0,48,36);
		$this->drawMultipleLines(2,35,1,'h');
		$y=1.8;
		$x=0;
		$xntrvl=3;
		$this->drawLine(1,'h');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$x=0;
		$xntrvl=3;
		$this->centerText($x,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		
		
		$y=2.8;
		foreach($data as $hdrk=>$d){
			$x=2.9;
			$hdrx=0;
			foreach($d['payments'] as $k=>$p){
				if ($k < 4) continue;
				if($hdrk<1){//HEADER
					$this->centerText($hdrx,0.8,$p['bill_month'],6,'b');	
					$hdrx+=6;
				}
				//$this->rightText($x,$y,$p['bill_month'],'','');
				$this->rightText($x,$y,number_format($p['payment'],2).' '.$k,'','');
				$this->rightText($x+3,$y,number_format($p['balance'],2),'','');
				$x+=6;
				
			}
			$y++;
		}
		
		$this->rightText(47.9,36.8,'Page '.$page.'-1 of '.$total_page,'','');

	}
	
}
?>
	