<?php
class ReservationsController extends AppController {

	var $name = 'Reservations';

	function index() {
		$this->Reservation->recursive = 0;
		$this->set('reservations', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid reservation', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('reservation', $this->reservation->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Reservation->create();
			if ($this->Feservation->save($this->data)) {
				$this->Session->setFlash(__('The reservation has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The reservation could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->Reservation->Account->find('list');
		$this->set(compact('accounts'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid reservation', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Reservation->save($this->data)) {
				$this->Session->setFlash(__('The reservation has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The reservation could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Reservation->read(null, $id);
		}
		$accounts = $this->Reservation->Account->find('list');
		$this->set(compact('accounts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for reservation', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Reservation->delete($id)) {
			$this->Session->setFlash(__('reservation deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('reservation was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
