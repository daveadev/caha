<?php
class Inquiry extends AppModel {
	var $name = 'Inquiry';
	var $useDbConfig = 'sem';
	//var $consumableFields = array('id','year_level_id','full_name', 'short_name','first_name','middle_name','last_name','suffix','gender');
	var $recursive = 2;
	var $virtualFields = array(
		'name'=>"CONCAT(Inquiry.id,' - ',Inquiry.first_name,' ',Inquiry.last_name)",
		'short_name'=>"CONCAT(LEFT(Inquiry.first_name,1),'.',Inquiry.last_name)",
		'full_name'=>"CONCAT_WS(Inquiry.first_name, LEFT(Inquiry.middle_name, 1), Inquiry.last_name, Inquiry.suffix)  ",
		'class_name'=>"UPPER(CONCAT(Inquiry.last_name,', ', Inquiry.first_name,' ',LEFT(Inquiry.middle_name,1),'. ',Inquiry.suffix))",
		'print_name'=>"(CONCAT(Inquiry.last_name,', ', Inquiry.first_name,' ',LEFT(Inquiry.middle_name,1),'. ',Inquiry.suffix))",
	); 
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'YearLevel' => array(
			'className' => 'YearLevel',
			'foreignKey' => 'year_level_id',
			'conditions' => '',
			'fields' => array('id','name','description'),
			'order' => ''
		),
		'CashierCollection' => array(
			'className' => 'CashierCollection',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);
	function findByName($name){
		//pr($name); 
		$url = $_GET['url'];
		if($url!='inquiries.json'){
			$Inquiries = $this->find('list',
								array('conditions'=>
									array('OR'=>array('Inquiry.first_name LIKE'=>$name,
														'Inquiry.first_name LIKE'=>$name,
														'Inquiry.middle_name LIKE'=>$name,
														'Inquiry.last_name LIKE'=>$name,
														'Inquiry.id LIKE'=>$name,
													)
											)
									)
								);
			//pr($Inquirys); exit();
			return $Inquiries;
		}
		
	}
}
