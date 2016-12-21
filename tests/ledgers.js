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
						"account_no": 12345,
						"name": "Juan Dela Cruz",
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
					  "id": 1,
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
					},
					{
					  "id": 2,
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
					},
					{
					  "id": 3,
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
					},
					{
					  "id": 4,
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
					},
					{
					  "id": 5,
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
					},
					{
					  "id": 6,
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
					},
					{
					  "id": 7,
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
					},
					{
					  "id": 8,
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
					},
					{
					  "id": 9,
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
					},
					{
					  "id": 10,
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
					},
					{
					  "id": 11,
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
					},
					{
					  "id": 12,
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