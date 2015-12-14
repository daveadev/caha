"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Booklets',
				},
				data:[
					{
					  "id": 1,
					  "series_start": 1888,
					  "series_end": 2888,
					  "series_counter": 1889,
					  "status": "active",
					  "cashier": "Cashier A",
					  "transactions":[
						{
						  "series": 1880,
						  "transactions": [
											{id:1,name:"Juan Dela Cruz",amount:3500}
										  ],
						  "amount": 3500
						}
					  ]
					},
					{
					  "id": 2,
					  "series_start": 1800,
					  "series_end": 2800,
					  "series_counter": 1801,
					  "status": "active",
					  "cashier": "Cashier B",
					  "transactions":[
						{
						  "series": 1880,
						  "transactions": [
											{id:2,name:"Ted Philip Lat",amount:4500}
										  ],
						  "amount": 4500
						}
					  ]
					},
					{
					  "id": 3,
					  "series_start": 1600,
					  "series_end": 2700,
					  "series_counter": 1601,
					  "status": "active",
					  "cashier": "Cashier C",
					  "transactions":[
						{
						  "series": 1880,
						  "transactions": [
											{id:3,name:"Juan Dela Cruz 1",amount:5500}
										  ],
						  "amount": 5500
						}
					  ]
					},
					{
					  "id": 4,
					  "series_start": 1601,
					  "series_end": 2700,
					  "series_counter": 1602,
					  "status": "active",
					  "cashier": "Cashier C",
					  "transactions":[
						{
						  "series": 1880,
						  "transactions": [
											{id:4,name:"Juan Dela Cruz 2",amount:6500}
										  ],
						  "amount": 6500
						}
					  ]
					},
					{
					  "id": 5,
					  "series_start": 1602,
					  "series_end": 2700,
					  "series_counter": 1603,
					  "status": "active",
					  "cashier": "Cashier C",
					  "transactions":[
						{
						  "series": 1880,
						  "transactions": [
											{id:5,name:"Juan Dela Cruz 3",amount:7500}
										  ],
						  "amount": 7500
						}
					  ]
					},
					{
					  "id": 6,
					  "series_start": 1701,
					  "series_end": 2700,
					  "series_counter": 1702,
					  "status": "active",
					  "cashier": "Cashier C"
					},
					{
					  "id": 7,
					  "series_start": 1702,
					  "series_end": 2700,
					  "series_counter": 1703,
					  "status": "active",
					  "cashier": "Cashier C"
					},
					{
					  "id": 8,
					  "series_start": 1703,
					  "series_end": 2700,
					  "series_counter": 1704,
					  "status": "active",
					  "cashier": "Cashier C"
					},
					{
					  "id": 9,
					  "series_start": 1704,
					  "series_end": 2700,
					  "series_counter": 1705,
					  "status": "active",
					  "cashier": "Cashier C"
					},
					{
					  "id": 10,
					  "series_start": 1705,
					  "series_end": 2700,
					  "series_counter": 1706,
					  "status": "active",
					  "cashier": "Cashier C"
					},
					{
					  "id": 11,
					  "series_start": 1706,
					  "series_end": 2700,
					  "series_counter": 1707,
					  "status": "active",
					  "cashier": "Cashier C"
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