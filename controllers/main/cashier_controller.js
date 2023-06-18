"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('CashierController',['$scope','$rootScope','$filter','api','Atomic',
	function($scope,$rootScope,$filter,api,atomic){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			atomic.ready(function(){
				$scope.SchoolYears = $filter('orderBy')(atomic.SchoolYears,'-id');
				$scope.ActiveSY = atomic.ActiveSY;
			});
			$scope.Headers = ['Description',{class:'col-md-4',label:'Amount'}];
			$scope.Props = ['description','amount'];
		}
		$scope.TransacDetails=[
				{description:'Initial Payment',amount:5000},
				{description:'Initial Payment 2',amount:5000},
				{description:'Initial Payment 3',amount:5000},
				{description:'Initial Payment 4',amount:5000},
				{description:'Initial Payment 5',amount:5000},
				{description:'Initial Payment 6',amount:5000},
				{description:'Initial Payment 7',amount:5000},
		];
		$scope.TotalAmount = 5000;
		$scope.SeriesNo = 'OR 12345';
		$scope.TransacDate = new Date();
	}]);

});