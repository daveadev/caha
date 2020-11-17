<?php
require('vendors/fpdf17/formsheet.php');
class StudentAccountCollection extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 26;
	protected static $_unit = 'in';
	protected static $_orient = 'L';	
	protected static $curr_page = 1;
	protected static $page_count;
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
		$this->leftText(0,$y++,'Student Account Collection','','');
		$this->leftText(0,$y++,'School Year: 2020 - 2021','','');
		$this->leftText(0,$y++,'As of '.date("M d,Y h:i:s A"),'','');
	}
	
	function data($data,$total_page,$page){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 1.2,
			'width'=> 25,
			'height'=> 7,
			'cols'=> 90,
			'rows'=> 35,	
		);
		$this->section($metrics);
		$y=1;
		$this->drawBox(0,0,45,35);
		$this->drawMultipleLines(1,34,1,'h');
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
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
		$this->drawLine($x+=$xntrvl,'v');
		$y=0.7;
		$this->GRID['font_size']=8;
		$this->centerText(1,$y,'Name',7,'b');
		$this->centerText(8,$y,'Year',2,'b');
		$this->centerText(10,$y,'Section',5,'b');
		$x=15;
		$xntrvl=3;
		$this->centerText($x,$y,'Total Fees',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Subsidy',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Fee Due',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'IP',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Payment',$xntrvl,'b');
		$this->centerText($x+=$xntrvl,$y,'Balance',$xntrvl,'b');
		//pr($data);
		$y++;
		
		//pr($data);exit;
		foreach($data as $d){
			$x=29.9;
			$this->centerText(1,$y,$d['name'],7,'');
			$this->centerText(8,$y,$d['year_level'],2,'');
			$this->centerText(10,$y,$d['section'],5,'');
			$this->rightText(17.9,$y,number_format($d['total_fees'],2),'','');
			$this->rightText(20.9,$y,number_format($d['subsidy'],2),'','');
			$this->rightText(23.9,$y,number_format($d['fee_dues'],2),'','');
			/* $this->rightText(26.9,$y,number_format($d['payments'][0]['payment'],2),'','');
			$i=1;
			$isFirstPage=true;
			array_shift($d['payments']);
			foreach($d['payments'] as $pymnt){
				
				if($i < 4){
					$this->rightText($x,$y,number_format($pymnt['payment'],2),'','');
					$this->rightText($x+3,$y,number_format($pymnt['balance'],2),'','');
				}
				$i++;
				$x+=6;
			}*/
			$y++;
		}
		
	}
	
	
	function first_page_payment($payments){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 1.2,
			'width'=> 12,
			'height'=> 7,
			'cols'=> 42,
			'rows'=> 35,	
		);
		$this->section($metrics);
		$y=1;
		$this->GRID['font_size']=8;
		$x=23.9;
	//	pr($payments);
		foreach($payments as $d){
			$this->rightText($x,$y,$d['payment'],'','');
			$this->rightText($x,$y,$d['balance'],'','');
			$x+=6;
		}
		
	}
	
	
	
	
	
	
	
}
?>
	