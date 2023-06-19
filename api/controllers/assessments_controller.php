<?php
class AssessmentsController extends AppController {

	var $name = 'Assessments';

	function index() {
		$this->Assessment->recursive = 0;
		$order = array('Assessment.created' => 'desc');
		$this->paginate['Assessment']['order'] = $order;
		$this->paginate['Assessment']['recursive']=1;
		$assessments = $this->paginate();
		if($this->isAPIRequest()){
			$yrLevels = $this->Assessment->Student->YearLevel->find('list',array('fields'=>array('id','description')));
			$sections = $this->Assessment->Student->YearLevel->Section->find('list',array('fields'=>array('id','description')));

			foreach($assessments as $i=>$a){
				//pr($a); exit();
				$data = $a['Assessment'];
				$fees = array();
				$sched = array();
				if(isset($a['Student']['sno'])){
					$stud = $a['Student'];
					$data['name'] = $stud['full_name'];
					$data['sno'] = $stud['sno'];
					$yrlvId =  $a['Student']['year_level_id'];
					$sectId =  $a['Student']['section_id'];
					$yearLevel = "";
					$section = "";
					if(isset($yrLevels[$yrlvId]))
						$yearLevel = $yrLevels[$yrlvId];
					if(isset($sections[$sectId]))
						$section = $sections[$sectId];
					$data['year_level_id'] =$yrlvId;
					$data['year_level'] =$yearLevel;
					$data['section'] =$section;
				
				}else{
					$stud = $a['Inquiry'];
					$data['name'] = $stud['first_name']. ' '.$stud['middle_name'].' '.$stud['last_name'];
				
				}
				$data['year_level_id'] = $stud['year_level_id'];
				
				foreach($a['AssessmentFee'] as $fee){
					array_push($fees,$fee);
				}
				$subjects = array();
				foreach($a['AssessmentPaysched'] as $ps){
					$p['transaction_type_id']=$ps['transaction_type_id'];
					$p['assessment_id']=$ps['assessment_id'];
					$p['bill_month']=$ps['bill_month'];
					$p['due_amount']=$ps['due_amount'];
					$p['paid_amount']=$ps['paid_amount'];
					$p['due_date']=$ps['due_date'];
					$p['paid_date']=$ps['paid_date'];
					$p['status']=$ps['status'];
					$p['order']=$ps['order'];
					array_push($sched,$p);
				}
				
				foreach($a['AssessmentSubject'] as $sub){
					$s = array('section_id'=>$sub['section_id'],'subject_id'=>$sub['subject_id']);
					array_push($subjects,$s);
				}
				$data['Fee'] = $fees;
				$data['Paysched'] = $sched;
				$data['Subject'] = $subjects;
				$assessments[$i]['Assessment'] = $data;
				
			}
			//exit();
		}
		//exit();
		$this->set('assessments', $assessments);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid account fee', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('assessments', $this->Assessment->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Assessment->create();
			if ($this->Assessment->save($this->data)) {
				$this->Session->setFlash(__('The account fee has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account fee could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->Assessment->Account->find('list');
		$fees = $this->Assessment->Fee->find('list');
		$this->set(compact('accounts', 'fees'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid account fee', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Assessment->save($this->data)) {
				$this->Session->setFlash(__('The account fee has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account fee could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Assessment->read(null, $id);
		}
		$accounts = $this->Assessment->Account->find('list');
		$fees = $this->Assessment->Fee->find('list');
		$this->set(compact('accounts', 'fees'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for account fee', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Assessment->delete($id)) {
			$this->Session->setFlash(__('Account fee deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Account fee was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
