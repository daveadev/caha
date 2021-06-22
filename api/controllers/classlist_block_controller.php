<?php
class ClasslistBlocksController extends AppController {

	var $name = 'ClasslistBlocks';
	var $uses = array('ClasslistBlock', 'Section');

	function index() {
		
		$this->ClasslistBlock->recursive = 1;
		$classlistBlocks = $this->paginate();
		//pr($classlistBlocks);exit;
		
		if($this->isAPIRequest()){
			foreach($classlistBlocks as $i =>$CLB){
				//pr($CLB);
				$CLB['esp']=$CLB['ClasslistBlock']['esp'];
				$CLB['sy']=$CLB['ClasslistBlock']['esp'];
				$student = $CLB['Student'];
				$sno = (string)$student['sno'];
				//pr($student);
                $CLB['ClasslistBlock']['sno']= $sno . ' ';
                $CLB['ClasslistBlock']['name']= $student['class_name'];
                $CLB['ClasslistBlock']['gender']=$student['gender'];
				
				
				
				$section = $CLB['Section'];
                $CLB['ClasslistBlock']['department_id']=$section['YearLevel']['department_id'];
                $CLB['ClasslistBlock']['year_level_id']=$section['YearLevel']['id'];
                $CLB['ClasslistBlock']['section']=$section['name'];
                $CLB['ClasslistBlock']['program']=$section['Program']['name'];
				
				
				$classlistBlocks[$i]=$CLB;
			}
		}
		
		//$classlistObj = array(array('ClasslistBlock'=>$classlistObj));
		$this->set('classlistBlocks', $classlistBlocks);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid classlist block', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('classlistBlock', $this->ClasslistBlock->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->ClasslistBlock->create();
			$this->data['ClasslistBlock']['status']='ACT';
			if ($this->ClasslistBlock->save($this->data)) {
				$this->Session->setFlash(__('The classlist block has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The classlist block could not be saved. Please, try again.', true));
			}
		}
		$students = $this->ClasslistBlock->Student->find('list');
		$sections = $this->ClasslistBlock->Section->find('list');
		$this->set(compact('students', 'sections'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid classlist block', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ClasslistBlock->save($this->data)) {
				$this->Session->setFlash(__('The classlist block has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The classlist block could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ClasslistBlock->read(null, $id);
		}
		$students = $this->ClasslistBlock->Student->find('list');
		$sections = $this->ClasslistBlock->Section->find('list');
		$this->set(compact('students', 'sections'));
	}

	function delete($id = null) {
		$this->data['ClasslistBlock']['status'] = 'ARC';
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for classlist block', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ClasslistBlock->save($this->data)) {
			$this->Session->setFlash(__('Classlist block deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Classlist block was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
