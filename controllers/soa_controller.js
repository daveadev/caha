"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('SoaController',['$scope','$rootScope','api',
	function($scope,$rootScope,api){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Ledger printing';
		}
		
		
		
		$scope.printSoa = function(){
			//$scope.ShowDemo = true;
		}
		
	}]);

});