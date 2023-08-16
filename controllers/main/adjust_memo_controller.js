"use strict";
define(['app','transact','api','atomic/bomb'],function(app,TRNX){
	const TRNX_LIST = TRNX.__LIST;
	console.log(TRNX_LIST);
	app.register.controller('AdjustMemoController',['$scope','$rootScope','$filter','api','Atomic',
	function($scope,$rootScope,$filter,api,atomic){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
			loadUIComps();
		}

		$selfScope.$watchGroup(['AMC.ActiveStudent'],function(entity){
			
		});
		function loadUIComps(){
			// Atomic Ready 
			atomic.ready(function(){
				var sys = atomic.SchoolYears;
				var sy = atomic.ActiveSY;
				$scope.SchoolYears = $filter('orderBy')(sys,'-id');
				$scope.ActiveSY = sy;
			});

			// Ledger Entries
			$scope.LEHdrs = [{label:'Transact Date',class:'col-md-2'},'Ref No', 'Description','Fees','Payments','Balance'];
			$scope.LEProps = ['date','ref_no','description','fee','payment','balance'];
			$scope.LEData = [
					{date:'17 Aug 2023',ref_no:'OR123', description:'Initial Payment',fee:5000,payment:0,balance:0}
					];
			// Payment Schedule
			$scope.PSHdrs = [{label:'Due Date',class:'col-md-2'},'Due Amount', 'Paid Amount','Balance','Status'];
			$scope.PSProps = ['due_date','due_amount','paid_amount','balance','status'];
			$scope.PSData = [
				{due_date:'17 Aug 2023',due_amount:5000, paid_amount:5000,balance:0, status:'PAID'}
				];

		}

	}]);
});