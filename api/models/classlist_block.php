<?php
class ClasslistBlock extends AppModel {
	var $name = 'ClasslistBlock';
	var $recursive = 2;
	var $useDbConfig = 'ser';
	var $order = array('ClasslistBlock.esp DESC');
	//var $cacheExpires = '+1 day';
	//var $usePaginationCache = true;
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'conditions' => '',
			'fields' => array(
				'Student.sno',
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
		)
	);
	
	function beforeFind($queryData){
		//pr($queryData);// exit();
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				if(!is_array($cond))
					break;
				$keys =  array_keys($cond); 
				$dept_id = null;
				$search = 'ClasslistBlock.department_id';
				if(in_array($search,$keys) && isset($cond[$search])){
					
					$value = $cond[$search];
					$sections = $this->Section->findByDepartmentId($value);
					//pr($sections);
					$section_ids = array_keys($sections);
					unset($cond[$search]);
					$cond['ClasslistBlock.section_id']=$section_ids;
					$dept_id = $value;
				}
				$search1 = ['ClasslistBlock.first_name LIKE','ClasslistBlock.middle_name','ClasslistBlock.last_name'];
				
				if(in_array($search1[0],$keys)){
					$val = array_values($cond);
					$students = $this->Student->findByName($val[0]);
					$student_ids= array_keys($students);
					unset($cond['ClasslistBlock.first_name LIKE']);
					unset($cond['ClasslistBlock.middle_name LIKE']);
					unset($cond['ClasslistBlock.last_name LIKE']);
					unset($cond['ClasslistBlock.sno LIKE']);
					unset($cond['ClasslistBlock.lrn LIKE']);
					$cond['ClasslistBlock.student_id']=$student_ids;
				}
				
				$search2 = 'ClasslistBlock.year_level_id';
				if(in_array($search2,$keys) && isset($cond[$search2])){
					$value = $cond[$search2];
					$sections = $this->Section->findByYrlvl($value);
					$yObj = $this->Section->YearLevel->findById($value);
					$dept_id =  $yObj['YearLevel']['department_id'];
					$section_ids = array_keys($sections);
					unset($cond[$search2]);
					unset($cond[$search]);
					$cond['ClasslistBlock.section_id']=$section_ids;
					$queryData['group']=array('ClasslistBlock.student_id');
				}

				$search3 = 'ClasslistBlock.esp';
				if(in_array($search3,$keys) && isset($cond[$search3])){
					$this->order = null;
					$value = $cond[$search3];
					$esp =  $value;
					$sy = floor($esp);
					unset($cond[$search3]);
					unset($cond[$search]);

					$min_esp =  $sy;
					$max_esp =  $esp;
					
					if(isset($_GET['section_id'])):
						$sectObj = $this->Section->findById($_GET['section_id']);
						$dept_id = $sectObj['Section']['department_id'];
					endif;
					
					if($dept_id=="SH"):
						$period =  round(($esp -  $sy)*10,2);
						if($period>2.5){
							$min_esp =  $sy+0.25;
							$max_esp =  $esp;
						}
					endif;

					$cond['ClasslistBlock.esp <=']=$max_esp;
					$cond['ClasslistBlock.esp >=']=$min_esp;
				}
				$conds[$i]=$cond;
			}
			$queryData['conditions']=$conds;
			//pr($queryData['conditions']);
		}
		
		return $queryData;
	}
	
	/* function findByStudId($sid){
		$students = $this->find('all',array('conditions'=>array('ClasslistBlock.student_id'=>$sid)));
		pr($students); exit();
		return $students;
	} */
}
