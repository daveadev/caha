"use strict";
define(['app','api'], function (app) {
    app.register.controller('LedgerController',['$scope','$rootScope','$uibModal','api', function ($scope,$rootScope,$uibModal,api) {
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Ledger';
			$scope.initLedgers=function(){
				api.GET('ledgers',function success(response){
					console.log(response.data);
					$scope.Ledgers=response.data;	
				});
			};
			$scope.initLedgers();
			$scope.hasInfo = false;
			$scope.hasNoInfo = true;
			$scope.openLedgerInfo=function(ledger){
				$scope.Ledger = ledger;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
			};
			$scope.removeTransactionInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.Ledger = null;
			};
			$scope.filterLedger=function(ledger){
				var searchBox = $scope.searchLedger;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(ledger.details) || keyword.test(ledger.account.account_name);
				return !searchBox || test;
			};
			$scope.clearSearch = function(){
				$scope.searchLedger = null;
			};
			$scope.openModal=function(){
				var modalInstance = $uibModal.open({
					animation: true,
					templateUrl: 'myModalContent.html',
					controller: 'ModalInstanceController',
				});
				modalInstance.result.then(function (selectedItem) {
				  $scope.selected = selectedItem;
				}, function (source) {
					if(source==='confirm')
						$scope.initLedgers();
				});
			};
		};
    }]);
	app.register.controller('ModalInstanceController',['$scope','$uibModalInstance','api', function ($scope, $uibModalInstance, api){
		$scope.confirmLedger = function(){
			 $scope.Ledgers={
						  account: {
									account_no:34567,
									account_name:$scope.accountName,
									account_type:"student"
									},
						  type: $scope.type,
						  date: $scope.date,
						  ref_no: $scope.refNo,
						  details: $scope.details,
						  amount: $scope.amount
						 };
			api.POST('ledgers',$scope.Ledgers,function success(response){
				$uibModalInstance.dismiss('confirm');
			});
		};
		$scope.cancelLedger = function(){
			$uibModalInstance.dismiss('cancel');
		};
		$scope.setType=function(value){
			$scope.type=value;
		};
	}]);
});


