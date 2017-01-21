"use strict";
define(['model'],function($model){
	
	var Ledger = new $model(
			{
				meta:{
					title: 'Ledgers',
				},
				data:[
					{
					  "id": 0,
					  "account": {
						"account_no": "S201512345",
						"account_name": "Juan Dela Cruz Masipag Jr",
						"account_type": "student"
					  },
					  "type": "credit",
					  "date": "June 5, 2015",
					  "ref_no": 12345,
					  "transaction_type_id": "IP",
					  "details": "Initial Payment",
					  "amount": 2500,
					},
					{
					  "id": 1,
					  "account": {
						"account_no": "S201512346",
						"account_name": "Ted Masipag Dela Cruz Jr",
						"account_type": "student"
					  },
					  "type": "debit",
					  "date": "June 5, 2015",
					  "ref_no": 12346,
					  "transaction_type_id": "IP",
					  "details": "Initial Payment",
					  "amount": 2500,
					}
				]
			},
			{ name : "Ledger",uses:['transaction_types']}
		);
		//test.setMeta({title:'Test'});
		//test.setData([{title:'Sample','description':'dasd'}]);
		/*
		test.GET = function(){
			return {success:test.list()};
		}
		test.POST = function(data){
			return {success:test.save(data)};
		}
		*/
		Ledger.POST = function(data){
			var tt = DEMO_REGISTRY.TransactionType;
			for (var i in tt)
			{
				console.log(data.transaction_type_id,tt[i]);
				if(data.transaction_type_id==tt[i].id)
					data.details=tt[i].name;
				
			}
			
			return {success:Ledger.save(data)};
			
		}
		return Ledger;
});