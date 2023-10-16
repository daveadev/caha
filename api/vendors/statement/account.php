<?php
require('vendors/fpdf17/formsheet.php');
require('vendors/fpdf17/barcode/php-barcode.php');

class AccountStatement extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 13;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	function AccountStatement($data=null){
		$this->data = $data;
		$this->showLines = !true;
		$this->FPDF(AccountStatement::$_orient, AccountStatement::$_unit,array(AccountStatement::$_width,AccountStatement::$_height));
		$this->createSheet();
	}
	function headerInfo(){
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.5,
			'width'=> 7.5,
			'height'=> 1.5,
			'cols'=> 38,
			'rows'=> 7.5,	
		);
		$this->section($metrics);
		$this->GRID['font_size']=12;
		$this->leftText(0,1,'Statement Of Account',10,'b');
		
		$student = $this->data['student'];

		$account = $this->data['account'];
		$sy = $account['school_year'];
		$this->section($metrics);
		$this->GRID['font_size']=10;
		$this->leftText(0,2.25,'Student Name',10,'');
		$this->leftText(5,2.25, $account['name'],10,'b');

		$this->leftText(0,3.25,'Level / Section',10,'');
		$this->leftText(5,3.25,$student['section'],10,'b');

		$this->leftText(0,4.25,'School Year',10,'');
		$this->leftText(5,4.25,$sy,10,'b');
		

		$this->DrawImage(23,-1,3,1,__DIR__ ."/../images/new_logo.jpg");

		$this->GRID['font_size']=9;
		$adddress = utf8_decode("A. Bonifacio St, Brgy. Canlalay Binãn, Laguna ");
		$contact = "(049) 511 4328 |  accounting@lakeshore.edu.ph";
		$this->leftText(24,3.5,$adddress,15);
		$this->leftText(24,4.25,$contact,15);
		
		$h = $this->GRID['cell_height'];
		$y=4;
		$this->data['last_y'] = $metrics['base_y']+ ($h*$y);
	}

	function paysched($type){
		$this->showLines = !true;
		$last_y = $this->data['last_y'];
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.3+$last_y,
			'width'=> 7.5,
			'height'=> 1.5,
			'cols'=> 38,
			'rows'=> 7.5,	
		);
		$this->section($metrics);
		$sched_key = 'paysched_'.$type;
		$sched_title =$type=='current'?'Payment Schedule':'Extension Payment Plan';
		$schedule = $this->data[$sched_key];
		$this->GRID['font_size']=10.5;
		$this->leftText(0,1.25,$sched_title,10,'b');
		$y=2.5;
		$this->GRID['font_size']=9.5;
		$this->leftText(0,$y,'Date Due',5,'b');
		$this->rightText(5,$y,'Fees',5,'b');
		$this->rightText(10,$y,'Payments',5,'b');
		$this->rightText(15,$y,'Balance',5,'b');
		//$this->rightText(16,$y,'Date Paid',5,'b');
		
		// Monthly schedule
		foreach($schedule as $sched):
			$y++;
			
			if(isset($sched['due_date'])):
				$this->leftText(0,$y,$sched['due_date'],5,'');
				$this->rightText(5,$y,$sched['due_amount'],5,'');
				$this->rightText(10,$y,$sched['paid_amount'],5,'');
				$this->rightText(15,$y,$sched['balance'],5,'');
				//$this->rightText(16,$y,'15 Sep 2023',5,'');
			else:

				$this->leftText(0,$y,'Total',5,'b');
				$this->rightText(5,$y,$sched['total_due'],5,'b');
				$this->rightText(10,$y,$sched['total_bal'],5,'b');
				$this->rightText(15,$y,$sched['total_pay'],5,'b');
				
			endif;
		endforeach;

		// Due Box
		$student = $this->data['student'];
		
		$this->SetDrawColor(27,151,68);
		$this->SetFillColor(195,237,209);
		$this->DrawBox(23,2,15,8,'DF');
		$this->leftText(24,3.5,'Student No.',4,'');
		$this->rightText(33,3.5,'Due Date',4,'');
		$this->leftText(24,7,'Please pay',4,'');

		$this->GRID['font_size']=14;
		$this->leftText(24,5,$student['sno'],4,'b');
		$this->rightText(33,5,'16 Oct 2023',4,'b');
		$this->leftText(24,8.5,'Php 1,000.00',4,'b');

		$this->GRID['font_size']=10;
		$note = "Kindly make payments at the school's accounting office on or before the deadline.";
		$this->wrapText(23,11,$note,14,'l',0.7);

		$h = $this->GRID['cell_height'];
		$this->data['last_y'] = $metrics['base_y']+ ($h*$y);
	}

	function ledger($type){
		$this->showLines = !true;
		$last_y = $this->data['last_y'];

		$ledger_key = 'ledger_'.$type;
		$ledger_title =$type=='current'?'Tuition Fee':'Ledger';
		$ledger = $this->data[$ledger_key];
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.125+$last_y,
			'width'=> 7.5,
			'height'=> 1.5,
			'cols'=> 38,
			'rows'=> 7.5,	
		);
		$this->section($metrics);

		$this->GRID['font_size']=10.5;
		$this->leftText(0,1.5,$ledger_title,10,'b');
		$y=2.5;
		$this->GRID['font_size']=9.5;
		$this->leftText(0,$y,'Date',5,'b');
		$this->leftText(5,$y,'Ref No',5,'b');
		$this->leftText(10,$y,'Details',8,'b');
		$this->rightText(22,$y,'Fees',5,'b');
		$this->rightText(27,$y,'Payments',5,'b');
		$this->rightText(32,$y,'Balance',5,'b');
		
		// Monthly schedule
		foreach($ledger as $entry):
			$y++;
			$this->leftText(0,$y,$entry['transac_date'],5,'');
			$this->leftText(5,$y,$entry['ref_no'],5,'');
			$this->leftText(10,$y,$entry['details'],8,'');
			$this->rightText(22,$y,$entry['fee'],5,'');
			$this->rightText(27,$y,$entry['pay'],5,'');
			$this->rightText(32,$y,$entry['bal'],5,'');
		endforeach;
		$y+=1.5;
		$this->centerText(0,$y,"************** Nothing follows ************** ",38,'i');

		$h = $this->GRID['cell_height'];
		$this->data['last_y'] = $metrics['base_y']+ ($h*$y);

		
	}
	function payment_ins(){

		$last_y = $this->data['last_y'];
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 10.5 ,
			'width'=> 7.5,
			'height'=> 1.5,
			'cols'=> 38,
			'rows'=> 7.5,	
		);
		$this->section($metrics);
		$this->GRID['font_size']=10;
		$this->leftText(0,0.5,'Payment Instruction',10,'b');
		$this->DrawBox(0,1,15.5,8,'D');
		
		$account = $this->data['account'];

		$this->GRID['font_size']=9;
		$this->leftText(1,2,'Student No.',10,'');
		$this->rightText(4,2,'Amount Due',10,'');
		$this->GRID['font_size']=11;
		$this->leftText(1,3,$account['sno'],10,'');
		$this->rightText(4,3,'Php 1,000.00',10,'');
		$note = "To avoid late fees, kindly make payments at the school's accounting office on or before the deadline.";
		$this->GRID['font_size']=8;
		$this->wrapText(0.75,6.75,$note,14,'l',0.7);
		$bx =2;
		$by = 11.5;
		$code=$account['sno'];
		$color = '000'; 
		$w = 0.021; 
		$h = 0.5; 
		$angle = 0; 
		$type = 'code128'; 
		Barcode::fpdf($this, $color, $bx, $by, $angle, $type, $code,$w,$h);  

		$this->leftText(16,2,'Notes:',10,'');
		$this->DrawBox(15.5,1,22.5,8,'D');
	}
}