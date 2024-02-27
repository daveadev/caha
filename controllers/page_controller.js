"use strict";
define(['app','api'], function (app) {
    app.register.controller('PageController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
       $scope.init = function (module_name) { 
			$rootScope.__MODULE_NAME = module_name || app.settings.DEFAULT_MODULE_NAME;
			$rootScope.__MODULE_NAME = 'SRP';
			$rootScope._APP = $rootScope._APP ||{};
			$rootScope._APP.CopyRight =  document.querySelector('meta[name="copyright"]').getAttribute('content');
			$rootScope._APP.VersionNo =  document.querySelector('meta[name="version"]').getAttribute('content');
			console.log($rootScope.__USER);
			$scope.ActiveUser = $rootScope.__USER;
			$scope.openListItem = function($index){
				$scope.ActiveListItem = $scope.List[$index];
			}
			getModules();
	   }

	   $rootScope.__isAllowed = function(module){
	   		const RIGHTS ={
	   				'admin':'all',
	   				'cashr':['cashier','cashier_collections','account_info'],
	   				'money':['collections','cashier_collections']
	   		}
	   		const USER = $rootScope.__USER.user;
	   		var allowedMods = RIGHTS[USER.user_type]|| false;
	   		if(allowedMods=='all') return true;
	   		if(typeof allowedMods == 'object')
	   			return allowedMods.indexOf(module)!== -1
	   }
	   
	   function getModules(){
		   var data = {id:$scope.ActiveUser.user.access,limit:'less'}
		   api.GET('master_modules',data, function success(response){
			   $scope.Modules = response.data;
			   console.log(data);
			   console.log(response.data);
		   });
	   }
	   
    }]);
});


