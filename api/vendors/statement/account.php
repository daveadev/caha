<?php
require('vendors/fpdf17/formsheet.php');
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
		$student = $this->data['student'];
		$level_section  = sprintf("%s %s",$student['year_level_id'],$student['section_id']);

		$account = $this->data['account'];
		$sy = $account['school_year'];
		$this->section($metrics);
		$this->GRID['font_size']=10;
		$this->leftText(0,1,'Student Name',10,'');
		$this->leftText(5,1, $account['name'],10,'b');

		$this->leftText(0,2,'Level / Section',10,'');
		$this->leftText(5,2,$level_section,10,'b');

		$this->leftText(0,3,'School Year',10,'');
		$this->leftText(5,3,$sy,10,'b');

		$h = $this->GRID['cell_height'];
		$y=4;
		$this->data['last_y'] = $metrics['base_y']+ ($h*$y);
	}

	function paysched($type){
		$this->showLines = !true;
		$last_y = $this->data['last_y'];
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.125+$last_y,
			'width'=> 7.5,
			'height'=> 1.5,
			'cols'=> 38,
			'rows'=> 7.5,	
		);
		$this->section($metrics);
		$sched_key = 'paysched_'.$type;
		$sched_title =$type=='current'?'Payment Schedule':'Extension Payment Plan';
		$schedule = $this->data[$sched_key];
		$this->GRID['font_size']=11;
		$this->leftText(0,1,$sched_title,10,'b');
		$y=2.5;
		$this->GRID['font_size']=10;
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

		// Totals
		
		$h = $this->GRID['cell_height'];
		$this->data['last_y'] = $metrics['base_y']+ ($h*$y);
	}

	function ledger(){
		$this->showLines = !true;
		$last_y = $this->data['last_y'];
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.125+$last_y,
			'width'=> 7.5,
			'height'=> 1.5,
			'cols'=> 38,
			'rows'=> 7.5,	
		);
		$this->section($metrics);

		$this->GRID['font_size']=11;
		$this->leftText(0,1,'Ledger',10,'b');
		$y=2.5;
		$this->GRID['font_size']=10;
		$this->leftText(0,$y,'Date',5,'b');
		$this->leftText(5,$y,'Ref No',5,'b');
		$this->leftText(10,$y,'Details',8,'b');
		$this->rightText(18,$y,'Fees',5,'b');
		$this->rightText(23,$y,'Payments',5,'b');
		$this->rightText(28,$y,'Balance',5,'b');
		
		// Monthly schedule
		$y++;
		$this->leftText(0,$y,'12 Sep 2023',5,'');
		$this->leftText(5,$y,'AR 1234',5,'');
		$this->leftText(10,$y,'Old Account',8,'');
		$this->rightText(18,$y,'1,000.00',5,'');
		$this->rightText(23,$y,'900.00',5,'');
		$this->rightText(28,$y,'100.00',5,'');
		$y++;
		$this->leftText(0,$y,'12 Sep 2023',5,'');
		$this->leftText(5,$y,'AR 1234',5,'');
		$this->leftText(10,$y,'Old Account',8,'');
		$this->rightText(18,$y,'1,000.00',5,'');
		$this->rightText(23,$y,'900.00',5,'');
		$this->rightText(28,$y,'100.00',5,'');
		
	}
}