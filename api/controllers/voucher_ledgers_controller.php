<?php
class VoucherLedgersController extends AppController {

	var $name = 'VoucherLedgers';

	function index() {
		$this->VoucherLedger->recursive = 0;
		$this->set('voucherLedgers', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid voucherLedger', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('voucherLedger', $this->voucherLedger->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->VoucherLedger->create();
			if ($this->VoucherLedger->save($this->data)) {
				$this->Session->setFlash(__('The voucherLedger has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The voucherLedger could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->VoucherLedger->Account->find('list');
		$this->set(compact('accounts'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid voucherLedger', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->VoucherLedger->save($this->data)) {
				$this->Session->setFlash(__('The voucherLedger has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The voucherLedger could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->VoucherLedger->read(null, $id);
		}
		$accounts = $this->VoucherLedger->Account->find('list');
		$this->set(compact('accounts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for voucherLedger', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->VoucherLedger->delete($id)) {
			$this->Session->setFlash(__('voucherLedger deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('voucherLedger was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
