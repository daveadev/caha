<?php
class AccountAdjustmentsController extends AppController {

	var $name = 'AccountAdjustments';
	var $uses = array('AccountAdjustment','Ledger');

	function index() {
		
		$this->AccountAdjustment->recursive = 0;
		$this->set('accountAdjustments', $this->paginate());
	}

	function view($id = null) {
		if($id=='ref_no'):
			$sy = $_GET['sy'];
			$prefix = $_GET['prefix'];
			$refNo = $this->Ledger->generateREFNO($sy,$prefix);
			$ADJ = array('AccountAdjustment'=>array('ref_no'=>$refNo));
			return $this->set('accountAdjustment',$ADJ);
		endif;
		if (!$id) {
			$this->Session->setFlash(__('Invalid account adjustment', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('accountAdjustment', $this->AccountAdjustment->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->AccountAdjustment->create();
			if ($this->AccountAdjustment->save($this->data)) {
				$trnx = $this->data['AccountAdjustment'];
				$aid = $trnx['account_id'];
				$this->AccountAdjustment->Account->postTransaction($aid,$trnx);
				$this->Session->setFlash(__('The account adjustment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account adjustment could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->AccountAdjustment->Account->find('list');
		$this->set(compact('accounts'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid account adjustment', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->AccountAdjustment->save($this->data)) {
				$this->Session->setFlash(__('The account adjustment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account adjustment could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->AccountAdjustment->read(null, $id);
		}
		$accounts = $this->AccountAdjustment->Account->find('list');
		$this->set(compact('accounts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for account adjustment', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->AccountAdjustment->delete($id)) {
			$this->Session->setFlash(__('Account adjustment deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Account adjustment was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
