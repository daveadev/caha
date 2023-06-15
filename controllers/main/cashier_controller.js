"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('CashierController',['$scope','$rootScope','api','Atomic',
	function($scope,$rootScope,api,atomic){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
		}
	}]);

});