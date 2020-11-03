"use strict";
define(['app','api'], function (app) {
    app.register.controller('PageController',['$scope','$rootScope','api', function ($scope,$rootScope,api) {
       $scope.init = function (module_name) { 
			$rootScope.__MODULE_NAME = module_name || app.settings.DEFAULT_MODULE_NAME;
			$rootScope.__MODULE_NAME = 'SRP';
			
			$scope.openListItem = function($index){
				$scope.ActiveListItem = $scope.List[$index];
			}
	   }

	   $rootScope.__isAllowed = function(module){
	   		const RIGHTS ={
	   				'admin':'all',
	   				'cashr':['cashier','cashier_collections'],
	   				'money':['collections','cashier_collections']
	   		}
	   		const USER = $rootScope.__USER.user;
	   		var allowedMods = RIGHTS[USER.user_type]|| false;
	   		if(allowedMods=='all') return true;
	   		if(typeof allowedMods == 'object')
	   			return allowedMods.indexOf(module)!== -1
	   }
	   
    }]);
});


