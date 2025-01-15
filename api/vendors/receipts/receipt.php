<?php
require('vendors/fpdf17/formsheet.php');
class OfficialReceipt extends Formsheet{
	protected static $_width = 6.29;
	protected static $_height = 8.5;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	
	function OfficialReceipt(){
		$this->showLines = !true;
		$this->FPDF(OfficialReceipt::$_orient, OfficialReceipt::$_unit,array(OfficialReceipt::$_width,OfficialReceipt::$_height));
		$this->createSheet();
		//$this->FONT = 'Courier';
	}
	
	function receipt(){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0,
			'base_y'=> 0,
			'width'=> OfficialReceipt::$_width,
			'height'=>OfficialReceipt::$_height,
			'cols'=> 20,
			'rows'=> 20 ,	
		);
		$this->section($metrics);
		//$this->DrawImage(0,0,4.25,6.4,__DIR__ ."/../images/receipt-clean.jpg");
	}
	
	
	function data($data){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0,
			'base_y'=> 0,
			'width'=> 4.25,
			'height'=> 6.7,
			'cols'=> 22,
			'rows'=> 34,	
		);
		$this->section($metrics);
	
		$y=1;
		$this->GRID['font_size']=10;
		//$this->SetTextColor(78,68,66);
		$this->rightText(15.8,4,'','','');
		$this->SetTextColor(231,31,54);
		$this->rightText(23,4,substr($data['ref_no'], 2),'','');
		
		$this->SetTextColor(44,39,41);
		$this->GRID['font_size']=8;
		$this->leftText(2,4.75,'X','','b');
		$this->GRID['font_size']=9;
		
		//Date
		$this->leftText(26,5.25,$data['transac_date'],'','');
		//Student No.
		$SOLDTO =  strtoupper(sprintf('%s | %s %s-%s',utf8_decode($data['student']),$data['sy'],$data['year_level'], $data['section']));
		$this->leftText(6.75,6,$SOLDTO,'','');
		$this->GRID['font_size']=8;
		$this->leftText(6.75,7,$SOLDTO,'','');
		$this->leftText(6.75,7.8,'N/A','','');
		$this->leftText(6.75,8.6,'N/A','','');
		//Receive payment from
		$this->GRID['font_size']=10;
		//$this->leftText(8,8.7,utf8_decode($data['student']),'','');
		$this->GRID['font_size']=9;
		//Payment for
		//$this->leftText(6,11,$data['sy'],'','');
		//Year
		//$this->leftText(15,10.75,$data['year_level'].' / ','','');
		//Section
		//$this->leftText(15,11.5,$data['section'],'','');
		
		$this->GRID['font_size']=10;
		$y=11.5;
		$this->GRID['font_size']=9;
		foreach($data['transac_details'] as $itm){
			//pr($itm);exit;
			$this->rightText(15,$y,'','','');
			$this->rightText(16,$y,strtoupper($itm['item']),'','');

			$this->rightText(31.25,$y,$itm['amount'],'','');
			$y++;
		}
		//pr($data); exit();
		//$data['check_details']='CHECK XXX';
		if(isset($data['check_details'])){
			$this->rightText(15,$y,'','','');
			$this->rightText(15,$y,$data['check_details'],'','');
			$y++;
		}
		$this->rightText(15,$y,'***** Nothing follows ****','','');
		
		$this->GRID['font_size']=9;
		
		//Total
		$total_sales =  number_format($data['total_paid'],2,'.',',');
		$this->rightText(31.25,40.25,$total_sales,'','');
		
		//Cashier
		$this->rightText(30,41.25,$data['cashier'],'','');
		
	
		
		

		$y++;
	
	}
	
}
?>
	