<?php
class Student extends AppModel {
	var $name = 'Student';
	var $useDbConfig = 'ser';
	var $consumableFields = array('id','status','sno','lrn','classroom_user_id','year_level_id','section_id','program_id','full_name', 'short_name','first_name','middle_name','last_name','prefix','suffix','gender','birthday','age','nationality','religion','mother_tongue','ethnic_group','weight','height','height_m2','bmi','bmi_category','height_fa');
	var $recursive = 2;
	var $virtualFields = array(
		'name'=>"CONCAT(Student.sno,' - ',Student.first_name,' ',Student.last_name)",
		'short_name'=>"CONCAT(LEFT(Student.first_name,1),'.',Student.last_name)",
		'full_name'=>"CONCAT(Student.prefix, Student.first_name,' ',LEFT(Student.middle_name,1),' ',Student.last_name,' ',Student.suffix)",
		'class_name'=>"UPPER(CONCAT(Student.last_name,', ',Student.prefix, Student.first_name,' ',LEFT(Student.middle_name,1),'. ',Student.suffix))",
		'print_name'=>"(CONCAT(Student.last_name,', ',Student.prefix, Student.first_name,' ',LEFT(Student.middle_name,1),'. ',Student.suffix))",
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
		'Section' => array(
			'className' => 'Section',
			'foreignKey' => 'section_id',
			'conditions' => '',
			'fields' => array('id','name'),
			'order' => ''
		),
	);
	function findByName($name){
		//pr($name); 
		$url = $_GET['url'];
		if($url!='students.json'){
			$students = $this->find('list',
								array('conditions'=>
									array('OR'=>array('Student.first_name LIKE'=>$name,
														'Student.first_name LIKE'=>$name,
														'Student.middle_name LIKE'=>$name,
														'Student.last_name LIKE'=>$name,
														'Student.sno LIKE'=>$name,
														'Student.id LIKE'=>$name,
													)
											)
									)
								);
			//pr($students); exit();
			return $students;
		}
		
	}
}
