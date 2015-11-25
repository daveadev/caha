"use strict";
define(['app','api'], function (app) {
    app.register.controller('TransactionController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Transaction';
			//Get transactions.js
			$scope.ActivePage = 1;
			$scope.NextPage=null;
			$scope.PrevPage=null;
			$scope.DataLoading = false;
			function getTransactions(data){
				$scope.DataLoading = true;	
				api.GET('transactions',data,function success(response){
					$scope.DataLoading = true;
					console.log(response.data);
					$scope.Transactions=response.data;
					$scope.NextPage=response.meta.next;
					$scope.PrevPage=response.meta.prev;
					$scope.DataLoading = false;
				});
			}
			getTransactions({page:$scope.ActivePage});
			$scope.navigatePage=function(page){
				$scope.ActivePage=page;
				getTransactions({page:$scope.ActivePage});
			};
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
			$scope.confirmSearch = function(){
				getTransactions({page:$scope.ActivePage,keyword:$scope.searchTransaction,fields:['account.account_name']});
			}
			//Clear search box
			$scope.clearSearch = function(){
				$scope.searchTransaction = null;
			};
			$scope.deleteTransaction = function(id){
				var data = {id:id};
				api.DELETE('transactions',data,function(response){
					$scope.removeTransactionInfo();
					getTransactions({page:$scope.ActivePage});
				});
			};
		};
    }]);
});


