"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('CashierController',['$scope','$rootScope','api','$filter',
	function($scope,$rootScope,api,$filter){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Cashier Collection';
			$scope.Options = [{'id':'daily','desc':'Daily'}]
			$scope.ActiveOpt = {'id':'daily','desc':'Daily'};
		}
		$selfScope.$watch("CS.Active",function(active){
			if(!active) return false;
			console.log(active);
			$scope.ActiveSY =  active.sy;
			if($scope.date_to)
				getCollections();
		});
		
		$scope.LoadReport = function(){
			getCollections();
		}
		
		$scope.gotoPage = function(page){
			getCollections(page);
		}
		
		$scope.Clear = function(){
			$scope.date_from = '';
			$scope.date_to = '';
			$scope.Collections = '';
		}
		
		function getCollections(page){
			var data = {
				from: $scope.date_from,
				to: $scope.date_to,
				'page': page
			}
			data.from = $filter('date')(new Date(data.from),'yyyy-MM-dd');
			data.to = $filter('date')(new Date(data.to),'yyyy-MM-dd');
			api.GET('cashier_collections',data, function success(response){
				$scope.Collections = response.data;
				$scope.Meta = response.meta;
			});
		}
		
	}]);

});