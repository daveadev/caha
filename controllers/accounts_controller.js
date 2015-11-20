"use strict";
define(['app','api'], function (app) {
    app.register.controller('AccountController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Account';
			//Get accounts.js
			api.GET('accounts',function success(response){
				console.log(response.data);
				$scope.Accounts=response.data;	
			});
			//Set for ng-show
			$scope.hasInfo = false;
			$scope.hasNoInfo = true;
			//Open account information
			$scope.openAccountInfo=function(account){
				$scope.Account = account;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
			};
			//Remove account information
			$scope.removeAccountInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.Account = null;
			};
			//Filter account
			$scope.filterAccount = function(account){
				var searchBox = $scope.searchAccount;
				var keyword = new RegExp(searchBox,'i');
				var test = keyword.test(account.account_name);
				return !searchBox || account.account_no==searchBox || test;
			};
			//Clear searchbox
			$scope.clearSearch = function(){
				$scope.searchAccount = null;
			};
		};
    }]);
});


