<?php
class ReceiptsController extends AppController{
	var $name = 'Receipts';
	var $uses = array('MasterConfig','Student');
	function view(){
		$refNo= $trnDate= $student= $sno= $yearLevel= $section= $syFor="XXX";
		$trnDate=  $totalPaid = $cashier= $verify ="";
		$trnxDtls = array(
			array('item'=>'XXX','amount'=>0)
		);
		/*
		Array
		(
		    [ref_no] => OR 133329
		    [transac_date] => 21 Aug 2023
		    [student] => Santos, Ysmael Zeth Cyrus Marcellana
		    [sno] => 2023-0085  
		    [year_level] => Gr 7
		    [section] => CHARITY
		    [sy] => 23-24
		    [transac_details] => Array
		        (
		            [0] => Array
		                (
		                    [item] => Subsequent Payment
		                    [amount] => 425.00
		                )

		        )

		    [total_paid] => 425.00
		    [cashier] => admin
		    [verify_sign] => 7c00f733dab1f0c886b14ae5dae7f8ff
		)
		*/
		$data = array(
				'ref_no'=>$refNo,
				'transac_date'=>$trnDate,
				'student'=>$student,
				'sno'=>$sno,
				'year_level'=>$yearLevel,
				'section'=>$section,
				'sy'=>$syFor,
				'transac_details'=> $trnxDtls,
				'total_paid'=>$totalPaid,
				'cashier'=>$cashier,
				'verify_sign'=>'1A2khsfdso1sa'
			);
		$this->set(compact('data'));
	}
}