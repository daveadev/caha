<?php
class ClasslistIrregular extends AppModel {
	var $name = 'ClasslistIrregular';
	var $recursive = 2;
	
	var $useDbConfig = 'ser';
	//var $cacheExpires = '+1 day';
	//var $usePaginationCache = true;
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'conditions' => '',
			'fields' => array(
				'Student.id',
				'Student.sno',
				'Student.lrn',
				'Student.classroom_user_id',
				'Student.gender',
				'Student.short_name',
				'Student.full_name',
				'Student.class_name',
				'Student.status',
				),
			'order' => ''
		),
		'Section' => array(
			'className' => 'Section',
			'foreignKey' => 'section_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Subject' => array(
			'className' => 'Subject',
			'foreignKey' => 'subject_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	function beforeFind($queryData){
		if($conds=$queryData['conditions']){
			$dept_id = null;
			foreach($conds as $i=>$cond){
				if(!is_array($cond))
					continue;
				$keys =  array_keys($cond);
				$search = 'ClasslistIrregular.department_id';
				if(in_array($search,$keys)){
					$value = $cond[$search];
					$sections = $this->Section->findByDepartmentId($value);
					$section_ids = array_keys($sections);
					$dept_id = $cond[$search];
					unset($cond[$search]);
					$cond['ClasslistIrregular.section_id']=$section_ids;
				}
				$search1 = ['ClasslistIrregular.first_name LIKE','ClasslistIrregular.middle_name','ClasslistIrregular.last_name'];
				
				if(in_array($search1[0],$keys)){
					$val = array_values($cond);
					$students = $this->Student->findByName($val[0]);
					$student_ids= array_keys($students);
					unset($cond['ClasslistIrregular.first_name LIKE']);
					unset($cond['ClasslistIrregular.middle_name LIKE']);
					unset($cond['ClasslistIrregular.last_name LIKE']);
					$cond['ClasslistIrregular.student_id']=$student_ids;
				}

				$conds[$i]=$cond;
			}
			
			
			$index = $esp =  $sy =  $section_id = null;

			foreach($conds as $i=>$cond){
				if(isset($cond['ClasslistIrregular.esp'])){
					$esp = $cond['ClasslistIrregular.esp'];
					$sy = floor($esp);
					$index = $i;
				}
				if(isset($cond['ClasslistIrregular.section_id !='])){
					$section_id = $cond['ClasslistIrregular.section_id !='];
				}
			}
			//pr($conds); pr('ssssssssssss');
			if(isset($conds[$index])) unset($conds[$index]);

			if($esp && $sy){	
				if($section_id){
					$Section = new Section;
					$Section->recursive=-1;
					$section = $Section->findById($section_id);
					$dept_id = $section['Section']['department_id'];
				}

				

				$min_esp =  $sy;
				$max_esp =  $esp;

				if($dept_id=="SH"):
					$period =  round(($esp -  $sy)*10,2);
					if($period>2.5){
						$min_esp =  $sy+0.25;
						$max_esp =  $esp;
					}
				endif;

				array_push($conds,array('ClasslistIrregular.esp <='=>$max_esp));
				array_push($conds,array('ClasslistIrregular.esp >='=>$min_esp));
				
			}
			//pr($conds); exit();
			$queryData['conditions']=$conds;
		}
		return $queryData;
	}
	
	
}
