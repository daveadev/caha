<?php
class StudentsController extends AppController {

	var $name = 'Students';
	var $uses = array('Student','MasterConfig');

	function index() {
		$this->Student->recursive = 0;
		$this->set('students', $this->paginate());
	}

	function view($id = null) {
		if($id=='search' && $this->isAPIRequest()):
			$keyword=$this->params['url']['keyword'];
			$fields=explode(',', $this->params['url']['fields']);
			$S = $this->Student->search($keyword,$fields);
			return $this->set('student',$S);
		endif;	
		if (!$id) {
			$this->Session->setFlash(__('Invalid student', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('student', $this->Student->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			if(!$this->data['Student']['id']):
				$_CONF = $this->MasterConfig->findBySysKey('SCHOOL_ALIAS');
				$_ALIAS = $_CONF['MasterConfig']['sys_value'];
				$SID = $this->Student->generateSID($_ALIAS,'S');
				$this->data['Student']['id'] = $SID;
			endif;
			$this->Student->create();
			if ($this->Student->save($this->data)) {
				$this->Session->setFlash(__('The student has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The student could not be saved. Please, try again.', true));
			}
		}
		$yearLevels = $this->Student->YearLevel->find('list');
		$sections = $this->Student->Section->find('list');
		$programs = $this->Student->Program->find('list');
		$this->set(compact('yearLevels','sections','programs'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid student', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Student->save($this->data)) {
				$this->Session->setFlash(__('The student has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The student could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Student->read(null, $id);
		}
		$yearLevels = $this->Student->YearLevel->find('list');
		$sections = $this->Student->Section->find('list');
		$programs = $this->Student->Program->find('list');
		$this->set(compact('yearLevels','sections','programs'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for student', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Student->delete($id)) {
			$this->Session->setFlash(__('Student deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Student was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function generateSIDS($count=1){
		
		for($ctr =0; $ctr<$count; $ctr++):
			$this->data = array('Student'=>array('id'=>null));
			if(!$this->data['Student']['id']):
				$_CONF = $this->MasterConfig->findBySysKey('SCHOOL_ALIAS');
				$_ALIAS = $_CONF['MasterConfig']['sys_value'];
				$SID = $this->Student->generateSID($_ALIAS,'S');
				$this->data['Student']['id'] = $SID;
			endif;
			$this->Student->create();
			$this->Student->save($this->data);
		endfor;
		
	}
}
