"use strict";
define(['app', 'api', 'atomic/bomb'], function(app) {
    app.register.controller('LedgerController', ['$scope', '$rootScope', '$uibModal', 'api', 
	function($scope, $rootScope, $uibModal, api) {
		const $selfscope = $scope;
		$scope = this;
        $scope.list = function() {
            $rootScope.__MODULE_NAME = 'Student Ledgers';
            //Initialize ledger and get ledgers.js
			$rootScope.$watch('_APP',function(app){
                if(app){
					$scope.SchoolYears = app.SCHOOL_YEARS;
					$scope.initLedgers();
				}
            });
            function getLedgers(data) {
				
                $scope.DataLoading = true;
                api.GET('ledgers', data, function success(response) {
                    console.log(response.data);
                    $scope.Ledgers = response.data;
                    $scope.NextPage = response.meta.next;
                    $scope.PrevPage = response.meta.prev;
                    $scope.TotalItems = response.meta.count;
                    $scope.LastItem = response.meta.page * response.meta.limit;
                    $scope.FirstItem = $scope.LastItem - (response.meta.limit - 1);
                    if ($scope.LastItem > $scope.TotalItems) {
                        $scope.LastItem = $scope.TotalItems;
                    };
                    $scope.DataLoading = false;
                });
            }
            $scope.initLedgers = function() {
				$scope.ActiveSY  = $rootScope._APP.ACTIVE_SY;
                $scope.hasInfo = false;
                $scope.hasNoInfo = true;
                $scope.ActivePage = 1;
                $scope.NextPage = null;
                $scope.PrevPage = null;
                $scope.DataLoading = false;
                getLedgers({ page: $scope.ActivePage,esp:$scope.ActiveSY });
            };
            
            $scope.navigatePage = function(page) {
                $scope.ActivePage = page;
                getLedgers({ page: $scope.ActivePage });
            };
            //Open ledger Information
            $scope.openLedgerInfo = function(ledger) {
				console.log(ledger);
                $scope.Ledger = ledger;
                $scope.hasInfo = true;
                $scope.hasNoInfo = false;
            };
            //Remove Transaction Info
            $scope.removeLedgerInfo = function() {
                $scope.hasInfo = false;
                $scope.hasNoInfo = true;
                $scope.Ledger = null;
            };
            //Filter ledger
            $scope.filterLedger = function(ledger) {
                var searchBox = $scope.searchLedger;
                var keyword = new RegExp(searchBox, 'i');
                var test = keyword.test(ledger.details) || keyword.test(ledger.account.account_name);
                return !searchBox || test;
            };
            $scope.confirmSearch = function() {
                    getLedgers({ page: $scope.ActivePage, keyword: $scope.searchLedger, fields: ['account.account_name'] });
                }
                //Clear search box
            $scope.clearSearch = function() {
                $scope.searchLedger = null;
            };
            $scope.deleteLedger = function(id) {
                var data = { id: id };
                api.DELETE('ledgers', data, function(response) {
                    $scope.removeLedgerInfo();
                    getLedgers({ page: $scope.ActivePage });
                });
            };


            //Open modal
            $scope.openModal = function() {
				var active = {
					sy:$scope.ActiveSY,
					SYs:$scope.SchoolYears,
				}
                var modalInstance = $uibModal.open({
                    animation: true,
                    templateUrl: 'myModalContent.html',
                    controller: 'ModalInstanceController',
					resolve: {
						active: function(){
							return active;
						}
					}
                });
                $rootScope.__MODAL_OPEN = true;
                modalInstance.result.then(function(selectedItem) {
                    $scope.selected = selectedItem;
                    $rootScope.__MODAL_OPEN = false;
                }, function(source) {
                    $rootScope.__MODAL_OPEN = false;
                    //Re initialize ledger when confirmed
                    if (source === 'confirm')
                        $scope.initLedgers();
                });
            };
        };
    }]);

    app.register.controller('ModalInstanceController', ['$scope', '$uibModalInstance', 'api','active','$filter',
	function($scope, $uibModalInstance, api, active,$filter) {
        //Gets the data entered in modal and push it to ledgers.js
		$scope.Saving = 0;
		$scope.type = 'credit';
		$scope.Details = [
			{id:'REFND',name:'Refund'},
			{id:'SPONS',name:'Sponsorship'},
			{id:'OTHRS',name:'Others'},
		];
		$scope.SchoolYears = active.SYs;
		$scope.SchoolYear = active.sy;
		$scope.DefaultSY = active.sy;
        //$scope.type = 'credit';
        $scope.confirmLedger = function() {
			getSchedules();
			getFees();
			getAccount();
			$scope.Saving = 1;
			
			var ledgerItem = {
				account_id:$scope.Account.id,
				transac_date:$scope.date,
				ref_no:$scope.Ref_no,
				transaction_type_id:$scope.Detail.id,
				details:$scope.Detail.name,
				esp:$scope.DefaultSY,
				amount:$scope.Amount,
				notes:$scope.Notes,
				sy:$scope.SchoolYear
			}
			if($scope.type=='credit')
				ledgerItem.type = '+';
			else
				ledgerItem.type = '-';
			if($scope.SchoolYear<$scope.DefaultSY)
				ledgerItem.details = 'SY '+$scope.SchoolYear+' - '+(parseInt($scope.SchoolYear+1))+' '+$scope.Detail.name;
			ledgerItem.transac_date = $filter('date')(new Date(ledgerItem.transac_date),'yyyy-MM-dd');
			$scope.LedgerEntry = ledgerItem;
            //console.log(tDate.getFullYear());
			
            api.POST('ledgers', ledgerItem, function success(response) {
				$scope.Saving = 0;
				document.getElementById('PrintLedger').submit();
                $uibModalInstance.dismiss('confirm');
            });
        };
		
		function getSchedules(){
			api.GET('account_schedules',{account_id:$scope.Account.id,limit:'less'}, function success(response){
				var amount = angular.copy($scope.Amount);
				angular.forEach(response.data, function(item){
					if(item.paid_amount==0&&amount!==0){
						if(amount>=item.due_amount){
							item.paid_amount = item.due_amount;
							item.status = 'PAID';
							amount-=item.due_amount;
						}else{
							item.paid_amount = amount;
							amount = 0;
						}
					}
				});
				var scheds = response.data;
				api.POST('account_schedules',scheds,function success(response){
					
				},function error(response){
					
				});
			}, function error(response){
				$scope.Schedules = '';
			});
		}
		
		function getFees(){
			api.GET('account_fees',{account_id:$scope.Account.id,limit:999},function success(response){
				var total_misc = 0;
				var total_paid = 0;
				var amount = angular.copy($scope.Amount);
				angular.forEach(response.data, function(item){
					if(item.fee_id!=='TUI'){
						total_misc+=item.due_amount;
					}
				});
				angular.forEach(response.data, function(item){
					if(item.fee_id!=='TUI'){
						total_paid+=item.paid_amount;
					}
				});
				if(total_misc>total_paid){
					angular.forEach(response.data, function(item){
						if(item.fee_id!=='TUI'&&item.paid!=item.due_amount&&amount!=0){
							var diff = item.due_amount-item.paid_amount;
							item.paid_amount = item.due_amount;
							amount-=diff;
						}
					});
				}else{
					angular.forEach(response.data, function(item){
						if(item.fee_id=='TUI'){
							item.paid_amount+=amount;
							amount = 0;
						}
					});
				}
				var fees = response.data;
				api.POST('account_fees',fees, function success(response){
					
				});
			});
		}
		
		function getAccount(){
			api.GET('accounts',{id:$scope.Account.id}, function success(response){
				$scope.ActiveAcc = response.data[0];
				if($scope.DefaultSY==$scope.SchoolYear&&$scope.ActiveAcc.outstanding_balance>0){
					$scope.ActiveAcc.outstanding_balance -= $scope.Amount;
				}
				if($scope.SchoolYear<$scope.DefaultSY&&$scope.ActiveAcc.old_balance>0){
					$scope.ActiveAcc.old_balance-=$scope.Amount;
					$scope.ActiveAcc.outstanding_balance-=$scope.Amount;
				}
				api.POST('accounts',$scope.ActiveAcc, function success(response){
					
				});
			});
		}

        //Close modal
        $scope.cancelLedger = function() {
            $uibModalInstance.dismiss('cancel');
        };
        //Change if it is debit or credit
        $scope.setType = function(value) {
            $scope.type = value;
        };
    }]);
});