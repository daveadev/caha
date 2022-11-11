"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('AccountReceivableController',['$scope','$rootScope','api','Atomic','$filter','$timeout','aModal',
	function($scope,$rootScope,api,atomic,$filter,$timeout,aModal){
		const $selfScope = $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Account Receivables';
			$scope.Today = new Date();
			$scope.isReady = false;
				
		};
		
		
		
		$selfScope.$watch("AR.Active",function(active){
			if(!active) return false;
			console.log(active);
			$scope.ActiveSY =  active.sy;
			getAR();
		});
		
		$scope.PrintAR = function(){
			document.getElementById('PrintAR').submit();
		}
		
		function getAR(){
			var data = {
				limit:'less',
				to:$filter('date')(new Date(),'yyyy-MM-dd'),
				esp:$scope.ActiveSY
			};
			api.GET('account_receivables',data, function success(response){
				let asstnc = response.data[0].totals.FinAsstn;
				angular.forEach(response.data[0].ARC, function(a){
					a['total_balance'] = a.m_balance+a.t_balance-asstnc;
				});
				$scope.AccountReceivables = response.data[0];
			}, function error(response){
				
			});
		}
		
	}]);
});