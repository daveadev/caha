<?php
class Billing extends AppModel {
	var $name = 'Billing';
	var $actsAs = array('Containable'); 
	var $belongsTo = array(
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Student' => array(
            'className' => 'Student',
            'foreignKey' => 'account_id',
            'order'=>array(
            	'Student.section_id',
            	'Student.gender',
            	'Student.last_name',
            	'Student.first_name',
            ),
        )
     );

     function generateREFNO($sy,$prefix=null){
		$REFNO_SERIES = 0;
		$syID =  substr($sy.'', -2);
		$REFNO_PREFIX = sprintf('%s%s',$prefix,$syID);
		$cond =  array('Billing.id LIKE'=>$REFNO_PREFIX.'%');
		$this->recursive=-1;
		
		$billObj = $this->find('first',array('conditions'=>$cond,'order'=>array('id'=>'desc')));
		if($billObj):
			$REFNO_SERIES =  (int)(str_replace($REFNO_PREFIX, '', $billObj['Billing']['id']));
		endif;
		$REFNO = $REFNO_PREFIX .str_pad($REFNO_SERIES+1, 6, 0, STR_PAD_LEFT);
		
		return $REFNO;
	}

    function checkAccount(&$account,$statement){
        $dueNow  =$account['due_now'];
        $hashObj = $this->checkHashObj($account['id'], $dueNow['date'], $dueNow['amount']);
        $hasID = isset($hashObj['id']);
        if(!$hasID && $hashObj['account_id']){
        	$sy  =floor($account['esp']);
			$prefix = substr($account['id'],0,2).'B';
            $BNO = $this->generateREFNO($sy,$prefix);
            $hashObj['id']=$BNO;
            $hashObj['sy']=$sy;
            $hashObj['statement']=json_encode($statement);
            $this->create();
            $this->save($hashObj);
        }else{
            $account['billing_no']=$hashObj['id'];
            $hashObj['statement']=json_encode($statement);
            $this->save($hashObj);
        }
        
        $account['billing_no'] =  $hashObj['id'];	
    }
    protected function checkHashObj($account_id,$date,$amount){
        $hashObj  =  $this->generateHash($account_id,$date,$amount);
        
        $billObj = $this->find('first',array('conditions'=>array('hash'=>$hashObj['hash'])));
        
        if($billObj):
        	$hashObj['id'] = $billObj['Billing']['id'];
            $hashObj['statement'] = $billObj['Billing']['statement'];
        endif;
        return $hashObj;
    }
    protected function generateHash($account_id,$date,$amount){
        $dateStr =  date('Y-m-d',strtotime($date));
        $amountNum  = floatval(str_replace(',', '', $amount));
        $text = sprintf('%s-%s-%s',$account_id,$dateStr,$amountNum);

        $hash =  md5($text);
        $hashObj = array(
        	'account_id'=>$account_id,
        	'due_date'=>$dateStr,
        	'due_amount'=>$amountNum,
        	'hash'=>$hash
        );
        return $hashObj;
    }
    protected function getLatestBills($year,$month) {
        $contains = array('Student.sno',
        				 'Student.full_name',
                         'Student.print_name',
        				 'Student.year_level_id',
        				 'Student.section_id',
                         'Student.last_name',
                         'Student.first_name',
                         'Student.middle_name',
                         'Student.mobile',
                         'Student.email',
        				 'Student.Section.name',
        				 'Student.YearLevel.name',
        				);
        $joins =array(
            array(
                    'table' => "(
                            SELECT
                                account_id,
                                MAX(bill.id) AS eff_bill_id,
                                GROUP_CONCAT(bill.id ORDER BY bill.id ASC) AS bill_ids,
                                GROUP_CONCAT(bill.due_amount ORDER BY bill.id ASC) AS bill_amounts
                            FROM
                                billings AS bill
                            WHERE 
                             YEAR(bill.due_date) = $year AND  MONTH(bill.due_date) =  $month
                            GROUP BY
                                bill.account_id  
                            )",
                    'alias' => 'LatestBill',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Billing.id = LatestBill.eff_bill_id'
                    )
                ),
            array(
                'table'=>'ledgers',
                'alias'=>'Ledger',
                'type'=>'LEFT',
                'conditions'=>array(
                            "Ledger.account_id = Billing.account_id",
                            "YEAR(Ledger.transac_date)"=>$year,
                            "MONTH(Ledger.transac_date)"=>$month,
                            "Ledger.type"=>'-',
                            "Ledger.transaction_type_id"=>array('SBQPY')
                         )
            )
        );
        $latestBills = $this->find('all', array(
            'fields' => array(
                'Billing.account_id',
                'Billing.id as bill_id',
                'Billing.due_amount',
                'Billing.due_date',
                'LatestBill.eff_bill_id',
                'LatestBill.bill_ids',
                'LatestBill.bill_amounts',
                "CAST(IFNULL(SUBSTRING_INDEX(LatestBill.bill_amounts, ',', 1), 0) AS DECIMAL(10,2)) - 
                CAST(IFNULL(SUBSTRING_INDEX(LatestBill.bill_amounts, ',', -1), 0) AS DECIMAL(10,2)) as paid_amount",
                " COALESCE(SUM(Ledger.amount), 0) AS ledger_paid_amount"
            ),
            'group' => 'Billing.account_id',
            'contain'=>$contains,
            'joins'=>$joins
        ));
        return $latestBills;
    }
    function getStudentBillingDetails($dueDate) {
        $year_month = strtotime($dueDate);
        $year = date('Y',$year_month);
        $month = date('m',$year_month);
        $latestBills=  $this->getLatestBills($year,$month);
    return $latestBills;
    }
}