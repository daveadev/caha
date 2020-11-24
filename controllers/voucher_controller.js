"use strict";
define(['app','api','atomic/bomb'],function(app){
	app.register.controller('VoucherController',['$scope','$rootScope','api','$filter','aModal',
	function($scope,$rootScope,api,$filter,aModal){
		const $selfScope = $scope;
		$scope = this;
		$scope.init = function(){
			$rootScope.__MODULE_NAME = 'Vouchers';
			$scope.Headers = ['Sno','Student','Voucher No',{label:'Amount',class:'amount total'},'Status'];
			$scope.Props = ['sno','student','voucher_no','amount','status'];
			
		}
		$selfScope.$watch("VC.Active",function(active){
			if(!active) return false;
			$scope.ActiveSY = active.sy;
			$scope.Departments = $rootScope._APP.DEPARTMENTS;
			getVouchers();
		});
		
		$scope.openModal = function(){
			aModal.open("VoucherModal");
		}
		
		$scope.SaveVoucher = function(){
			console.log($scope.Student);
			var data = {
				account_id:$scope.Student.id,
				voucher_no:$scope.voucher_no,
				issue_date:$scope.date,
				amount:$scope.amount,
				available_balance:$scope.amount,
				esp:$scope.ActiveSY
			};
			api.POST('vouchers',data, function success(response){
				aModal.close("VoucherModal");
				getVouchers(1);
			});
		}
		
		$scope.Cancel = function(){
			aModal.close("VoucherModal");
		}
		
		function getVouchers(page){
			var data={
				esp:$scope.ActiveSY,
				page:page
			}
			api.GET('vouchers',data, function success(response){
				$scope.Data = response.data;
				$scope.Meta = response.meta;
			});
		}
		
	}]);
});