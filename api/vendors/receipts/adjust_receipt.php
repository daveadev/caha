<?php
require('vendors/fpdf17/formsheet.php');
class AdjustmentReceipt extends Formsheet{
	protected static $_width = 7;
	protected static $_height = 5.4;
	protected static $_unit = 'in';
	protected static $_orient = 'L';	
	protected static $curr_page = 1;
	protected static $page_count;
	
	function AdjustmentReceipt(){
		$this->showLines = !true;
		$this->FPDF(AdjustmentReceipt::$_orient, AdjustmentReceipt::$_unit,array(AdjustmentReceipt::$_width,AdjustmentReceipt::$_height));
		$this->createSheet();
	}
	
	function receipt($offset=0){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0+$offset,
			'base_y'=> 0,
			'width'=> 3.5,
			'height'=> 5.4,
			'cols'=> 38,
			'rows'=> 62,	
		);
		$this->section($metrics);
		//$this->DrawImage(0,0,7,5.4,__DIR__ ."/../images/adjust_memo.png");
	}
	
	
	function data($data,$offset=0){
		//$this->showLines = true;
		$metrics = array(
			'base_x'=> 0+$offset,
			'base_y'=> 0,
			'width'=> 3.5,
			'height'=> 5.4,
			'cols'=> 22,
			'rows'=> 34,	
		);
		$this->section($metrics);
		
		$y=1;
		$this->GRID['font_size']=10;
		//$this->SetTextColor(78,68,66);
		$this->rightText(15.8,4,'','','');
		$this->SetTextColor(231,31,54);
		$this->rightText(20.5,4,$data['ref_no'],'','');
		
		$this->SetTextColor(44,39,41);
		$this->GRID['font_size']=9;
		
		//Date
		$this->leftText(14.75,8,$data['transac_date'],'','');
		//Student No.
		$this->leftText(6,8,$data['sno'],'','');
		//Receive payment from
		$this->leftText(6,10,utf8_decode($data['student']),'','');
		//Payment for
		$this->leftText(15,12.5,$data['sy'],7,'');
		//Year
		$this->leftText(6,12.25,$data['year_level'].' / ','','');
		//Section
		$this->leftText(6,13,$data['section'],'','');
		
		
		$y=16;
		$this->GRID['font_size']=9;
		foreach($data['transac_details'] as $itm){
			//pr($itm);exit;
			$this->rightText(15,$y,'','','');
			$this->rightText(8,$y,$itm['item'],5,'');

			$this->rightText(13,$y,$itm['amount'],7,'');
			$y++;
		}
		//pr($data); exit();
		if(isset($data['check_details'])){
			$this->rightText(15,$y,'','','');
			$this->rightText(13,$y,$data['check_details'],7,'');
		}
		
		//Total
		$this->rightText(13,29,$data['total_paid'],7,'');
		
		//Cashier
		//$this->centerText(13,31.5,$data['cashier'],7,'');
		
	
		
		

		$y++;
	
	}
	
}
?>
	