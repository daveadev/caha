<?php
class MasterConfigsController extends AppController {

	var $name = 'MasterConfigs';
	var $uses = array('MasterConfig', 'MasterPeriod','YearLevel','Program', 'Department');
	function beforeFilter() {
		$this->Auth->allow('index');
	}
	function index() {
		$this->MasterConfig->recursive = 0;
		
		$masterConfig =  $this->paginate();
		$config = array();
		foreach($masterConfig as $mcv){
			
			$key = $mcv['MasterConfig']['sys_key'];
			$val = $mcv['MasterConfig']['sys_value'];
			if($key == 'DEFAULT_'){
				$val=json_decode($val,true);
			}
			$config[$key] = $val;
		}
		
		$start = $config['START_SY'];
		$active = $config['ACTIVE_SY'];
		$sy_obj = array();
		for($sy=$start; $sy<=$active; $sy++){
			$newstring = substr($sy,-2);
			$data = array('id'=>$sy,'label'=>$sy.'-'.($sy+1),'code'=>$newstring);
			
			array_push($sy_obj,$data);
		}
		
		$periods = $this->MasterPeriod->find('all', array('fields'=>array("MasterPeriod.id","MasterPeriod.name","MasterPeriod.alias","MasterPeriod.key")));
		$this->YearLevel->recursive = 0;
		$period_obj = array();
		foreach($periods as $period){
			$period['MasterPeriod']['alias']=json_decode($period['MasterPeriod']['alias'],true);
			array_push($period_obj,$period['MasterPeriod']);
		}

		$yearLevels = $this->YearLevel->find('all', array('fields'=>array("YearLevel.id" , "YearLevel.name" , "YearLevel.alias", "YearLevel.department_id")));
		$YL_obj = array();
		foreach($yearLevels as $yearlevel){
			array_push($YL_obj,$yearlevel['YearLevel']);
		}
		$semester_obj = array(
				array(
					'id'=>25,
					'name'=>'First Semester',
					'alias'=>array(
						'short'=>'1st',
						'full'=>'First Sem',
						),
					'key'=>'FRSTSEMS'
				),
				array(
					'id'=>45,
					'name'=>'Second Semester',
					'alias'=>array(
						'short'=>'2nd',
						'full'=>'Second Sem',
						),
					'key'=>'SCNDSEMS'
				)

			);
		$programs = $this->Program->find('list');
		$departments = $this->Department->find('list');
		$config['SCHOOL_YEARS'] = $sy_obj;
		$config['PERIODS'] = $period_obj;
		$config['SEMESTERS'] = $semester_obj;
		$config['DEPARTMENTS'] = $departments;
		$config['YEAR_LEVELS'] = $YL_obj;
		$config['PROGRAMS'] = $programs;
		$config = array(array('MasterConfig'=>$config));
		$this->set('masterConfigs', $config);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid master config', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('masterConfig', $this->MasterConfig->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->MasterConfig->create();
			if(isset($this->data['MasterConfig']['from'])){
				$sy = $this->data['MasterConfig']['sys_value'];
				$con = new ConnectionManager;
				$cn = $con->getDataSource('default');
				$cn->query("CREATE table rawscores_".$sy." (
				id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				recordbook_id int,
				student_id char(8),
				measurable_item_id int,
				score int,
				mantissa decimal(3,2),
				created timestamp,
				modified timestamp
				)");
				
				$cn->query("CREATE table conductscores_".$sy." (
				id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				recordbook_id int,
				student_id char(8),
				rbx_code char(8),
				score int,
				mantissa decimal(3,2),
				created timestamp
				)");
			}
			if ($this->MasterConfig->saveAll($this->data['MasterConfig'])) {
				$response = array();
				$response['status'] = true;
				$response['data'] = $this->data['MasterConfig'];
				echo json_encode($response);
				exit();
				
			} else {
				$response = array();
				$response['status'] = false;
				$response['data'] = $this->data['MasterConfig'];
				echo json_encode($response);
				exit();
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid master config', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->MasterConfig->save($this->data)) {
				$this->Session->setFlash(__('The master config has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The master config could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->MasterConfig->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for master config', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->MasterConfig->delete($id)) {
			$this->Session->setFlash(__('Master config deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Master config was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	function configs(){
		$fields = array('id','sys_key','sys_value');
		$sys_keys = array('SCHOOL_LOGO');
		$response['SchoolLogo'] = $this->MasterConfig->find('first',array('conditions'=>array('sys_key'=>$sys_keys),'fields'=>$fields));

		$sys_keys = array('SCHOOL_NAME','SCHOOL_ALIAS','SCHOOL_ADDRESS','SCHOOL_ID','SCHOOL_COLOR');
		$response['SchoolInfo'] = $this->MasterConfig->find('all',array('conditions'=>array('sys_key'=>$sys_keys),'fields'=>$fields));

		$sys_keys = array('START_SY','ACTIVE_SY','ACTIVE_DEPT');
		$response['SYConfig'] = $this->MasterConfig->find('all',array('conditions'=>array('sys_key'=>$sys_keys),'fields'=>$fields));
		
		
		foreach($response['SYConfig'] as $k => $d){
			if($d['MasterConfig']['sys_key']=='START_SY' || $d['MasterConfig']['sys_key']=='ACTIVE_SY'){
				$start_sy = $d['MasterConfig']['sys_value'];
				$end_sy = $d['MasterConfig']['sys_value']+1;
				$response['SYConfig'][$k]['MasterConfig']['sys_value_str'] = $start_sy.'-'.$end_sy; 
			}	
		}
		
		
	
		$sys_keys = array('DEFAULT_');
		$response['Default'] = $this->MasterConfig->find('first',array('conditions'=>array('sys_key'=>$sys_keys),'fields'=>$fields));

		$decode = json_decode($response['Default']['MasterConfig']['sys_value'],true);
		$i=array_pop(array_keys($response['SYConfig']))+1;
		foreach($decode as $nk=>$n){
			if($nk=='PERIOD' || $nk=='SEMESTER'){
				$response['SYConfig'][$i]['MasterConfig']['sys_key'] =$nk;
				$response['SYConfig'][$i]['MasterConfig']['sys_value'] =$n['alias']['full'];
				$response['SYConfig'][$i]['MasterConfig']['sys_object'] =$n;
				$i++;
			}else{
				$response['FormulaConfig'][$nk] =$n;
			}					
		}
		
		echo json_encode($response);
		exit;
	}

	function upload(){
		if(isset($_FILES['file'])){
			$file_name = basename($_FILES["file"]["name"]);
			$file_path = dirname(dirname(dirname(__FILE__))). DS . "webroot" . DS . "img" . DS . "school_logo" . DS ;
			$filename = $file_path .  $file_name;
			$size = $_FILES['file']['size'];
			
			//update duplicated filename
			$actual_name = pathinfo($filename,PATHINFO_FILENAME);
			$original_name = $actual_name;
			$extension = pathinfo($filename, PATHINFO_EXTENSION);
			$i = 1;
			while(file_exists($filename)){           
				$actual_name = (string)$original_name.' ('.$i.')';
				$filename = $file_path . $actual_name.".".$extension;
				$i++;
			}//end
			
			if(move_uploaded_file($_FILES['file']['tmp_name'],$filename)){
				echo "file uploaded successfully.";
			}else{
				echo "error uploading file.";
			}
		}
		exit;
	}
}
