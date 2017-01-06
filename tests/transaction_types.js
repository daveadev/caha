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
			//convert transac_type_data to transac_type_obj
			//Loop Ledger compare to transac_type_obj
			//copy amount from transac_type_obj to transac_type_data
			
			var tto = {};
			for (var i in trnx_type.data) {
				var obj = trnx_type.data[i];
				tto[obj.id] = obj;
				
			}

			var ledgers = DEMO_REGISTRY.Ledger;
			 for (var i in ledgers) {
				 var ttid = ledgers[i].transaction_type_id;
				 var flag = ledgers[i].type=='debit'?-1:1;
				 var account_no = ledgers[i].account.account_no;
				
				if(tto[ttid]&&data.account_no==account_no){
					tto[ttid].amount += ledgers[i].amount*flag;
				}
					
				 console.log(data,ttid,tto[ttid]);

			 }
			 
			 for (var i in trnx_type.data){
				var id = trnx_type.data[i].id;
				
				trnx_type.data[i].amount = tto[id].amount;
				
			 }

			return {success:trnx_type.list()};
		}
		
		return trnx_type;
});