<?php
require('vendors/fpdf17/formsheet.php');
class PaymentPlanReceipt extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 14;
	protected static $_unit = 'in';
	protected static $_orient = 'P';
	
	function PaymentPlanReceipt(){
		$this->showLines = !true;
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
			'rows'=> 30
		);
		$this->section($metrics);
		$lnX=1;
		$lnY=5;
		$date = 'August 01, 2023';
		$agrText = sprintf('This agreement made on %s, between:',$date);
		$this->leftText($lnX,$lnY,$agrText,20);

		$lnX = 2;
		$lnY++;
		$agrText = 'Lake Shore Educational Institution an educational institution duly organized and existing under the laws of the Republic of the Philippines, with office address at A. Bonifacio Street., Biñan City, Laguna, Philippines, herein referred to as "LSEI"';
		$agrText = utf8_decode($agrText);
		$this->wrapText($lnX,$lnY,$agrText,18.25);

		$lnY +=4;
		$agrText = 'and';
		$this->centerText($lnX,$lnY,$agrText,16);

		$lnY++;
		$agrText = 'Legal Guardian who assumed parental responsibility of the student stated in Schedule 1, and herein referred to as "Parent"';
		$this->wrapText($lnX,$lnY,$agrText,18);

		$lnX = 1;
		$lnY+=4;
		$agrText = 'WHEREAS:';
		$this->leftText($lnX,$lnY,$agrText,20);
		

		$this->leftText($lnX,$lnY+1.75,"1.",'');
		$lnY+=3;
		$text = "At the date of this Agreement, Parent has remaining balance with LSEI in the amount of **Twelve Thousand One Hundred Fifty-Five Pesos (Php12,155.00)** during the School Year - 2023.";
		$this->complexFormat($lnX,$lnY,$text,1.05);
		$this->leftText($lnX,$lnY+1,"2.",'');
		$lnY+=2.25;
		$text = "Parent requested extension of the unpaid balance to the succeeding School Year 2023 - 2024 after experiencing financial difficulties during the period of School Year 2022 - 2023.";
		$this->complexFormat($lnX,$lnY,$text,1.05);

		$this->leftText($lnX,$lnY+1,"3.",'');
		$lnY+=2.25;
		$text="LSEI taking into account the request of the Parent, LSEI has prepared a Special Extension Payment Plan (Schedule 1).";

		$this->complexFormat($lnX,$lnY,$text,1.05);
		$lnY+=2;
		$this->centerText($lnX,$lnY++,"LSEI Special Extension Payment Plan",20);
		$this->centerText($lnX,$lnY++,"Schedule 1",20);
		$lnY = $this->student_info($lnX,$lnY);
		$lnY = $this->payment_schedule($lnX,$lnY);

		$lnY+=1;
		$this->leftText($lnX,$lnY+1,"4.",'');
		$lnY+=2.25;
		$text="It is understood that:";
		$this->complexFormat($lnX,$lnY,$text,1.05);

		$lnX+=0.5;
		$this->leftText($lnX,$lnY,"4.1",'');
		$this->leftText($lnX+0.75,$lnY,"Schedule 1 refers only to the unpaid amount due for School Year 2022 - 2023.",'');
		$lnY+=1.05;
		$this->leftText($lnX,$lnY,"4.2",'');
		$this->leftText($lnX+0.75,$lnY,"Payment due for School Year 2023 - 2024 must be made on time.",'');
		$lnY+=1.05;
		$this->leftText($lnX,$lnY,"4.3",'');
		$this->leftText($lnX+0.75,$lnY,"Default in payment may result to non-admission of students the following School Year.",'');
		$lnY+=1.05;
		$this->leftText($lnX,$lnY,"4.4",'');
		$this->leftText($lnX+0.75,$lnY,"Credentials will not be released unless all accounts have been cleared.",'');
		$lnY+=1.05;

		$lnX-=0.5;
		$this->leftText($lnX,$lnY+1,"5.",'');
		$lnY+=2.25;
		$text="Parent acknowledges that this agreement has been properly discussed by LSEI and that Parent understands and accepts the terms and conditions stated herein.";
		
		$this->complexFormat($lnX,$lnY,$text,1.05);

		$lnY+=3;
		$this->leftText($lnX,$lnY,"Conforme:",20);
		$this->leftText($lnX+8,$lnY,"Witness:",20);

		$lnY+=4;
		$this->drawLine($lnY-1,'h',array($lnX,6));
		$this->GRID['font_size']=8;
		//$this->SetTextColor(100,100,100);
		$this->leftText($lnX+4,$lnY-1.25,"/ mm / dd / yyyy ",20,'');
		//$this->SetTextColor(0,0,0);
		$this->GRID['font_size']=9.5;
		$this->leftText($lnX,$lnY,"Juan Dela Cruz Sr.",20,'b');

		$this->drawLine($lnY-1,'h',array($lnX+8,4.5));
		$this->leftText($lnX+8,$lnY,"Cristina Casalla",20,'b');
		$lnY+=1;
		$this->leftText($lnX,$lnY,"Parent of Child",20,'i');
		$this->leftText($lnX+8,$lnY,"Finance Officer",20,'i');

		
	
	}

	function student_info($x,$y,$info=null){
		$w =  19;
		$h = 4;
		$colX = $x+($w/1.5);
		$this->DrawBox($x,$y,$w,$h,'D');
		$this->drawLine($colX,'v',array($y,$h));
		$this->drawLine($y+1.9,'h',array($x,$w));
		$this->drawLine($x+3.5,'v',array($y,$h));
		$x+=0.5;
		$y+=1.25;
		$this->leftText($x-0.25,$y,"Name of Student:",3);
		$this->leftText($x+3.25,$y,"Juan Dela Cruz",3,'b');

		$this->leftText($colX+0.25,$y,"Old Balance:",3);
		$this->leftText($colX+3.25,$y,"Php 12,155.00",3,'b');
		$y+=1.9;
		$this->leftText($x-0.25,$y,"Name of Parent:",3);
		$this->leftText($x+3.25,$y,"Juan Dela Cruz Sr.",3,'b');
		$this->leftText($colX+0.25,$y,"Installments:",3);
		$this->leftText($colX+3.25,$y,"9 months",3,'b');

		return $y;
	}

	function payment_schedule($x,$y, $info=null){
		$noOfLines = 15;
		$totalLines =  $noOfLines;
		$noOfTables = 1;
		$dispTable = 1;
		if($noOfLines>12):
			$noOfLines = floor($noOfLines/2);
			$noOfTables =2;
		endif;
		$y+=2.5;
		$this->centerText($x,$y,"Payment Schedule",20);
		$lnH = 1.25;
		$ofY = -0.25;
		$lines = array();
		$w =  10;
		$h = ($noOfLines+2)*$lnH;
		$boxX = $x+5;
		
		if($noOfTables>1):
			$w=8;
			$boxX = $x+1;
		endif;
		$colW =  $w/2;
		$colX = $boxX+$colW;
		$y++;
		$posY =  $y;
		$topLn = 0;
		$btmLn = 0;
		do{
			$this->drawLine($colX,'v',array($posY,$h));
			$this->DrawBox($boxX,$posY,$w,$h,'D');

			$y = $posY+$lnH;
			$this->drawLine($y,'h',array($boxX,$w));
			$topLn = $y+$ofY;
			$coor = array($boxX,$topLn,$colX);
			array_push($lines,$coor);
			
			$ln=0;
			do{
				$y+=$lnH;
				$ln+=$lnH;
				$this->drawLine($y,'h',array($boxX,$w));
				$coor = array($boxX,$y+$ofY,$colX);
				array_push($lines,$coor);
				$btmLn = $coor[1];
			}while($ln<$h-$lnH);
			$dispTable++;

			if($noOfTables>1):
				$boxX = $boxX+$w+1;
				$colX =  $boxX+$colW;
			endif;
		}while($dispTable<=$noOfTables);
		$lnCtr=1;
		foreach($lines as $i=>$ln):
			$lx =  $ln[0];
			$ly =  $ln[1];
			$lc =  $ln[2];
			if($i==count($lines)-1):
				$this->rightText($lx+$ofY,$ly,"Total Due",$colW,'b');
				$this->rightText($lc+$ofY,$ly,"Php 12,155.00",$colW,'b');
			elseif($ly==$topLn):
				$this->centerText($lx,$ly,"Due Date",$colW);
				$this->centerText($lc,$ly,"Amount Due",$colW);
			else:
				if($ly==$btmLn):
					$this->centerText($lx-$ofY,$ly-0.25,"-----------------------",$colW);
					$this->rightText($lc+$ofY,$ly,"Next Table",$colW);
				elseif($i<=$totalLines):
					$this->leftText($lx-$ofY,$ly,"$i Sep dd, YYYY",$colW);
					$this->rightText($lc+$ofY,$ly,"Php 1,000.00",$colW);
				endif;
			endif;
			$lnCtr++;

		endforeach;
		
		return $y;
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