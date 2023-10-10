define(['app','util','api'],function(app,util) {
	const TRNX ={};
	const INI_TRNX = [
				{id:'INIPY', description:'Initial Payment',amount:0},
				{id:'SBQPY', description:'Subsequent Payment',amount:0},
				{id:'OLDAC', description:'Old Account',amount:0}
			];
	var api,list,listIndex,paysched;
	(function(){
		list= INI_TRNX;
		listIndex = [];
		paysched = [];
		list.map(function(item,index){
			listIndex[item.id]=index;
		});
	})();
	TRNX.link = function(_api){
		api =_api;
	}
	TRNX.util = util;
	TRNX.getList = function(){
		return list;
	}
	TRNX.getSched = function(){
		return paysched;
	}
	function updateAmount(id,action,amount){
		var index = listIndex[id];
		switch(action){
			case 'add':
				list[index].amount += amount;
			break;
			case 'set':
			default:
				list[index].amount = amount;
			break;
		}
		list[index].disp_amount = util.formatMoney(list[index].amount);
	}
	function updateDisplay(id,status){
		var index = listIndex[id];
		switch(status){
			case 'enable': 
				delete list[index].disabled;
			break;
			case 'disable': 
				list[index].disabled=true;
			break;
			case 'show': 
				delete list[index].hide;
			break;
			case 'hide': 
				list[index].hide=true;
			break;
			
		}	
	}
	function formatPaysched(psched,is_old){
		let _paysched=[];
		psched.map(function(sched){
			
			sched.disp_amount =  util.formatMoney(sched.due_amount);
			
			if(sched.bill_month=="UPONNROL")
				sched.disp_date = "Upon Enrollment";
			else
				sched.disp_date = util.formatDate(new Date(sched.due_date));
			if(is_old){
				sched.transaction_type_id ='OLDAC';
				sched.amount = sched.due_amount;
			}
			if(sched.status!=='PAID'){
				_paysched.push(sched);	
			}
			
		});
		paysched = angular.copy( _paysched);
	}
	function computeAmounts(){
		paysched.map(function(sched){
			var ttid=  sched.transaction_type_id;
			var due_date = new Date(sched.due_date);
			var due_year = due_date.getFullYear();
			var due_month = due_date.getMonth()+1;

			var today = new Date();
			var tod_year = today.getFullYear();
			var tod_month = today.getMonth()+1;
			
			var isDue = due_year<tod_year || (due_year==tod_year && due_month<=tod_month);
			if(!isDue) return;
				switch(ttid){
					case 'INIPY':
						updateAmount(ttid,'set',sched.due_amount);
						return;
					break;
					case 'SBQPY':
						updateAmount('SBQPY','add',sched.due_amount);
					break;
					case 'OLDAC':
						updateAmount('OLDAC','add',sched.due_amount);
					break;
				}
		});
	}
	function defaultList(){
		updateAmount('INIPY','set',0);
		updateAmount('SBQPY','set',0);
		updateAmount('OLDAC','set',0);
		updateDisplay('INIPY','show');
		updateDisplay('SBQPY','hide');
		updateDisplay('OLDAC','hide');
	}
	function updateDisplays(){
		list.map(function(lItem){
			var hasAmt = lItem.amount>0;
			var disp =hasAmt?'show':'hide';
			updateDisplay(lItem.id,disp);
		});
	}
	
	TRNX.runDefault = function(){
		defaultList();
	}
	TRNX.getAssessment = function(sid,sy){
		var esp = sy + '.25';
		var filter = {
			student_id:sid,
			esp:esp,
			limit:1,
			status:'ACTIV'
		};
		return TRNX.requestAccount('assessments',filter,'Paysched');
	}

	TRNX.getAccount = function(sid,sy){
		var esp = sy + '.25';
		var filter = {
			id:sid,
			limit:1,
		};
		updateDisplay('OLDAC','show');
		return TRNX.requestAccount('accounts',filter,'Paysched');
	}
	TRNX.getOldAccount = function(sid,sy){
		var esp = sy + '.25';
		var filter = {
			account_id:sid,
			limit:1,
		};
		return TRNX.requestAccount('payment_plans',filter,'schedule');
	}
	TRNX.requestAccount = function(endpoint,filter,field){
		var success  =function(response){
			var data =  response.data[0];
			var _paysched = data[field];
			var is_old = endpoint=='payment_plans';
			formatPaysched(_paysched,is_old);
			computeAmounts();
			updateDisplays();
		};
		var error  =function(response){

		};
		return api.GET(endpoint,filter,success,error);
	}
	return TRNX;
});