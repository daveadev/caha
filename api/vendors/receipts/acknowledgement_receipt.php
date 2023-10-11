<?php
require('vendors/fpdf17/formsheet.php');
require('vendors/utils/number_to_words.php');
class AcknowledgementReceipt extends Formsheet{
	//20cm x 10.7cm
	protected static $_width = 7.7;
	protected static $_height = 4.1;
	protected static $_unit = 'in';
	protected static $_orient = 'L';	
	protected static $curr_page = 1;
	protected static $page_count;
	
	function AcknowledgementReceipt($data=null){
		$this->data=$data;
		$this->showLines = true;
		$this->FPDF(AcknowledgementReceipt::$_orient, AcknowledgementReceipt::$_unit,array(AcknowledgementReceipt::$_width,AcknowledgementReceipt::$_height));
		$this->createSheet();
	}
	
	function template(){
		$this->showLines = !true;
		$metrics = array(
			'base_x'=> 0,
			'base_y'=> 0,
			'width'=> 7.7,
			'height'=>4.1,
			'cols'=> 28,
			'rows'=> 17,	
		);
		$this->section($metrics);
		//$this->DrawImage(0,0,7.7,4.1,__DIR__ ."/../images/ar-clean.png");


		$metrics = array(
			'base_x'=> 1,
			'base_y'=> 0.25,
			'width'=> 7.7,
			'height'=>4.4,
			'cols'=> 28,
			'rows'=> 17,	
		);
		$this->section($metrics);
		$y=5;
		$this->GRID['font_size']=11;
		$date = $this->data['transac_date'];
		$student = $this->data['student'];
		$section = $this->data['section'];
		$cashier = $this->data['cashier'];
		$amount = (float)$this->data['pay_due'];
		$details = $this->data['details'];

		$amt_money = number_format($amount,2,'.',',');
		$amt_words = NumberToWordsConverter::amountToWords($amount);
		$pay_for = array();
		foreach($details as $dtl):
			$pay_for[] = $dtl['description'];
		endforeach;
		$pay_for = implode('/', $pay_for);
		$this->leftText(18,$y,$date,'','');
		$y+=1;
	
		$this->leftText(4.5,$y,$student,'','');
		$y+=2;
		
		$this->leftText(4.5,$y,$section,'','');
		$y+=1;

		$y+=1;
		$this->leftText(-2.5,$y,$amt_words,16,'');

		$this->leftText(18,$y,$amt_money,'','');
		$y+=1;
		$this->leftText(6,$y,$pay_for,16,'');
		$y+=2;

		$this->leftText(18,$y,$cashier,'','');
		
	}
	
	
	
}
?>
	