define(['app','util','api'],function(app,util) {
	const TRNX ={};
	const INI_TRNX = [
				{id:'INIPY', description:'Initial Payment',amount:0},
				{id:'SBQPY', description:'Subsequent Payment',amount:0}
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
	function formatPaysched(psched){
		psched.map(function(sched){
			sched.disp_amount =  util.formatMoney(sched.due_amount);
			
			if(sched.bill_month=="UPONNROL")
				sched.disp_date = "Upon Enrollment";
			else
				sched.disp_date = util.formatDate(new Date(sched.due_date));
			
			paysched.push(sched);
		});
	}
	function computeAmounts(){
		paysched.map(function(sched){
			var ttid=  sched.transaction_type_id;
			switch(ttid){
				case 'INIPY':
					updateAmount(ttid,'set',sched.due_amount);
					return;
				break;
				case 'SBQPY':
					updateAmount('SBQPY','add',sched.due_amount);
				break;
			}
		});
	}
	function defaultList(){
		updateAmount('INIPY','set',0);
		updateAmount('SBQPY','set',0);
		updateDisplay('INIPY','show');
		updateDisplay('SBQPY','hide');
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
		paysched=[];
		var success  =function(response){
			var ASS =  response.data[0];
			formatPaysched(ASS.Paysched);
			computeAmounts();
		};
		var error  =function(response){

		};
		return api.GET('assessments',filter, success,error);
	}
	return TRNX;
});