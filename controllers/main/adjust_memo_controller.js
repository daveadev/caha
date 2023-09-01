"use strict";
define(['app','adjust-memo','api','atomic/bomb'],function(app,AM){
	const DATE_FORMAT = 'dd MMM yyyy';
	const ADJUST_TYPES = {};
	const ADJUST_TRNX = {ref_no:'AMFXXXXXX',id:'TMP_LE'};
	const ADJ_RFNO_PRFX = ADJUST_TRNX.ref_no.substr(0,3);
	app.register.controller('AdjustMemoController',['$scope','$rootScope','$filter','$timeout','api','Atomic','aModal',
	function($scope,$rootScope,$filter,$timeout,api,atomic,aModal){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
			loadUIComps();
		}
		$scope.computeAdjust = function(){

			let amt = $scope.AdjustAmount;
			let type = $scope.AdjustType;
			let trnx = ADJUST_TRNX;
			$scope.changesApplied = false;
			$scope.PrintDetails = {}; 
			$scope.ActiveTabIndex = 0;
			computeLedgerEntry(amt,type,trnx);
			computePaymentSched(amt,trnx);
						
		}
		$scope.clearAdjust = function(){
			let trnx = ADJUST_TRNX;
			let amount = $scope.AdjustAmount;
			clearLedgerEntry(trnx,amount);
			clearPaymentSched(trnx,amount);
			//$scope.AdjustAmount = null;
			//$scope.AdjustType = null;

		}
		$scope.applyAdjust = function(){
			modalDefaults();
			aModal.open("AdjustMemoModal");
		}
		$scope.reprintReceipt = function(){
			let aAcc =  $scope.ActiveAccount;
			let ref_id = $scope.AdjustRefno;
			let trnx = $filter('filter')($scope.AMFRefNos,{id:ref_id})[0];
				trnx.amount = parseFloat(trnx.payment.replace(',', ''));
				trnx.details = trnx.description;
			let pObj = buildPrintDetails(aAcc,trnx);
			triggerPrint(pObj);
		}
		$scope.closeAdjModal = function(){
			aModal.close("AdjustMemoModal");
		}
		$scope.confirmAdjModal = function(){
			confirmAdjust();
			aModal.close("AdjustMemoModal");
		}
		$scope.checkRefNo = function(){

		}
		$selfScope.$watchGroup(['AMC.ActiveStudent','AMC.AdjustType','AMC.AdjustAmount','AMC.ActiveStudent','AMC.LEActiveItem','AMC.PSActiveItem'],function(vars){
			if(!$scope.ActiveStudent) return;
			$scope.allowCompute =  $scope.AdjustType && $scope.AdjustAmount && !$scope.LEActiveItem.id;
			$scope.allowInput = $scope.ActiveStudent.id   && !$scope.LEActiveItem.id;
			$scope.allowClear = $scope.AdjustType && $scope.AdjustAmount && !$scope.allowCompute;
			$scope.allowApply = $scope.LEActiveItem.id || $scope.PSActiveItem.id;
			$scope.AdjustAmountDisp =  $filter('currency')($scope.AdjustAmount);
			//console.log($scope.LEActiveItem , $scope.PSActiveItem, $scope.allowApply);
		});
		$selfScope.$watchGroup(['AMC.LEUpdate','AMC.PSUpdate'],function(vars){
			$scope.changesApplied = $scope.LEUpdate && $scope.PSUpdate	&& $scope.SavingAdjust;
			if($scope.changesApplied){
				$scope.SavingAdjust = false;
				$scope.allowApply = false;
				$scope.AdjustType = null;
				$scope.AdjustAmount = null;
			}
			
		});
		$selfScope.$watchGroup(['AMC.ActiveAccount','AMC.AdjustDetails'],function(vars){
			if(!vars[0] && !vars[1]) return;
			// Print Adjustment Receipt
			if($scope.AdjustDetails.id && $scope.ActiveAccount.id){
				let aAcc =  $scope.ActiveAccount;
				let aDtl = $scope.AdjustDetails;
				let pObj = buildPrintDetails(aAcc,aDtl);
				triggerPrint(pObj);

			}
		});

		$selfScope.$watch('AMC.ActiveStudent',function(entity){
			let STU = $scope.ActiveStudent;
			if(!STU) return;
			let SID = STU.id;
			let ESP = $scope.ActiveSY;
			if(SID==null){
				resetStudentAccount();
				resetLedgerEntry();
				resetPaymentSched();
			}else{
				loadStudentAccount(SID);
				loadLedgerEntry(SID, ESP);
				loadPayschedule(SID,ESP)
			}
		});
		$selfScope.$watchGroup(['AMC.LERunBalance','AMC.PSRunBalance'],function(entity){
			if(entity[0]!=undefined) $scope.LERunBalanceDisp = $filter('currency')(entity[0]);
			if(entity[1]!=undefined) $scope.PSRunBalanceDisp = $filter('currency')(entity[1]);
		});
		function loadUIComps(){
			$rootScope.$watch('_APP',(app)=>{
				$scope.ActiveSY = app.ACTIVE_SY;
			});
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
					//{date:'17 Aug 2023',ref_no:'OR123', description:'Initial Payment',fee:5000,payment:0,balance:0}
					];
			$scope.LEActiveItem = {};
			// Payment Schedule
			$scope.PSHdrs = AM.UI.PAYMENT_SCHED.Headers;
			$scope.PSProps = AM.UI.PAYMENT_SCHED.Properties;
			$scope.PSData = [
				//{due_date:'17 Aug 2023',due_amount:5000, paid_amount:5000,balance:0, status:'PAID'}
				];
			$scope.PSActiveItem = {};


		}
		function loadStudentAccount(student_id){
			$scope.ActiveTabIndex = 2; 
			let filter = {id:student_id};
			let success = function(response){
				var account = response.data[0];
				$scope.ActiveAccount = account;
				$scope.OutBalance = $filter('currency')(account.outstanding_balance);
				$scope.PayTotal = $filter('currency')(account.payment_total);
			};
			let error = function(response){
				console.log(response);
			};
			$scope.ActiveAccount = {};
			$scope.AMFRefNos = [];
			api.GET('accounts',filter,success,error);
		}
		function loadLedgerEntry(student_id,sy){
			let filter= {account_id:student_id,esp:sy,limit:'less'};
			let success = function(response){
				let entries = sortByTransacDateAndRef(response.data);
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

				let amfs = $filter('filter')($scope.LEData,{ref_no:ADJ_RFNO_PRFX});
				$scope.AMFRefNos =  amfs;
				document.getElementById('PrintSoa').submit();

			};
			let error = function(response){
				console.log(response);
			};
			api.GET('ledgers',filter,success,error);

			function sortByTransacDateAndRef(arr) {
			  return arr.slice().sort((a, b) => {
			    const dateA = new Date(a.transac_date);
			    const dateB = new Date(b.transac_date);

			    if (dateA < dateB) {
			      return -1;
			    }
			    if (dateA > dateB) {
			      return 1;
			    }

			    // If transac_date is equal, sort by ref_no
			    return a.ref_no.localeCompare(b.ref_no);
			  });
			}

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
						obj.api_id = sched.id;
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
			$scope.ActiveTabIndex = 0;
			let atObj = ADJUST_TYPES[type];
			let initBal = $scope.LERunBalance;
			let newBal = initBal -  amount;
			let entryObj = {
				id:trnx.id,
				date: $filter('date')(new Date(),DATE_FORMAT),
				ref_no: trnx.ref_no,
				description: atObj.code,
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
			let origPS = angular.copy($scope.PSData);
			$scope.PSData.map((sched,index)=>{
				sched.paid_amount =  parseFloat(sched.paid_amount.replace(',', ''));
				sched.balance =  parseFloat(sched.balance.replace(',', ''));
				$scope.PSData[index] = sched;
			});
			let updateIndex = 0;
			let recomputedSchedule = distributePayment($scope.PSData,amount,trnx);
				recomputedSchedule.map((sched,index)=>{
					let ogPaidAmt =  parseFloat(origPS[index].paid_amount.replace(',', ''))
					if(sched.paid_amount!= ogPaidAmt && updateIndex==0){
						updateIndex = index;
					}
					$scope.PSData[index].paid_amount = $filter('currency')(sched.paid_amount);
					$scope.PSData[index].balance = $filter('currency')(sched.balance);
				});

			$scope.PSActiveItem =$scope.PSData[updateIndex];
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
		  return paymentSchedule;
		}
		// Clear Ledger Entries
		function clearLedgerEntry(trnx,amount){
			let entries = $scope.LEData;
			let initBal = $scope.LERunBalance;
			let newBal = initBal +  amount;
			$scope.LEData = $filter('filter')(entries,{'id':'!'+trnx.id});
			$scope.LERunBalance =  newBal;
			$scope.LEActiveItem = {};
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
			$scope.PSData.map((sched,index)=>{
				$scope.PSData[index].paid_amount = $filter('currency')(sched.paid_amount);
				$scope.PSData[index].balance = $filter('currency')(sched.balance);
			});
			$scope.PSRunBalance =  newBal;
			$scope.PSActiveItem = {};
		}

		// Apply Ledger Entry
		function applyLedgerEntry(entries,sid,sy){
			let entry = {account_id:sid,sy:sy,esp:sy};
			let success = function(response){
				$scope.LEUpdate=true;
				$scope.LEActiveItem={};
				let transac = response.data;
					delete transac.id;

				applyAccountAdjust(transac);

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
				entry.transac_date = $filter('date')(new Date($scope.AdjDate),'yyyy-MM-dd');
				entry.transaction_type_id =e.code;
				entry.details =e.description;
				$scope.LEUpdate=false;
				api.POST('ledgers',entry,success,error);
			});
			
		}
		// Apply Payment Schedule 
		function applyPaymentSched(schedule,sid,index){

			if(!schedule.length){
				$scope.PSUpdate = true;
				$scope.PSActiveItem = {};
				return false;
			}
			let sched = {account_id:sid};
			if(index==undefined) index=0;
			let adjSched = schedule[index];
			if(adjSched.api_id)
				sched.id = adjSched.api_id;
			if(adjSched.status)
				sched.status = adjSched.status;

			sched.paid_amount = parseFloat(adjSched.paid_amount.replace(',', ''));
			sched.paid_date =  $filter('date')(new Date($scope.AdjDate),'yyyy-MM-dd');

			let success = function(response){
				if(index<schedule.length-1){
					return applyPaymentSched(schedule,sid,index+1);
				}else{
					$scope.PSUpdate = true;
					$scope.PSActiveItem = {};
					return loadPayschedule(sid,$scope.ActiveSY);
				}
				
			}
			let error = function(response){}
			$scope.PSUpdate = false;
			return api.POST('account_schedules',sched,success,error);

			
		}
		function applyAccountAdjust(trnx){
			let aObj =  trnx;
				aObj.item_code = trnx.transaction_type_id;
				aObj.adjust_date = trnx.transac_date;
				aObj.flag = trnx.type;
			let success = function(response){
				let SID = trnx.account_id;
				let ESP = trnx.esp;
				$scope.AdjustDetails = response.data;
				loadStudentAccount(SID);
				loadLedgerEntry(SID, ESP);
			};
			let error = function(response){};
			$scope.AdjustDetails = {};
			api.POST('account_adjustments',aObj, success,error);
		}
		// Reset Student Account
		function resetStudentAccount(){
			$scope.AdjustType = null;
			$scope.AdjustAmount = null;
			$scope.OutBalance = null;
			$scope.PayTotal = null;
			$scope.ActiveAccount = {}; 
			$scope.PrintDetails = {};
			$scope.AMFRefNos = [];

		}
		// Reset Ledger Entry
		function resetLedgerEntry(){
			$scope.LEData = [];
			$scope.LERunBalance = null;
			$scope.LERunBalanceDisp = null;
			$scope.LEActiveItem = {};
		}
		// Reset Payment Schedule
		function resetPaymentSched(){
			$scope.PSData = [];
			$scope.PSRunBalance = null;
			$scope.PSRunBalanceDisp = null;
			$scope.PSActiveItem = {};
		}
		// Confirm Adjust Memo
		function confirmAdjust(){
			$scope.allowApply = false;
			$scope.allowClear = false;
			$scope.SavingAdjust = true;
			let trnx = ADJUST_TRNX;
			let entries = $filter('filter')($scope.LEData,{id:trnx.id});
			let schedule = $filter('filter')($scope.PSData,{id:trnx.id});
			let sid = $scope.ActiveStudent.id;
			let sy = $scope.ActiveSY;
			applyLedgerEntry(entries,sid,sy);
			applyPaymentSched(schedule,sid);
		}
		// Modal defaults
		function modalDefaults(){
			$scope.AdjDate = new Date();
			let refNo = ADJ_RFNO_PRFX;
			let filter = {sy:$scope.ActiveSY,prefix:refNo};
			let success = function(response){
				$scope.AdjRefNo = response.data.ref_no;
			};
			let error = function(response){};

			$scope.AdjRefNo = filter.prefix;
			api.GET('account_adjustments/ref_no',filter,success,error);
		}
		// Modal check refNo
		// Build Print details
		function buildPrintDetails(aAcc,aDtl){
			let pObj = {};
				pObj.student = aAcc.name;
				pObj.sno = aAcc.sno;
				pObj.year_level = aAcc.year_level;
				pObj.section = aAcc.section;
				pObj.ref_no = aDtl.ref_no;
				pObj.transac_date =  $filter('date')(new Date(aDtl.transac_date),DATE_FORMAT);
				pObj.sy = $scope.ActiveSY;
				pObj.total_paid = $filter('currency')(aDtl.amount);
				pObj.transac_details = [{item:aDtl.details,amount:pObj.total_paid}];
				return pObj;
		}
		function triggerPrint(pObj){
			$scope.PrintDetails = pObj;
			$timeout(function(){
				document.getElementById('PrintAdjustReceipt').submit();			
				$scope.ActiveTabIndex = 2;
				$scope.AdjustDetails = {};
			},200);
		}
	}]);
});