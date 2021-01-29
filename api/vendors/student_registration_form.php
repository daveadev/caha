<?php
require('vendors/fpdf17/formsheet.php');
class StudentRegistrationForm extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 6.5;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	protected static $ctr = 1.8;
	protected static $grand_total = 0;
	
	function StudentRegistrationForm(){
		$this->showLines = !true;
		$this->FPDF(StudentRegistrationForm::$_orient, StudentRegistrationForm::$_unit,array(StudentRegistrationForm::$_width,StudentRegistrationForm::$_height));
		$this->createSheet();
	}
	
	function hdr(){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.25,
			'base_y'=> 0.25,
			'width'=> 6,
			'height'=> 0.5,
			'cols'=> 38,
			'rows'=> 4,	
		);
		$this->section($metrics);
		
		$y=1;
		$this->DrawImage(4,-0.7,0.7,0.7,__DIR__."/images/logo.png");
		$this->GRID['font_size']=10;
		$this->centerText(0,$y++,'LAKE SHORE EDUCATIONAL INSTITUTION',38,'b');
		$this->GRID['font_size']=7;
		$this->centerText(0,$y++,'BONIFACIO ST., CANLALAY, BINAN, LAGUNA',38,'');
		$this->centerText(0,$y++,'STUDENT REGISTRATION FORM',38,'b');
		$this->centerText(0,$y++,'ONE YEAR DURATION SCHOOL YEAR 2020-2021',38,'');
		$y++;
		$this->leftText(0,$y,'STUDENT ID:','','b');
		$this->leftText(15,$y++,'LEVEL/COURSE:','','b');
		$this->leftText(0,$y++,'NAME:','','b');
		$this->leftText(0,$y++,'','','');
	}
	
	function data(){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0.25,
			'base_y'=> 1.3,
			'width'=> 6,
			'height'=> 0.7,
			'cols'=> 33,
			'rows'=> 4,	
		);
		$this->section($metrics);
		$y=1;
		
		//$this->drawBox(0,0,33,4);
		//$this->drawLine(3,'v');
		//$this->drawLine(13,'v');
		//$this->drawLine(15,'v');
		//$this->drawLine(20,'v');
		//$this->drawLine(30,'v');
		//$this->drawLine(33,'v');
		
		$y=1;
		$this->leftText(3,$y,'SUBJECTS',10,'b');
		$this->centerText(13,$y,'UNITS',2,'b');
		$this->leftText(15.2,$y,'SECTION','','b');
		$this->leftText(20.2,$y,'SCHEDULE OF PAYMENT','','b');
		
		//FEES BREAKDOWN
		$y=2;
		$amount = '99,999.99';
		for($i=1;$i<10;$i++){
			$this->leftText(0.2,$y,'SUB'.$i,'','');
			$this->leftText(3.2,$y,'FEE '.$i,'','');
			$this->centerText(13,$y,'1.0',2,'');
			$this->leftText(15.2,$y,'SECTION '.$i,'','');
			$y++;
		}
		$this->drawLine($y-0.6,'h',array(0,19));
		$this->leftText(0.2,$y,'Total No. of Subject: 9','','b');
		$this->centerText(13,$y,'8.0',2,'b');
		
		//PAYMENT SCHED
		$y=2;
		$amount = '99,999.99';
		for($i=1;$i<15;$i++){
			$int= mt_rand(1262055681,1272509157);
			$sched = date("F d,Y",$int);
			$this->leftText(20.2,$y,$sched,'','');
			$this->rightText(30,$y,$amount,3,'');
			$y++;
		}
		$this->rightText(30,$y,$amount,3,'b');
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		$y=34;
		$this->leftText(2,$y,'IMPORTANT:',10,'b');
	}	
	
}
?>
	