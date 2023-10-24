<?php
class Student extends AppModel {
	var $name = 'Student';
	var $useDbConfig = 'ser';
	var $actsAs = 'Containable';
	var $consumableFields = array('id','status','sno','lrn','classroom_user_id','year_level_id','section_id','program_id','full_name', 'short_name','first_name','middle_name','last_name','prefix','suffix','gender','birthday','age','nationality','religion','mother_tongue','ethnic_group','weight','height','height_m2','bmi','bmi_category','height_fa');
	var $recursive = 2;
	var $virtualFields = array(
		'name'=>"CONCAT(Student.sno,' - ',Student.first_name,' ',Student.last_name)",
		'short_name'=>"CONCAT(LEFT(Student.first_name,1),'.',Student.last_name)",
		'full_name'=>"CONCAT(Student.first_name,' ',LEFT(COALESCE(Student.middle_name),1),' ',Student.last_name,' ',Student.suffix)",
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
			'fields' => array('id','name','description','department_id'),
			'order' => ''
		),
		'Section' => array(
			'className' => 'Section',
			'foreignKey' => 'section_id',
			'conditions' => '',
			'fields' => array('id','name'),
			'order' => ''
		),
		'Program' => array(
			'className' => 'Program',
			'foreignKey' => 'program_id',
			'conditions' => '',
			'fields' => array('id','name','description'),
			'order' => ''
		),
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	var $hasMany = array(
        'Assessment' => array(
            'className' => 'Assessment',
            'foreignKey' => 'student_id'
        )
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
														'Student.rfid LIKE'=>$name,
													)
											)
									)
								);
			//pr($students); exit();
			return $students;
		}
		
	}

	// Student Unified Search
	function search($keyword,$fields =null){
		// Import Inquiry Model
		App::import('Model','Inquiry');
		App::import('Model','MasterConfig');
		$INQ =  new Inquiry();
		$CNF =  new MasterConfig();
		// Set up conditions filter by fields and keywords
		if(!$fields):
			$cond = array("full_name LIKE"=>"$keyword%");
		else:
			$cond=array();
			// fields can be first_name, last_name, middle_name etc.
			foreach($fields as $f):
				$cond["$f LIKE"]="$keyword%"; // Build cond from fields
			endforeach;
			$cond = array('OR'=>$cond); // Make sure to use OR operator
		endif;

		// Define response fields
		$flds = array('id','lrn','full_name','program_id','year_level_id','student_type','department_id');
		// Find all Inquiry based on the filter
		$condInq = $cond;
		unset($condInq['OR']['sno LIKE']);
		unset($condInq['OR']['rfid LIKE']);
		$I = array();
		$includeNewStud = true;
		if($includeNewStud):
			$inqOptions = array(
				'conditions'=>$condInq,
				'recursive'=>-1,
				'fields'=>$flds
			);

			// Active SY to filter in ASM
			$ESP = $CNF->findBySysKey('ACTIVE_SY')['MasterConfig']['sys_value'];
			
			// Check INQ with ASM not yet enrolled 
			$inqOptions['joins']=array(
				array(
		            'table' => 'assessments', 
		            'alias' => 'Assessment',
		            'type' => 'INNER', 
		            'conditions' => array(
		                'Assessment.student_id = Inquiry.id',
		                array('Assessment.student_id LIKE'=>'LSN%'),
		                array('Assessment.status != '=>'NROLD'),
		                array('FLOOR(Assessment.esp)'=>$ESP)
		            )
		        )
			);
			$I = $INQ->find('all',$inqOptions);
		endif;
		
		// Update flds for students
		array_pop($flds); // Remove deparment_id
		array_pop($flds); // remove student_type
		$flds[]='sno'; // Add sno

		// Setup STU and contain relevant fields
		$STU =$this; 
		$STU->contain('Account.subsidy_status','Program','YearLevel','Section');
		// Find all students based on filter
		$S = $STU->find('all',array('conditions'=>$cond,'fields'=>$flds));


		// Setup RES to contain all RESults
		$RES = array();
		$hashes = array();
		// Loop into students and build stu object
		foreach($S as $i=>$SO):
			$stu =$SO['Student'];
			$stu['student_type']=null;
			if(isset($SO['Account']['subsidy_status'])):
				//Can be ESC , PUB or REG
				$stu['student_type']=null;$SO['Account']['subsidy_status'];
			endif;
			$stu['department_id']=$SO['YearLevel']['department_id'];
			$stu['section']=$SO['YearLevel']['name'].' '.$SO['Section']['name'];
			$stu['enroll_status']='OLD';
			// Add hash to check for duplicate names
			$hash = md5($stu['full_name']);
			$stu['hash']=$hash;
			if(!isset($hashes[$hash]))
				$hashes[$hash]= $hash;
			array_push($RES, $stu);
		endforeach;

		// Loop into students and build inq object
		foreach($I as $IO):
			$inq = $IO['Inquiry'];
			$inq['sno']='N/A'; // No SNO yet since new student
			$inq['enroll_status']='NEW';
			$hash = md5($inq['full_name']);
			// Add hash to check for duplicate names
			$inq['hash']=$hash;
			if(isset($hashes[$hash]))
				continue;
			array_push($RES, $inq);
		endforeach;

		// Sort RES by full_name field
		usort($RES, function($a, $b) {
		    return strcmp($a['full_name'], $b['full_name']);
		});

		// Sanitize RES to differentiate NEW and OLD Students 
		foreach($RES as $i=>$R):
			$sno = $R['sno']=trim($R['sno']);
			$R['full_name']= ucwords(strtolower($R['full_name']));
			// Prefix SNO if OLD
			$prefix = $R['enroll_status']=='OLD'?$sno:'NEW';
			$R['display_name']=sprintf("%s %s",$prefix,$R['full_name']);
			$RES[$i]=$R;
		endforeach;


		// Return RES inside Student key
		$RES = array('Student'=>$RES);
		return $RES;
	}

	function generateSNO($sy){
		$SNO_SERIES = 1;
		$SNO_PREFIX = sprintf('%d-',$sy);
		$cond =  array('Student.sno LIKE'=>$SNO_PREFIX.'%');
		$this->recursive=-1;
		
		$stuObj = $this->find('first',array('conditions'=>$cond,'order'=>array('sno'=>'desc')));

		if($stuObj)
			$SNO_SERIES =  (int)(str_replace($SNO_PREFIX, '', $stuObj['Student']['sno']));
		$SNO = $SNO_PREFIX .str_pad($SNO_SERIES+1, 4, 0, STR_PAD_LEFT);
		
		return $SNO;
	}
	function generateSID($school='1A',$dept=null){
		$prefix = $school.$dept;

		$length = 4;
		$isTaken = false;
		
		$this->recursive=-1;
		do{
			$min = pow(10, $length - 1) ;
			$max = pow(10, $length) - 1;
			$SID =  $this->luhnify(mt_rand($min, $max));
			$SID = $prefix.$SID;
			$isTaken = $this->findById($SID);

		}while($isTaken);
		return $SID;
	}
	protected function luhnify($number) {
	    $sum = 0;               // Luhn checksum w/o last digit
	    $even = true;           // Start with an even digit
	    $n = $number;

	    // Lookup table for the digitsums of 2*$i
	    $evendig = array(0, 2, 4, 6, 8, 1, 3, 5, 7, 9);

	    while ($n > 0) {
	        $d = $n % 10;
	        $sum += ($even) ? $evendig[$d] : $d;

	        $even = !$even;
	        $n = ($n - $d) / 10;
	    }

	    $sum = 9*$sum % 10;

	    return 10 * $number + $sum; 
	}
}
