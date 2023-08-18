"use strict";
define(['app','adjust-memo','api','atomic/bomb'],function(app,AM){
	const DATE_FORMAT = 'dd MMM yyyy';
	const ADJUST_TYPES = {};
	const ADJUST_TRNX = {ref_no:'LSDxxxx',id:'TMP_LE'};
	app.register.controller('AdjustMemoController',['$scope','$rootScope','$filter','api','Atomic',
	function($scope,$rootScope,$filter,api,atomic){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
			loadUIComps();
		}
		$scope.computeAdjust = function(){

			let amt = $scope.AdjustAmount;
			let type = $scope.AdjustType;
			let trnx = ADJUST_TRNX;
			computeLedgerEntry(amt,type,trnx);
			computePaymentSched(amt,trnx);
						
		}
		$scope.clearAdjust = function(){
			let trnx = ADJUST_TRNX;
			let amount = $scope.AdjustAmount;
			clearLedgerEntry(trnx,amount);
			clearPaymentSched(trnx,amount);
			$scope.AdjustAmount = null;
			$scope.AdjustType = null;

		}
		$scope.applyAdjust = function(){
			$scope.allowApply = false;
			$scope.allowClear = false;
			$scope.SavingAdjust = true;
			let trnx = ADJUST_TRNX;
			let entries = $filter('filter')($scope.LEData,{id:trnx.id});
			let schedule = $filter('filter')($scope.PSData,{id:trnx.id});
			let sid = $scope.ActiveStudent.id;
			let sy = $scope.ActiveSY;
			applyLedgerEntry(entries,sid,sy);
			applyPaymentSched(schedule,account);
			applyAccount(account);
		}
		$selfScope.$watchGroup(['AMC.AdjustType','AMC.AdjustAmount','AMC.ActiveStudent','AMC.LEActiveItem','AMC.PSActiveItem'],function(vars){
			$scope.allowCompute =  $scope.AdjustType && $scope.AdjustAmount && !$scope.LEActiveItem.id;
			$scope.allowInput = $scope.ActiveStudent.id   && !$scope.LEActiveItem.id;
			$scope.allowClear = $scope.AdjustType && $scope.AdjustAmount && !$scope.allowCompute;
			$scope.allowApply = $scope.LEActiveItem.id && $scope.PSActiveItem.id;
		});
		$selfScope.$watchGroup(['AMC.ActiveStudent'],function(entity){
			let STU = $scope.ActiveStudent;
			if(!STU) return;

			let SID = STU.id;
			let ESP = $scope.ActiveSY;
			loadStudentAccount(SID);
			loadLedgerEntry(SID, ESP);
			loadPayschedule(SID,ESP);
		});
		$selfScope.$watchGroup(['AMC.LERunBalance','AMC.PSRunBalance'],function(entity){
			if(entity[0]!=undefined) $scope.LERunBalanceDisp = $filter('currency')(entity[0]);
			if(entity[1]!=undefined) $scope.PSRunBalanceDisp = $filter('currency')(entity[1]);
		});
		function loadUIComps(){
			// Atomic Ready 
			atomic.ready(function(){
				var sys = atomic.SchoolYears;
				var sy = atomic.ActiveSY;
				$scope.SchoolYears = $filter('orderBy')(sys,'-id');
				$scope.ActiveSY = sy;
			});

			// Adjusting Transactions
			$scope.AMTypes = AM.UI.TYPES;
			AM.UI.TYPES.map((type)=>{
				ADJUST_TYPES[type.id] = type;
			});

			// Ledger Entries
			$scope.LEHdrs = AM.UI.LEDGER.Headers;
			$scope.LEProps = AM.UI.LEDGER.Properties;
			$scope.LEData = [
					{date:'17 Aug 2023',ref_no:'OR123', description:'Initial Payment',fee:5000,payment:0,balance:0}
					];
			// Payment Schedule
			$scope.PSHdrs = AM.UI.PAYMENT_SCHED.Headers;
			$scope.PSProps = AM.UI.PAYMENT_SCHED.Properties;
			$scope.PSData = [
				{due_date:'17 Aug 2023',due_amount:5000, paid_amount:5000,balance:0, status:'PAID'}
				];

		}

		function loadStudentAccount(student_id){
			let filter = {id:student_id};
			let success = function(response){
				var account = response.data[0];
				$scope.OutBalance = $filter('currency')(account.outstanding_balance);
				$scope.PayTotal = $filter('currency')(account.payment_total);
			};
			let error = function(response){
				console.log(response);
			};
			api.GET('accounts',filter,success,error);
		}
		function loadLedgerEntry(student_id,sy){
			let filter= {account_id:student_id,esp:sy};
			let success = function(response){
			let entries = $filter('orderBy')(response.data,'transac_date');
				entries = $filter('orderBy')(entries,'ref_no');
			$scope.LEData = [];
			let runBalance = 0;
				entries.map((entry)=>{
					let amt = entry.amount;
					if(!amt) return;

					let obj ={};
						obj.id =  entry.id;
						obj.date = $filter('date')(new Date(entry.transac_date),DATE_FORMAT);
						obj.ref_no = entry.ref_no;
						obj.description = entry.details;
						if(entry.type=='+'){
							obj.fee = $filter('currency')(amt);
							runBalance+= amt;
						}else if(entry.type=='-'){
							obj.payment = $filter('currency')(amt);
							runBalance-= amt;
						}
						obj.balance = $filter('currency')(runBalance);
					$scope.LEData.push(obj);
				});	
				$scope.LERunBalance = runBalance;

			};
			let error = function(response){
				console.log(response);
			};
			api.GET('ledgers',filter,success,error);
		}

		function loadPayschedule(student_id){
			let filter = {account_id:student_id};
			let success = function(response){
				let schedule = $filter('orderBy')(response.data,'order');
				let runBalance = 0;
				$scope.PSData = [];
				schedule.map((sched)=>{
					let due_amt = sched.due_amount;
					let paid_amt = sched.paid_amount;
					let trnx_type = sched.transaction_type_id;
					let balance = due_amt - paid_amt;
						runBalance+=balance;
					let obj = {};
						obj.due_date =trnx_type=='INIPY'?'Upon Enrollment':$filter('date')(sched.due_date,DATE_FORMAT);
						obj.due_amount =$filter('currency')(due_amt);
						obj.paid_amount =$filter('currency')(paid_amt);
						obj.balance = $filter('currency')(balance);
						if(sched.status!='NONE')
							obj.status = sched.status;
					$scope.PSData.push(obj);
				});
				$scope.PSRunBalance = runBalance;
			};
			let error = function(response){
				console.log(response);
			};
			api.GET('account_schedules',filter,success,error);
		}
		function computeLedgerEntry(amount,type,trnx){
			let atObj = ADJUST_TYPES[type];
			let initBal = $scope.LERunBalance;
			let newBal = initBal -  amount;
			let entryObj = {
				id:trnx.id,
				date: $filter('date')(new Date(),DATE_FORMAT),
				ref_no: trnx.ref_no,
				description: atObj.name,
				code: atObj.id,
				fee:null,
				payment:$filter('currency')(amount),
				balance:$filter('currency')(newBal)
			};
			$scope.LEData.push(entryObj);
			$scope.LEActiveItem = $scope.LEData[$scope.LEData.length-1];
			$scope.LERunBalance = newBal;
		}
		function computePaymentSched(amount,trnx){
			let initBal = $scope.PSRunBalance;
			let newBal = initBal -  amount;
			let paymentSchedule = [];
			$scope.PSData.map((sched,index)=>{
				sched.paid_amount =  parseFloat(sched.paid_amount.replace(',', ''));
				sched.balance =  parseFloat(sched.balance.replace(',', ''));
				$scope.PSData[index] = sched;
			});

			let recomputedSchedule = distributePayment($scope.PSData,amount,trnx);

			$scope.PSData = recomputedSchedule;
			$scope.PSActiveItem =$scope.PSData[1];
			$scope.PSRunBalance = newBal;
		}
		// Function to distribute a payment among items with no payment
		function distributePayment(paymentSchedule,paymentAmount,trnx) {
		  let remainingAmount = paymentAmount;
		  if(remainingAmount>=0){
			  for (let i = 1; i < paymentSchedule.length; i++) {
			    if (paymentSchedule[i].status !== 'PAID') {
			      const balance = paymentSchedule[i].balance;
			      const remainingBalance = Math.min(remainingAmount, balance);
			      const itemId = paymentSchedule[i].id;
			      paymentSchedule[i].api_id = itemId;
			      paymentSchedule[i].id = trnx.id;
			      paymentSchedule[i].paid_amount += remainingBalance;
			      paymentSchedule[i].balance -= remainingBalance;

			      if (paymentSchedule[i].balance === 0.00) {
			        paymentSchedule[i].status = 'PAID';
			      }

			      remainingAmount -= remainingBalance;

			      if (remainingAmount <= 0.00) {
			        break;
			      }
			    }
			  }
		  }else{
		  	for (let i = paymentSchedule.length - 1; i >= 1; i--) {
		      const paidAmount = Math.min(paymentSchedule[i].paid_amount, Math.abs(remainingAmount));
		      paymentSchedule[i].paid_amount -= paidAmount;
		      paymentSchedule[i].balance += paidAmount;

		      if (paymentSchedule[i].status === 'PAID') {
		        delete paymentSchedule[i].status;
		      }
		      if(paymentSchedule[i].id == trnx.id){
		      	delete paymentSchedule[i].id;
		      	paymentSchedule[i].id = paymentSchedule[i].api_id;
		      }

		      remainingAmount += paidAmount;

		      if (remainingAmount >= 0) {
		        break;
		      }
		    }

		  }

		  paymentSchedule.map((sched,index)=>{
				paymentSchedule[index].paid_amount = $filter('currency')(sched.paid_amount);
				paymentSchedule[index].balance = $filter('currency')(sched.balance);
			});
		  return paymentSchedule;
		}
		// Clear Ledger Entries
		function clearLedgerEntry(trnx,amount){
			let entries = $scope.LEData;
			let initBal = $scope.LERunBalance;
			let newBal = initBal +  amount;
			$scope.LEData = $filter('filter')(entries,{'id':'!'+trnx.id});
			$scope.LERunBalance =  newBal;
		}

		// Clear Payment Schedule
		function clearPaymentSched(trnx,amount){
			let schedule = $scope.PSData;
				schedule.map((sched,index)=>{
					sched.paid_amount =  parseFloat(sched.paid_amount.replace(',', ''));
					sched.balance =  parseFloat(sched.balance.replace(',', ''));
					schedule[index] = sched;
				});
			let initBal = $scope.PSRunBalance;
			let newBal = initBal +  amount;
			$scope.PSData = distributePayment(schedule,amount*-1,trnx);
			$scope.PSRunBalance =  newBal;
		}

		// Apply Ledger Entry
		function applyLedgerEntry(entries,sid,sy){
			let entry = {account_id:sid,sy:sy,esp:sy};
			let success = function(response){
				$scope.LEUpdate=true;
			};
			let error = function(response){};
			entries.map((e)=>{
				if(e.fee){
					entry.type='+';
					entry.amount = parseFloat(e.fee.replace(',', ''));
				}else if(e.payment){
					entry.type='-';
					entry.amount = parseFloat(e.payment.replace(',', ''));
				}
				entry.transac_date = $filter('date')(new Date(e.date),'yyyy-MM-dd');
				entry.transaction_type_id =e.code;
				entry.details =e.description;
				$scope.LEUpdate=false;
				api.POST('ledgers',entry,success,error);
			});
			
		}
	}]);
});