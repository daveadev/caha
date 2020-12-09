"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('CashierController',['$scope','$rootScope','api','$filter',
	function($scope,$rootScope,api,$filter){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Cashier Collection';
			$scope.Options = ['OR','AR']
			$scope.ActiveOpt = 'OR';
			$scope.CHeaders = ['cnt','Sno','Received from','Level','Section','Status','Date','Particular','Ref no',{label:'Amount',class:'amount total'}];
			$scope.Headers = ['cnt','Sno','Received from','Level','Section','Status','Date','Particular','Ref no',{label:'Amount',class:'amount total'},{label:'Total Due',class:'amount total'},{label:'Total Paid',class:'amount total'},{label:'Balance',class:'amount total'},];
			$scope.CProps = ['cnt','sno','received_from','level','section','status','date','particulars','ref_no','amount'];
			$scope.Props = ['cnt','sno','received_from','level','section','status','date','particulars','ref_no','amount','total_due','total_paid','balance'];
			getTransacs();
			$scope.ActiveUser = $rootScope.__USER.user;
			console.log($scope.ActiveUser);
		}
		$selfScope.$watch("CS.Active",function(active){
			if(!active) return false;
			console.log(active);
			$scope.ActiveSY =  active.sy;
			if($scope.date_to)
				getCollections(1);
		});
		$scope.setActOption = function(opt){
			$scope.ActiveOpt = opt;
			getCollections(1);
		}
		$scope.LoadReport = function(){
			getCollections(1);
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
				type:$scope.ActiveOpt,
				'page': page,
			}
			if($scope.ActiveUser.user_type!='cashr'){
				data.from = $scope.date_from;
				data.to = $scope.date_to;
				data.from = $filter('date')(new Date(data.from),'yyyy-MM-dd');
				data.to = $filter('date')(new Date(data.to),'yyyy-MM-dd');
			}else{
				data.date = $filter('date')(new Date($scope.cash_date),'yyyy-MM-dd');
				data.cashr = true;
			}
			//data.limit = 'less';
			api.GET('cashier_collections',data, function success(response){
				$scope.NoCollections = 0;
				$scope.Collections = response.data[0];
				if(!$scope.Collections.total)
					$scope.NoCollections = 1;
				$scope.Meta = response.meta;
				if($scope.Meta.page==1) getForPrinting(data);
			},function error(response){
				
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