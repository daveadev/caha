"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Transactions Type',
				},
				data:[
					  {
						"id": "IP",
						"name": "Initial Payment",
						"amount": 5000
					  },
					   {
						"id": "OLD",
						"name": "Old Account",
						"amount": 5000
					  },
					  {
						"id": "SP",
						"name": "Subsequent Payment",
						"amount": 3000
					  },
					  {
						"id": "PEU",
						"name": "P.E. Uniform",
						"amount": 4000
					  },
					  {
						"id": "BK",
						"name": "Books",
						"amount": 6000
					  }
					]
			},
		{ name: "TransactionType" });
});