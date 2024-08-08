define(['app','util','api'],function(app,util) {
	const TRNX ={};
	const INI_TRNX = [
				{id:'INRES', description:'Initial Reservation',amount:3000, docType:'OR'},
				{id:'REGFE', description:'Registration Fee',amount:1500, docType:'OR'},
				{id:'INIPY', description:'Initial Payment',amount:0, docType:'OR'},
				{id:'SBQPY', description:'Subsequent Payment',amount:0, docType:'OR'},
				{id:'OLDAC', description:'Old Account',amount:0, docType:'OR'},
				{id:'EXTPY', description:'Ext. Payment Plan',amount:0, docType:'A2O'},
				//{id:'UNIFM', description:'Uniform',amount:100},
				{id:'OTHRS', description:'Others',amount:0.10, docType:'A2O'}
				//{id:'FORMS', description:'Forms',amount:0}
			];
	var api,list,listIndex,paysched,payscheds;
	(function(){
		list= INI_TRNX;
		listIndex = [];
		paysched = [];
		payscheds = {};
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
	TRNX.getSched = function(type){
		let ps = paysched;
		if(type=='old') 
			ps=payscheds.old; 
		if(type=='regular')
			ps=payscheds.regular; 
		return ps;
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
			if(sched.paid_amount>0)
				sched.due_amount -= sched.paid_amount;
			sched.disp_amount =  util.formatMoney(sched.due_amount);
			
			if(sched.transaction_type_id=="INIPY")
				sched.disp_date = "Upon Enrollment";
			else if(sched.transaction_type_id=="REGFE")
				sched.disp_date = "Registration";
			else if(sched.transaction_type_id=="ACECF")
				sched.disp_date = new Date(sched.due_date).toLocaleString('en-US',{month:'short'})+" ACEC";
			else
				sched.disp_date = util.formatDate(new Date(sched.due_date));
			if(is_old){
				sched.transaction_type_id ='EXTPY';
				sched.amount = sched.due_amount;
			}
			
			_paysched.push(sched);	
			
			
		});
		paysched = angular.copy( _paysched);
	}
	function computeAmounts(is_old){
		let INIPY_UNPAID = false;
		paysched.map(function(sched,index){
			function checkDue(due_date){
				var due_year = due_date.getFullYear();
				var due_month = due_date.getMonth()+1;
				var due_check = parseInt(due_year+''+(due_month<10?'0'+due_month:due_month));
				var today = new Date();
				var tod_year = today.getFullYear();
				var tod_month = today.getMonth()+1;
				var tod_check =parseInt(tod_year+''+(tod_month<10?'0'+tod_month:tod_month));
				var isDue = due_year<tod_year || (due_year==tod_year && due_month<=tod_month);
				var isOverDue = isDue && tod_check>due_check;	
				var dueObj = {due:isDue,overDue:isOverDue};
				return dueObj;
			}

			var ttid=  sched.transaction_type_id;
			var due_date = new Date(sched.due_date);
			var dueObj = checkDue(due_date);
			var isDue =  dueObj.due;
			var isOverDue =  dueObj.isOverDue;
			var trnx = list[listIndex[ttid]];
			
			if(sched.status=='PAID') {
				paysched[index].class='success';
				paysched[index].disp_status = 'PAID';
				if(sched.bill_month=="UPONNROL"){
					updateAmount('INRES','set',0);
					updateDisplay('INRES','hide');
				}
				return;
			}
			paysched[index].class='';
			paysched[index].disp_status='-';
			
			if(sched.bill_month=="UPONNROL" && sched.status!='PAID')
				isDue = true;
			
			
			if(!isDue && trnx.amount>0  || INIPY_UNPAID ){
				// Prevent update for next schedule
				return;	
			}
			paysched[index].class=isDue?'danger':'warning';
			paysched[index].disp_status =isDue?'DUE':'UNPAID';
			if(isOverDue)
				paysched[index].disp_status= 'OVER DUE';
			switch(ttid){
				case 'INIPY':
					updateAmount('INIPY','set',sched.due_amount);
					// NOTE: Override Initial Payment if overdue already combine to Subsequent Payment
					let nextPAY = paysched[index+1];
					let nextDueDate =  new Date(nextPAY.due_date);
					let nextDueObj = checkDue(nextDueDate);
					let combineToSBQ = nextDueObj.due ||nextDueObj.overDue;
					let isPartial =  sched.status!='PAID' && sched.due_amount!=5000;
					if(combineToSBQ || isPartial){
						updateAmount('INIPY','set',0);
						updateAmount('SBQPY','set',sched.due_amount);
						if(isPartial){
							updateAmount('INRES','set',0);
							updateDisplay('INRES','hide');
						}
					}else if(isDue){
						INIPY_UNPAID = true;

					}

				break;
				case 'SBQPY':  case 'ACECF':
					updateAmount('SBQPY','add',sched.due_amount);
				break;
				case 'OLDAC':
					updateAmount('OLDAC','add',sched.due_amount);
				break;
				case 'EXTPY':
					updateAmount('EXTPY','add',sched.due_amount);
				break;
				default:
					updateAmount(ttid,'set',sched.due_amount);
				break;
			}
		});
		if(is_old) 
			payscheds.old = angular.copy(paysched);
		else
			payscheds.regular = angular.copy(paysched);
	}
	function defaultList(){
		updateAmount('INRES','set',0);
		updateAmount('INIPY','set',0);
		updateAmount('SBQPY','set',0);
		updateAmount('OLDAC','set',0);
		updateAmount('EXTPY','set',0);
		updateAmount('REGFE','set',0);
		
		
		updateDisplay('INIPY','hide');
		updateDisplay('SBQPY','hide');
		updateDisplay('OLDAC','hide');
		updateDisplay('EXTPY','hide');
		updateDisplay('REGFE','hide');
		updateAmount('OTHRS','set',0.10);
		updateDisplay('OTHRS','disable');

		//updateAmount('UNIFM','set',100);
		//updateDisplay('UNIFM','disable');
	}
	function updateDisplays(is_old){
		list.map(function(lItem){
			var hasAmt = lItem.amount>0;
			var disp =hasAmt?'show':'hide';
			updateDisplay(lItem.id,disp);
		});

		if(is_old) 
			payscheds.old = angular.copy(paysched);
		else
			payscheds.regular = angular.copy(paysched);
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
		var timestamp = new Date();
			timestamp =  timestamp.getDate()+'-'+timestamp.getUTCMilliseconds();
		var filter = {
			id:sid,
			limit:1,
			refresh: timestamp
		};
		updateDisplay('OLDAC','show');
		updateDisplay('OTHRS','enable');
		
		//updateDisplay('UNIFM','enable');
		payscheds.regular = [];
		return TRNX.requestAccount('accounts',filter,'Paysched');
	}
	TRNX.getOldAccount = function(sid,sy){
		var esp = sy + '.25';
		var filter = {
			account_id:sid,
			limit:1,
		};
		payscheds.old = [];
		return TRNX.requestAccount('payment_plans',filter,'schedule');
	}
	TRNX.requestAccount = function(endpoint,filter,field){
		var success  =function(response){
			var data =  response.data[0];
			var _paysched = data[field];
			var is_old = endpoint=='payment_plans';
			var accountType = data.account_type;
			switch(accountType){
				case 'student':
					formatPaysched(_paysched,is_old);
					computeAmounts();
					updateDisplays(is_old);
					updateDisplay('OTHRS','enable');
				break;
				case 'others':
					updateDisplay('INRES','hide');
					updateDisplay('OLDAC','show');
					updateDisplay('OTHRS','enable');
				break;
			}
		};
		var error  =function(response){

		};
		return api.GET(endpoint,filter,success,error);
	}
	TRNX.updateAmount = updateAmount;
	TRNX.updateDisplay = updateDisplay;
	return TRNX;
});