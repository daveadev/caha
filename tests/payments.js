"use strict";
define(['model','util','../tests/accounts',,'../tests/ledgers', '../tests/transactions'], function($model,util) {

    var data = {
        meta: {
            title: 'Payments',
        },
        data: [

        ]
    };
    var registry = { name: "Payment", uses: ["accounts","ledgers", "transactions"] };
    var Payment = new $model(data, registry);
    Payment.POST = function(data) {
        console.log(data);
        var tDate = new Date();
		var nDate =  util.formatDate(tDate);
      
        var booklet = DEMO_REGISTRY.Booklet[0];
        //console.log(booklet);
       
	    var accounts = DEMO_REGISTRY.Account;
		 var account_index = {};
		 var account = {};
		 for (var i in accounts) {
			 if(data.student.id == accounts[i].account_no)
			 {
				 account = accounts[i];
				 account_index = i;
			 }
			 
			 
		 }
		
	   var ledger = {
            account: {
                account_no : data.student.id,
                account_name: data.student.name,
                account_type: "student"
            },
            type: "debit",
            date: nDate
            
        };

       
		
       var transaction = {
            type: "payment",
            status: "fulfilled",
            date: nDate,
            account: {
                account_no: data.student.id,
                account_name: data.student.name,
                account_type: "student"
            },
			amount : data.amount,
            transaction_details: [],
            transaction_payments: [] 
            };

 
		var transactions = DEMO_REGISTRY.Transaction;
			transaction.id = transactions.length;
		//Process transaction details for Ledger entry and Account Payment History
        for (var i in data.transactions) {
            var trnx = data.transactions[i];
            var ledgers = DEMO_REGISTRY.Ledger;
			//Create ledger entry base on transaction
			ledger.id = ledgers.length;
            ledger.ref_no = booklet.series_counter;
            ledger.details = trnx.id;
            ledger.amount = trnx.amount;
            DEMO_REGISTRY.Ledger.push(ledger);  
			//Build transaction detail
			var detail = {
				"ref_no": booklet.series_counter,
                    "details": trnx.id,
                    "amount": trnx.amount
				
			};
			transaction.transaction_details.push(detail);
			//Build history for Account payment history
			var hist = detail;
				hist.date=nDate;
			account.payment_history.push(hist);
		}
		//Process payments for transaction_payments
		for (var i in data.payments) {
			var pymt = data.payments[i];
			var details ="CASH";
				if(pymt.id!='CASH'){
					details=[];	
					details.push(pymt.details.bank);
					details.push(pymt.details.ref_no);
					details.push(util.formatDate(pymt.details.date));
					details =  details.join(" ");
				}
				
			var payment = {
						"type": pymt.id,
                        "details": details,
                        "amount": pymt.amount
				
			};
			transaction.transaction_payments.push(payment);
        }
       
	     DEMO_REGISTRY.Transaction.push(transaction); 
	     DEMO_REGISTRY.Account[account_index]=account; 
		 
		

        booklet.series_counter++;
        DEMO_REGISTRY.Booklet[0] = booklet;
        return { success: Payment.save(data) };

    }

    return Payment;
});