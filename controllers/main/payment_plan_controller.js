"use strict";
define(['app','transact','api','atomic/bomb'],function(app,TRNX){
	app.register.controller('PaymentPlanController',['$scope','$rootScope','$filter','api','Atomic',
	function($scope,$rootScope,$filter,api,atomic){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
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
			$scope.PlanHeaders = [{label:"#",class:'col-md-1'},"Due Date", "Due Amount", "Paid Amount", "Status"];
			$scope.PlanProps = ["pay_count","due_date", "due_amount", "paid_amount", "status"];
			$scope.PlanInputs = [{field:"pay_count", disabled:true},{field:'due_date',type:'date'},{field:'due_amount',type:'number'},{field:'paid_amount',type:'number'},{field:'status'}];
			$scope.PlanData = [{due_date:new Date(), due_amount:0, paid_amount:0 , status:'UNPAID'}];
		}

		$selfScope.$watchGroup(['PPC.TotalDue','PPC.TotalPayments','PPC.PaymentTerms'],function(entity){
			// Compute default monthly due
			if(entity[0] && entity[2]){
				var totalDue = $scope.TotalDue;
				var totalPay = $scope.TotalPayments;
				var payTerms = $scope.PaymentTerms;
				var monthlyDue = Math.ceil((totalDue - totalPay) / payTerms);
				$scope.MonthlyDue = monthlyDue;
			}
		});
		$scope.computePlan = function(){
			var totalDue = $scope.TotalDue;
			var totalPay = $scope.TotalPayments;
			var payTerms = $scope.PaymentTerms;
			var monthlyDue = $scope.MonthlyDue;
			var payStart = $scope.PaymentStart;
			var paysched = generatePaymentSchedule(totalDue,payTerms,totalPay,monthlyDue,payStart);
			$scope.PlanData = paysched;
		}

		// Function to generate payment schedule array
		function generatePaymentSchedule(totalDue, payTerms, totalPay,monthlyDue,payStart) {
		  const paymentSchedule = [];
		  const startDate = new Date(payStart);
		  for (let i = 1; i <= payTerms; i++) {
		  	const dueDate = new Date(startDate);
    		dueDate.setMonth(startDate.getMonth() + (i-1));

		    const paymentEntry = {
		      pay_count: i,
		      due_date: dueDate,
		      due_amount: monthlyDue,
		      paid_amount: 0,
		      status: 'UNPAID'
		    };

		    paymentSchedule.push(paymentEntry);
		  }

		  // Adjust the last month's due amount
		  const lastMonthIndex = paymentSchedule.length - 1;
		  const adjustedLastMonthDue = totalDue - (monthlyDue * (payTerms - 1));
		  paymentSchedule[lastMonthIndex].due_amount = adjustedLastMonthDue;

		  return paymentSchedule;
		}


	}]);
});