"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('CollectionController',['$scope','$rootScope','api','$filter',
	function($scope,$rootScope,api,$filter){
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
		}
		$selfScope.$watch("CC.Active",function(active){
			if(!active) return false;
			console.log(active);
			$scope.ActiveSY =  active.sy;
			getCollections();
		});
		$scope.setActOption = function(opt){
			$scope.ActiveOpt = opt;
			if(opt.id=='month')
				getLedgerMonths();
			if($scope.Collections)
				getCollections();
		}
		$scope.SetActiveMonth = function(mo){
			$scope.ActiveMonth = mo;
		}
		
		
		$scope.LoadReport = function(){
			$scope.Loading = 1;
			getCollections();
		}
		
		$scope.ChangeDate = function(){
			$scope.Loaded = 0;
		}
		
		function formatDate(date){
			var d = new Date(date)
		}
		
		function getLedgerMonths(){
			var trnx = ['INIPY','SBQPY'];
			var data = {
				esp:2020,
				type:'-',
				transaction_type_id:trnx,
				limit:'less'
			}
			api.GET('Ledgers',data,function success(response){
				var Months = [];
				angular.forEach(response.data, function(led){
					var date = new Date(led.transac_date);
					var month = date.getMonth();
					var year = date.getYear();
					var selection = month+'-'+year;
					if(Months.indexOf(selection)===-1)
						Months.push(selection);
				});
				console.log(Months);
			});
		}
		
		function getCollections(){
			$scope.Collections = '';
			
			var data = {
				type:$scope.ActiveOpt.id,
				esp:2020,
				from:$scope.date_from,
				to:$scope.date_to
			};
			data.from = $filter('date')(new Date(data.from),'yyyy-MM-dd');
			data.to = $filter('date')(new Date(data.to),'yyyy-MM-dd');
			api.GET('collections',data, function success(response){
				var collection = response.data[0];
				var total_recvbl = collection.total_receivables-collection.total_subsidies;	
				collection['cfp'] = (collection.collection_forwarded/total_recvbl)*100;
				collection['bbp'] = (collection.net_receivables/total_recvbl)*100;
				$scope.Loaded = 1;
				$scope.Collections = collection;
				$scope.Loading = 0;
			});
		}
		
		
	}]);

});