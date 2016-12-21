"use strict";
define(['model'],function($model){

		var data = {
				meta:{
					title: 'Payments',
				},
				data:[
					  
					]
			};
		var registry = { name : "Payment", uses: ["ledgers"]};
		var Payment = new $model(data,registry);
		return Payment;
});