"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('ReceiptsController',['$scope','$rootScope','api','$filter','aModal',
	function($scope,$rootScope,api,$filter,aModal){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Receipts';
			$scope.Options = ['OR','AR']
			$scope.ActiveOpt = 'OR';
			$scope.Headers = ['OR','Transact Date','Student',{label:'Amount',class:'amount total'},'Status'];
			$scope.Props = ['ref_no','transac_date','name','amount','status'];
			$scope.Tabs = [{id:1,name:'Breakdown'},{id:2,name:'Booklets'}];
			$scope.Tabss = [{id:1,name:'Cash'},{id:2,name:'Non-cash'}];
			$scope.ActiveTab = 1;
			$scope.ActiveTab1 = 1;
			$scope.Loading = 1;
			$scope.NoReceipts = 0;
			$scope.Dinominations = [
				{denomination:1000.00,quantity:0},
				{denomination:500.00,quantity:0},
				{denomination:200.00,quantity:0},
				{denomination:100.00,quantity:0},
				{denomination:50.00,quantity:0},
				{denomination:20.00,quantity:0},
				{denomination:10.00,quantity:0},
				{denomination:5.00,quantity:0},
				{denomination:1.00,quantity:0},
				{denomination:0.50,quantity:0},
				{denomination:0.25,quantity:0},
				{denomination:0.05,quantity:0},
				{denomination:0.01,quantity:0},
			];
			$scope.ActiveUser = $rootScope.__USER.user;
			
			
		}
		$selfScope.$watch("RS.Active",function(active){
			if(!active) return false;
			console.log(active);
			$scope.ActiveSY =  active.sy;
			if($scope.date_to)
				getOrs(1);
		});
		
		$scope.setActOption = function(opt){
			$scope.ActiveOpt = opt;
			getOrs(1);
		}
		
		$scope.LoadReport = function(){
			$scope.Receipts = '';
			getOrs(1);
		}
		
		$scope.gotoPage = function(page){
			getOrs(page)
		}
		
		$scope.openModal = function(){
			aModal.open("ReceiptModal");
		}
		
		$scope.Cancel = function(){
			aModal.close("ReceiptModal");
		}
		
		$scope.Reprint = function(){
			document.getElementById('PrintReceipt').submit();
		}
		
		function getOrs(page){
			var data = {
				type:$scope.ActiveOpt,
				from:$filter('date')(new Date($scope.date_from),'yyyy-MM-dd'),
				to: $filter('date')(new Date($scope.date_to),'yyyy-MM-dd'),
				page:page
			}
			api.GET('transactions',data, function success(response){
				$scope.Receipts = response.data;
				$scope.Meta = response.meta;
				angular.forEach($scope.Receipts, function(r){
					r.amount = $filter('currency')(r.amount);
				});
				$scope.Loading = 0;
			}, function error(response){
				$scope.NoReceipts = 1;
			});
		}
		
	}]);

});