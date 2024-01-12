<?php
require('vendors/fpdf17/formsheet.php');
require('vendors/fpdf17/barcode/php-barcode.php');

class AccountStatement extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 11;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	protected static $_MAX_LINES=10;
	function AccountStatement($data=null){
		$this->data = $data;
		$this->loadConfig($this->data['config']);
		$this->showLines = !true;
		$this->FPDF(AccountStatement::$_orient, AccountStatement::$_unit,array(AccountStatement::$_width,AccountStatement::$_height));
		$this->createSheet();
	}
	function loadConfig($conf){
		$basePath = __DIR__;
		$path = $basePath .'/config/'.$conf;
		$contents = file_get_contents($path);
		$this->config = json_decode($contents,true);
		$this->config['artwork']['basePath']=$basePath.'/images/';

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
		$name  =$account['name'];
		if(mb_check_encoding($account['name'],'utf8')):
			$name =  utf8_decode($name);
		endif;

		$this->data['account']['name']=$name;
		$this->leftText(5,2.25, $name,10,'b');

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
		$ledger_key = 'ledger_'.$type;
		$ledger = $this->data[$ledger_key];
		$totalPages =  count($ledger)> AccountStatement::$_MAX_LINES?2:1;
		$this->GRID['font_size']=10.5;
		$this->leftText(0,1.25,$sched_title,10,'b');
		
		$this->rightText(33,1.25,"Page 1 of $totalPages",5,'b');
		$y=2.5;
		$this->GRID['font_size']=9.5;
		$this->leftText(0,$y,'Date Due',5,'b');
		$this->rightText(5,$y,'Fees',5,'b');
		$this->rightText(10,$y,'Payments',5,'b');
		$this->rightText(15,$y,'Balance',5,'b');
		//$this->rightText(16,$y,'Date Paid',5,'b');


		$schedLen = count($schedule);
		$dueNow = array('date'=>'NO DUE','amount'=>'0.00');

		if($schedLen){
			$dueIndex = $schedLen-1;
			
			while(!isset($schedule[$dueIndex]['due_now'])){
				$dueIndex--;
			}
			
			$dueNow = $schedule[$dueIndex]['due_now'];
			
			$this->data['account']['due_now']=$dueNow;	

		}
		

		// Monthly schedule
		foreach($schedule as $index=>$sched):
			$y++;
			if(isset($sched['due_date'])):
				if(in_array($index,$dueNow['months'])):
					$this->SetFillColor(195,237,209);
					$this->DrawBox(-0.5,$y-0.75,21,1,'FD');
				endif;
				$this->leftText(0,$y,$sched['due_date'],5,'');
				
				
				$this->rightText(5,$y,$sched['due_amount'],5,'');
				$this->rightText(10,$y,$sched['paid_amount'],5,'');
				$this->rightText(15,$y,$sched['balance'],5,'');
				
				//$this->rightText(16,$y,'15 Sep 2023',5,'');
			else:

				$this->leftText(0,$y,'Total',5,'b');
				$this->rightText(5,$y,$sched['total_due'],5,'b');
				$this->rightText(10,$y,$sched['total_pay'],5,'b');
				$this->rightText(15,$y,$sched['total_bal'],5,'b');
				
			endif;
		endforeach;
		if($schedLen==1){
			$y=12.5;
		}
		if(!$schedule || $schedLen<2){
			$y=5.5;
			$this->centerText(0,$y++," ********** NO APPLICABLE FEES  **********",21,'b');
			if($type!='current')
				$this->centerText(0,$y++,"+         Student did not apply for EPP          +",21,'');
			$this->centerText(0,$y++,"**************** Nothing follows **************** ",21,'i');
			$y=12.5;
		}
		if($schedLen==2)
			$y=12.5;
		// Due Box
		$account = $this->data['account'];
		$student = $this->data['student'];
		

		if(isset($account['billing_no'])):
			$billingNo = $account['billing_no'];
			$this->leftText(23,1.3,'Ref No.:',5,'');
			$this->leftText(25.5,1.3,$billingNo,5,'u');
		endif;
		
		$this->SetDrawColor(27,151,68);
		$this->SetFillColor(195,237,209);
		$this->DrawBox(23,2,15,8,'DF');
		$this->leftText(24,3.5,'Student No.',4,'');
		$this->rightText(33,3.5,'Due Date',4,'');
		$this->leftText(24,7,'Please pay',4,'');

		$this->GRID['font_size']=14;
		$this->leftText(24,5,$student['sno'],4,'b');
		if(isset($dueNow['date']))
		$this->rightText(33,5,$dueNow['date'],4,'b');
		$this->leftText(24,8.5,'Php '.$dueNow['amount'],4,'b');



		$this->GRID['font_size']=10;
		$note = "For any discrepancies/clarifications, please consult with the LSEI Finance Department immediately.";
		if(isset($this->config['reminder'])):
			$note =$this->config['reminder'];
		endif;
		$this->wrapText(23,11,$note,15,'l',0.7);

		$h = $this->GRID['cell_height'];
		$this->data['last_y'] = $metrics['base_y']+ ($h*$y);
	}

	function ledger($type, $start=0,$prevEntry=null){
		$this->showLines = !true;
		$last_y = $this->data['last_y'];

		$ledger_key = 'ledger_'.$type;
		$ledger_title =$type=='current'?'Ledger':'Ledger';
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
		$_ln_ctr=1;
		if($prevEntry):
			$y++;
			$this->leftText(0,$y,'******',5,'');
			$this->leftText(5,$y,'******',5,'');
			$this->leftText(10,$y,'Running Balance',8,'');
			$this->rightText(22,$y,'******',5,'');
			$this->rightText(26.5,$y,'******',5,'');
			$this->rightText(32,$y,$prevEntry['bal'],5,'');
			$_ln_ctr++;
		endif;
		for($lIndex=$start;$lIndex<count($ledger);$lIndex++):
			$entry= $ledger[$lIndex];
			$y++;
			$this->leftText(0,$y,$entry['transac_date'],5,'');
			$this->leftText(5,$y,$entry['ref_no'],5,'');
			$this->leftText(10,$y,$entry['details'],8,'');
			$this->rightText(22,$y,$entry['fee'],5,'');
			$this->rightText(27,$y,$entry['pay'],5,'');
			$this->rightText(32,$y,$entry['bal'],5,'');
			$_ln_ctr++;
			if($_ln_ctr>AccountStatement::$_MAX_LINES):
				$y+=1.5;
				$this->centerText(0,$y,"**************** See page 2 of 2 **************** ",38,'i');
				$this->payment_ins();
				$this->reply_slip();
				$this->createSheet();
				$this->pageHeader();
				$prevEntry = $ledger[$lIndex];
				return $this->ledger($type, $lIndex+1,$prevEntry);
			endif;
		endfor;
		$y+=1.5;
		$this->centerText(0,$y,"**************** Nothing follows **************** ",38,'i');
		$y++;
		$dateGen = sprintf("** Generated as of %s",date('d M Y , h:i:a **', time()));
		$this->centerText(0,$y,$dateGen,38,'i');

		
		$h = $this->GRID['cell_height'];
		$this->data['last_y'] = $metrics['base_y']+ ($h*$y);

		
	}
	function payment_ins(){
		$last_y = $this->data['last_y'];
		
		
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 7.75,
			'width'=> 7.7,
			'height'=> 1.5,
			'cols'=> 38,
			'rows'=> 7.5,	
		);
		if(isset($this->data['payment_ins_printed'])):
			$metrics['base_y']+=1;
		endif;

		$this->data['payment_ins_printed']=true;
		$this->section($metrics);

		$artwork = $this->config['artwork'];
		if($artwork['image']):
			$image = $artwork['basePath'].$artwork['image'];
			$iw = $artwork['width'];
			$ih = $artwork['height'];
			$this->DrawImage(24,-10,$iw,$ih,$image);
		endif;

		$this->GRID['font_size']=10;
		$this->leftText(0,0.5,'Payment Instruction',10,'b');
		$this->DrawBox(0,1,15.5,8,'D');
		$account = $this->data['account'];
		$dueNow = array('amount'=>'0.00');
		if(isset($account['due_now']))
			$dueNow = $account['due_now'];

		$this->GRID['font_size']=9;
		$this->leftText(1,2,'Student No.',10,'');
		$this->rightText(4,2,'Amount Due',10,'');

		if(isset($account['billing_no'])):
			$billingNo = $account['billing_no'];
			$this->leftText(29.5,0.5,'Reference No.:',5,'');
			$this->rightText(28,0.5,$billingNo,10,'b');
		endif;
		
		$this->GRID['font_size']=11;
		$this->leftText(1,3,$account['sno'],10,'');
		$this->rightText(4,3,'Php '.$dueNow['amount'],10,'');
		$note = "To avoid  late  fees, kindly  make  payments  at the ";
		$this->GRID['font_size']=8.25;
		$this->leftText(0.95,7,$note,5,'');
		$note = "school's accounting office on or before the deadline.";
		$this->leftText(0.95,7.8,$note,5,'');
		$note = "For your convenience, bring this  when paying.";
		$this->leftText(0.95,8.6,$note,5,'');
		$bx =2;
		$by = $metrics['base_y']+0.98;
		$code=$account['sno'];
		$color = '000'; 
		$w = 0.021; 
		$h = 0.5; 
		$angle = 0; 
		$type = 'code128'; 
		Barcode::fpdf($this, $color, $bx, $by, $angle, $type, $code,$w,$h);  
		$font_size = 9.5;
		$this->GRID['font_size']=$font_size;
		$this->leftText(16.5,2,'Notes:',10,'');
		$notes = $this->config['notes'];
		if(isset($this->config['font_size'])):
			$font_size = $this->config['font_size'];
		endif;
		$this->GRID['font_size']=$font_size;
		$this->DrawBox(15.5,1,22.5,8,'D');
		$this->wrapText(16.25,2.5,$notes,21,'i',0.7);
		$h = $this->GRID['cell_height'];
		$y=6.5;
		$this->data['last_y'] = $metrics['base_y']+ ($h*$y);
	}
	function reply_slip(){
		$last_y = $this->data['last_y'];
		if(isset($this->data['reply_slip_printed'])) return;
		$this->data['reply_slip_printed']=true;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.6+$last_y,
			'width'=> 7.5,
			'height'=> 1.5,
			'cols'=> 38,
			'rows'=> 10,	
		);
		$this->section($metrics);
		
		
		$this->GRID['font_size']=9;
		$this->SetFillColor(195,237,209);
		$this->DrawBox(-2.5,0.5,2,9.5,'F');
		$this->RotateText(-1.25,8.25,'R E P L Y  S L I P',90);
		$this->RotateText(-1.25,8.25,'R E P L Y  S L I P',90);
		$x =-10;
		$w = 58;
		$h =0.5;
		$x=0;
		for($lx=-3;$lx<$metrics['cols']+3;$lx+=1){
			$this->DrawLine($h,'h',array($lx,0.5));
		}
		
		$y=2;
		
		$student = $this->data['student'];
		$account = $this->data['account'];
		$billingNo = 'N/A';
		$this->GRID['font_size']=9;
		if(isset($account['billing_no'])):
			$billingNo =  $account['billing_no'];
			$this->leftText(0,$y,'Ref No.: ',3,'');
			$this->leftText(3,$y++,$billingNo ,10,'b');
		endif;
		$y+=0.5;
		$this->GRID['font_size']=8;
		$this->leftText(0,$y++,'Student Name / Level & Section:',10,'');
		
		$this->leftText(0,$y++, $account['name'],10,'b');
		$this->leftText(0,$y++, $student['section'],10,'b');
		
		
		$dueNow = array('date'=>'NO DUE','amount'=>'0.00');
		if(isset($account['due_now']))
			$dueNow = $account['due_now'];
		
		$dueShort =  sprintf("Php %s/ %s",$dueNow['amount'],$dueNow['date']);
		$y=2;
		$this->GRID['font_size']=9;
		$this->rightText(10,$y,'Due Details: ',5,'');
		$this->leftText(15.5,$y++,$dueShort,10,'b');
		$y+=0.5;
		$this->GRID['font_size']=8;
		$this->rightText(12,$y++,'Parent / Guardian:',4,'');
		$this->rightText(11.2,$y++,'Relationship:',4,'');
		$this->rightText(11.2,$y++,'Contact No.:',4,'');
		$y=3.6;
		$this->DrawLine($y++,'h',array(15.5,7));
		$this->DrawLine($y++,'h',array(15.5,7));
		$this->DrawLine($y,'h',array(15.5,7));
		$y=6;
		$this->DrawLine($y-1,'h',array(28,11));
		$this->leftText(35.5,$y-1.25,' / ',10,'');
		$this->leftText(28,$y,'Signature Over Printed Name  / Date Signed',10,'');
		
		$y+=1.5;
		$this->GRID['font_size']=7.5;
		$message= 'By signing this form, I hereby confirm the receipt of the Statement of Account with Ref No. '.$billingNo .' from Lake Shore Educational Insitution\'s Finance Office.';
		$this->centerText(0,$y,$message,40,'i');

	}
	function pageHeader(){
		$last_y = 0.25;
		$metrics = array(
			'base_x'=> 0.5,
			'base_y'=> 0.125+$last_y,
			'width'=> 7.5,
			'height'=> 1.5,
			'cols'=> 38,
			'rows'=> 7.5,	
		);
		$this->section($metrics);
		$y=0;
		$this->GRID['font_size']=9;
		$student = $this->data['student'];
		$account = $this->data['account'];
		$sy = $account['school_year'];
		$this->GRID['font_size']=10;
		$name  =trim($account['name']);
		$this->leftText(0,$y, $name .' / '.$student['section'],10,'b');
		$this->rightText(30,$y,"Statement of Account",7,'b');
		$y++;
		$this->leftText(0,$y,'School Year',5,'');
		$this->leftText(4,$y,$sy,10,'b');
		$this->rightText(30,$y,"Page 2 of 2",7,'');
		

		$h = $this->GRID['cell_height'];
		$this->data['last_y'] = $metrics['base_y']+ ($h*$y);
	}
}