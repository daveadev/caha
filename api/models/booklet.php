<?php
class Booklet extends AppModel {
	var $name = 'Booklet';

	var $belongsTo = array(

		'Cashier' => array(
			'className' => 'Cashier',
			'foreignKey' => 'cashier_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	var $hasMany = array(
		'CashierCollection' => array(
			'className' => 'CashierCollection',
			'foreignKey' => 'booklet_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	function updateSeries($ref_no,$booklet_id){
		$BKL = $this->findById($booklet_id);
		$series_no =(int)preg_replace('/\D/', '', $ref_no);
		$booklet = array('is_valid'=>false);
		if($BKL):
			$booklet = $BKL['Booklet'];
			$inRange = $series_no>=$booklet['series_start'] && $series_no<=$booklet['series_end'];
			if($inRange):
				$booklet['series_counter']= $series_no+1;
				$booklet['status']='ACTIV';
				if($booklet['series_counter']>$booklet['series_end'])
					$booklet['status']='CONSM';
				$this->save($booklet);
				$booklet['is_valid'] = true;
			else:
				$booklet['is_valid']=false;
			endif;
		endif;
		$bookletInfo = array();
		if($booklet['is_valid']):
			$bookletInfo['booklet_id'] = $booklet['id'];
			$bookletInfo['next_series_no'] = $booklet['series_counter'];
			$bookletInfo['status'] = $booklet['status'];
		endif;
		return $bookletInfo;
	}
	
}