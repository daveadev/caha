"use strict";
define(['app','api'], function (app) {
    app.register.controller('AccountController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Account';
			api.GET('accounts',function success(response){
				console.log(response.data);
				$scope.Accounts=response.data;	
			});
			$scope.hasInfo = false;
			$scope.hasNoInfo = true;
			$scope.openAccountInfo=function(account){
				$scope.Account = account;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
			};
			$scope.removeAccountInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.Account = null;
			};
			$scope.filterAccount = function(account){
				return account.account_no==$scope.searchAccount || !$scope.searchAccount;
			};
		};
    }]);
});


