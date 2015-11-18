"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Students',
				},
				data:[
					{
					  "id":"S201512345",
					  "educ_level_id": "GS",
					  "year_level_id": "G1",
					  "first_name": "Juan",
					  "middle_name": "Masipag",
					  "last_name": "Dela Cruz",
					  "suffix_name": "Jr",
					  "gender": "M",
					  "birthday": "1990-01-01",
					  "birthplace": "Malvar",
					  "religion": "Roman Catholic",
					  "citizenship": "Filipino",
					  "contact_numbers": [
						{
						  "type": "landline",
						  "number": "1234567"
						},
						{
						  "type": "mobile",
						  "number": "09171234567"
						}
					  ],
					  "addresses": [
						{
						  "type": "current",
						  "country": "PH",
						  "province": "BTG",
						  "city": "Santo Tomas",
						  "barangay": "San Roque",
						  "address": "Dona Fidela"
						},
						{
						  "type": "permanent",
						  "country": "PH",
						  "province": "BTG",
						  "city": "Balayan",
						  "barangay": "Gimalas",
						  "address": "123"
						}
					  ]
					}
				]
			}
		);
});