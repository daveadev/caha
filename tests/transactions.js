"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Transaction',
				},
				data:[
					{
					  "id": 1,
					  "type": "payment",
					  "status": "fulfilled",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 1",
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
					},
					{
					  "id": 2,
					  "type": "payment",
					  "status": "cancelled",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 2",
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
					},
					{
					  "id": 3,
					  "type": "payment",
					  "status": "fulfilled",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 3",
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
					},
					{
					  "type": "payment",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 4",
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
					},
					{
					  "type": "payment",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 5",
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
					},
					{
					  "type": "payment",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 6",
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
					},
					{
					  "type": "payment",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 7",
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
					},
					{
					  "type": "payment",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 8",
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
					},
					{
					  "type": "payment",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 9",
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
					},
					{
					  "type": "payment",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 10",
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
					},
					{
					  "type": "payment",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 11",
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
					,{
					  "type": "payment",
					  "date": "June 5, 2015",
					  "account": {
						"account_no": 12345,
						"account_name": "Juan Dela Cruz 12",
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