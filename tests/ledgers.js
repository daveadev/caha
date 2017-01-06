"use strict";
define(['model'],function($model){
	return new $model(
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
					  "details": "Tuition Fee",
					  "amount": 7000,
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
					  "details": "Initial Payment",
					  "amount": 2500,
					}
				]
			},
			{ name : "Ledger"}
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
});