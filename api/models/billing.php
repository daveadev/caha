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
    protected function getLatestBills() {
        $contains = array('Student.sno',
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
                    'table' => '(SELECT account_id, MAX(bill.id) AS bill_id FROM billings AS bill  GROUP BY bill.account_id)',
                    'alias' => 'LatestBill',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Billing.id = LatestBill.bill_id'
                    )
                )
        );
        $latestBills = $this->find('all', array(
            'fields' => array(
                'Billing.account_id',
                'Billing.id as bill_id',
                'Billing.due_amount',
                'LatestBill.account_id',
                'LatestBill.bill_id'
            ),
            'contain'=>$contains,
            'joins'=>$joins
        ));

        return $latestBills;
    }
    function getStudentBillingDetails() {
      $latestBills=  $this->getLatestBills();
      return $latestBills;
    }
}