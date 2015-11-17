"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Transaction',
				},
				data:[
					{
					  "type": "payment",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz",
						"account_type": "student"
					  },
					  "amount": 3500,
					  "transaction_details": [
						{
						  "ref_no": 12345,
						  "details": "Initial Payment",
						  "amount": 3500
						}
					  ],
					  "transaction_payments": [
						{
						  "type": "cash",
						  "details": "cash",
						  "amount": 1000
						},
						{
						  "type": "card",
						  "details": "BDO-123X",
						  "amount": 2500
						}
					  ]
					}
				]
			}
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