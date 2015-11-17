"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Ledgers',
				},
				data:[
					{
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz",
						"account_type": "student"
					  },
					  "type": "credit",
					  "date": "June 5, 2015",
					  "ref_no": 12345,
					  "details": "Tuition Fee",
					  "amount": 7000,
					},
					{
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 2",
						"account_type": "student"
					  },
					  "type": "debit",
					  "date": "June 5, 2015",
					  "ref_no": 12346,
					  "details": "Initial Payment",
					  "amount": 2500,
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