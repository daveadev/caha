"use strict";
define(['app','api'], function (app) {
    app.register.controller('TransactionController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Trancsaction';
			//Get transactions.js
			api.GET('transactions',function success(response){
				console.log(response.data);
				$scope.Transactions=response.data;	
			});
			//Set for ng-show
			$scope.hasInfo = false;
			$scope.hasNoInfo = true;
			//Open Transaction Information
			$scope.openTransactionInfo=function(transaction){
				$scope.Transaction = transaction;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
			};
			//Remove Transaction Information
			$scope.removeTransactionInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.Transaction = null;
			};
			//Filter Transaction
			$scope.filterTransaction=function(transaction){
				var searchBox = $scope.searchTransaction;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(transaction.account.account_name);
				return !searchBox || test;
			};
			//Clear search box
			$scope.clearSearch = function(){
				$scope.searchTransaction = null;
			};
		};
    }]);
});


