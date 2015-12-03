"use strict";
define(['app','api'], function (app) {
    app.register.controller('LedgerController',['$scope','$rootScope','$uibModal','api', function ($scope,$rootScope,$uibModal,api) {
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Student Ledgers';
			//Initialize ledger and get ledgers.js
			function getLedgers(data){
				$scope.DataLoading = true;
				api.GET('ledgers',data,function success(response){
				console.log(response.data);
				$scope.Ledgers=response.data;
				$scope.NextPage=response.meta.next;
				$scope.PrevPage=response.meta.prev;
				$scope.TotalItems=response.meta.count;
				$scope.LastItem=response.meta.page*response.meta.limit;
				$scope.FirstItem=$scope.LastItem-(response.meta.limit-1);
				if($scope.LastItem>$scope.TotalItems){
					$scope.LastItem=$scope.TotalItems;
				};
				$scope.DataLoading = false;				
				});
			}
			$scope.initLedgers=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.ActivePage = 1;
				$scope.NextPage=null;
				$scope.PrevPage=null;
				$scope.DataLoading = false;
				getLedgers({page:$scope.ActivePage});
			};
			$scope.initLedgers();
			$scope.navigatePage=function(page){
				$scope.ActivePage=page;
				getLedgers({page:$scope.ActivePage});
			};
			//Open ledger Information
			$scope.openLedgerInfo=function(ledger){
				$scope.Ledger = ledger;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
			};
			//Remove Transaction Info
			$scope.removeLedgerInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.Ledger = null;
			};
			//Filter ledger
			$scope.filterLedger=function(ledger){
				var searchBox = $scope.searchLedger;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(ledger.details) || keyword.test(ledger.account.account_name);
				return !searchBox || test;
			};
			$scope.confirmSearch = function(){
				getLedgers({page:$scope.ActivePage,keyword:$scope.searchLedger,fields:['account.account_name']});
			}
			//Clear search box
			$scope.clearSearch = function(){
				$scope.searchLedger = null;
			};
			$scope.deleteLedger = function(id){
				var data = {id:id};
				api.DELETE('ledgers',data,function(response){
					$scope.removeLedgerInfo();
					getLedgers({page:$scope.ActivePage});
				});
			};
			//Open modal
			$scope.openModal=function(){
				var modalInstance = $uibModal.open({
					animation: true,
					templateUrl: 'myModalContent.html',
					controller: 'ModalInstanceController',
				});
				modalInstance.result.then(function (selectedItem) {
				  $scope.selected = selectedItem;
				}, function (source) {
					//Re initialize ledger when confirmed
					if(source==='confirm')
						$scope.initLedgers();
				});
			};
		};
    }]);
	app.register.controller('ModalInstanceController',['$scope','$uibModalInstance','api', function ($scope, $uibModalInstance, api){
		//Gets the data entered in modal and push it to ledgers.js
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
		//Close modal
		$scope.cancelLedger = function(){
			$uibModalInstance.dismiss('cancel');
		};
		//Change if it is debit or credit
		$scope.setType=function(value){
			$scope.type=value;
		};
	}]);
});


