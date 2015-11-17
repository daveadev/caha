"use strict";
define(['app','api'], function (app) {
    app.register.controller('LedgerController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Ledger';
			api.GET('ledgers',function success(response){
				console.log(response.data);
				$scope.Ledgers=response.data;	
			});
			$scope.hasInfo = false;
			$scope.hasNoInfo = true;
			$scope.openLedgerInfo=function(ledger){
				$scope.Ledger = ledger;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
			};
			$scope.removeTransactionInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.Ledger = null;
			};
			$scope.filterLedger=function(ledger){
				var searchBox = $scope.searchLedger;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(ledger.details) || keyword.test(ledger.account.account_name);
				return !searchBox || test;
			};
			$scope.clearSearch = function(){
				$scope.searchLedger = null;
			};
		};
    }]);
});


