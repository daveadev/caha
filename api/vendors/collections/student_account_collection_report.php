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
		$this->leftText(0,$y++,'School Year: 2021 - 2022','','');
		$this->leftText(0,$y++,'As of '.date("M d,Y h:i:s A"),'','');
	}
	
	function data($data,$thdr,$total_page,$page){
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
		$this->drawLine(1,'h',array(30,18));
		$this->drawLine(8,'v');
		$this->drawLine(10,'v');
		$this->drawLine(15,'v');
		$x=15;
		$xntrvl=3;
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
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
		$this->centerText($x+=$xntrvl,$y,'Reservation',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Advances',$xntrvl,'b');
		$y=1.8;
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		/* $this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b'); */
		
		$pb = array_slice($thdr, 6);
		$x=30;
		$xntrvl=6;
		$y=0.8;
		foreach($pb as $k => $col){
			if($k<6){
				if ($k % 2 == 0) {
					$hdr = explode(" ",$col);
					$hdr = str_replace('-', ' 20', $hdr[0]);
					//$hdr = str_replace('20', '2020', $hdr);
					$hdr = str_replace('IP', 'Initial Payment', $hdr);
					$this->centerText($x,$y,$hdr,$xntrvl,'b');
					$x+=$xntrvl;
				}
			}
		}
		
		//pr($pb);exit;
		$y=2.8;
		foreach($data as $hdrk=>$d){
			$x=29.9;
			$this->leftText(0.2,$y,$d['student'],'','');
			$this->centerText(8,$y,$d['year_level'],2,'');
			$this->leftText(10.2,$y,$d['section'],'','');
			$this->rightText(17.9,$y,$d['fee'],'','');
			$this->rightText(20.9,$y,$d['subsidy'],'','');
			$this->rightText(23.9,$y,$d['fee_dues'],'','');	
			
			$x=23.9;
			$this->rightText($x+=3,$y,$d['reservation'],'','');
			$this->rightText($x+=3,$y,$d['advances'],'','');
			$this->rightText($x+=3,$y,$d['pay1'],'','');
			$this->rightText($x+=3,$y,$d['bal1'],'','');
			$this->rightText($x+=3,$y,$d['pay2'],'','');
			$this->rightText($x+=3,$y,$d['bal2'],'','');
			$this->rightText($x+=3,$y,$d['pay3'],'','');
			$this->rightText($x+=3,$y,$d['bal3'],'','');
			//$this->rightText($x+=3,$y,$d['pay4'],'','');
			//$this->rightText($x+=3,$y,$d['bal4'],'','');
			
			/*
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
			} */
			$y++;
		}
		$this->rightText(47.9,36.8,'Page '.$page.'.0 of '.$total_page,'','');
		
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
		
		$x=0;
		$xntrvl=6;
		$y=0.8;
		foreach($pb as $k => $col){
			if ($k < 6) continue;
				if ($k % 2 == 0) {
					$hdr = explode(" ",$col);
					$hdr = str_replace('-', ' 20', $hdr[0]);
					//$hdr = str_replace('20', '2020', $hdr);
					$hdr = str_replace('IP', 'Initial Payment', $hdr);
					$this->centerText($x,$y,$hdr,$xntrvl,'b');
					$x+=$xntrvl;
				}
		}
		
		//pr($hdrk); exit();
		$y=2.8;
		foreach($data as $hdrk=>$d){
			//pr($d); exit();
			$x=-0.1;
			if(!isset($d['pay5'])){
				//pr($d); exit();
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
				$this->rightText($x+=3,$y,'--','','');
			}else{
				$this->rightText($x+=3,$y,$d['pay4'],'','');
				$this->rightText($x+=3,$y,$d['bal4'],'','');
				$this->rightText($x+=3,$y,$d['pay5'],'','');
				$this->rightText($x+=3,$y,$d['bal5'],'','');
				$this->rightText($x+=3,$y,$d['pay6'],'','');
				$this->rightText($x+=3,$y,$d['bal6'],'','');
				$this->rightText($x+=3,$y,$d['pay7'],'','');
				$this->rightText($x+=3,$y,$d['bal7'],'','');
				$this->rightText($x+=3,$y,$d['pay8'],'','');
				$this->rightText($x+=3,$y,$d['bal8'],'','');
				if(!isset($d['pay9'])){
					continue;
				}
				$this->rightText($x+=3,$y,$d['pay9'],'','');
				$this->rightText($x+=3,$y,$d['bal9'],'','');
				$this->rightText($x+=3,$y,$d['pay10'],'','');
				$this->rightText($x+=3,$y,$d['bal10'],'','');
			}
			
			
			/* $hdrx=0;
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
				
			} */
			$y++;
		}
		
		$this->rightText(47.9,36.8,'Page '.$page.'.1 of '.$total_page,'','');
	}
	
	function hidden_balance($data,$thdr,$total_page,$page){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 1,
			'width'=> 12,
			'height'=> 7.2,
			'cols'=> 51,
			'rows'=> 36,	
		);
		$this->section($metrics);
		$y=1;
		$this->drawBox(0,0,51,36);
		$this->drawMultipleLines(2,35,1,'h');
		$this->drawLine(1,'h',array(27,24));
		$this->drawLine(8,'v');
		$this->drawLine(10,'v');
		$this->drawLine(15,'v');
		$x=15;
		$xntrvl=3;
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$this->drawLine($x+=$xntrvl,'v',array(1,35));
		$y=1.2;
		$this->GRID['font_size']=8;
		$this->centerText(1,$y,'Name',6,'b');
		$this->centerText(8,$y,'Year',2,'b');
		$this->centerText(10,$y,'Section',5,'b');
		$x=15;
		$this->centerText(15,$y,'Total Fees',3,'b');
		$this->centerText(18,$y,'Subsidy',3,'b');
		$this->centerText(21,$y,'Fee Due',3,'b');
		$this->centerText(24,$y-0.3,'Initial',3,'b');
		$this->centerText(24,$y+0.4,'Payment',3,'b');
		$y=0.7;
		$this->centerText(27,$y,'Payment',24,'b');
		
		$pb = array_slice($thdr, 7);
		$x=27;
		$xntrvl=3;
		$y=1.7;
		foreach($pb as $k => $col){
			$hdr = explode(" ",$col);
			$hdr = str_replace('-', ' 20', $hdr[0]);
			$this->centerText($x,$y,ucfirst(strtolower($hdr)),$xntrvl,'b');
			$x+=$xntrvl;
		}
		
		$y=2.8;
		//pr($pb);exit;
		foreach($data as $hdrk=>$d){
			$x=29.9;
			$this->leftText(0.2,$y,$d['student'],'','');
			$this->centerText(8,$y,$d['year_level'],2,'');
			$this->leftText(10.2,$y,$d['section'],'','');
			$this->rightText(17.9,$y,$d['fee'],'','');
			$this->rightText(20.9,$y,$d['subsidy'],'','');
			$this->rightText(23.9,$y,$d['fee_dues'],'','');	
			$x=23.9;
			$this->rightText($x+=3,$y,$d['pay1'],'','');
			$this->rightText($x+=3,$y,$d['pay2'],'','');
			$this->rightText($x+=3,$y,$d['pay3'],'','');
			$this->rightText($x+=3,$y,$d['pay4'],'','');
			$this->rightText($x+=3,$y,$d['pay5'],'','');
			$this->rightText($x+=3,$y,$d['pay6'],'','');
			$this->rightText($x+=3,$y,$d['pay7'],'','');
			$this->rightText($x+=3,$y,$d['pay8'],'','');
			$this->rightText($x+=3,$y,$d['pay9'],'','');
			$y++;
		}
		$this->rightText(50.9,36.8,'Page '.$page.' of '.$total_page,'','');
	}
	
	
}
?>
	