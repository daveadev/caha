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
			//$scope.Headers = ['Sno','Received from','Level','Section','Status','Date','Particular','OR #',{label:'Amount',class:'amount total'},{label:'Total Due',class:'amount total'},{label:'Total Paid',class:'amount total'},{label:'Balance',class:'amount total'}];
			//$scope.Props = ['sno','name','year_level','section','status','transac_date','details','ref_no','amount','total_due','total_paid','balance'];
			getTransacs();
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
		
		$scope.PrintData = function(){
			document.getElementById('PrintCashierCollection').submit();
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
				
				$scope.Collections = response.data[0];
				$scope.Meta = response.meta;
				getForPrinting(data);
			});
		}
		
		function getForPrinting(data){
			data.limit = 'less';
			api.GET('cashier_collections',data, function success(response){
				var print = {data:response.data};
				$scope.CashierData =  print;
			});
		}
		
		function getTransacs(){
			var data = {limit:'less'};
			api.GET('transaction_types',data, function success(response){
				var Trnx = {};
				console.log(response);
				angular.forEach(response.data, function(tr){
					console.log(tr);
					Trnx[tr.id] = tr.name;
				})
				$scope.Transacs = Trnx;
				console.log(Trnx);
			});
		}
		
	}]);

});