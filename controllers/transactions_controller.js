"use strict";
define(['app','api'], function (app) {
    app.register.controller('TransactionController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Trancsaction';
			api.GET('transactions',function success(response){
				console.log(response.data);
				$scope.Transactions=response.data;	
			});
			$scope.hasInfo = false;
			$scope.hasNoInfo = true;
			$scope.openTransactionInfo=function(transaction){
				$scope.Transaction = transaction;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
			};
			$scope.removeTransactionInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.Trancsaction = null;
			};
		};
    }]);
});


