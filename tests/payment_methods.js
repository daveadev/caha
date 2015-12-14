"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Payment Methods',
				},
				data:[
					  {
						"id": "CASH",
						"name": "Cash",
						"description":null,
						"amount": 5000,
						"icon": null
					  },
					  {
						"id": "CHCK",
						"name": "Check",
						"description":"Payments thru checks.",
						"amount": 4000,
						"icon": "ok"
					  },
					  {
						"id": "CARD",
						"name": "Card",
						"description":"Accepts debit/credit card.",
						"amount": 3000,
						"icon": "credit-card"
					  },
					  {
						"id": "CHRG",
						"name": "Charge",
						"description":"Credits to your account.",
						"amount": 2000,
						"icon": "flash"
					  }
					]
			}
		);
});