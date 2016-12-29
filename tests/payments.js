"use strict";
define(['model','util','../tests/ledgers', '../tests/transactions'], function($model,util) {

    var data = {
        meta: {
            title: 'Payments',
        },
        data: [

        ]
    };
    var registry = { name: "Payment", uses: ["ledgers", "transactions"] };
    var Payment = new $model(data, registry);
    Payment.POST = function(data) {
        console.log(data);
        var tDate = new Date();
		var nDate =  util.formatDate(tDate);
      
        var booklet = DEMO_REGISTRY.Booklet[0];
        //console.log(booklet);
        var ledger = {
            account: {
                id: data.student.id,
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

           console.log(transaction);
 
		var transactions = DEMO_REGISTRY.Transaction;
			transaction.id = transactions.length;
			
        for (var i in data.transactions) {
            var trnx = data.transactions[i];
            var pymt = data.payments[i];

            var ledgers = DEMO_REGISTRY.Ledger;
            console.log(DEMO_REGISTRY);
            ledger.id = ledgers.length;
            ledger.ref_no = booklet.series_counter;
            ledger.details = trnx.id;
            ledger.amount = trnx.amount;
            DEMO_REGISTRY.Ledger.push(ledger);  

			
			var detail = {
				"ref_no": booklet.series_counter,
                    "details": trnx.id,
                    "amount": trnx.amount
				
			};
			transaction.transaction_details.push(detail);
			
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

        booklet.series_counter++;
        DEMO_REGISTRY.Booklet[0] = booklet;
        return { success: Payment.save(data) };

    }

    return Payment;
});