<?php
// Import necessary CakePHP classes
App::import('Core', array('Model', 'Shell'));

// Define your custom shell
class UpdateAccountsShell extends Shell {
    
    // Load necessary models
    public $uses = array('Account', 'AccountFee');

    public function main() {
        // Step 1: Check if account_id is provided in arguments
        if (empty($this->args)) {
            $this->out('No account_id provided. Running update for all accounts.');
            $accountIds = $this->Account->find('list', array('fields' => array('Account.id')));
        } else {
            $accountId = $this->args[0];
            $accountIds = array($accountId);
        }

        // Step 2: Loop through each account_id
        foreach ($accountIds as $accountId) {
            // Load Account Fees for the given account_id
            $accountFees = $this->AccountFee->find('all', array(
                'conditions' => array('AccountFee.account_id' => $accountId)
            ));

            if (empty($accountFees)) {
                $this->out("No account fees found for account ID: $accountId. Skipping...");
                continue;
            }

            // Step 3: Calculate assessment_total, discount_amount, payment_total, and outstanding_balance
            $assessmentTotal = 0;
            $discountAmount = 0;
            $paymentTotal = 0;

            foreach ($accountFees as $fee) {
                $dueAmount = $fee['AccountFee']['due_amount'];
                $paidAmount = $fee['AccountFee']['paid_amount'];
                
                if ($fee['AccountFee']['fee_id'] == 'DSC') { // Assuming 'DSC' represents a discount
                    $discountAmount += $dueAmount;
                } else {
                    $assessmentTotal += $dueAmount;
                    $paymentTotal += $paidAmount;
                }
            }

            // Calculate outstanding balance
            $outstandingBalance = $assessmentTotal - $discountAmount - $paymentTotal;

            // Step 4: Update the Account table with computed values
            $accountData = array(
                'Account' => array(
                    'id' => $accountId,
                    'assessment_total' => $assessmentTotal,
                    'discount_amount' => $discountAmount,
                    'payment_total' => $paymentTotal,
                    'outstanding_balance' => $outstandingBalance
                )
            );

            if ($this->Account->save($accountData)) {
                $this->out("Account ID: $accountId updated successfully.");
            } else {
                $this->out("Failed to update account ID: $accountId.");
            }

            // Output the totals as a table
            $this->out("\n-------------------------------------------------");
            $this->out(sprintf("| %-15s | %-15s | %-15s | %-15s |", 'Assessment Total', 'Discount Amount', 'Payment Total', 'Outstanding Balance'));
            $this->out("\n-------------------------------------------------");
            $this->out(sprintf("| %-15.2f | %-15.2f | %-15.2f | %-15.2f |", $assessmentTotal, $discountAmount, $paymentTotal, $outstandingBalance));
            $this->out("\n-------------------------------------------------");
        }
    }
}
?>