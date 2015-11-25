"use strict";
define(['app','api'], function (app) {
    app.register.controller('AccountController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Account';
			//Get accounts.js
			$scope.ActivePage = 1;
			$scope.NextPage=null;
			$scope.PrevPage=null;
			$scope.DataLoading = false;
			
			function getAccounts(data){
				$scope.DataLoading = true;
				api.GET('accounts',data,function success(response){
				console.log(response.data);
				$scope.Accounts=response.data;	
				$scope.NextPage=response.meta.next;
				$scope.PrevPage=response.meta.prev;
				$scope.DataLoading = false;
				});
			}
			getAccounts({page:$scope.ActivePage});
			$scope.navigatePage=function(page){
				$scope.ActivePage=page;
				getAccounts({page:$scope.ActivePage});
			};
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
			$scope.confirmSearch = function(){
				getAccounts({page:$scope.ActivePage,keyword:$scope.searchAccount,fields:['account_name']});
			}
			//Clear searchbox
			$scope.clearSearch = function(){
				$scope.searchAccount = null;
			};
			$scope.deleteAccounts = function(id){
				var data = {id:id};
				api.DELETE('accounts',data,function(response){
					$scope.removeAccountInfo();
					getAccounts({page:$scope.ActivePage});
				});
			};
		};
    }]);
});


