"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('CollectionController',['$scope','$rootScope','api',
	function($scope,$rootScope,api){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Collection Reports';
			$scope.Options = [{'id':'daily','desc':'Daily'},{'id':'month','desc':'Monthly'}]
			$scope.ActiveOpt = {'id':'daily','desc':'Daily'};
			$scope.Props = ['month','details','collection','balance'];
			//$scope.DProps = ['student','guardians_string','address'];
			$scope.Headers = ['Month','Details','Collection',{label:'Balance',class:'amount total peso'}];
			$scope.DHeaders = ['Date','Month','Details','Collection','Balance'];
			$scope.Months = [
				{id:9,'month':'Sep',},
				{id:10,'month':'Oct'},
				{id:11,'month':'Nov'},
				{id:12,'month':'Dec'},
				{id:1,'month':'Jan'},
				{id:2,'month':'Feb'},
				{id:3,'month':'Mar'},
				{id:4,'month':'Apr'},
				{id:5,'month':'May'},
				{id:6,'month':'Jun'},
				{id:7,'month':'Jul'},
				{id:8,'month':'Aug'},
			];
			$scope.ActiveMonth = {id:9,'month':'Sep'};
			$scope.index = 0;
			getCollections();
		}
		$selfScope.$watch("CC.Active",function(active){
			if(!active) return false;
			console.log(active);
			$scope.ActiveSY =  active.sy;
			getCollections();
		});
		$scope.setActOption = function(opt){
			$scope.ActiveOpt = opt;
			getCollections();
		}
		$scope.SetActiveMonth = function(mo){
			$scope.ActiveMonth = mo;
		}
		$scope.navigateMonth = function(dest){
			if(dest=='next'){
				$scope.index = $scope.index +1;
			}else{
				$scope.index = $scope.index -1;
			}
			$scope.ActiveMonth = $scope.Months[$scope.index];
		}
		
		function getCollections(){
			
			var data = {
				type:$scope.ActiveOpt.id,
				esp:2020,
				from:'2020-09-01',
				to:'2021-04-30'
			};
			if(data.type=='daily'){
				data.from = 2020+'-'+$scope.ActiveMonth.id+'-01'
				data.to = 2020+'-'+$scope.ActiveMonth.id+'-31'
			}
			api.GET('collections',data, function success(response){
				$scope.Collections = response.data[0];
			});
		}
		
		
	}]);

});