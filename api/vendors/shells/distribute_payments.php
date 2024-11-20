<?php
// Import necessary CakePHP classes
App::import('Core', array('Model', 'Shell'));

// Define your custom shell
class DistributePaymentsShell extends Shell {
    
    // Load necessary models
    public $uses = array('AccountSchedule', 'Ledger', 'AccountFee', 'Account');

    public function main() {
        // Step 1: Parse account_id from arguments or get all account_ids if not provided.
        if (empty($this->args)) {
            // No account_id provided, get all account_ids from the accounts table.
            $accounts = $this->Account->find('list', array(
                'fields' => array('Account.id')
            ));
        } else {
            $accounts = array($this->args[0]);
        }

        // Step 2: Loop through each account_id
        foreach ($accounts as $accountId) {
            $this->out("\nProcessing Account ID: " . $accountId);
            
            // Step 3: Load account schedules for the given account_id.
            $accScheConf = array(
                'recursive' => -1,
                'conditions' => array(
                    'AccountSchedule.account_id' => $accountId,
                    'AccountSchedule.transaction_type_id !=' => 'ACECF'
                ),
                'order' => array('AccountSchedule.due_date' => 'ASC')
            );
            $accountSchedules = $this->AccountSchedule->find('all', $accScheConf);

            if (empty($accountSchedules)) {
                $this->out('No account schedules found for this account_id, skipping...');
                continue; // Skip this account if no schedules are found
            }

            // Step 4: Load Account Fees for the given account_id.
            $accountFees = $this->AccountFee->find('all', array(
                'conditions' => array('AccountFee.account_id' => $accountId),
                'recursive'=>-1
            ));

            // Check if account fees are loaded
            if (empty($accountFees)) {
                $this->out('No account fees found for this account_id, skipping...');
                continue; // Skip this account if no fees are found
            }

            // Step 5: Prepare fees dynamically
            $fees = array();
            foreach ($accountFees as $fee) {
                $feeId = $fee['AccountFee']['fee_id'];
                $dueAmount = $fee['AccountFee']['due_amount'];
                $fees[$feeId] = $dueAmount;
            }

            // Step 6: Create the 2D array for due amounts.
            $feeDistribution = array();

            // Step 7: Populate the first row for REGFE.
            $feeDistribution[0] = array(
                'REGFE' => isset($fees['REG']) ? $fees['REG'] : 0,
                'ANN' => 0,
                'TUI' => 0,
                'ENE' => 0,
                'LER' => 0,
            );

            // Step 8: Distribute TUI and ENE over the remaining months.
            $totalMonths = count($accountSchedules) - 1; // Remaining months excluding REGFE and ANN
            $tuiMonthly = isset($fees['TUI']) ? ($fees['TUI'] / $totalMonths) : 0;
            $eneMonthly = isset($fees['ENE']) ? ($fees['ENE'] / $totalMonths) : 0;

            // Step 9: Populate the second row for ANN.
            $feeDistribution[1] = array(
                'REGFE' => 0,
                'ANN' => isset($fees['ANN']) ? $fees['ANN'] : 0,
                'TUI' => $tuiMonthly,
                'ENE' => $eneMonthly,
                'LER' => 0,
            );

            // Step 10: Populate the rest of the months.
            for ($i = 2; $i < 1 + $totalMonths; $i++) {
                $feeDistribution[$i] = array(
                    'REGFE' => 0,
                    'ANN' => 0,
                    'TUI' => $tuiMonthly,
                    'ENE' => $eneMonthly,
                    'LER' => 0,
                );
            }

            // Step 11: Adjust LER to balance discrepancies with Account Schedules.
            foreach ($feeDistribution as $index => $fees) {
                $totalDue = array_sum($fees);
                $scheduledDue = $accountSchedules[$index]['AccountSchedule']['due_amount'];

                // Adjust LER if there's a difference.
                if ($totalDue < $scheduledDue) {
                    $feeDistribution[$index]['LER'] = $scheduledDue - $totalDue;
                }
            }

            // Step 12: Display the initial fee distribution table for debugging.
            $this->out("Distribution Table for Account ID: " . $accountId);
            $this->displayTable($feeDistribution);

            // Step 13: Load payments from the ledger, excluding discounts.
            $ledgerPayments = $this->Ledger->find('all', array(
                'conditions' => array(
                    'Ledger.account_id' => $accountId,
                    'Ledger.type' => '-',
                    'Ledger.transaction_type_id !=' => 'DSCNT'
                ),
                'order' => array('Ledger.transac_date' => 'ASC',
                    'FIELD(Ledger.transaction_type_id, "REGFE", "SBQPY")'=>'ASC'),
                'recursive'=>-1
            ));
            
            // Step 14: Apply payments to the fee distribution.
            foreach ($ledgerPayments as $payment) {
                $paymentAmount = $payment['Ledger']['amount'];
                foreach ($feeDistribution as $index => &$fees) {
                    $totalDue = array_sum($fees);
                    if ($paymentAmount <= 0) {
                        break;
                    }
                    if ($totalDue >= 0) {
                        foreach ($fees as $feeType => &$amountDue) {
                            if ($amountDue >= 0) {
                                $paymentToApply = min($paymentAmount, $amountDue);
                                $amountDue -= $paymentToApply;
                                $paymentAmount -= $paymentToApply;
                                // Output the updated array after each payment is applied

                                $this->out("Updating Table after applying payment of " . number_format($paymentToApply, 2) . " to Fee Type: " . $feeType . " for Month " . ($index + 1));
                                $this->displayTable($feeDistribution);

                                //sleep(1);  Add a delay to view the update gradually
                            }
                        }
                    }
                }
            }
            unset($fees);

            // Step 15: Display the updated fee distribution table for debugging.
            $this->out("Updated Distribution Table after Applying Payments for Account ID: " . $accountId);
            $this->displayTable($feeDistribution);

            // Step 16: Update Account Fees paid_amount and calculate percentage paid.
            
            foreach ($accountFees as &$accountFee) {
                $feeId = $accountFee['AccountFee']['fee_id'];
                $accountFee['AccountFee']['paid_amount'] = 0;
                $endingBalance = 0;
                
                foreach ($feeDistribution as $fi=>$fees) {
                    if (isset($fees[$feeId])) {
                        $endingBalance += $fees[$feeId];
                    }
                }
                $paidAmount = $accountFee['AccountFee']['due_amount'] - $endingBalance;
                $accountFee['AccountFee']['paid_amount'] = $paidAmount;
                // Calculate percentage paid
                $percentagePaid = ($accountFee['AccountFee']['due_amount'] > 0) ? ($paidAmount / $accountFee['AccountFee']['due_amount']) * 100 : 0;
                $accountFee['AccountFee']['percentage'] = $percentagePaid/100;
                $this->AccountFee->save($accountFee);
                $this->out("Updated Paid Amount for Account ID: $accountId, Fee ID: $feeId - New Paid Amount: " . $accountFee['AccountFee']['paid_amount'] . " - Percentage Paid: " . number_format($percentagePaid, 2) . "%");
            }
        }
    }

    // Helper function to display the table
    private function displayTable($feeDistribution) {
        $this->out(str_pad("Month", 10) . str_pad("REGFE", 10) . str_pad("ANN", 10) . str_pad("TUI", 10) . str_pad("ENE", 10) . str_pad("LER", 10) . str_pad("Total", 10));
        foreach ($feeDistribution as $monthIndex => $fees) {
            $totalDuePerMonth = array_sum($fees);
            $this->out(
                str_pad("Month " . ($monthIndex + 1), 10) .
                str_pad(number_format($fees['REGFE'], 2), 10) .
                str_pad(number_format($fees['ANN'], 2), 10) .
                str_pad(number_format($fees['TUI'], 2), 10) .
                str_pad(number_format($fees['ENE'], 2), 10) .
                str_pad(number_format($fees['LER'], 2), 10) .
                str_pad(number_format($totalDuePerMonth, 2), 10)
            );
        }
    }
}

?>
