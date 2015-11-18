"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Countries',
				},
				data:[
					  {
						"id": "PH",
						"name": "Philippines"
					  },
					  {
						"id": "JPN",
						"name": "Japan"
					  }
					]
			}
		);
});