<?php
// Import necessary CakePHP classes
App::import('Core', array('Model', 'Shell'));

// Define your custom shell
class InitializeAccountHistoryShell extends Shell {
    
    // Load necessary models
    public $uses = array('Account', 'AccountHistory', 'Ledger');

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
            // Load the account to get assessment_total and discount_amount
            $account = $this->Account->findById($accountId);
            if (empty($account)) {
                $this->out("No account found for account ID: $accountId. Skipping...");
                continue;
            }

            $assessmentTotal = $account['Account']['assessment_total'];
            $discountAmount = $account['Account']['discount_amount'];
            $totalDue = $assessmentTotal - $discountAmount;

            // Step 3: Delete all account histories for this account_id
            $this->AccountHistory->deleteAll(array('AccountHistory.account_id' => $accountId), false);
            $this->out("Deleted existing account histories for account ID: $accountId");

            // Step 4: Load ledger transactions (REGFE, INIPY, SBQPY, ACECF) sorted by transact_date
            $ledgerTransactions = $this->Ledger->find('all', array(
                'conditions' => array(
                    'Ledger.account_id' => $accountId,
                    'Ledger.transaction_type_id' => array('REGFE', 'INIPY', 'SBQPY', 'ACEC','ACECF')
                ),
                'order' => array('Ledger.transac_date' => 'ASC'),
                'recursive' => -1
            ));

            if (empty($ledgerTransactions)) {
                $this->out("No ledger transactions found for account ID: $accountId. Skipping...");
                continue;
            }

            // Step 5: Insert account histories in the order of payment
            $runningTotalPaid = 0;
            foreach ($ledgerTransactions as $transaction) {
                $ledger = $transaction['Ledger'];

                // Skip REGFE transactions with type '+'
                if ($ledger['transaction_type_id'] == 'REGFE' && $ledger['type'] == '+') {
                    continue;
                }

                if ($ledger['type'] == '+') {
                    $totalDue += $ledger['amount'];
                }

                $runningTotalPaid += ($ledger['type'] == '-') ? $ledger['amount'] : 0;
                $balance = $totalDue - $runningTotalPaid;

                // Prepare data for new account history entry
                $historyData = array(
                    'AccountHistory' => array(
                        'account_id' => $accountId,
                        'transac_date' => $ledger['transac_date'],
                        'transac_time' => $ledger['transac_time'],
                        'total_due' => $totalDue,
                        'total_paid' => $runningTotalPaid,
                        'balance' => $balance,
                        'ref_no' => $ledger['ref_no'],
                        'details' => $ledger['details'],
                        'flag' => $ledger['type'],
                        'amount' => $ledger['amount'],
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s')
                    )
                );

                // Save the new account history entry
                $this->AccountHistory->create();
                if ($this->AccountHistory->save($historyData)) {
                    $this->out("Account history for account ID: $accountId, Ref No: {$ledger['ref_no']} added successfully.");
                } else {
                    $this->out("Failed to add account history for account ID: $accountId, Ref No: {$ledger['ref_no']}.");
                }
            }
        }
    }
}
?>