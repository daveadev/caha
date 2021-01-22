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
	
	}
	
}
?>
	