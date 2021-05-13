<?php
class AssessmentsController extends AppController {

	var $name = 'Assessments';

	function index() {
		$this->Assessment->recursive = 0;
		$assessments = $this->paginate();
		//pr($this->paginate);exit();
		if($this->isAPIRequest()){
			$yrLevels = $this->Assessment->Student->YearLevel->find('list',array('fields'=>array('id','description')));
			$sections = $this->Assessment->Student->YearLevel->Section->find('list',array('fields'=>array('id','description')));

			foreach($assessments as $i=>$a){
				//pr($a);
				$data = $a['Assessment'];
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
				$data['section_id'] = $stud['section_id'];
				$assessments[$i]['Assessment'] = $data;
				
			}
			//exit();
		}
		
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
