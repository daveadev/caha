<?php
// Import necessary CakePHP classes
App::import('Core', array('Model', 'Shell'));

// Define your custom shell
class InitializeFeesShell extends Shell {
    
    // Load models
    var $uses = array('Ledger', 'Fee', 'AccountFee');

    // Main function that runs when the shell command is executed
    function main() {
        $this->out("Starting Ledger to Account Fees Population...");

        // Delete all existing records in the account_fees table
        $this->out("Deleting all records from AccountFees...");
        if ($this->AccountFee->deleteAll(array('1 = 1'), false)) {
            $this->out("All records deleted successfully.");
        } else {
            $this->out("Failed to delete records from AccountFees.");
            return; // Exit if deletion fails to prevent data issues.
        }

        // Fetch ledger transactions with ref_no starting with "GRA"
        $conditions = array('Ledger.ref_no LIKE' => 'GRA%');
        $ledgerEntries = $this->Ledger->find('all', array('conditions' => $conditions));

        foreach ($ledgerEntries as $entry) {
            $accountId = $entry['Ledger']['account_id'];
            $transactionTypeId = $entry['Ledger']['transaction_type_id'];
            $details = $entry['Ledger']['details'];
            $amount = $entry['Ledger']['amount'];

            // Extract the first 3 characters of transaction_type_id to form fee_id
            $feeId = substr($transactionTypeId, 0, 3);

            // Determine the order of the fee based on the fee details
            $order = $this->getFeeOrder($details);

            // Check if the fee already exists in the 'fees' table using fee_id
            $fee = $this->Fee->find('first', array('conditions' => array('Fee.id' => $feeId)));

            if (empty($fee)) {
                // If the fee doesn't exist, create a new one
                $feeData = array(
                    'Fee' => array(
                        'id' => $feeId,
                        'name' => $details, // Use the 'details' column from the ledger as the fee name
                        'type' => 'FEE',
                        'order' => $order,  // Assign the calculated order
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s')
                    )
                );

                // Save the new fee
                $this->Fee->create();
                if ($this->Fee->save($feeData)) {
                    $this->out("New Fee Added: $feeId - $details with Order: $order");
                } else {
                    $this->out("Failed to add Fee: $feeId");
                }
            } else {
                // If the fee exists, update the order if needed
                $fee['Fee']['order'] = $order;
                $fee['Fee']['modified'] = date('Y-m-d H:i:s');

                if ($this->Fee->save($fee)) {
                    $this->out("Fee Updated: $feeId - $details with new Order: $order");
                } else {
                    $this->out("Failed to update Fee: $feeId");
                }
            }

            // Prepare data to insert into account_fees table
            $accountFeeData = array(
                'AccountFee' => array(
                    'account_id' => $accountId,
                    'fee_id' => $feeId, // Use the first 3 characters of transaction_type_id
                    'due_amount' => $amount,
                    'paid_amount' => 0.00,
                    'adjust_amount' => 0.00,
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s')
                )
            );

            // Save the new account fee entry
            $this->AccountFee->create();
            if ($this->AccountFee->save($accountFeeData)) {
                $this->out("Account Fee added for Account ID: $accountId, Fee ID: $feeId");
            } else {
                $this->out("Failed to add Account Fee for Account ID: $accountId, Fee ID: $feeId");
            }
        }

        $this->out("Process Completed.");
    }

    // Function to determine the fee order based on the fee description
    function getFeeOrder($details) {
        switch (strtolower($details)) {
            case 'registration fee':
                return 1;
            case 'annual school fee':
                return 2;
            case 'tuition':
                return 3;
            case 'energy fee':
                return 4;
            case 'learning material':
                return 5;
            case 'discount':
            case 'employee discount':
            case 'cash discount':
                return 6;
            default:
                return 999; // Default order for unrecognized fee types
        }
    }
}
?>
