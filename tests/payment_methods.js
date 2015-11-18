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
						"amount": 5000,
						"icon": "usd"
					  },
					  {
						"id": "CHCK",
						"name": "Check",
						"amount": 4000,
						"icon": "ok"
					  },
					  {
						"id": "CARD",
						"name": "Card",
						"amount": 3000,
						"icon": "credit-card"
					  },
					  {
						"id": "CHRG",
						"name": "Charge",
						"amount": 2000,
						"icon": "flash"
					  }
					]
			}
		);
});