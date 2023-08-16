"use strict";
define(['app','adjust-memo','api','atomic/bomb'],function(app,AM){
	app.register.controller('AdjustMemoController',['$scope','$rootScope','$filter','api','Atomic',
	function($scope,$rootScope,$filter,api,atomic){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
			loadUIComps();
		}

		$selfScope.$watchGroup(['AMC.ActiveStudent'],function(entity){
			let STU = $scope.ActiveStudent;
			let SID = STU.id;
			let ESP = $scope.ActiveSY;
			loadStudentAccount(SID);
			loadLedgerEntry(SID, ESP);
			loadPayschedule(SID,ESP);
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
			$scope.AMTypes = AM.UI.TYPES;;

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
					entries = $filter('orderBy')(response.data,'type');
				console.log(entries);

			};
			let error = function(response){
				console.log(response);
			};
			api.GET('ledgers',filter,success,error);
		}

	}]);
});