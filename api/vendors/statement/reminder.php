<?php
require('vendors/fpdf17/formsheet.php');
class AccountReminder extends Formsheet{
	protected static $_width = 8.5;
	protected static $_height = 11;
	protected static $_unit = 'in';
	protected static $_orient = 'P';	
	protected static $curr_page = 1;
	protected static $page_count;
	function AccountReminder($data=null){
		$this->data = $data;
		$this->loadConfig($this->data['config']);
		$this->showLines = !true;
		$this->FPDF(AccountReminder::$_orient, AccountReminder::$_unit,array(AccountReminder::$_width,AccountReminder::$_height));
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
		$this->leftText(0,1,'Payment Reminder',10,'b');
		
		$this->section($metrics);
		$this->GRID['font_size']=10;
		$y=2.25;
		$this->leftText(0,$y,'Date Generated:',10,'');
		$this->leftText(5.5,$y, '23 Nov 2023, 1:00 PM',10,'b');

		

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

	function reminder(){
		$metrics = array(
			'base_x'=> 0.75,
			'base_y'=> 2,
			'width'=> 7.25,
			'height'=> 1.5,
			'cols'=> 38,
			'rows'=> 7.5,	
		);
		
		$this->section($metrics);
		$y=1;
		$this->GRID['font_size']=11;
		$this->leftText(0,$y,'Dear Parent/Guardian,',10,'');
		
		$notes ="First, we would like to extend our gratitude for choosing to join our Lake Shorean family this Academic Year 2023-2024.";
		$y++;
		$this->wrapText(-0.25,$y,$notes,38,'',1);

		$notes ="It is also in this light that we are writing this letter to share with you our appreciation of your on-time settlement of your dues with Lake Shore Educational Institution.";
		$y+=3;
		$this->wrapText(-0.25,$y,$notes,38,'',1);

		$student = $this->data['student'];
		$account = $this->data['account'];
		
		$y+=4;
		$x= 3;
		$this->rightText($x,$y,'Name of Student:',7,'');
		$this->leftText($x+8,$y++,$account['name'],10,'b');
		
		$this->rightText($x,$y,'Level / Section:',7,'');
		$this->leftText($x+8,$y++,$student['section'],10,'b');
		
		$this->rightText($x,$y,'Amount Due:',7,'');
		$this->leftText($x+8,$y++,'P1,000.00',10,'b');

		$this->rightText($x,$y,'Statement Date:',7,'');
		$this->leftText($x+8,$y++,'15 November 2023',10,'b');
		
		$y++;
		$notes ="Overall, this helps us ensure the continuous delivery of our quality services as an educational institution.";
		
		$this->wrapText(-0.25,$y,$notes,38,'',1);
		$y+=3;
		$this->leftText(0,$y,'Respectfully,',10,'');
		$y+=2;
		$this->leftText(0,$y,'LSEI Finance,',10,'');
	}
}