<?php
class RemittancesController extends AppController {

	var $name = 'Remittances';
	var $uses = array('Remittance','RemittanceBreakdown','RemittanceBooklet','RemittanceNoncash');

	function index() {
		$this->Remittance->recursive = 0;
		$remittances = $this->paginate();
		if($this->isAPIRequest()){
			foreach($remittances as $i=>$r){
				//pr($r);
				$rem['id'] = $r['Remittance']['id'];
				$rem['cashier_id'] = $r['Remittance']['cashier_id'];
				$rem['remittance_date'] = $r['Remittance']['remittance_date'];
				$rem['total_collection'] = $r['Remittance']['total_collection'];
				$rem['breakdown'] = array();
				$rem['booklets'] = array();
				$rem['noncash'] = array();
				foreach($r['RemittanceBreakdown'] as $a=>$rb){
					array_push($rem['breakdown'],$rb);
				}
				foreach($r['RemittanceBooklet'] as $a=>$rb){
					array_push($rem['booklets'],$rb);
				}
				foreach($r['RemittanceNoncash'] as $a=>$rb){
					array_push($rem['noncash'],$rb);
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
				$last = $this->Remittance->id;
				$details = $this->data['Remittance']['details'];
				$booklets = $this->data['Remittance']['booklets'];
				$noncash = $this->data['Remittance']['noncash'];
				foreach($details as $i=>$d){
					$d['remittance_id'] = $last;
					$details[$i] = $d;
				}
				foreach($booklets as $i=>$d){
					$d['remittance_id'] = $last;
					$booklets[$i] = $d;
				}
				foreach($noncash as $i=>$d){
					$d['remittance_id'] = $last;
					$noncash[$i] = $d;
				}
				$this->RemittanceBreakdown->saveAll($details);
				$this->RemittanceBooklet->saveAll($booklets);
				$this->RemittanceNoncash->saveAll($noncash);
				//pr($details);
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
