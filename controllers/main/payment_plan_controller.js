"use strict";
define(['app','transact','api','atomic/bomb'],function(app,TRNX){
	const DATE_FORMAT = {display:'dd MMM yyyy',data:'yyyy-MM-dd'};
	const NEXT_SY = false;
	app.register.controller('PaymentPlanController',['$scope','$rootScope','$filter','$timeout','api','Atomic',
	function($scope,$rootScope,$filter,$timeout,api,atomic){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Extension Plan';
			atomic.ready(function(){
				var sys = atomic.SchoolYears;
				var sy = atomic.ActiveSY;
				if(NEXT_SY){
					var asy = sy +1;
					var nsy ={};
						nsy.id =  asy;
						nsy.label = asy + '-'+ (asy+1);
						nsy.code =  (asy+''.substring(2));
					sys.push(nsy);
				}

				$scope.SchoolYears = $filter('orderBy')(sys,'-id');
				$scope.ActiveSY = sy;
				
			});
			$scope.TotalPayments = 0;
			$scope.PlanHeaders = [{label:"#",class:'col-md-1'},"Due Date", {label:"Due Amount",class:'text-right'}];
			$scope.PlanProps = ["pay_count","due_date_display", "due_amount_display"];
			$scope.PlanInputs = [{field:"pay_count", disabled:true},{field:'due_date',type:'date'},{field:'due_amount',type:'number'},{field:'paid_amount',type:'number'},{field:'status'}];
			$scope.PlanData = [];
			$scope.PayPlans = [];
		}

		$selfScope.$watch('PPC.ActiveStudent',function(entity){
			let STU = $scope.ActiveStudent;
			if(!STU) return;
			let SID = STU.id;
			let ESP = $scope.ActiveSY;
			if(!STU.id){
				resetPaymentPlan();
			}else{
				loadPayPlans(SID,ESP);
			}
		});
		$selfScope.$watchGroup(['PPC.ActiveStudent','PPC.TotalDue','PPC.Guarantor','PPC.PaymentTerms','PPC.PaymentStart'],function(entity){
			if(!$scope.ActiveStudent) return;
			$scope.allowCompute =  $scope.TotalDue && $scope.PaymentTerms && $scope.PaymentStart && $scope.Guarantor;
			$scope.allowInput = $scope.ActiveStudent.id  ;
			// Compute default monthly due
			if(entity[1] && entity[3]){
				var totalDue = $scope.TotalDue;
				var totalPay = $scope.TotalPayments;
				var payTerms = $scope.PaymentTerms;
				var monthlyDue = Math.ceil((totalDue - totalPay) / payTerms);
				$scope.MonthlyDue = monthlyDue;
			}
		});
		$selfScope.$watch('PPC.PlanData',function(){
			$scope.allowApply = $scope.PlanData.length;
		});
		$scope.computePlan = function(){
			var totalDue = $scope.TotalDue;
			var totalPay = $scope.TotalPayments;
			var payTerms = $scope.PaymentTerms;
			var monthlyDue = $scope.MonthlyDue;
			var payStart = $scope.PaymentStart;
			var paysched = generatePaymentSchedule(totalDue,payTerms,totalPay,monthlyDue,payStart);
			$scope.PlanData = paysched;
			$scope.allowCompute = false;
			$scope.allowInput = false;

		}
		$scope.revertExtension = function(){
			$scope.allowCompute=true;
			$scope.allowInput=true;
			$scope.PlanData = [];
		}
		$scope.applyExtension = function(){
			let data = {
				account_id: $scope.ActiveStudent.id,
				esp:$scope.ActiveSY,
				payment_start:$filter('date')(new Date($scope.PaymentStart),DATE_FORMAT.data),
				total_due: $scope.TotalDue,
				terms:$scope.PaymentTerms,
				monthly_payments:$scope.MonthlyDue,
				guarantor:$scope.Guarantor,
				schedule:$scope.PlanData
			}
			var success = function(response){
				var data = response.data;
				printPaymentPlan(data);
				resetPaymentPlan();
				loadPayPlans(data.account_id,data.esp,true);
			};
			var error = function(response){

			};
			api.POST('payment_plans',data,success,error);

		}
		$scope.reprintExtension = function(){
			var pid = $scope.PayPlan;
			var details = $filter('filter')($scope.PayPlans,{id:pid})[0];
			printPaymentPlan(details);
		}
		function loadStudentAccount(sid){

			let filter = {id:sid};
			let success = function(response){
				var acc = response.data[0];
				$scope.PaymentStart = new Date("Sep 16, 2023");
				$scope.PaymentTerms = 9;
				if(acc.old_balance>0)
					$scope.TotalDue = acc.old_balance;
			};
			let error = function(response){};
			api.GET('accounts',filter,success,error);
		}
		function loadPayPlans(sid,esp,is_print){
			let filter = {account_id:sid, esp:esp};
			let success = function(response){
				var plans = response.data;
				plans.map((pObj,pIndex)=>{
					pObj.schedule.map((sObj,sIndex)=>{
						var sched = sObj;
							sched.due_date_display = $filter('date')(new Date(sched.due_date),DATE_FORMAT.display);
							sched.due_amount_display =$filter('currency')(sched.due_amount);
							plans[pIndex].schedule[sIndex]=sched;
						});
				});
				$scope.PayPlans = plans;
				if(plans.length && !is_print){
					alert("Payment Extension found! Click REPRINT to view. ");
					$scope.PayPlan = plans[0].id;
				}
			};
			let error = function(response){
				loadStudentAccount(sid);
			};
			return api.GET('payment_plans',filter,success,error);
		}
		function resetPaymentPlan(){
			$scope.TotalDue = undefined;
			$scope.PaymentStart = undefined;
			$scope.PaymentTerms= undefined;
			$scope.Guarantor= undefined;
			$scope.MonthlyDue= undefined;
			$scope.PlanData = [];
			$scope.PrintDetails = {};
			$scope.PayPlans=[];
		}
		function printPaymentPlan(details){
			$scope.PrintPaymentDetails = details;
			$timeout(function(){
				document.getElementById('PrintPaymentPlan').submit();			
			},200);
		}


		// Function to generate payment schedule array
		function generatePaymentSchedule(totalDue, payTerms, totalPay,monthlyDue,payStart) {
		  const paymentSchedule = [];
		  const startDate = new Date(payStart);
		  for (let i = 1; i <= payTerms; i++) {
		  	let dueDate = new Date(startDate);
    				dueDate.setMonth(startDate.getMonth() + (i-1));
    		let dueDateDisp = $filter('date')(new Date(dueDate),DATE_FORMAT.display);
    		let dueDateData = $filter('date')(new Date(dueDate),DATE_FORMAT.data);
    		let monthlyDueDisp =  $filter('currency')(monthlyDue);
		    const paymentEntry = {
		      pay_count: i,
		      due_date: dueDateData,
		      due_date_display: dueDateDisp,
		      due_amount: monthlyDue,
		      due_amount_display: monthlyDueDisp,
		      paid_amount: 0,
		      status: 'UNPAID'
		    };

		    paymentSchedule.push(paymentEntry);
		  }

		  // Adjust the last month's due amount
		  const lastMonthIndex = paymentSchedule.length - 1;
		  const adjustedLastMonthDue = totalDue - (monthlyDue * (payTerms - 1));
		  paymentSchedule[lastMonthIndex].due_amount = adjustedLastMonthDue;
		  paymentSchedule[lastMonthIndex].due_amount_display = $filter('currency')(adjustedLastMonthDue);

		  return paymentSchedule;
		}



	}]);
});