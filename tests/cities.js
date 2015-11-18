"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Cities',
				},
				data:[
					  {
						"id": 1,
						"name": "Sto. Tomas",
						"province_id": "BTG"
					  },
					  {
						"id": 2,
						"name": "Tanauan",
						"province_id": "BTG"
					  },
					  {
						"id": 3,
						"name": "Malvar",
						"province_id": "BTG"
					  },
					  {
						"id": 4,
						"name": "Calamba",
						"province_id": "LAG"
					  },
					  {
						"id": 5,
						"name": "Makiling",
						"province_id": "LAG"
					  },
					  {
						"id": 6,
						"name": "San Pablo",
						"province_id": "LAG"
					  }
					]
			}
		);
});