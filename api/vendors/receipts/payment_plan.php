<?php
require('vendors/fpdf17/formsheet.php');
class PaymentPlanReceipt extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 14;
	protected static $_unit = 'in';
	protected static $_orient = 'P';
	
	function PaymentPlanReceipt(){
		$this->showLines = true;
		$this->_colorful=true;
		$this->FPDF(PaymentPlanReceipt::$_orient, PaymentPlanReceipt::$_unit,array(PaymentPlanReceipt::$_width,PaymentPlanReceipt::$_height));
		$this->createSheet();
	}

	function agreement(){
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.5,
			'width'=> 7.5,
			'height'=> 5,
			'cols'=> 20,
			'rows'=> 30,
			'border'=>true,	
		);
		$this->section($metrics);
		$lnX=1;
		$lnY=1;
		$date = 'August 01, 2023';
		$agrText = sprintf('This agreement made on %s, between:',$date);
		$this->leftText($lnX,$lnY,$agrText,20);

		$lnX = 2;
		$lnY++;
		$agrText = 'Lake Shore Educational Institution an educational institution duly organized and existing under the laws of the Republic of the Philippines, with office address at A. Bonifacio Street., Binan City, Laguna, Philippines, herein referred to as "LSEI"';
		$this->wrapText($lnX,$lnY,$agrText,18.25);

		$lnY +=4.5;
		$agrText = 'and';
		$this->centerText($lnX,$lnY,$agrText,16);

		$lnY++;
		$agrText = 'Legal Guardian who assumed parental responsibility of the student stated in Schedule 1, and herein referred to as "Parent"';
		$this->wrapText($lnX,$lnY,$agrText,18);

		$lnX = 1;
		$lnY+=4.5;
		$agrText = 'WHEREAS:';
		$this->leftText($lnX,$lnY,$agrText,20);
		
		$lnY+=3;
		$text = "At the date of this Agreement, Parent has remaining balance with LSEI in the amount of **Twelve Thousand One Hundred Fifty-Five Pesos (Php12,155.00)** during the School Year 2022 – 2023.";
		$this->complexFormat($lnX,$lnY,$text,1);


		if(0):

		$lnY+=2;
		$agrText ='1. At the date of this Agreement, Parent has remaining balance with LSEI in the amount of';
		$this->leftText($lnX,$lnY,$agrText,20);
		$agrText = 'Twelve Thousand One Hundred Fifty-Five Pesos (Php12,155.00) ';
		$lnY++;
		$this->leftText($lnX,$lnY,$agrText,20,'b');

		$agrText ='during the School Year 2022 – 2023.';
		$this->leftText($lnX,$lnY,$agrText,20);
		endif;
	}
	
	function complexFormat($x,$y,$text,$left=0.45,$right=0.5,$top=0){
		
		// Split the text into parts based on the **
		$parts = explode('**', $text);
		$cW = $this->GRID['cell_width'];
		$cH = $this->GRID['cell_height'];
		$cS = $this->GRID['font_size'];
		$cX = $cW * $x;
		$cY = $cH * $y;
		
		$this->SetXY($cX,$cY);
		$this->SetMargins($left,$top,$right);
		$this->Cell(0,$cH," ");
		foreach ($parts as $index => $part) {
		    if ($index % 2 === 0) {
		        // Regular font for non-bold parts
		        $this->Write($cH,$part);
		    } else {
		        // Bold font for parts between **
		        $this->SetFont('Arial', 'B', $cS);
		        $this->Write($cH, $part);
		        $this->SetFont('Arial', '', $cS); // Reset font to regular
		    }
		}
	}
}

?>