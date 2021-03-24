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
			$scope.Reasons = [
				{id:'PRNERR', label:'Printer Error'},
				{id:'ENCERR', label:'Encoding Error'},
				{id:'SYSERR', label:'System Error'},
			];
			$scope.ActiveTab = 1;
			$scope.ActiveTab1 = 1;
			$scope.Loading = 1;
			$scope.NoReceipts = 0;
			$scope.Today = $filter('date')(new Date(),'yyyy-MM-dd');
			console.log($scope.Today);
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
			$scope.ActivePage = page;
			getOrs(page)
		}
		
		$scope.openModal = function(){
			aModal.open("ReceiptModal");
		}
		
		$scope.ConfirmCancellation = function(){
			$scope.EnableCancel = 0;
			$scope.Saving = 0;
			aModal.close("ReceiptModal");
			var OR = $scope.ActiveReceipt.ref_no.split(" ");
			OR = OR[1];
			var amount = $scope.ActiveReceipt.amount.toString().replace(',','');
			amount = amount.split(".");
			console.log(parseInt(amount[1]));
			if(!parseInt(amount[1]))
				amount = amount[0];
			else
				amount=amount[0]+'.'+amount[1];
			console.log(amount);
			var date = $scope.ActiveReceipt.transac_date.split("-");
			date = date[2]+date[1]+date[0]
			$scope.validation = OR+' '+amount+' '+date;
			aModal.open("CancelModal");
		}
		
		$scope.Cancel = function(modal){
			aModal.close(modal);
		}
		
		$scope.Reprint = function(){
			document.getElementById('PrintReceipt').submit();
		}
		
		$scope.Validate = function(){
			//console.log($scope.Validation+'x');
			//console.log($scope.validation+'x');
			//console.log($scope.Reason);
			if($scope.validation===$scope.Validation){
				$scope.EnableCancel = 1;
			}
		}
		
		$scope.CancelConfirmed = function(){
			$scope.Saving = 1;
			var data = {
				action:'cancel',
				transac:$scope.ActiveReceipt
			}
			api.POST('transactions',data, function success(response){
				$scope.Saving = 0;
				aModal.close("CancelModal");
				getOrs($scope.ActivePage);
			});
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