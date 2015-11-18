"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Deparments',
				},
				data:[
					  {
						"id": "PS",
						"name": "Preschool",
						"alias": "Preschool"
					  },
					  {
						"id": "GS",
						"name": "Grade School",
						"alias": "Grade S."
					  },
					  {
						"id": "HS",
						"name": "High School",
						"alias": "High S."
					  }
					]
			}
		);
});