<?php
class Household extends AppModel {
	var $name = 'Household';
	var $useDbConfig = 'ser';
	var $hasMany = array(
		'HouseholdMember' => array(
			'className' => 'HouseholdMember',
			'foreignKey' => 'household_id',
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
	function generateHONO(){
		$prefix = 44;
		$length = 5;
		$isTaken = false;
		do{
			$min = pow(10, $length - 1) ;
			$max = pow(10, $length) - 1;
			$HONO =  $this->luhnify($prefix.mt_rand($min, $max));  
			$isTaken = $this->findById($HONO);
		}while($isTaken);
		return $HONO;
	}

	protected function luhnify($number) {
	    $sum = 0;               // Luhn checksum w/o last digit
	    $even = true;           // Start with an even digit
	    $n = $number;

	    // Lookup table for the digitsums of 2*$i
	    $evendig = array(0, 2, 4, 6, 8, 1, 3, 5, 7, 9);

	    while ($n > 0) {
	        $d = $n % 10;
	        $sum += ($even) ? $evendig[$d] : $d;

	        $even = !$even;
	        $n = ($n - $d) / 10;
	    }

	    $sum = 9*$sum % 10;

	    return 10 * $number + $sum; 
	}

	function saveFamily($student_id,$houseInfo){
		$SID = $student_id;
		$HID = $this->generateHONO();
		$houseInfo['id']=$HID;
		$this->create();
		$this->save($houseInfo);
		$HMM = $this->HouseholdMember;
		$GRD = $HMM->Guardian;

		$member = array(
				'household_id'=>$HID,
				'type'=>'STU',
				'entity_id'=>$SID,
			);
		$HMM->create();
		$HMM->save($member);
		foreach($houseInfo['guardians'] as $gObj):
			$GRD->create();
			$GRD->save($gObj);
			$member = array(
				'household_id'=>$HID,
				'type'=>'GRD',
				'entity_id'=>$GRD->id,
			);
			$HMM->create();
			$HMM->save($member);
		endforeach;
	}
}