<?php
class RemittancesController extends AppController {

	var $name = 'Remittances';

	function index() {
		$this->Remittance->recursive = 0;
		$remittances = $this->paginate();
		if($this->isAPIRequest()){
			foreach($remittances as $i=>$r){
				$rem['id'] = $r['Remittance']['id'];
				$rem['cashier_id'] = $r['Remittance']['cashier_id'];
				$rem['remittance_date'] = $r['Remittance']['remittance_date'];
				$rem['total_collection'] = $r['Remittance']['total_collection'];
				$rem['breakdown'] = array();
				foreach($r['RemittanceBreakdown'] as $a=>$rb){
					array_push($rem['breakdown'],$rb);
				}
				$remittances[$i]['Remittance'] = $rem;
			}
		}
		//exit();
		$this->set('remittances', $remittances);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid remittance', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('remittance', $this->Remittance->read(null, $id));
	}

	function add() {
		//pr($this->data); exit();
		
		if (!empty($this->data)) {
			$this->Remittance->create();
			if ($this->Remittance->save($this->data)) {
				$this->Session->setFlash(__('The Remittance has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Remittance could not be saved. Please, try again.', true));
			}
			
		}
		
		
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Remittance', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Remittance->save($this->data)) {
				$this->Session->setFlash(__('The Remittance has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Remittance could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Remittance->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Remittance', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Remittance->delete($id)) {
			$this->Session->setFlash(__('Remittance deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Remittance was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	
}
