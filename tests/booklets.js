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
					  "cashier": "Cashier A"
					},
					{
					  "id": 2,
					  "series_start": 1800,
					  "series_end": 2800,
					  "series_counter": 1801,
					  "status": "active",
					  "cashier": "Cashier B"
					},
					{
					  "id": 3,
					  "series_start": 1700,
					  "series_end": 2700,
					  "series_counter": 1701,
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