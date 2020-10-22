"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('SoaController',['$scope','$rootScope','api',
	function($scope,$rootScope,api){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Ledger printing';
			$scope.Printed = 0;
		}
		
		
		
		$scope.printSoa = function(){
			console.log($scope.Student); 
			//$scope.SOA = {account_id:$scope.Student.id};
			$scope.Printed = 1;
			document.getElementById('PrintSoa').submit();
			//$scope.ShowDemo = true;
		}
		
	}]);

});