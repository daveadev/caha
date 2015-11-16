"use strict";
define(['app','api'], function (app) {
    app.register.controller('AccountController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Account';
			api.GET('accounts',function success(response){
				console.log(response.data);
				$scope.Accounts=response.data;	
			});
			$scope.openAccountInfo=function(account){
				$scope.Account = account;
			};
		};
    }]);
});


