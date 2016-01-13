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
						"description":"Payments thru cash.",
						"amount": 0,
						"icon": null
					  },
					  {
						"id": "CHCK",
						"name": "Check",
						"description":"Payments thru checks.",
						"amount": 0,
						"icon": "ok"
					  },
					  {
						"id": "CARD",
						"name": "Card",
						"description":"Accepts debit/credit card.",
						"amount": 0,
						"icon": "credit-card"
					  },
					  {
						"id": "CHRG",
						"name": "Charge",
						"description":"Credits to your account.",
						"amount": 0,
						"icon": "flash"
					  }
					]
			}
		);
});