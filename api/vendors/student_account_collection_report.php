<?php
require('vendors/fpdf17/formsheet.php');
class StudentAccountCollection extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 13;
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
	
	function data(){
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
		$this->drawBox(0,0,42,35);
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
		
	}
}
?>
	