"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Provinces',
				},
				data:[
					  {
						"id": "BTG",
						"name": "Batangas",
						"country_id": "PH"
					  },
					  {
						"id": "LAG",
						"name": "Laguna",
						"country_id": "PH"
					  }
					]
			}
		);
});