"use strict";
define(['model','../tests/ledgers'],function($model){
	var trnx_type =  new $model(
			{
				meta:{
					title: 'Transactions Type',
				},
				data:[
					  {
						"id": "IP",
						"name": "Initial Payment",
						"amount": 5000
					  },
					   {
						"id": "OLD",
						"name": "Old Account",
						"amount": 5000
					  },
					  {
						"id": "SP",
						"name": "Subsequent Payment",
						"amount": 3000
					  },
					  {
						"id": "PEU",
						"name": "P.E. Uniform",
						"amount": 4000
					  },
					  {
						"id": "BK",
						"name": "Books",
						"amount": 6000
					  }
					]
			},
		{ name: "TransactionType", uses:['ledgers'] });
		


		trnx_type.GET =  function(data){
			                    
			
			//Get DEMO_REGISTRY.LEdger
			// Find matching transac code
			// Update trnx_type.data[i].amount
			// trnx_type.data;

			 var ledgers = DEMO_REGISTRY.Ledger;
			 for (var i in ledgers) {		 	
			 	console.log(trnx_type.data[i].name);
			 	console.log(ledgers[i].details);

			 	if(trnx_type.data[i].name == "Initial Payment")
			 	{
			 		trnx_type.data[i].amount = ledgers[i].amount;
			 	}
			 	else if(trnx_type.data[i].name == "Old Account")
			 	{
			 		trnx_type.data[i].amount = ledgers[i].amount;
			 	}
			 }	 
			return {success:trnx_type.list()};
		}
		
		return trnx_type;
});