"use strict";
define(['model'],function($model){

		var data = {
				meta:{
					title: 'Payments',
				},
				data:[
					  
					]
			};
		var registry = { name : "Payment", uses: ["ledgers"]};
		var Payment = new $model(data,registry);
		Payment.POST = function(data) {
			console.log(data);
			var tDate = new Date();
		var monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            var month = monthNames[tDate.getMonth()]
            var day = tDate.getDate();
            var year = tDate.getFullYear();
            var nDate = month + ' ' + day + ', ' + year;	
			var ledger = {
                account: {
                    id: data.student.id,
                    acount_name: data.student.name,
                    account_type: "student"
                },
                type: "debit",
                date: nDate
            };	
			
			for (var i in data.transactions)
			{
				var trnx = data.transactions[i];
				var ledgers = DEMO_REGISTRY.Ledger;
				console.log(DEMO_REGISTRY);
				ledger.id = ledgers.length;
				ledger.refno = "xx";
				ledger.details = trnx.id;
				ledger.amount = trnx.amount;
				DEMO_REGISTRY.Ledger.push(ledger);	
			}
			
			return {success:Payment.save(data)};
			
		}
		
		return Payment;
});